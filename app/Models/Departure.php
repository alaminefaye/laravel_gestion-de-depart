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

    public const STATUS_ON_TIME = 1;
    public const STATUS_DELAYED = 2;
    public const STATUS_CANCELLED = 3;

    protected $fillable = [
        'route',
        'scheduled_time',
        'delayed_time',
        'status',
        'bus_id',
        'prix',
        'places_disponibles'
    ];

    protected $casts = [
        'scheduled_time' => 'datetime',
        'delayed_time' => 'datetime',
        'prix' => 'decimal:0'
    ];

    protected $dates = [
        'scheduled_time',
        'delayed_time'
    ];

    protected $appends = [
        'status_label',
        'formatted_scheduled_date',
        'formatted_scheduled_time',
        'formatted_delayed_date',
        'formatted_delayed_time',
        'formatted_price',
        'calculated_places_disponibles'
    ];

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function getStatusLabelAttribute()
    {
        return match((int)$this->status) {
            self::STATUS_ON_TIME => 'À l\'heure',
            self::STATUS_DELAYED => 'En retard',
            self::STATUS_CANCELLED => 'Annulé',
            default => 'Inconnu'
        };
    }

    public static function getStatusOptions()
    {
        return [
            self::STATUS_ON_TIME => 'À l\'heure',
            self::STATUS_DELAYED => 'En retard',
            self::STATUS_CANCELLED => 'Annulé'
        ];
    }

    public function setScheduledTimeAttribute($value)
    {
        $this->attributes['scheduled_time'] = $value ? Carbon::parse($value) : null;
    }

    public function setDelayedTimeAttribute($value)
    {
        $this->attributes['delayed_time'] = $value ? Carbon::parse($value) : null;
    }

    public function getFormattedScheduledTimeAttribute()
    {
        return $this->scheduled_time ? $this->scheduled_time->format('H:i') : '';
    }

    public function getFormattedDelayedTimeAttribute()
    {
        return $this->delayed_time ? $this->delayed_time->format('H:i') : '';
    }

    public function getFormattedScheduledDateAttribute()
    {
        return $this->scheduled_time ? $this->scheduled_time->format('d/m/Y') : '';
    }

    public function getFormattedDelayedDateAttribute()
    {
        return $this->delayed_time ? $this->delayed_time->format('d/m/Y') : '';
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->prix, 0, ',', ' ') . ' FCFA';
    }

    public function getCalculatedPlacesDisponiblesAttribute()
    {
        if (!$this->bus) {
            return 0;
        }
        
        $capacite = $this->bus->capacite ?? 0;
        $places_reservees = $this->reservations()->count();
        
        return max(0, $capacite - $places_reservees);
    }
}
