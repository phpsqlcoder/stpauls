<?php

namespace App\EcommerceModel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductReview extends Model
{
    use SoftDeletes;

    protected $table = 'ecommerce_product_review';
    protected $fillable = ['product_id', 'user_id','review','rating','customer_id','is_approved','approver','approved_date'];

    public function customer()
    {
        return $this->belongsTo('App\EcommerceModel\Customer','customer_id');
    }

    public function product()
    {
        return $this->belongsTo('App\EcommerceModel\Product');
    }

    public static function review_counter($productId,$rating)
    {
        $qry = 
            ProductReview::where('is_approved',1)
            ->where('product_id',$productId)
            ->where('rating',$rating)
            ->count();

        return $qry;
    }
}
