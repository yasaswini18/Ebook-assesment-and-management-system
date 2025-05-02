<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        // Get total counts
        $totalBooks = Book::count();
        $totalEvaluations = Evaluation::count();
        $recentEvaluations = Evaluation::with(['book', 'user'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get book quality scores (average ratings)
        $bookScores = DB::table('evaluations')
            ->join('evaluation_criteria', 'evaluations.id', '=', 'evaluation_criteria.evaluation_id')
            ->join('books', 'evaluations.book_id', '=', 'books.id')
            ->select('books.id', 'books.title', DB::raw('AVG(evaluation_criteria.rating) as average_score'))
            ->groupBy('books.id', 'books.title')
            ->orderBy('average_score', 'desc')
            ->take(10)
            ->get();

        // Get criteria averages
        $criteriaAverages = DB::table('evaluation_criteria')
            ->join('criteria', 'evaluation_criteria.criteria_id', '=', 'criteria.id')
            ->select('criteria.name', DB::raw('AVG(evaluation_criteria.rating) as average_score'))
            ->groupBy('criteria.name')
            ->orderBy('average_score', 'desc')
            ->get();

        return view('dashboard', compact(
            'totalBooks',
            'totalEvaluations',
            'recentEvaluations',
            'bookScores',
            'criteriaAverages'
        ));
    }
}
