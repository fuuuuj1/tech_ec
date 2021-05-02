<?php

namespace App\Http\Requests\Mypage\Requsets;

use Illuminate\Foundation\Http\FormRequest;

class EditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // falseを返した場合は画面にエラーメッセージが表示される
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // ここで任意のバリデーションルールを定義できる プロフィール画面の編集で定義するべきバリデーションは
        // 送信されてきた「名前」情報に対して、入力必須、文字列、255字を超えていないかのバリデーションを定義している
        return [
            'name' => ['required', 'string', 'max:255'],
        ];
    }
}
