<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReservationRequest;
use App\Http\Resources\ReservationResource;
use App\Models\Reservation;
use App\Models\Showing;
use App\Notifications\ReservationPlaced;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class ReservationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReservationRequest $request, Showing $showing): JsonResponse
    {
        $reservation = new Reservation($request->validated());

        $reservation->total = count($reservation->seats) * 9;
        $reservation->token = Str::random(32);
        $reservation->showing()->associate($showing);
        $reservation->save();

        Notification::route('mail', $reservation->email)
            ->notify(new ReservationPlaced($reservation));

        return response()->json(
            new ReservationResource($reservation),
            201
        );
    }

    public function show(Reservation $reservation): ReservationResource
    {
        $reservation->load('showing.movie');

        return new ReservationResource($reservation);
    }

    public function qr(Reservation $reservation): Response
    {
        $url = URL::frontend()->reservation($reservation);

        $options = new QROptions([
            'outputBase64' => false,
            'quietzoneSize' => 2,
        ]);

        $qrcode = (new QRCode($options))->render($url);

        return response($qrcode, 200, ['Content-Type' => 'image/svg+xml']);
    }
}
