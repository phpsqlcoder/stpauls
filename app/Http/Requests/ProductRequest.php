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
            'code' => 'required|max:150',
            'name' => 'required|max:150|unique:products,name',
            'category_id' => 'required',
            'uom' => 'required',
            'meta_title' => 'max:60',
            'meta_keyword' => 'max:150',
            'meta_description' => 'max:250',
            'price' => 'required',
            'size' => 'max:30'
        ];
    }
}
