<?php


namespace app\api\model;


use think\Model;

class Apptoken extends Model
{
    public function create_token($appid,$appsecret){
        $where[] = ['aoo_id','=',$appid];
        $where[] = ['app_secret','=',$appsecret];
        if (!empty($self = $this->where($where)->find())){
            $token = md6($appid.$appsecret.date('Ymd',time()));
            $saveData = ['app_token'=>$token,'token_time'=>time()+API_TOKEN_TIME];
//            $this->allowField(true)->save($saveData,['id'=>$self['id']]);
            return $saveData;
        }else{
            return ret('appid与appsecret不一致',1202);
        }
    }
}