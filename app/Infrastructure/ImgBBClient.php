<?php

namespace App\Infrastructure;
use Illuminate\Support\Facades\Http;


class ImgBBClient{
    public function uploadImage($image){
        $apiKey = env('IMGBB_API_KEY');
        $response = Http::asMultipart()->post('https://api.imgbb.com/1/upload', [
            'key' => $apiKey,
            'image' => base64_encode(file_get_contents($image->getRealPath())),
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['data']['url'];
        } else {
            throw new \Exception('Error uploading image to ImgBB: ' . $response->body());
        }
    }
}
