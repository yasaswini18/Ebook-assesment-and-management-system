<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Criteria;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Display the reports index page.
     */
    public function index()
    {
        // Get books with evaluation counts
        $books = Book::withCount('evaluations')
            ->having('evaluations_count', '>', 0)
            ->orderBy('title')
            ->get();

        // Get criteria with average ratings
        $criteriaAverages = DB::table('evaluation_criteria')
            ->join('criteria', 'evaluation_criteria.criteria_id', '=', 'criteria.id')
            ->select('criteria.name', DB::raw('AVG(evaluation_criteria.rating) as average_score'))
            ->groupBy('criteria.name')
            ->orderBy('average_score', 'desc')
            ->get();

        // Get top rated books
        $topBooks = DB::table('evaluations')
            ->join('evaluation_criteria', 'evaluations.id', '=', 'evaluation_criteria.evaluation_id')
            ->join('books', 'evaluations.book_id', '=', 'books.id')
            ->select('books.id', 'books.title', DB::raw('AVG(evaluation_criteria.rating) as average_score'), DB::raw('COUNT(DISTINCT evaluations.id) as evaluation_count'))
            ->groupBy('books.id', 'books.title')
            ->having('evaluation_count', '>=', 1)
            ->orderBy('average_score', 'desc')
            ->take(5)
            ->get();

        return view('reports.index', compact('books', 'criteriaAverages', 'topBooks'));
    }

    /**
     * Generate a report for a specific book.
     */
    public function bookReport(Book $book)
    {
        // Get all evaluations for this book
        $evaluations = Evaluation::with(['user', 'evaluationCriteria.criteria'])
            ->where('book_id', $book->id)
            ->orderBy('evaluation_date', 'desc')
            ->get();

        // Calculate average ratings per criteria
        $criteriaAverages = DB::table('evaluation_criteria')
            ->join('criteria', 'evaluation_criteria.criteria_id', '=', 'criteria.id')
            ->join('evaluations', 'evaluation_criteria.evaluation_id', '=', 'evaluations.id')
            ->where('evaluations.book_id', $book->id)
            ->select('criteria.name', DB::raw('AVG(evaluation_criteria.rating) as average_score'))
            ->groupBy('criteria.name')
            ->orderBy('average_score', 'desc')
            ->get();

        // Calculate overall average
        $overallAverage = $evaluations->isEmpty() ? 0 :
            DB::table('evaluation_criteria')
                ->join('evaluations', 'evaluation_criteria.evaluation_id', '=', 'evaluations.id')
                ->where('evaluations.book_id', $book->id)
                ->avg('evaluation_criteria.rating');

        return view('reports.book', compact('book', 'evaluations', 'criteriaAverages', 'overallAverage'));
    }

    /**
     * Export report data.
     */
    public function export(Request $request, $type)
    {
        $reportType = $request->input('report_type', 'all_books');
        $bookId = $request->input('book_id');

        // Prepare data based on report type
        if ($reportType === 'single_book' && $bookId) {
            $book = Book::findOrFail($bookId);
            $data = $this->getSingleBookReportData($book);
            $filename = 'book_report_' . $book->id;
            $title = 'Book Report: ' . $book->title;
        } else {
            $data = $this->getAllBooksReportData();
            $filename = 'all_books_report';
            $title = 'All Books Report';
        }

        // Generate export based on type
        if ($type === 'pdf') {
            $pdf = PDF::loadView('reports.export.pdf', [
                'data' => $data,
                'title' => $title,
                'reportType' => $reportType
            ]);

            return $pdf->download($filename . '.pdf');
        } elseif ($type === 'excel') {
            return Excel::download(new \App\Exports\ReportExport($data, $title, $reportType), $filename . '.xlsx');
        }

        return back()->with('error', 'Invalid export type.');
    }

    /**
     * Get report data for a single book.
     */
    private function getSingleBookReportData(Book $book)
    {
        // Get all evaluations for this book
        $evaluations = Evaluation::with(['user', 'evaluationCriteria.criteria'])
            ->where('book_id', $book->id)
            ->orderBy('evaluation_date', 'desc')
            ->get();

        // Calculate average ratings per criteria
        $criteriaAverages = DB::table('evaluation_criteria')
            ->join('criteria', 'evaluation_criteria.criteria_id', '=', 'criteria.id')
            ->join('evaluations', 'evaluation_criteria.evaluation_id', '=', 'evaluations.id')
            ->where('evaluations.book_id', $book->id)
            ->select('criteria.name', DB::raw('AVG(evaluation_criteria.rating) as average_score'))
            ->groupBy('criteria.name')
            ->orderBy('average_score', 'desc')
            ->get();

        // Calculate overall average
        $overallAverage = $evaluations->isEmpty() ? 0 :
            DB::table('evaluation_criteria')
                ->join('evaluations', 'evaluation_criteria.evaluation_id', '=', 'evaluations.id')
                ->where('evaluations.book_id', $book->id)
                ->avg('evaluation_criteria.rating');

        return [
            'book' => $book,
            'evaluations' => $evaluations,
            'criteriaAverages' => $criteriaAverages,
            'overallAverage' => $overallAverage
        ];
    }

    /**
     * Get report data for all books.
     */
    private function getAllBooksReportData()
    {
        // Get books with evaluation counts and average scores
        $books = DB::table('books')
            ->leftJoin('evaluations', 'books.id', '=', 'evaluations.book_id')
            ->leftJoin('evaluation_criteria', 'evaluations.id', '=', 'evaluation_criteria.evaluation_id')
            ->select(
                'books.id',
                'books.title',
                'books.author',
                'books.publisher',
                'books.year',
                'books.type',
                DB::raw('COUNT(DISTINCT evaluations.id) as evaluation_count'),
                DB::raw('AVG(evaluation_criteria.rating) as average_score')
            )
            ->groupBy('books.id', 'books.title', 'books.author', 'books.publisher', 'books.year', 'books.type')
            ->orderBy('average_score', 'desc')
            ->get();

        // Get criteria with average ratings
        $criteriaAverages = DB::table('evaluation_criteria')
            ->join('criteria', 'evaluation_criteria.criteria_id', '=', 'criteria.id')
            ->select('criteria.name', DB::raw('AVG(evaluation_criteria.rating) as average_score'))
            ->groupBy('criteria.name')
            ->orderBy('average_score', 'desc')
            ->get();

        return [
            'books' => $books,
            'criteriaAverages' => $criteriaAverages
        ];
    }
}
