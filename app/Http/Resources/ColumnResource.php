<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ColumnResource extends JsonResource
{
    /**
     * @param $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'cards' => $this->cards,
        ];
    }
}
