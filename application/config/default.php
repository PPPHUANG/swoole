<?php

return[
    'host' => '0.0.0.0',
    'port' => 9501,
    'worker_num' => 1,
    'mysql' => [
        'pool_size' => 3,                       //连接池大小
        'pool_get_timeout' => 0.5,              //获取连接池中连接超时时间
        'master' => [
            'host' => '127.0.0.1',              //数据库地址
            'port' => 3306,                     //数据库端口
            'user' => 'root',                   //数据库用户名
            'password' => '',                   //数据库密码
            'database' => 'test',               //默认数据库名
            'timeout' => 0.5,                   //数据库连接超时时间
            'charset' => 'utf8mb4',             //默认字符集
            'strict_type' => true               //true 自动把数字转为int类型
        ]
    ]
];