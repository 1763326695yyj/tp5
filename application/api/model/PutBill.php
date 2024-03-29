<?php


namespace app\api\model;


use think\Exception;
use think\Model;
use think\Validate;

//销毁订单 预计返款时间，金额，返款备注 没写

class PutBill extends Model
{
    public $table = PUT_BILL;
    public $rule;
    public $rule_edit;
    public $msg;
    public $msg_edit;
    public $state;
    public $state_sn;
    public $bill_type;
    public $house_type;
    public $receipt_type;
    public $order_type;
    public $pay_type;
    public $goodsModel;
    public $companyModel;
    public $houseModel;
    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        //动态验证规则
        $this->rule_edit = [
            'bill_type' => 'require',
//            'mobile' => ['require','regex'=>'/^1([38][0-9]|4[579]|5[0-3,5-9]|6[6]|7[0135678]|9[89])\d{8}$/'],
            'total' => 'require|float',
            'pay_type' => 'require',
            'state' => 'require|number',

        ];
        //动态验证数组，error_message
        $this->msg_edit = [
            'bill_type.require' => '类型必填',
            'total.require'     => '总价必填',
            'total.float'     => '价格输入有误',
            'pay_type.require'     => '付款状态必填',
            'state.require'     => '单据状态必填',
            'state.number'     => '单据状态格式不正确',
        ];
        //动态验证规则
        $this->rule = [
            'bill_type' => 'require',
//            'mobile' => ['require','regex'=>'/^1([38][0-9]|4[579]|5[0-3,5-9]|6[6]|7[0135678]|9[89])\d{8}$/'],
            'total' => 'require|float',
            'pay_type' => 'require',
            'goods_info' => 'require|array',
            'company_info' => 'require',
            'state' => 'require|number',

        ];
        //动态验证数组，error_message
        $this->msg = [
            'bill_type.require' => '类型必填',
            'total.require'     => '总价必填',
            'total.float'     => '价格输入有误',
            'pay_type.require'     => '付款状态必填',
            'goods_info.require'     => '商品信息必填',
            'goods_info.array'     => '商品信息格式错误',
            'company_info.require'     => '公司信息必填',
            'company_info.array'     => '公司信息格式错误',
            'state.require'     => '单据状态必填',
            'state.number'     => '单据状态格式不正确',
        ];
        $this->state = [
            1 => '购入入库',
            2 => '退回入库',
            3 => '调库入库',
            4 => '销售出库',
            5 => '销毁出库',
            6 => '调库出库',
            7 => '其他入库',
            8 => '其他出库',
            9 => '入库订单',
            10 => '出库订单',
            11 => '下游退货',
            12 => '上游退货',
        ];
        $this->state_sn = [
            1 => 'GRRK',
            2 => 'THRK',
            3 => 'DKRK',
            4 => 'XSCK',
            5 => 'XHCK',
            6 => 'DKCK',
            7 => 'QTRK',
            8 => 'QTCK',
            9 => 'RKDD',
            10 => 'CKDD',
            11 => 'XYTH',
            12 => 'SYTH',
        ];
        $this->pay_type = [
            1 => '已付款',
            2 => '未付款',
        ];
        $this->receipt_type = [
            1 => '带票',
            2 => '无发票',
        ];
        $this->house_type = [
            1 => '单仓库',
            2 => '多仓库',
        ];
        $this->bill_type = [
          1 => '入库订单',
          2 => '入库单',
          3 => '出库订单',
          4 => '出库单',
        ];
        $this->order_type = [
            1 => '已完结',
            2 => '未完结',
            3 => '退回',
            4 => '未退回',
        ];
        $this->goodsModel = new Goods();
        $this->companyModel = new Company();
        $this->houseModel = new House();
    }

    /**
     * @param $id
     * @param $bill_sn
     * @param $bill_type
     * @param int $state
     * @param int $start_time
     * @param int $end_time
     * @param string $is_del
     * @param string $is_bad
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function putList($id,$bill_sn='',$bill_type='',$state=0,$start_time=0,$end_time=0,$is_del='0,1',$is_bad='0,1'){
        try {
            $wheres[] = ['is_del', 'in', $is_del];
            $wheres[] = ['is_bad', 'in', $is_bad];
            if (!empty($id)) $wheres[] = ['id', 'in', $id];
            if (!empty($bill_sn)) $wheres[] = ['bill_sn', 'like', "%" . $bill_sn . "%"];
            if (!empty($bill_type)) $wheres[] = ['bill_type', '=', $bill_type];
            if (!empty($state)) $wheres[] = ['state', '=', $state];
            if (!empty($start_time)) $wheres[] = ['create_time', '>=', strtotime($start_time)];
            if (!empty($end_time)) $wheres[] = ['create_time', '<=', strtotime($end_time)];
            $put_arr = $this->where($wheres)->select();
//            return  ['code'=>01,'data'=>$end_time];
            foreach ($put_arr as $key => $vp) {
                //数据处理
                $put_arr[$key]['create_time'] = date('Y-m-d H:i:s', $vp['create_time']);
                $put_arr[$key]['save_time'] =
                $put_arr[$key]['state_n'] = $this->state[$vp['state']] ?? '';
                $put_arr[$key]['bill_type_n'] = $this->bill_type[$vp['bill_type']] ?? '';
                $put_arr[$key]['house_type_n'] = $this->house_type[$vp['house_type']] ?? '';
                $put_arr[$key]['pay_type_n'] = $this->pay_type[$vp['pay_type']] ?? '';
                $put_arr[$key]['order_type_n'] = $this->order_type[$vp['order_type']] ?? '';
                $put_arr[$key]['order_receipt_type_n'] = $this->order_type[$vp['order_receipt_type']] ?? '';
                $put_arr[$key]['order_goods_type_n'] = $this->order_type[$vp['order_goods_type']] ?? '';
                $put_arr[$key]['receipt_type_n'] = $this->receipt_type[$vp['receipt_type']] ?? '';
                if (!empty($vp['refunds_time'])) $put_arr[$key]['refunds_time'] =  date('Y-m-d H:i:s', $vp['refunds_time']);
                $put_arr[$key]['goods_info'] = $this->putGoodsFind($vp['id']);
                $put_arr[$key]['company_info'] = $this->putCompanyFind($vp['id']);
//                if ($vp['pay_type'] == 1) {
//                    $put_arr[$key]['pay_info'] = $this->putPayFind($vp['id']);
//                }
            }
            return ['data' => $put_arr, 'code' => 0];
        }catch (Exception $e){
            return ['data'=>$e->getMessage(),'code'=>1005];
        }
    }

    /**
     * @param $putData
     * @return array
     * @throws \think\exception\PDOException
     * 新增入库单（订单）
     */
    public function putAdd($putData){
        $this->startTrans();
        try {
            $validate = Validate::make($this->rule, $this->msg);
            if (!$validate->check($putData)) {
                return ['data' => $validate->getError(), 'code' => 1006];
            } else {
                $data = [
                    'bill_sn' => $this->state_sn[$putData['state']].rand(1,9).time().rand(1000,9999), 'bill_type' => $putData['bill_type'],
                    'total' => $putData['total'], 'house_type' => $putData['house_type']??1, 'pay_type' => $putData['pay_type'], 'receipt_type' => $putData['receipt_type']??2, 'match_sn' => $putData['match_sn']??'',
                    'state' => $putData['state'], 'create_time' => time(), 'save_time' => time(), 'is_del' => $putData['is_del']??1, 'is_bad' => $putData['is_bad']??1,
                    'cash_paid' => $putData['cash_paid']??0.00, 'use_refunds' => $putData['use_refunds']??0.00, 'account_balance' => $putData['account_balance']??0.00, 'charge_balance' => $putData['charge_balance']??0.00,
                    'order_type' => $putData['order_type']??'', 'order_receipt_type' => $putData['order_receipt_type']??'', 'order_goods_type' => $putData['order_goods_type']??'',
                    'refunds' => $putData['refunds']??'','refunds_time' => !empty($putData['refunds_time'])?strtotime($putData['refunds_time']):'','refunds_remark' =>$putData['refunds_remark']??""
                    ];
//                return ['code'=>1000,'data'=>$data];
                    $this->allowField(true)->save($data);

                if (!empty($this->id)) {
                    //商品关联 1对多
                    $pglink_data =  $this->putGoodsLink($this->id,$putData['goods_info']);
                    //供应商关联 1对1
                   $pclink_data =  $this->putCompanyLink($this->id,$putData['company_info']);
                    if ($pglink_data['code'] == 0 ){
                      if ($pclink_data['code'] == 0) {
                          $this->commit();
                          return ['data' => $this->id, 'code' => 0];
                      }else{
                          return $pclink_data;
                      }
                    }else{
                        return $pglink_data;
                    }
                }
            }
        }catch (Exception $e){
            $this->rollback();
            return  ['data'=>$e->getMessage(),'code'=>1005];
        }
    }

    /**
     * @param $putData
     * @return array
     * @throws \think\exception\PDOException
     * 更新入库单（订单）
     */
    public function putEdit($putData){
        $this->startTrans();
        try {
            $validate = Validate::make($this->rule_edit, $this->msg_edit);
            if (!$validate->check($putData)) {
                return ['data' => $validate->getError(), 'code' => 1006];
            } else {
                if (empty($put = PutBill::get($putData['id']))){
                    return ['data' => '参数错误', 'code' => 1004];
                }
                if ($put['bill_type'] == 1 || $put['bill_type'] == 2){
                    if($putData['bill_type'] == 3)  return ['data' => '参数错误', 'code' => 1004];
                    if($putData['bill_type'] == 4)  return ['data' => '参数错误', 'code' => 1004];
                }
                if ($put['bill_type'] == 3 || $put['bill_type'] == 4){
                    if($putData['bill_type'] == 1)  return ['data' => '参数错误', 'code' => 1004];
                    if($putData['bill_type'] == 2)  return ['data' => '参数错误', 'code' => 1004];
                }
                $data = [
                    'bill_type' => $putData['bill_type'],
                    'total' => $putData['total'],
                    'house_type' => $putData['house_type']??1,
                    'pay_type' => $putData['pay_type'],
                    'receipt_type' => $putData['receipt_type']??$put['receipt_type'],
                    'match_sn' => $putData['match_sn']??$put['match_sn'],
                    'state' => $putData['state']??$put['state'],
                    'save_time' => time(),
                    'is_del' => $putData['is_del']??1,
                    'is_bad' => 1,
                    'id' => $putData['id']??0,
                    'cash_paid' => $putData['cash_paid']??$put['cash_paid'],
                    'use_refunds' => $putData['use_refunds']??$put['use_refunds'],
                    'account_balance' => $putData['account_balance']??$put['account_balance'],
                    'charge_balance' => $putData['charge_balance']??$put['charge_balance'],
                    'order_type' => $putData['order_type']??'',
                    'order_receipt_type' => $putData['order_receipt_type']??'',
                    'order_goods_type' => $putData['order_goods_type']??'',
                    'refunds' => $putData['refunds']??$put['refunds'],'refunds_time' => !empty($putData['refunds_time'])?strtotime($putData['refunds_time']):$put['refunds_time'],'refunds_remark' =>$putData['refunds_remark']??$put['refunds_remark'],
                ];

                $this->allowField(true)->save($data,['id'=>$data['id']]);
                if (!empty($putData['goods_info'])) {
                    //商品关联 1对多
                    $pglink_data = $this->putGoodsLink($data['id'], $putData['goods_info']);
                }else{
                    $pglink_data['code'] = 0;
                }
                    if (!empty($putData['company_info'])) {
                        //供应商关联 1对1
                        $pclink_data = $this->putCompanyLink($data['id'], $putData['company_info']);
                    }else{
                        $pclink_data['code'] = 0;
                    }
                    if ($pglink_data['code'] == 0 ){
                        if ($pclink_data['code'] == 0) {
                            $this->commit();
                            return ['data' => $this->id, 'code' => 0];
                        }else{
                            return $pclink_data;
                        }
                    }else{
                        return $pglink_data;
                    }
            }
        }catch (Exception $e){
            $this->rollback();
            return  ['data'=>$e->getMessage(),'code'=>1005];
        }
    }

    /**
     * @param $put_id
     * @param $key
     * @param $value
     * @return array
     * 单据单个字段修改
     */
    public function putOneEdit($put_id,$key,$value){
        try{
            $use_arr = [
                'order_type',
                'order_receipt_type',
                'order__goods_type',
            ];
            if (in_array($key,$use_arr)){
                $this->save([$key=>$value],[$this->pk=>$put_id]);
                return ['code'=>0,'data'=> '操作成功'];
            }else{
                return ['code'=>1004,'data'=>'参数错误，当前字段不可单独修改'];
            }

        }catch (Exception $e){
            return ['code'=>1005,'data'=>$e->getMessage()];
        }
    }

    /**
     * @param $put_id
     * @param $goods_info
     * @return array
     * 入库-商品关联
     */
    public function putGoodsLink($put_id,$goods_info){
        try{
            $data = [];
            $putbill = PutBill::get($put_id);
            //只有入库单和出库单需要改动仓库 bill_type = 2,4
//            $wherePGDel[] = ['bill_sn','=',$putbill['bill_sn']];
//            //入库的先删后加
//            $wherePGDel[] = ['type','=',1];
//            $this->houseModel->pleceGoodsDel($wherePGDel);
            foreach ($goods_info as $key=>$vg){
                if (empty($vg['id'])){
                    continue;
                }
                if ($putbill['bill_type'] == 2 || $putbill['bill_type'] == 4) {
                    if ($putbill['bill_type'] == 2)$hg_type = 1;
                    if ($putbill['bill_type'] == 4)$hg_type = 2;
                    $houseGoods = $this->houseModel->BillGoodsHouseAdd($vg['id'], $vg['house_info']['house_id'], $putbill['bill_sn'],$hg_type??1,$vg['num']??1,$vg['house_info']['house_num'] ?? '', $vg['house_info']['house_row'] ?? '', $vg['house_info']['house_col'] ?? '');
                    if ($houseGoods['code'] != 0) {
                        return $houseGoods;
                        continue;
                    }
                }
                $data[] = [
                    'put_id' => $put_id,
                    'goods_id' => $vg['id'],
                    'goods_info' => json_encode($this->goodsModel->goodsList('',$vg['id'])['data'],JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES),
                    'num' => $vg['num']??0,
                    'mark_time' => strtotime($vg['make_time']??'')??"",
                    'house_id' => $vg['house_info']['house_id'],
                    'house_info' => json_encode($this->houseModel->houseList('',$vg['house_info']['house_id'],2)['data']??'',JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)??'',
                    'price' => $vg['price']??0.00,
                    'warn' => $vg['warn']??0
                ];
            }
            $whereLinkDel[] = ['put_id','=',$put_id];
            $this->table(PUT_GOODS_LINK)->where($whereLinkDel)->delete();
            $req =  $this->table(PUT_GOODS_LINK)->insertAll($data);
            if (!empty($req)) {
                return ['data' => '操作成功', 'code' => 0];
            }else{
                return ['data' => '参数错误', 'code' => 1004];
            }
        }catch (Exception $e){
            return ['data' => $e->getMessage(),'code'=>1005];
        }
    }

    /**
     * @param $put_id
     * @param $company_info
     * @return array
     * 入库-供应商关联
     */
    public function putCompanyLink($put_id,$company_info){
        try {
            if (empty($company_info['id'])) {
                return ['data' => '参数错误', 'code' => 1004];
            }
            $data = [
                'put_id' => $put_id,
                'company_id' => $company_info['id'],
                'status' => $company_info['status']??'',
                'company_info' => json_encode($this->companyModel->companyList('',$company_info['id'])['data'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ];
            $whereLinkDel[] = ['put_id','=',$put_id];
            $this->table(PUT_COMPANY_LINK)->where($whereLinkDel)->delete();
            $req = $this->table(PUT_COMPANY_LINK)->insert($data);
            if (!empty($req)) {
                return ['data' => '操作成功', 'code' => 0];
            } else {
                return ['data' => '参数错误', 'code' => 1004];
            }
        }catch (Exception $e){
            return ['data'=>$e->getMessage(),'code'=>1005];
        }

    }

    /**
     * @param $put_id
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 单据相关联商品查询
     */
    public function putGoodsFind($put_id){
        $whereFind[] = ['put_id','=',$put_id];
        $goods_info = $this->table(PUT_GOODS_LINK)->where($whereFind)->select();
        foreach ($goods_info as $key=>$v) {
            $goods_info[$key]['goods_info'] = json_decode(stripslashes($v['goods_info']), true);
            $goods_info[$key]['house_info'] = json_decode(stripslashes($v['house_info']), true);
            if (!empty($v['mark_time'])) {
                $goods_info[$key]['mark_time'] = date('Y-m-d H:i:s',$v['mark_time']);
            }
        }
        return $goods_info;
    }

    /**
     * @param $put_id
     * @param $company_id
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 单据相关联公司查询
     */
    public function putCompanyFind($put_id,$company_id=''){
        if (!empty($put_id))$whereFind[] = ['put_id','in',$put_id];
        if (!empty($company_id))$whereFind[] = ['company_id','in',$company_id];

        $whereFind[] = ['put_id','>',0];
        $company_info = $this->table(PUT_COMPANY_LINK)->where($whereFind)->select();

        foreach ($company_info as $key=>$v){
            $company_info[$key]['company_info']   =  json_decode( stripslashes($v['company_info']),true);

        }
        return $company_info;
    }

    /**
     * @param $where
     * @param $field
     * 条件查询一条
     */
    public function putFindOne($where,$field){
        return $this->where($where)->column($field);
    }
}