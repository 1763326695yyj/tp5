<?php
define('TOKEN_TIME',7200);
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------
// 应用公共文件
function ret($data=[],$code=0){
    return json(['error_code'=>$code,'data'=>$data]);
}
function object_array($array) {
    if(is_object($array)) {
        $array = (array)$array;
    }
    if(is_array($array)) {
        foreach($array as $key=>$value) {
            $array[$key] =object_array($value);
        }
    }
    return $array;
}

function md6($str){
    return md5(md5($str.'njlg'));
}