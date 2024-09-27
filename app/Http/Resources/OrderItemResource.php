<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            ...parent::toArray($request),
            "price_at_order" => toCurrency($this->price_at_order, $this->order->currency_id),
            "sub_total" => toCurrency($this->sub_total, $this->order->currency_id),
            "product" => new ProductResource($this->whenLoaded('product')),
        ];
    }
}
