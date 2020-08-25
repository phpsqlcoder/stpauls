<?php

namespace App\EcommerceModel;

use Illuminate\Database\Eloquent\Model;

class ProductTag extends Model
{    
    public $table = 'product_tags';
    protected $fillable = [ 'product_id', 'tag', 'created_by' ];


    public static function related_products($product_id)
    {
        $tags = ProductTag::where('product_id',$product_id)->get();

        $products = "";
        foreach($tags as $tag){
            $related_tags = ProductTag::where('product_id','<>',$product_id)->where('tag',$tag->tag)->get();
            foreach($related_tags as $rtag){
                $products .= $rtag->product_id."|";
            }
        }
        
        return rtrim($products,'|');
    }
}
