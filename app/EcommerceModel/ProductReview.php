<?php

namespace App\EcommerceModel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductReview extends Model
{
    use SoftDeletes;

    protected $table = 'ecommerce_product_review';
    protected $fillable = ['product_id', 'product_name', 'user_id','review','rating','customer_id','is_approved','approver','approved_date'];

    public function customer()
    {
        return $this->belongsTo('App\EcommerceModel\Customer','customer_id','customer_id');
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

    public static function category_rating_counter($category,$rating)
    {
        $count = 
            \App\EcommerceModel\Product::where('status','PUBLISHED')->where('category_id',$category)->whereIn('id', function($query) use ($rating){
                $query->select('product_id')->from('ecommerce_product_review')
                    ->where('is_approved',1)
                    ->where('rating',$rating);
            })->count();

        return $count;

    }
}
