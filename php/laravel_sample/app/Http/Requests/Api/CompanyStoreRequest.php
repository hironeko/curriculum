<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CompanyStoreRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:100'
            ],
            'name_kana' => [
                'required',
                'string',
                'max:100'
            ],
            'post_code' => [
                'required',
                'string',
                'max:8'
            ],
            'prefecture' => [
                'required',
                'string',
                'max:8'
            ],
            'address' => [
                'required',
                'string',
                'max:100'
            ],
            'tel' => [
                'required',
                'string',
                'max:11'
            ],
            'representative_first_name' => [
                'required',
                'string',
                'max:100'
            ],
            'representative_last_name' => [
                'required',
                'string',
                'max:100'
            ],
            'representative_first_name_kana' => [
                'required',
                'string',
                'max:100'
            ],
            'representative_last_name_kana' => [
                'required',
                'string',
                'max:100'
            ],
        ];
    }
}
