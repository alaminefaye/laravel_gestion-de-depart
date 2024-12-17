<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Bus;
use App\Models\Reservation;
use Carbon\Carbon;

class Departure extends Model
{
    use HasFactory;

    const STATUS_ON_TIME = '1';
    const STATUS_DELAYED = '2';
    const STATUS_CANCELLED = '3';

    protected $fillable = [
        'route',
        'scheduled_time',
        'delayed_time',
        'status',
        'bus_id',
        'prix',
        'places_disponibles',
        'taux_occupation'
    ];

    protected $casts = [
        'scheduled_time' => 'datetime',
        'delayed_time' => 'datetime',
        'prix' => 'decimal:2'
    ];

    protected $dates = [
        'scheduled_time',
        'delayed_time'
    ];

    protected $attributes = [
        'status' => self::STATUS_ON_TIME,
    ];

    public static $statusTranslations = [
        self::STATUS_ON_TIME => 'À l\'heure',
        self::STATUS_DELAYED => 'En retard',
        self::STATUS_CANCELLED => 'Annulé'
    ];

    public function getStatusLabelAttribute()
    {
        return self::$statusTranslations[$this->status] ?? $this->status;
    }

    public function isDelayed()
    {
        return $this->status === self::STATUS_DELAYED;
    }

    public static function getStatusOptions()
    {
        return self::$statusTranslations;
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function getPlacesRestantesAttribute()
    {
        return $this->places_disponibles - $this->reservations()->count();
    }

    public function getFormattedScheduledTimeAttribute()
    {
        if (!$this->scheduled_time) {
            return '';
        }
        
        if (is_string($this->scheduled_time)) {
            return Carbon::parse($this->scheduled_time)->format('H:i');
        }
        
        return $this->scheduled_time->format('H:i');
    }

    public function getFormattedDelayedTimeAttribute()
    {
        if (!$this->delayed_time) {
            return '';
        }
        
        if (is_string($this->delayed_time)) {
            return Carbon::parse($this->delayed_time)->format('H:i');
        }
        
        return $this->delayed_time->format('H:i');
    }

    public function getScheduledDateAttribute()
    {
        if (!$this->scheduled_time) {
            return '';
        }
        
        if (is_string($this->scheduled_time)) {
            return Carbon::parse($this->scheduled_time)->format('Y-m-d');
        }
        
        return $this->scheduled_time->format('Y-m-d');
    }
}
