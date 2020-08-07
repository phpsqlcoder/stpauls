<?php

namespace App\EcommerceModel;

use Illuminate\Database\Eloquent\Model;

class ProductAdditionalInfo extends Model
{
    protected $table = 'product_additional_info';
    protected $fillable = ['product_id', 'synopsis','authors','materials','no_of_pages','isbn','editorial_reviews','about_author','additional_info'];
    
    public $timestamps = false;
}
