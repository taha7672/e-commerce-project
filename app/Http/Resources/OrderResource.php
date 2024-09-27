<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        return [
            ...parent::toArray($request),
            "total_amount" => toCurrency($this->total_amount, $this->currency_id),
            "vat_amount" => toCurrency($this->vat_amount, $this->currency_id),
            "discount_amount" => toCurrency($this->discount_amount, $this->currency_id),
            "shipping_amount" => toCurrency($this->shipping_amount, $this->currency_id),
            "paid_amount" => toCurrency($this->paid_amount, $this->currency_id),
            "items" => OrderItemResource::collection($this->whenLoaded('items')),
            "payment" => new PaymentResource($this->whenLoaded('payment')),
        ];
    }
}
