<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InformationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'id' => $this->id,
            'title' => $this->title,
            'phone' => $this->phone,
            'address' => $this->address,
            'social_media' => $this->social_media,
            'user_id' => $this->user_id,
        ];
    }
}
