<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use League\Glide\Filesystem\FileNotFoundException;
use League\Glide\Responses\SymfonyResponseFactory;
use League\Glide\ServerFactory;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ImageController extends Controller
{
    public function __invoke(Request $request, string $path): StreamedResponse
    {
        $server = ServerFactory::create([
            'source' => Storage::path(''),
            'cache' => Storage::path('cache'),
            'response' => new SymfonyResponseFactory($request),
        ]);

        Log::info('Trying to get image' . $path);

        try {
            return $server->getImageResponse($path, $request->except(['expires', 'signature']));
        } catch (FileNotFoundException $e) {
            abort(404);
        }
    }
}
