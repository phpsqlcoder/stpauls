<?php

namespace App\EcommerceModel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use DB;

class ProductCategory extends Model
{
    use SoftDeletes;

    public $table = 'product_categories';
    protected $fillable = [ 'parent_id', 'name', 'slug', 'description', 'status', 'created_by',];

    public function get_url()
    {
        return env('APP_URL')."/product-categories/".$this->slug;
    }

    public function child_categories() {
        return  $this->hasMany(ProductCategory::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function featured_products()
    {
        return $this->products()->where('is_featured', 1)->get();
    }

    public static function product_category($id)
    {
        $categories = 
            DB::select("SELECT T2.id, T2.parent_id,T2.name
                FROM (
                    SELECT
                        @r AS _id,
                        (SELECT @r := parent_id FROM product_categories WHERE id = _id) AS parent_id,
                        @l := @l + 1 AS lvl
                    FROM
                        (SELECT @r := $id, @l := 0) vars,
                        product_categories m
                    WHERE @r <> 0) T1
                JOIN product_categories T2
                ON T1._id = T2.id
                ORDER BY T1.lvl DESC ");

        return $categories;

    } 

    public static function count_unsale_products($catid)
    {   
        $products = DB::table('products')->where('status','PUBLISHED')->where('category_id',$catid)->get();

        $count = 0;
        foreach($products as $product){
            $qry = DB::table('promos')->join('onsale_products','promos.id','=','onsale_products.promo_id')->where('promos.status','ACTIVE')->where('promos.is_expire',0)->where('onsale_products.product_id',$product->id)->exists();

            if($qry){
            } else {
               $count += 1; 
            } 
        }

        return $count;
    }

    public static function check_product_in_promo($promoId,$productId)
    {
        $qry = DB::table('promos')->join('onsale_products','promos.id','=','onsale_products.promo_id')->where('onsale_products.promo_id',$promoId)->where('onsale_products.product_id',$productId)->count();
        
        return $qry; 
    }
}
