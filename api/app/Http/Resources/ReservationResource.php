<?php

namespace App\Http\Resources;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Reservation */
class ReservationResource extends JsonResource
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
            'email' => $this->email,
            'seats' => $this->seats,
            'total' => $this->total,
            'token' => $this->token,
            'qr_url' => $this->qr_url,
            'showing' => new ShowingResource($this->whenLoaded('showing')),
        ];
    }
}
