<?php

namespace App\EcommerceModel;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Auth;
use DB;

class SalesDetail extends Model
{
    use SoftDeletes;

    protected $table = 'ecommerce_sales_details';
    protected $fillable = [
        'sales_header_id', 'product_id', 'product_name', 'product_category', 'price', 'tax_amount', 'promo_id', 'promo_description', 'discount_amount', 'gross_amount', 'net_amount', 'qty', 'uom', 'created_by'
    ];

    public $timestamp = true;


    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function product()
    {
        return $this->belongsTo('\App\EcommerceModel\Product');
    }

    public function header()
    {
        return $this->belongsTo('\App\EcommerceModel\SalesHeader', 'sales_header_id');
    }

    public function category()
    {
        return $this->belongsTo('\App\ProductCategory','product_category');
    }

    public static function rate_product($productid)
    {
        $qry = DB::table('ecommerce_sales_details')->join('ecommerce_sales_headers','ecommerce_sales_details.sales_header_id','ecommerce_sales_headers.id')->select('ecommerce_sales_details.product_id')->where('ecommerce_sales_details.product_id',$productid)->where('customer_id',Auth::id())->where('ecommerce_sales_headers.payment_status','PAID')->latest('ecommerce_sales_details.id')->count();

        return $qry;
    }

}
