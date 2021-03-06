<?php

namespace App\EcommerceModel;

use App\Product;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{

    protected $table = 'ecommerce_shopping_cart';
    protected $fillable = ['user_id', 'product_id', 'price', 'qty'];
    const GUEST_CART = 'cart';
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo('\App\EcommerceModel\Product');
    }

    public function getItemTotalPriceAttribute()
    {
        return ($this->product->price * $this->qty);
    }

    public function getPriceWithCurrencyAttribute()
    {
        return " ".number_format($this->price,2);
    }

    public function getTotalWeightAttribute()
    {
        return ($this->product->weight * $this->qty);
    }

    public static function total_cart()
    {
        if (\Auth::check()) {
            $cart = Cart::where('user_id', auth()->id())->get();
        } else {
            $cart = session(Cart::GUEST_CART, []);
        }       

        return self::total_price_of_cart($cart);
    }

    public static function total_items_of_guest_cart()
    {
        $cart = session(Cart::GUEST_CART, []);

        return self::total_items_of_cart($cart);
    }

    public static function total_items_of_auth_cart()
    {
        $cart = Cart::where('user_id', auth()->id())->get();

        return self::total_items_of_cart($cart);
    }

    private static function total_items_of_cart($cart)
    {
        $qty = 0;
        foreach ($cart as $order) {
            $qty += $order->qty;
        }

        return $qty;
    }

    private static function total_price_of_cart($cart)
    {
        $qty = 0;
        foreach ($cart as $order) {
            $qty += $order->qty * $order->price;
        }

        return $qty;
    }


}
