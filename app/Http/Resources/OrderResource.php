<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'customer_name' => $this->customer_name,
            'customer_email' => $this->customer_email,
            'customer_phone_number' => $this->customer_phone_number,
            'customer_address' => $this->customer_address,
            'code' => $this->code,
            'date_depart' => $this->date_depart,
            'quantity_people' => $this->quantity_people,
            'quantity_children' => $this->quantity_children,
            'quantity_baby' => $this->quantity_baby,
            'total_people' => (int) $this->quantity_people + $this->quantity_children + $this->quantity_baby,
            'discount_code' => $this->discount_code,
            'discount_percent' => (int) $this->discount_percent,
            'total' => $this->total,
            'discount' => $this->discount,
            'total_amount' => $this->total_amount,
            'note' => $this->note,
            'status' => (int) $this->status,
            'reason_cancel' => $this->status === 4 ? $this->reason_cancel : '',
            'is_paid' => (bool) $this->is_paid,
            'payment_method' => $this->whenLoaded('payment')->payment_method,
            'tour' => new TourResource($this->whenLoaded('tour')->load(['category', 'ratings'])),
            'user' => new UserResource($this->whenLoaded('user')->load(['role'])),
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
        ];
    }
}
