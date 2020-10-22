<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code' => 'required|max:150|unique:products,code',
            'name' => 'required|max:150|unique:products,name',
            'category_id' => 'required',
            'price' => 'required',
            'uom' => 'required',
            'qty' => 'required',
            'meta_title' => 'max:60',
            'meta_keyword' => 'max:150',
            'meta_description' => 'max:250',
            'size' => 'max:30',
            'weight' => 'required'
        ];
    }
}
