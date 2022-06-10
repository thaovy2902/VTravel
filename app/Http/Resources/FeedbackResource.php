<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FeedbackResource extends JsonResource
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
      'name' => $this->name,
      'email' => $this->email,
      'phone_number' => $this->phone_number,
      'type' => $this->type,
      'content' => $this->content,
      'is_seen' => (bool) $this->is_seen,
      'created_at' => $this->created_at->diffForHumans(),
    ];
  }
}
