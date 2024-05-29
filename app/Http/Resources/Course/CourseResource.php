<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'code' => $this->code,
            'weeks' => $this->weeks,
            'credits' => $this->credits,
            'status' => $this->status,
            'status_name' => __('attribute.'.$this->status->name),
            'details' => $this->details,
            'created_at' => $this->created_at,
        ];
    }
}
