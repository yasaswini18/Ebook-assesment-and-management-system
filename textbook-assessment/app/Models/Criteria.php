<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Criteria extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'criteria';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'weight',
        'is_active',
    ];

    /**
     * Get the evaluation criteria for this criteria.
     */
    public function evaluationCriteria(): HasMany
    {
        return $this->hasMany(EvaluationCriteria::class);
    }
}
