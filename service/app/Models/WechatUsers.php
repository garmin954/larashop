<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WechatUsers extends Model
{
    protected $table = "wechat_users"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;
    protected $fillable = ['user_id','openid','nickname','avatar','sex','unionid','add_time','xcx_openid'];


    public function createWechatUser($uid, $data)
    {
        return $this->create([
            "user_id" => $uid,
            "openid" => '',
            "nickname" => $data['nickName'],
            "avatar" => $data['avatarUrl'],
            "sex"=> $data['gender'],
            "unionid" => isset($data['unionid'])?$data['unionid']:'',
            "add_time" => time(),
            "xcx_openid" => $data['openId'],
        ]);

    }
}
