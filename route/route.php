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
    'gl' => 'api/goods/goods_list',
    'gs' => 'api/goods/goods_save',
    'aa' => 'api/goods/attr_add',
    'ta' => 'api/goods/text_add',
    'tf' => 'api/goods/text_find',
    'ic' => 'api/goods/int_change',
    'caf' => 'api/goods/cache_arr_find',
];
