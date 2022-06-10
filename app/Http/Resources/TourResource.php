<?php

namespace App\Http\Resources;

use App\Http\Resources\RatingResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TourResource extends JsonResource
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
            'code' => $this->code,
            'name' => $this->name,
            'slug' => $this->slug,
            'image' => $this->image,
            'gallery' => $this->gallery,
            'depart' => $this->depart,
            'description' => $this->description,
            'from_place_name' => $this->from_place_name,
            'to_place_code' => $this->to_place_code,
            'to_place_name' => $this->to_place_name,
            'transport' => $this->transport,
            'number_days' => $this->number_days,
            'number_persons' => $this->number_persons,
            'price_default' => $this->price_default,
            'price_children' => $this->price_children,
            'price_baby' => $this->price_baby,
            'note' => $this->note,
            'is_featured' => (bool) $this->is_featured,
            'is_active' => (bool) $this->is_active,
            'category_id' => $this->category_id,
            'category_name' => $this->whenLoaded('category')->name,
            'category_slug' => $this->whenLoaded('category')->slug,
            'ratings' => RatingResource::collection($this->whenLoaded('ratings')->load(['user', 'tour'])),
            'avg_rating' => $this->avgRating ?? 0,
            'count_rating' => $this->countRating ?? 0,
            'is_rating' => auth()->check() ? !!$this->ratings->where('user_id', auth()->user()->id)->count() : false,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
        ];
    }
}
