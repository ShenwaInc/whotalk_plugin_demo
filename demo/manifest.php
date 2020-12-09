<?php
defined('IN_IA') or exit('Access Denied');

$plugin = array(
    'identity'  =>   'demo',                //插件标识，仅限英文小写字母，必须和插件文件夹名一致
    'category'  =>  'private',              //插件类型，可自定义类型名，如果插件只允许超管账号使用，请设置为 private
    'name'  =>  'Whotalk插件开发演示',       //插件名称
    'version'   =>  '1.0.1',                //插件版本号，当更新时更改版本号后即可通过后台升级
    'author'    =>  'ShenWa Studio.',       //插件作者
    'thumb' =>   MODULE_URL.'icon.jpg',     //图标路径
    'summary'   =>  '插件开发演示'    //插件说明
);

/*
 * 插件安装时需要执行的SQL语句，直接将SQL写入EOF内即可
 * 如需创建数据表，请务必包含下方演示的字段
*/
$plugin['install'] = <<<EOF
CREATE TABLE IF NOT EXISTS `ims_whotalk_demo` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(11) NOT NULL DEFAULT '0' COMMENT '公众号id',
`status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
`addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
`dateline` int(11) NOT NULL DEFAULT '0' COMMENT '发布时间',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
EOF;

/*
 * 插件升级时需要执行的SQL语句，直接将SQL写入EOF内即可
 * 注：升级如果需要改动数据，除了要在 upgrade 添加SQL外，install 也要编写最新的数据库安装语句
 * 注：在使用 pdo_fieldexists 判断字段是否存在前，必须先通过 pdo_tableexists 判断数据表是否存在，否则会报错
*/
$plugin['upgrade'] = '';
if(pdo_tableexists('whotalk_demo')){
    if(!pdo_fieldexists('whotalk_demo','name')){
        $plugin['upgrade'] .= "ALTER TABLE `ims_whotalk_demo` ADD `name` varchar(255) NOT NULL DEFAULT '' AFTER `name`;";
    }
}

/*
 * 插件卸载时需要执行的SQL语句，直接将SQL写入EOF内即可
*/

$plugin['uninstall'] = <<<EOF
DROP TABLE IF EXISTS `ims_whotalk_demo`;
EOF;

return $plugin;


