<?php


namespace app\api\controller;
use app\api\model\Attr as attrModel;
use app\api\model\AttrText;

class Attr extends Base
{
    public  $attrModel;
    public  $attrText;
    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        $this->attrModel = new attrModel();
        $this->attrText = new AttrText();
    }
    /**
     * 属性添加
     * @return \think\response\Json
     */
    public function attr_add(){
        $param = $this->request->param();
        if (array_key_exists('attr_name',$param)){
            $addAttrData = $this->attrModel->add($param);
            return ret($addAttrData['data'],$addAttrData['code']);
        }else{
            return ret('参数错误',1004);
        }
    }

    /**
     * 查询当前属性下所有属性值
     */
    public function text_find(){
        $param = $this->request->param();
        if (array_key_exists('attr_id',$param) ){
            $ATFdata = $this->attrModel->AttrTextFind($param['attr_id']);
            return ret($ATFdata['data'],$ATFdata['code']);
        }else{
            return ret('参数错误',1004);
        }
    }

    /**
     * 属性值添加
     */
    public function text_add(){
        $param = $this->request->param();
        if (array_key_exists('attr_id',$param) && array_key_exists('text_name',$param)){
            $ATAdata = $this->attrText->add($param);
            return ret($ATAdata['data'],$ATAdata['code']);
        }else{
            return ret('参数错误',1004);
        }
    }
}