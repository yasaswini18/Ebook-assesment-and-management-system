<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Criteria;
use App\Models\Evaluation;
use App\Models\EvaluationCriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Evaluation::with(['book', 'user']);

        // Filter by book
        if ($request->has('book_id') && $request->input('book_id')) {
            $query->where('book_id', $request->input('book_id'));
        }

        // Filter by user (for admins only)
        if ($request->has('user_id') && $request->input('user_id') && Auth::user()->hasRole('admin')) {
            $query->where('user_id', $request->input('user_id'));
        } else {
            // Non-admins can only see their own evaluations
            if (!Auth::user()->hasRole('admin')) {
                $query->where('user_id', Auth::id());
            }
        }

        // Filter by date range
        if ($request->has('date_from') && $request->input('date_from')) {
            $query->where('evaluation_date', '>=', $request->input('date_from'));
        }

        if ($request->has('date_to') && $request->input('date_to')) {
            $query->where('evaluation_date', '<=', $request->input('date_to'));
        }

        // Sort by
        $sortField = $request->input('sort', 'evaluation_date');
        $sortDirection = $request->input('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $evaluations = $query->paginate(10);

        // Get books for filter dropdown
        $books = Book::orderBy('title')->get();

        return view('evaluations.index', compact('evaluations', 'books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, Book $book = null)
    {
        // Get all active criteria
        $criteria = Criteria::where('is_active', true)->orderBy('weight', 'desc')->get();

        // If no criteria exist, redirect back with error
        if ($criteria->isEmpty()) {
            return redirect()->route('evaluations.index')
                ->with('error', 'No active assessment criteria found. Please create criteria first.');
        }

        // If book is not provided, get all books for selection
        if (!$book) {
            $books = Book::orderBy('title')->get();
            return view('evaluations.create', compact('criteria', 'books'));
        }

        return view('evaluations.create', compact('criteria', 'book'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'comments' => 'nullable|string',
            'evaluation_date' => 'required|date|before_or_equal:today',
            'criteria' => 'required|array',
            'criteria.*.id' => 'required|exists:criteria,id',
            'criteria.*.rating' => 'required|integer|min:1|max:5',
            'criteria.*.notes' => 'nullable|string',
        ]);

        // Create the evaluation
        $evaluation = Evaluation::create([
            'book_id' => $validated['book_id'],
            'user_id' => Auth::id(),
            'comments' => $validated['comments'],
            'evaluation_date' => $validated['evaluation_date'],
        ]);

        // Create the evaluation criteria ratings
        foreach ($validated['criteria'] as $criteriaData) {
            EvaluationCriteria::create([
                'evaluation_id' => $evaluation->id,
                'criteria_id' => $criteriaData['id'],
                'rating' => $criteriaData['rating'],
                'notes' => $criteriaData['notes'] ?? null,
            ]);
        }

        return redirect()->route('evaluations.show', $evaluation)
            ->with('success', 'Evaluation created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Evaluation $evaluation)
    {
        // Eager load relationships
        $evaluation->load(['book', 'user', 'evaluationCriteria.criteria']);

        // Calculate average rating
        $totalWeight = 0;
        $weightedSum = 0;

        foreach ($evaluation->evaluationCriteria as $criteria) {
            $totalWeight += $criteria->criteria->weight;
            $weightedSum += $criteria->rating * $criteria->criteria->weight;
        }

        $averageRating = $totalWeight > 0 ? round($weightedSum / $totalWeight, 1) : 0;

        return view('evaluations.show', compact('evaluation', 'averageRating'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Evaluation $evaluation)
    {
        // Check if user is authorized to edit this evaluation
        if (!Auth::user()->hasRole('admin') && $evaluation->user_id !== Auth::id()) {
            return redirect()->route('evaluations.index')
                ->with('error', 'You are not authorized to edit this evaluation.');
        }

        // Eager load relationships
        $evaluation->load(['book', 'evaluationCriteria.criteria']);

        // Get all active criteria
        $criteria = Criteria::where('is_active', true)->orderBy('weight', 'desc')->get();

        return view('evaluations.edit', compact('evaluation', 'criteria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Evaluation $evaluation)
    {
        // Check if user is authorized to update this evaluation
        if (!Auth::user()->hasRole('admin') && $evaluation->user_id !== Auth::id()) {
            return redirect()->route('evaluations.index')
                ->with('error', 'You are not authorized to update this evaluation.');
        }

        $validated = $request->validate([
            'comments' => 'nullable|string',
            'evaluation_date' => 'required|date|before_or_equal:today',
            'criteria' => 'required|array',
            'criteria.*.id' => 'required|exists:criteria,id',
            'criteria.*.rating' => 'required|integer|min:1|max:5',
            'criteria.*.notes' => 'nullable|string',
        ]);

        // Update the evaluation
        $evaluation->update([
            'comments' => $validated['comments'],
            'evaluation_date' => $validated['evaluation_date'],
        ]);

        // Update or create the evaluation criteria ratings
        foreach ($validated['criteria'] as $criteriaData) {
            $evaluationCriteria = EvaluationCriteria::updateOrCreate(
                [
                    'evaluation_id' => $evaluation->id,
                    'criteria_id' => $criteriaData['id'],
                ],
                [
                    'rating' => $criteriaData['rating'],
                    'notes' => $criteriaData['notes'] ?? null,
                ]
            );
        }

        return redirect()->route('evaluations.show', $evaluation)
            ->with('success', 'Evaluation updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Evaluation $evaluation)
    {
        // Check if user is authorized to delete this evaluation
        if (!Auth::user()->hasRole('admin') && $evaluation->user_id !== Auth::id()) {
            return redirect()->route('evaluations.index')
                ->with('error', 'You are not authorized to delete this evaluation.');
        }

        // Delete related evaluation criteria
        $evaluation->evaluationCriteria()->delete();

        // Delete the evaluation
        $evaluation->delete();

        return redirect()->route('evaluations.index')
            ->with('success', 'Evaluation deleted successfully.');
    }
}
