<?php
// +---------------------------------------------------------------------+
// | OneBase    | [ WE CAN DO IT JUST THINK ]                            |
// +---------------------------------------------------------------------+
// | Licensed   | http://www.apache.org/licenses/LICENSE-2.0 )           |
// +---------------------------------------------------------------------+
// | Author     | Bigotry <3162875@qq.com>                               |
// +---------------------------------------------------------------------+
// | Repository | https://gitee.com/Bigotry/OneBase                      |
// +---------------------------------------------------------------------+

namespace app\api\logic;

use app\api\error\CodeBase;
use app\api\error\Common as CommonError;
use \Firebase\JWT\JWT;

/**
 * 接口基础逻辑
 */
class Common extends ApiBase
{

    /**
     * 登录接口逻辑
     */
    public function login($data = [])
    {
      
        $validate_result = $this->validateMember->scene('login')->check($data);
        
        if (!$validate_result) {
            
            return CommonError::$usernameOrPasswordEmpty;
        }
        
        $member = $this->logicMember->getMemberInfo(['username' => $data['username']]);
        
        if (data_md5_key($data['password']) !== $member['password']) {
            
            return CommonError::$passwordError;
        }
        
        return $this->tokenSign($member);
    }
    
    /**
     * JWT验签方法
     */
    public static function tokenSign($member)
    {
        
        $key = API_KEY . JWT_KEY;
        
        $jwt_data = ['id' => $member['id'] ,'member_id' => $member['id'], 'nickname' => $member['nickname'], 'username' => $member['username'], 'create_time' => $member['create_time']];
        
        $token = [
            "iss"   => "OneBase JWT",         // 签发者
            "iat"   => TIME_NOW,              // 签发时间
            "exp"   => TIME_NOW + TIME_NOW,   // 过期时间
            "aud"   => 'OneBase',             // 接收方
            "sub"   => 'OneBase',             // 面向的用户
            "data"  => $jwt_data
        ];
        
        $jwt = JWT::encode($token, $key);
        
        $jwt_data['user_token'] = $jwt;
        
        return $jwt_data;
    }
}
