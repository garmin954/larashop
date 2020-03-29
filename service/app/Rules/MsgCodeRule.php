<?php

namespace App\Rules;

use App\Models\SmsLog;
use Illuminate\Contracts\Validation\Rule;

class MsgCodeRule implements Rule
{

    protected $phone;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($phone)
    {
        $this->phone = $phone;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (empty($this->phone)){
            return false;
        }

        $sms_where = [
            'code'=>json_encode(['code'=>intval($value)]), // 验证码数据
            'phone'=>$this->phone,
            'is_type'=>2, // 类型
        ];


        $sms_log = SmsLog::where($sms_where)->where('add_time','>',time()-300)->first();

        if(empty($sms_log)){
            return false;
        }

        // 标记已使用
        SmsLog::where('id',$sms_log['id'])->update(['is_use'=>1]);

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '验证码错误！';
    }
}
