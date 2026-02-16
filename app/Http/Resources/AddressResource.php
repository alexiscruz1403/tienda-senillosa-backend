<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "city" => $this->city ?? '',
            "street"=> $this->street ?? '',
            "postal_code"=> $this->postal_code ?? '',
            "province"=> $this->province ?? '',
            "department"=> $this->department ?? '',
            "additional_info"=> $this->additional_info ?? '',
        ];
    }
}
