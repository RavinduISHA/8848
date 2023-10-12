<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'short_description',
        'category_id',
        'sku',
        'in_stock',
        'image_1_url',
        'image_2_url',
        'image_3_url',
        'image_4_url'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variants()
    {
        return $this->hasMany(Variant::class);
    }

    public function price(){
        $prices = $this->variants->pluck('price')->toArray();
        if(count($prices) <= 1){
            return $prices[0] ?? 0;
        } else{
            return min($prices) . ' - ' . max($prices);
        }
    }

    public function image($index)
    {
        $url = $this['image_' . $index . '_url'];
        if(!$url){
            return '/images/product-dummy.jpeg';
        }
        if(str_starts_with($url, 'http')){
            return $url;
        } else{
            return '/' . str_replace('public', 'storage', $url);
        }
    }

}
