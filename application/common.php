<?php
define('ATTR_TEXT','wk_attr_text');
define('ATTR','wk_attr');
define('ATTR_TEXT_GOODS_LINK','wk_goods_attr_text_link');
define('ATTR_LINK','wk_attr_link');
define('GOODS_UNIT_LINK','wk_goods_unit_link');
define('PROVINCES','wk_provinces');
define('CITIES','wk_cities');
define('AREAS','wk_areas');
define('ATTR_TABLE_LINK','wk_attr_table_link');
define('COMPANY','wk_company');
define('COMPANY_ATTR_LINK','wk_company_attr_link');
define('COMPANY_PIC','wk_company_pic');


define('TOKEN_TIME',7200);
define('API_TOKEN_TIME',7200);
//token
define('API_SECRET','weikongkejicom');
//define('API_ID','admin');
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