<?php

namespace App\Http\Resources;

use App\Models\Showing;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Showing */
class ShowingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'rows' => $this->rows,
            'columns' => $this->columns,
            'starts_at' => $this->starts_at,
            'movie' => new MovieResource($this->whenLoaded('movie')),
            'seats_taken' => $this->whenLoaded('reservations', function ($reservations) {
                return $reservations->pluck('seats')->flatten(1);
            }),
        ];
    }
}
