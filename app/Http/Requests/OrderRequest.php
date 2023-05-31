<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 *
 */
class OrderRequest extends FormRequest
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
        $request['customer_name'] = 'required|max:250';
        $request['phone_no'] = 'required|numeric|digits:10';
        $request['product_id'] = 'required';
        $request['quantity'] = 'required';
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
            'customer_name.required' => 'customer Name cannot be empty.',
            'phone_no.required' => 'phone number cannot be empty.',
            'product_id.required' => 'product cannot be empty',
            'quantity.required' => 'quantity cannot be empty',

        ];
    }
}
