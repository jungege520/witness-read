<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 17/5/26
 * Time: 下午11:23
 */
return [
    'disks' => [
        'qiniu' => [
            'driver' => 'qiniu',
            'domains' => [
                'default' => 'opb1hhqzs.bkt.clouddn.com', //你的七牛域名
                'https' => '',         //你的HTTPS域名
                'custom' => '',                //你的自定义域名
            ],
            'access_key' => 'IdL1wiByfNrlNHvRjOE1EoH9CljdnwS4NQYd9NYC',  //AccessKey
            'secret_key' => 'gy3_H3ABF2Jj_vm9B4PnUV5SREEAxk0Q3ObQmuaR',  //SecretKey
            'bucket' => 'fastgoo',  //Bucket名字
            'notify_url' => '',  //持久化处理回调地址
            'access' => 'public'  //空间访问控制 public 或 private
        ],
    ],
];