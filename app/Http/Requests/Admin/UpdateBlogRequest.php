<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBlogRquest extends FormRequest
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
    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'], //　`exists:` 送信された値が指定したテーブル(テーブル：categories, カラム：ID)に存在するか確認する
            'title' => ['required', 'max:255'],
            'image' => [
                'nullable', // 省略化
                'file', // ファイルがアップロードされている
                'image', // 画像ファイルである
                'max:2000', // ファイル容量が2000kb以下である
                'mimes:jpeg,jpg,png', // 形式はjpegかpng
                'dimensions:min_width=300,min_height=300,max_width=1200,max_height=1200', // 画像の解像度が300px * 300px ~ 1200px * 1200px
            ],
            'body' => ['required', 'max:20000'],
            'cats.*' => ['distinct', 'exists:cats,id']
        ];
    }
}
