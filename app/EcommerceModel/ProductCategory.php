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

    public function parent()
    {
        return $this->belongsTo(ProductCategory::class, 'parent_id');
    }

    public function child_categories() {
        return  $this->hasMany(ProductCategory::class, 'parent_id')->orderBy('name','asc');
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
        $count = Product::whereNotIn('id',function($query){
                $query->select('product_id')->from('onsale_products')
                ->join('promos','onsale_products.promo_id','=','promos.id')
                ->where('promos.status','ACTIVE')
                ->where('promos.is_expire',0);
            })->where('status','PUBLISHED')->where('discount','<',1)->where('category_id',$catid)->count();

        return $count;
    }

    public static function categoryName($catId)
    {
        $qry = ProductCategory::find($catId);

        return $qry->name;
    }

    public function getTotalSubAttribute()
    {
        $counter = 0;
        $subcategories = [];
        foreach($this->child_categories as $child){
            $counter++;
            foreach($child->child_categories as $sub){
                $counter++;
            }
        }

        return $counter;
    }


    public function getTotalProductsAttribute()
    {
        $products = Product::where('category_id',$this->id)->count();

        return $products;
    }

    public function getCategoryLevelAttribute()
    {
        $parent = $this->parent;
        $counter = 0;

        while(!is_null($parent)) {
            $parent = $parent->parent;
            $counter++;
        }

        return $counter;
    }

    
    // coupons
    public function  published_products()
    {
        return $this->hasMany(Product::class, 'category_id')->where('status','PUBLISHED');
    }
}
