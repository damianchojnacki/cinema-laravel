<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Reservation extends Model
{
    /** @use HasFactory<\Database\Factories\ReservationFactory> */
    use HasFactory;

    protected $casts = [
        'seats' => 'json',
    ];

    /**
     * @return BelongsTo<Showing, $this>
     */
    public function showing(): BelongsTo
    {
        return $this->belongsTo(Showing::class);
    }

    /**
     * @return HasOneThrough<Movie, Showing, $this>
     */
    public function movie(): HasOneThrough
    {
        return $this->hasOneThrough(Movie::class, Showing::class, 'id', 'id', 'showing_id', 'movie_id');
    }

    /**
     * @return Attribute<?string, never>
     */
    protected function qrUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => route('reservations.qr', ['reservation' => $this])
        );
    }
}
