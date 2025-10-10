<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StationResource extends JsonResource
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
            'name' => $this->name_en,
            'name_th' => $this->name_th,
            'piername' => $this->piername_en,
            'piername_th' => $this->piername_th,
            'nickname' => $this->nickname,
            'type' => $this->type
        ];
    }
}
