<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RatingResource extends JsonResource
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
      'scores' => $this->scores,
      'content' => $this->content,
      'is_active' => (bool) $this->is_active,
      'author' => $this->whenLoaded('user')->name,
      'avatar' => $this->whenLoaded('user')->avatar,
      'tour' => $this->whenLoaded('tour')->name,
      'is_author' => auth()->check() ? (int) $this->user_id === (int) auth()->user()->id : false,
      'created_at' => (string) $this->created_at->diffForHumans(),
    ];
  }
}
