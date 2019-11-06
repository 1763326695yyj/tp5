<?php


namespace app\api\controller;

use app\api\model\Company as companyModel;

class Company extends Base
{
    public $companyModel;
    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        $this->companyModel = new companyModel();
    }

    public function company_save(){
        $param = $this->request->param();
        if (array_key_exists('companyData',$param) && array_key_exists('type',$param) && !empty($param['companyData']['attr_info']) && !empty($param['companyData']['image_url']) && is_array($param['companyData']['attr_info'])){
            if ($param['type'] == 'update'){
                //修改
            }elseif ($param['type'] == 'create') {
                //新增
                $CAdata = $this->companyModel->companyAdd($param['companyData']);
             }else{
                //其他
                return ret('参数错误',1004);
            }
              if ($CAdata['code'] == 0){
                  //添加公司-属性关联
               $CALdata =  $this->companyModel->companyAttrLink($param['companyData']['attr_info'],$CAdata['data']);
               if (!empty($CALdata)){
                   //添加图片(暂无)
                   $CPAdata =  $this->companyModel->companyPicAdd($param['companyData']['image_url']);
                   if ($CPAdata){
                       $this->companyModel->companySaveOne($CAdata['data'],'is_del',0);
                       return ret('添加成功');
                   }
               }
              }else{
                  $reqData = $CAdata;
              }
              return ret($reqData['data'],$reqData['code']);

        }
        return ret('参数错误',1004);
    }

}