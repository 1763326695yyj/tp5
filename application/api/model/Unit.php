<?php


namespace app\api\model;


use think\facade\Cache;
use think\Model;

class Unit extends Model
{
    public $table = 'wk_unit';
    public $unit_type = [
    1 => '开启单位转换',
    0 => '不开启单位转换'
];

    /**
     * @return array|mixed 返回属性数组
     * 构建单位数组缓存
     */
    public function SelectUnit(){

        if (!Cache::has('unit_arr')){
            $arr = $this->select();
            $reqArr = [];
            foreach ($arr as $key=>$v){
                $reqArr[$v['id']]['unit_name'] = $v['unit_name'];
                $reqArr[$v['id']]['is_del'] = $v['is_del'];
            }
            Cache::set('unit_arr',$reqArr,3600*24*30);
            return ['data'=>$reqArr,'code'=>0];
        }
        return ['data'=>Cache::get('unit_arr'),'code'=>0];
    }

    /**
     * @param $goods_id
     * @param $unit_id
     * @param int $change
     * @param int $switch_unit_id
     * @param int $switch_num
     * @return bool 数据库操作结果
     * 创建商品单位关联
     */
    public function goodsUnitLink($goods_id,$unit_id,$change=0,$switch_unit_id=0,$switch_num=0){
        if ($change != 0 && !empty($change)){
            //转换
            $data['switch_unit_id'] = $switch_unit_id;
            $data['switch_num'] = $switch_num;
            $data['type'] = 1;
        }else{
            $data['type'] = 0;
        }
        $data['goods_id'] = $goods_id;
        $data['unit_id'] = $unit_id;
        $whereDel[] = ['goods_id','=',$goods_id];
        $this->table(GOODS_UNIT_LINK)->where($whereDel)->delete();
        $booler = $this->table(GOODS_UNIT_LINK)->insert($data);
        return $booler;
    }

    /**
     * @param $goods_id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException 商品单位信息数组
     * 商品单位信息查询
     */
    public function goodsUnitFind($goods_id){
        $UnitArr = [];
        $whereUnit[] = ['goods_id','=',$goods_id];
        $unit_info = $this->table(GOODS_UNIT_LINK)->where($whereUnit)->find();
        $UnitArr['unit_name'] = Cache::get('unit_arr')[$unit_info['unit_id']]['unit_name']??'';
        $UnitArr['unit_type'] = $this->unit_type[$unit_info['type']]??'';
        $UnitArr['unit_switch_unit_name'] = Cache::get('unit_arr')[$unit_info['switch_unit_id']]['unit_name']??'';
        $UnitArr['unit_switch_num'] = $unit_info['switch_num']??'';
        return $UnitArr;
    }
}