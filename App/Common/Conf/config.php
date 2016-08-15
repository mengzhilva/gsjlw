<?php
return array(
    //'配置项'=>'配置值'
    
    /* 数据库设置 */
    // 'DB_TYPE'               =>  'mysqli',     // 数据库类型
    // 'DB_HOST'               =>  '103.16.124.78', // 服务器地址
    // 'DB_NAME'               =>  'sc',          // 数据库名
    // 'DB_USER'               =>  'root',      // 用户名
    // 'DB_PWD'                =>  'root',          // 密码
    // 'DB_PORT'               =>  '3306',        // 端口
    // 'DB_PREFIX'             =>  '',    // 数据库表前缀
    
    //PDO连接方式
    'DB_TYPE'   => 'pdo', // 数据库类型
    'DB_USER'   => 'root', // 用户名
    'DB_PWD'    => '151100', // 密码
    'DB_PREFIX' => '', // 数据库表前缀 
    'DB_DSN'    => 'mysql:host=localhost;dbname=gsjlwt',

    //设置模块访问列表
    'MODULE_ALLOW_LIST'    =>  array('Home','Admin'),
    'DEFAULT_MODULE'       =>  'Home',

    'URL_MODEL'             =>  2,       // URL访问模式,可选参数0、1、2、3,代表以下四种模式：
    // 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE  模式); 3 (兼容模式)  默认为PATHINFO 模式
    
    // 显示页面Trace信息
    'SHOW_PAGE_TRACE' =>false, 




    // 是否开启模板编译缓存,设为false则每次都会重新编译
    'TMPL_CACHE_ON'         =>  false,
//图片地址
	'IMGURL' =>		'http://gsjlw.com/'
);
