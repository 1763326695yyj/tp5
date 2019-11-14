<?php


namespace app\api\controller;


use app\api\model\Receipt as ReceiptModel;

class Receipt extends Base
{
    public $receiptModel;
    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        $this->receiptModel = new ReceiptModel();
    }

    /**
     * 发票查询
     */
  public function receipt_list(){
      $param = $this->request->param();
//      if (array_key_exists('keywords',$param)){
          $arr = $this->receiptModel->receiptList($param['keywords']??"");
          return ret($arr['data'],$arr['code']);
//      }else{
//          return ret('参数错误',1004);
//      }
  }


    public function receipt_save(){
        $param = $this->request->param();
        if (array_key_exists('receiptData',$param) && array_key_exists('type',$param)){
            if ($param['type'] == 'create') {
               $RSdata =  $this->receiptModel->receiptAdd($param['receiptData']);
            }elseif ($param['type'] == 'update'){
                $RSdata =  $this->receiptModel->receiptEdit($param['receiptData']);
            }else{
                return ret('参数 type 错误',1004);
            }
            return ret($RSdata['data'],$RSdata['code']);
        }else{
            return ret('参数错误',1004);
        }
    }
}