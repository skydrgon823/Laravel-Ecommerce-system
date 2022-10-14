<?php

namespace Botble\Ecommerce\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                         => $this->id,
            'name'                       => $this->name,
            'sku'                        => $this->sku,
            'description'                => $this->description,
            'slug'                       => $this->slug,
            'with_storehouse_management' => $this->with_storehouse_management,
            'quantity'                   => $this->quantity,
            'is_out_of_stock'            => $this->isOutOfStock(),
            'stock_status_label'         => $this->stock_status_label,
            'stock_status_html'          => $this->stock_status_html,
            'price'                      => $this->price_with_taxes,
            'sale_price'                 => $this->front_sale_price_with_taxes,
            'original_price'             => $this->original_price,
            'image_with_sizes'           => $this->image_with_sizes,
            'display_price'              => format_price($this->price_with_taxes),
            'display_sale_price'         => format_price($this->front_sale_price_with_taxes),
            'sale_percentage'            => get_sale_percentage($this->price, $this->front_sale_price),
            'unavailable_attribute_ids'  => $this->unavailableAttributeIds,
            'success_message'            => $this->successMessage,
            'error_message'              => $this->errorMessage,
            'weight'                     => $this->weight,
            'height'                     => $this->height,
            'wide'                       => $this->wide,
            'length'                     => $this->length,
        ];
    }
}
