<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->resource->id,
            "title" => $this->resource->title,
            "sku" => $this->resource->sku,
            "imagen" => $this->resource->imagen,
            "product_categorie_id" => $this->resource->product_categorie_id,
            "product_categorie" => [
                "id" => $this->resource->product_categorie->id,
                "name" => $this->resource->product_categorie->name,
            ],
            "price_general" => $this->resource->price_general,
            "price_company" => $this->resource->price_company,
            "description" => $this->resource->description,
            "is_discount" => $this->resource->is_discount,
            "max_discount" => $this->resource->max_discount,
            "is_gift" => $this->resource->is_gift,
            "disponibilidad" => $this->resource->disponibilidad,
            "state" => $this->resource->state,
            "state_stock" => $this->resource->state_stock,
            "warranty_day" => $this->resource->warranty_day,
            "tax_selected" => $this->resource->tax_selected,
            "importe_iva" => $this->resource->importe_iva,
            "created_at" => $this->resource->created_at->format("Y-m-d h:i A"),
        ];
    }
}
