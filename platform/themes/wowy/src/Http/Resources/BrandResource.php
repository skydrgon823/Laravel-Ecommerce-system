<?php

namespace Theme\Wowy\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use RvMedia;

class BrandResource extends JsonResource
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
            'id'      => $this->id,
            'name'    => $this->name,
            'website' => $this->website,
            'logo'    => RvMedia::getImageUrl($this->logo, null, false, RvMedia::getDefaultImage()),
        ];
    }
}
