<?php
define('ATTR_TEXT','wk_attr_text');
define('ATTR','wk_attr');
define('ATTR_TEXT_GOODS_LINK','wk_goods_attr_text_link');
define('ATTR_TEXT_SEND_LINK','wk_send_attr_text_link');
define('ATTR_LINK','wk_attr_link');
define('GOODS_UNIT_LINK','wk_goods_unit_link');
define('GOODS','wk_goods');
define('PROVINCES','wk_provinces');
define('CITIES','wk_cities');
define('AREAS','wk_areas');
define('ATTR_TABLE_LINK','wk_attr_table_link');
define('COMPANY','wk_company');
define('COMPANY_ATTR_LINK','wk_company_attr_link');
define('COMPANY_PIC','wk_company_pic');
define('PUT_BILL','wk_put_bill');
define('PUT_GOODS_LINK','wk_put_goods_link');
define('PUT_COMPANY_LINK','wk_put_company_link');
define('HOUSE','wk_house');
define('HOUSE_PLECE','wk_house_plece');
define('BILL_HOUSE_GOODS','wk_bill_house_goods_link');
define('HOUSE_GOODS_LINK','wk_house_goods_link');
define('RECEIPT','wk_receipt');
define('RECEIPT_INFO','wk_receipt_info');
define('RECEIPT_COMPANY','wk_receipt_company_link');
define('SEND_SHEET','wk_send_sheet');
define('SEND_STAFF_LINK','wk_send_staff_link');

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