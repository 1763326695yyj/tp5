<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::get('think', function () {
    return 'hello,ThinkPHP5!';
});

Route::get('hello/:name', 'index/hello');
//Route::domain('api','api');
return [
    'ggl' => 'api/goods/goods_list',
    'ggs' => 'api/goods/goods_save',
    'aaa' => 'api/attr/attr_add',
    'ata' => 'api/attr/text_add',
    'atf' => 'api/attr/text_find',
    'gic' => 'api/goods/int_change',
    'icaf' => 'api/index/cache_arr_find',
    'ccs' => 'api/company/company_save',
];
