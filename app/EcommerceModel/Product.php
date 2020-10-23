<?php

namespace App\EcommerceModel;

use App\EcommerceModel\Wishlist;
use App\EcommerceModel\ProductReview;
use App\EcommerceModel\Cart;
use App\User;
use Carbon;
use DB;
use App\InventoryReceiverDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Product extends Model
{
    use SoftDeletes;

    public $table = 'products';
    protected $fillable = [ 'code', 'category_id', 'name', 'slug', 'short_description', 'description', 'currency', 'price', 'size','weight', 'status', 'is_featured', 'uom', 'created_by', 'meta_title', 'meta_keyword', 'meta_description','zoom_image','reorder_point','discount','is_pickup','is_recommended','isfront','beta_id'];

    public function get_url()
    {
        return env('APP_URL')."/product-info/".$this->slug;
    }

    public function user()
    {
        return $this->belongsTo('App\User','created_by');
    }

    public function getPriceWithCurrencyAttribute()
    {
    	return " ".number_format($this->price,2);
    }

    public function ratings()
    {
        return $this->hasMany(ProductReview::class,'product_id');
    }

   
    public function tags(){
        return $this->hasMany('App\EcommerceModel\ProductTag');
    }

    public function category(){
        return $this->belongsTo('App\EcommerceModel\ProductCategory')->withTrashed()->withDefault(['id' => '0','name' => 'Uncategorized']);
        
    }

    public function additional_info()
    {
        return $this->belongsTo('\App\EcommerceModel\ProductAdditionalInfo','id','product_id');
    }

    // public static function colors($value){

    //     $colors = \DB::table('products_variations')->select('color')->distinct()->where('product_id',$value)->get();
    //     return $colors;

    // }

    // public static function sizes($value){

    //     $sizes = \DB::table('products_variations')->select('size')->distinct()->where('product_id',$value)->get();
    //     return $sizes;

    // }

    public function photos()
    {
        return $this->hasMany('App\EcommerceModel\ProductPhoto');
    }

    public function getPhotoPrimaryAttribute()
    {
        $photo = $this->photos()->where('is_primary', 1)->first();
        if(!$photo){
            return '0/no_image_available.jpg';
        }
        else{
            return $photo->path;
        }
    }

    public function getInventoryAttribute()
    {
        
        $in = \DB::table('inventory_receiver_details')
                ->leftJoin('inventory_receiver_header', 'inventory_receiver_details.header_id', '=', 'inventory_receiver_header.id')
                ->where('inventory_receiver_details.product_id','=',$this->id)
                ->where('inventory_receiver_header.status','=','POSTED')
                ->sum('inventory_receiver_details.inventory');

        if(empty($in))
            $in=0;

        $cart = \App\EcommerceModel\Cart::where('product_id',$this->id)->sum('qty');
        if(empty($cart))
            $cart=0;

        $out = \DB::table('ecommerce_sales_details')
                ->leftJoin('ecommerce_sales_headers', 'ecommerce_sales_details.sales_header_id', '=', 'ecommerce_sales_headers.id')
                ->where('ecommerce_sales_details.product_id','=',$this->id)
                ->where('ecommerce_sales_headers.delivery_status','=','Scheduled for Processing')
                ->where('ecommerce_sales_headers.status','=','active')
                ->sum('qty');

        if(empty($out))
            $out=0;
        
        return ($in - ($out + $cart));
      
    }

    public function getInventoryActualAttribute()
    {
        $in = \DB::table('inventory_receiver_details')
                ->leftJoin('inventory_receiver_header', 'inventory_receiver_details.header_id', '=', 'inventory_receiver_header.id')
                ->where('inventory_receiver_details.product_id','=',$this->id)
                ->where('inventory_receiver_header.status','=','POSTED')
                ->sum('inventory_receiver_details.inventory');
        if(empty($in))
            $in=0;        

        
        $out = \DB::table('ecommerce_sales_details')
                ->leftJoin('ecommerce_sales_headers', 'ecommerce_sales_details.sales_header_id', '=', 'ecommerce_sales_headers.id')
                ->where('ecommerce_sales_details.product_id','=',$this->id)
                ->where('ecommerce_sales_headers.delivery_status','=','Scheduled for Processing')
                ->where('ecommerce_sales_headers.status','=','active')
                ->sum('qty');
        if(empty($out))
            $out=0;
        
        return ($in - $out);
      
    }

    public function getMaxpurchaseAttribute() //use for identifying the maximum qty a customer can order
    {
        

        $in = \DB::table('inventory_receiver_details')
                ->leftJoin('inventory_receiver_header', 'inventory_receiver_details.header_id', '=', 'inventory_receiver_header.id')
                ->where('inventory_receiver_details.product_id','=',$this->id)
                ->where('inventory_receiver_header.status','=','POSTED')
                ->sum('inventory_receiver_details.inventory');
        if(empty($in))
            $in=0;

        $cart = \App\EcommerceModel\Cart::where('product_id',$this->id)->sum('qty');
         if(empty($cart))
            $cart=0;
        
        $out = \DB::table('ecommerce_sales_details')
                ->leftJoin('ecommerce_sales_headers', 'ecommerce_sales_details.sales_header_id', '=', 'ecommerce_sales_headers.id')
                ->where('ecommerce_sales_details.product_id','=',$this->id)
                ->where('ecommerce_sales_headers.delivery_status','=','Scheduled for Processing')
                ->where('ecommerce_sales_headers.status','=','active')
                ->sum('ecommerce_sales_details.qty');
        if(empty($out))
            $out=0;
        
        $inventory = $in - ($out + $cart + $this->reorder_point);

        return $inventory;
      
    }

    public static function related_products($id){

        $products = Product::whereStatus('PUBLISHED')->where('id','<>',$id)->take(3)->get();


        $data = '';

        foreach($products as $product){
            $data .= '
                <div class="col-md-4 col-sm-6 item">
                    <div class="product-link">
                        <div class="product-card">
                            <a href="'.route("product.front.show",$product->slug).'">
                                <div class="product-img">
                                    <img src="'.asset("storage/products/".$product->photoPrimary).'" alt="" />
                                </div>
                                <div class="gap-30"></div>
                                <p class="product-title">'.$product->name.'</p>
                            </a>
                            <div class="rating small">
                                '.$product->ratingStar.'
                            </div>
                            <h3 class="product-price">'.$product->priceWithCurrency.'</h3>
                        </div>
                    </div>
                </div>
            ';
        }

        return $data;
    }

    public function on_sale()
    {
        return $this->belongsTo('\App\StPaulModel\OnSaleProducts','id','product_id');
    }

    // public function getDiscountedAmountAttribute()
    // {
    //     $saleChecker = DB::table('promos')->join('onsale_products','promos.id','=','onsale_products.promo_id')->where('promos.status','ACTIVE')->where('promos.is_expire',0)->where('onsale_products.product_id',$this->id)->count();

    //     if($saleChecker > 0){

    //         $discount = ($this->on_sale->promo_details->discount/100);
    //         $discountedAmount = ($this->price * $discount);

    //     } else {



    //     }




    //     $discount = ($this->on_sale->promo_details->discount/100);

    //     return ($this->price * $discount);
    // }

    // public function getDiscountedPriceAttribute()
    // {
    //     return ($this->price - $this->DiscountedAmount);
    // }

    public function getDiscountedPriceAttribute()
    {
        $saleChecker = DB::table('promos')->join('onsale_products','promos.id','=','onsale_products.promo_id')->where('promos.status','ACTIVE')->where('promos.is_expire',0)->where('onsale_products.product_id',$this->id)->count();

        if($saleChecker > 0){
            $discount = ($this->on_sale->promo_details->discount/100);
            $discountedAmount = ($this->price * $discount);

            $price = ($this->price - $discountedAmount);
        } else {
            if($this->discount > 0){
                $price = ($this->price - $this->discount);
            } else {
                $price = $this->price;
            }
        }

        return $price;
    }

    public static function onsale_checker($id)
    {
        $checkproduct = DB::table('promos')->join('onsale_products','promos.id','=','onsale_products.promo_id')->where('promos.status','ACTIVE')->where('promos.is_expire',0)->where('onsale_products.product_id',$id)->count();

        return $checkproduct;
    }

    public static function products_cat($categoryid)
    {
        $products = 
            Product::whereNotIn('id',function($query){
                $query->select('product_id')->from('onsale_products')
                ->join('promos','onsale_products.promo_id','=','promos.id')
                ->where('promos.status','ACTIVE')
                ->where('promos.is_expire',0);
            })->where('isfront',1)->where('category_id',$categoryid)->where('status', 'PUBLISHED')->where('is_recommended',0)->orderBy('name','asc')->get();

        return $products;
    }
















    

    // public static function totalProduct()
    // {
    //     $total = Product::withTrashed()->get()->count();

    //     return $total;
    // }

    // public function is_editable()
    // {
    //     return $this->status != 'UNEDITABLE';
    // }

    // public static function info($p){

    //     $pd = Product::where('name','=',$p)->first();

    //     return $pd;
    // }

    // public static function detail($p){

    //     $pd = Product::where('name',$p)->get();

    //     return $pd;
    // }    

    public function get_image_file_name()
    {
        $path = explode('/', $this->zoom_image);
        $nameIndex = count($path) - 1;
        if ($nameIndex < 0)
            return '';

        return $path[$nameIndex];
    }

    public function reviews()
    {
        return $this->hasMany('App\EcommerceModel\ProductReview');
    }

    // public function getRatingAttribute()
    // {
    //     return $this->reviews->avg('rating');
    // }

    // public function getRatingStarAttribute(){
    //     $star = 5 - (integer) $this->rating;
    //     $front = '';
    //     for($x = 1; $x<=$this->rating; $x++){
    //         $front.='<span class="fa fa-star checked"></span>';
    //     }

    //     for($x = 1; $x<=$star; $x++){
    //         $front.='<span class="fa fa-star"></span>';
    //     }

    //     return $front;
    // }


    

}
