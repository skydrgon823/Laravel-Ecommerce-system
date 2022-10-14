<?php

namespace Theme\Wowy\Http\Resources;

use Botble\Blog\Http\Resources\CategoryResource;
use Botble\Blog\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use RvMedia;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'url'         => $this->url,
            'description' => $this->description,
            'image'       => $this->image ? RvMedia::url($this->image) : null,
            'category'    => $this->categories->count() > 0 ? new CategoryResource($this->categories->first()) : new CategoryResource(new Category()),
            'created_at'  => $this->created_at->translatedFormat('M d, Y'),
            'views'       => number_format($this->views),
        ];
    }
}
