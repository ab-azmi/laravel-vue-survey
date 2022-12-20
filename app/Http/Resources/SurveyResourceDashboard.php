<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class SurveyResourceDashboard extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'image_url' => $this->image ? URL::to($this->image) : null,
            'title' => $this->title,
            'slug' => $this->slug,
            'status' => $this->status !== 'draft',
            'expire_date' => Carbon::parse($this->expire_date)->diffForHumans(),
            'created_at' => Carbon::parse($this->created_at)->format('D F Y, H:i a'),
            'questions' => $this->questions->count(),
            'answers' => $this->answers->count(),
        ];
    }
}
