<?php

namespace App\Models\Product;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Config\ProductCategorie;
use App\Models\Product\ProductWarehouse;
use App\Models\Product\ProductWallet;

class Product extends Model
{
    use SoftDeletes;
    protected $fillable = [
        "title",
        "sku",
        "imagen",
        "product_categorie_id",
        "price_general",
        "price_company",
        "description",
        "is_discount",
        "max_discount",
        "is_gift",
        "disponibilidad",
        "state",
        "state_stock",
        "warranty_day",
        "tax_selected",
        "importe_iva",
    ];

    public function setCreatedAtAttribute($value)
    {
    	date_default_timezone_set('America/Lima');
        $this->attributes["created_at"]= Carbon::now();
    }

    public function setUpdatedAtAttribute($value)
    {
    	date_default_timezone_set("America/Lima");
        $this->attributes["updated_at"]= Carbon::now();
    }

    public function product_categorie()
    {
        return $this->belongsTo(ProductCategorie::class, "product_categorie_id");
    }

    //LAS EXISTENCIAS DISPONIBLES DE UN PRODUCTO
    public function warehouses()
    {
        return $this->hasMany(ProductWarehouse::class);
    }

    //LOS PRECIOS DE UN PRODUCTO
    public function wallets()
    {
        return $this->hasMany(ProductWallet::class);
    }
}
