<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluationCriteria extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'evaluation_criteria';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'evaluation_id',
        'criteria_id',
        'rating',
        'notes',
    ];

    /**
     * Get the evaluation that owns this evaluation criteria.
     */
    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class);
    }

    /**
     * Get the criteria that owns this evaluation criteria.
     */
    public function criteria(): BelongsTo
    {
        return $this->belongsTo(Criteria::class);
    }
}
