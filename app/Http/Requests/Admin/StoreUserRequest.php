<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'image' => [
                'required',
                'file', // ファイルがアップロードされている
                'image', // 画像ファイルである
                'max:20000', // ファイル容量が20Mb以下である
                'mimes:jpeg,jpg,png', // 形式はjpegかpng
                'dimensions:min_width=100,min_height=100,max_width=1000,max_height=1000', // 画像の解像度が100px * 100px ~ 1000px * 1000px
            ],
            'introduction' => ['required', 'string', 'max:255'],
        ];
    }
    
    // 属性名の翻訳
    public function attributes()
    {
        return [
            'introduction' => '自己紹介文'
        ];
    }
}
