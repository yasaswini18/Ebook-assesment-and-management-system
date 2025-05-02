<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use Illuminate\Http\Request;

class CriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Criteria::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by active status
        if ($request->has('status')) {
            $status = $request->input('status');
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Sort by
        $sortField = $request->input('sort', 'name');
        $sortDirection = $request->input('direction', 'asc');
        $query->orderBy($sortField, $sortDirection);

        $criteria = $query->paginate(10);

        return view('criteria.index', compact('criteria'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('criteria.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'weight' => 'required|integer|min:1|max:10',
            'is_active' => 'boolean',
        ]);

        // Set default value for is_active if not provided
        if (!isset($validated['is_active'])) {
            $validated['is_active'] = false;
        }

        $criteria = Criteria::create($validated);

        return redirect()->route('criteria.show', $criteria)
            ->with('success', 'Assessment criteria created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Criteria $criteria)
    {
        // Get evaluations that used this criteria
        $evaluationCount = $criteria->evaluationCriteria()->count();

        return view('criteria.show', compact('criteria', 'evaluationCount'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Criteria $criteria)
    {
        return view('criteria.edit', compact('criteria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Criteria $criteria)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'weight' => 'required|integer|min:1|max:10',
            'is_active' => 'boolean',
        ]);

        // Set default value for is_active if not provided
        if (!isset($validated['is_active'])) {
            $validated['is_active'] = false;
        }

        $criteria->update($validated);

        return redirect()->route('criteria.show', $criteria)
            ->with('success', 'Assessment criteria updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Criteria $criteria)
    {
        // Check if this criteria is used in any evaluations
        $usageCount = $criteria->evaluationCriteria()->count();

        if ($usageCount > 0) {
            return redirect()->route('criteria.index')
                ->with('error', 'Cannot delete criteria that is used in evaluations. Consider marking it as inactive instead.');
        }

        $criteria->delete();

        return redirect()->route('criteria.index')
            ->with('success', 'Assessment criteria deleted successfully.');
    }
}
