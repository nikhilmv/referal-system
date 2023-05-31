<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 *
 */
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

        $request = [];
        $request['product_name'] = 'required|max:250';
        $request['category_id'] = 'required|numeric';
        $request['price'] = 'required|numeric';
        // $request['product_image'] = 'required|max:500';
        return $request;
    }
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'product_name.required' => 'Product Name cannot be empty.',
            'category_id.required' => 'Category cannot be empty.',
            'price.required' => 'Price cannot be empty',
            'product_image.required' => 'Product image cannot be empty',

        ];
    }
}
