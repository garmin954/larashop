<?php

namespace App\Http\Requests\Home\Login;

use App\Rules\MobileRule;
use App\Rules\MsgCodeRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'phone' => ['required', new MobileRule(), 'unique:users'],
            'password' => ['required'],
            'code' => ['required', new MsgCodeRule(request()->phone)]
        ];
    }

    public function messages()
    {
        return [
            'phone.require' => '手机号码不能为空',
            'phone.unique' => '手机号码已注册',
            'password.require' => '密码不能为空',
            'code.require' => '验证码不能为空',
        ];
    }
}
