<?php


namespace app\api\model;


use think\Model;

class Log extends Model
{
    public $crud = [
        'c' => '新增',
        'r' => '读取',
        'u' => '更新',
        'd' => '删除'
    ];
    public $table = 'wk_log';


    /**
     * @param $value
     * @return \think\response\Json
     * 创建日志
     */
    public function createLog($value){
        if (!empty($value['table_name']) && !empty($value['crud']) && !empty($this->crud[$value['crud']])){
            if (!empty($value['table_id'])){
               $table_id = '主键为'.$value['table_id'];
            }else{
                $table_id = '';
            }
            $value['logs'] = date('Y-m-d H:i:s').' '.$value['user_name'].' '.'对表'.$value['table_name'].$table_id." 进行了".$this->crud[$value['crud']]."操作";
        }else{
            $value['logs'] = '空';
        }

        $logData = [
            'user_id' => $value['user_id']??0,
            'user_name' => $value['user_name']??'',
            'time' => time(),
            'table_name' => $value['table_name']??'',
            'crud' => $value['crud']??'',
            'remark' => $value['remark']??'',
            'log' => $value['log']??$value['logs'],
        ];
        return ['data'=>$this->save($logData),'code'=>0];

    }
}