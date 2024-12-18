<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'is_active',
        'position',
        'audio_file'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public const POSITIONS = [
        'header' => 'En-tête',
        'footer' => 'Pied de page',
        'sidebar' => 'Barre latérale'
    ];

    /**
     * Get the formatted position name.
     *
     * @return string
     */
    public function getFormattedPositionAttribute(): string
    {
        return self::POSITIONS[$this->position] ?? ucfirst($this->position);
    }

    /**
     * Scope a query to only include active announcements.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include announcements for a specific position.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $position
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePosition($query, string $position)
    {
        return $query->where('position', $position);
    }
}
