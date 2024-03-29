<?php


namespace app\api\controller;

use app\api\model\PutBill as putModel;

class PutBill extends Base
{
    public $putModel;
    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        $this->putModel = new putModel();
    }
// public function putList($id,$bill_sn,$bill_type,$start_time=0,$end_time=0,$is_del='0,1'){
    public function put_list(){
        $param = $this->request->param();
       $put_data = $this->putModel->putList($param['id']??'',$param['bill_sn']??'',$param['bill_type']??'',$param['state']??'',$param['start_time']??'',$param['end_time']??'',$param['is_del']??'0,1',$param['is_bad']??'0,1');
        if ($put_data['code']==0){
            return ret($put_data['data'],$put_data['code']);
        }else{
            return ret($put_data['data'],$put_data['code']);
        }
    }

    public function put_save(){
        $param = $this->request->param();
        if (array_key_exists('type',$param) && array_key_exists('putData',$param)){
            if ($param['type'] == 'update'){
                //修改
                $save_data = $this->putModel->putEdit($param['putData']);
            }elseif ($param['type']== 'create'){
                //新增
                $save_data = $this->putModel->putAdd($param['putData']);
            }else{
                return ret('参数错误',1004);
            }
            if (!empty($save_data)){
                 return ret($save_data['data'],$save_data['code']);
            }

        }else{
          return ret('参数错误',1004);
        }
    }
    public function order_type_save(){
        $param = $this->request->param();
        if (array_key_exists('value',$param) && array_key_exists('id',$param) && array_key_exists('key',$param)){
           $putList =   $this->putModel->putList($param['id']);
          if (!empty($put_one = $putList['data'][0])){
              if ($put_one['bill_type'] == 1 || $put_one['bill_type'] == 3){
                 $req =  $this->putModel->putOneEdit($param['id'],$param['key'],$param['value']);
                return ret($req['data'],$req['code']);
              }else{
                  return ret('参数错误,单据类型有误',1004);
              }
          }
        }else{
            return ret('参数错误',1004);
        }
    }
}