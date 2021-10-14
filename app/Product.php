<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Product extends Model
{
    use SoftDeletes;
    public function images()
    {
        return $this->hasMany('App\Product_images', 'product_id','id');
    }
    public function category()
    {
        return $this->hasOne('App\Product_categories', 'id','category_id');
    }
}
