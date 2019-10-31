<?php


namespace app\api\model;
header("Content-Type: text/html; charset=UTF-8");

use think\Db;
use think\Model;
use think\Validate;

class Client extends Model
{
    public $rule;
    public $msg;
    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        $this->rule = [
            'username' => 'require|max:25',
            'pwd' => 'regex:/^[\w.]{5,20}$/'
        ];
        $this->msg = [
            'username.require' => '名称必须',
            'username.max'     => '名称最多不能超过25个字符',
            'pwd.regex'     => '密码格式不对',
        ];
    }

    /**
     *账号密码验证用户
     */
    public function NamePwdFind($data){
        $validate   = Validate::make($this->rule,$this->msg);
        if (!$validate->check($data)){
            return ret($validate->getError(),4000);
        }else{
           $user = $this->where('username',$data['username'])->where('userpwd',md6($data['pwd']))->find();
           if (empty($user)){
               return ret('账号或密码输入错误',4001);
           }else{
               if ($user['status'] == 0){
                   return ret('该账号已被锁定',4002);
               }else{
                   $saveData = ['token'=>md6($user['userpwd']),'token_time'=>time()];
                   $this->save($saveData,['uid'=>$user['uid']]);
                   $reqData = ['user'=>$user,'token'=>$saveData['token']];
                   return ret($reqData);
               }
           }
        }
    }

    /**
     * token验证用户
     * info_type 1 需要获取用户信息 0 不需要返回用户信息
    */
    public function tokenFind($token,$info_type=''){
        $user = $this->where('token',$token)->find();
        if (empty($user)){
            return ret('token验证失败',2001);
        }else{
            if ($user['token_time'] < time()-TOKEN_TIME){
                return ret('token失效',2002);
            }
            if ($user['status'] == 0){
                return ret('该账号已被锁定',4002);
            }
            $this->save(['token'=>md6($user['userpwd']),'token_time'=>time()],['uid'=>$user['uid']]);
            $user = $this->where('uid',$user['uid'])->find();
            if ($info_type == 1) {
                return $user->toArray();
            }else{
                return true;
            }
        }
    }

}