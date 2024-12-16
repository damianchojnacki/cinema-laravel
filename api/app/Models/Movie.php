<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\Storage;

class Movie extends Model
{
    /** @use HasFactory<\Database\Factories\MovieFactory> */
    use HasFactory;

    /**
     * @return Attribute<?string, never>
     */
    protected function posterUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->poster_path ? Storage::disk('public')->url($this->poster_path) : null,
        );
    }

    /**
     * @return Attribute<?string, never>
     */
    protected function backdropUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->backdrop_path ? Storage::disk('public')->url($this->backdrop_path) : null,
        );
    }

    /**
     * @return HasMany<Showing, $this>
     */
    public function showings(): HasMany
    {
        return $this->hasMany(Showing::class);
    }

    /**
     * @return HasManyThrough<Reservation, Showing, $this>
     */
    public function reservations(): HasManyThrough
    {
        return $this->hasManyThrough(Reservation::class, Showing::class);
    }
}
