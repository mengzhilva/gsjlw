<?php
return array(
    //'配置项'=>'配置值'
		//memcached配置，比memcache更新的缓存
		'DATA_CACHE_TYPE' => 'Memcached',
		'MEMCACHED_SERVER' =>  array(array('localhost',11211,100)),//此选项为memcached的设置，必填，否则不成功，参考http://www.ucai.cn/ask/detail?queid=4048
		'DATA_CACHE_TIME' => 100,//秒，Memcached的过期时间，必填，如果是memcache 不填则是0 永不过期
		//memcache配置，
		//'DATA_CACHE_TYPE' => 'Memcache',//打开即可
		
    'URL_MODEL'             => 2,
    "SESSION_USER_KEY" => "scuser",
     // 'URL_ROUTER_ON'   => true,
    'URL_ROUTE_RULES'=> array(
                        '/^(\w+)\/login/'    => 'User/login?city=:1',//登陆
                        '/^(\w+)\/register/'    => 'User/register?city=:1',//注册
                        '/^(\w+)\/findpsw/'    => 'User/findpsw?city=:1',//找回密码
                        '/^(\w+)\/reset/'    => 'User/reset?city=:1',//重置密码
            //:c指控制器名，：a指action名
        ':city^Index-Public-User$' => 'Home',//^Index-Public-User这串内容的作用是排除Index-Public-User这些关键字，他们都是分组名、模块名或者我们后面定义的路由规则的开头字符串，这样就兼容了正常的URL模式，如果一级目录不是城市，即直接转为正常路由或匹配下面的路由项
        ':city^Index-Public-User/:c$' => 'Home',
        ':city^Index-Public-User/:c/:a' => 'Home',//city作为get参数，注意这里排除了控制器名(以及后面定义的路由规则，避免冲突)，如果一级目录不是城市，即直接为正常路由
        
        //':city/new/'  => 'about',
    ),

        /* 模板相关配置 */
    'TMPL_PARSE_STRING' => array(
        '__IMG__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/images',
        '__CSS__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/css',
        '__JS__'     => __ROOT__ . '/Public/' . MODULE_NAME . '/js',
        '__SWF__'     => __ROOT__ . '/Public/' . MODULE_NAME . '/swf',
        'PC_SITE_URL' => "",
    ),


);