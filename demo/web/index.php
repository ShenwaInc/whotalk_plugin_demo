<?php
defined('IN_IA') or exit('Access Denied');

/*
 * 后台的控制器不需要申明类和函数，直接根据 $_W['action'] 区分路由
 * 不需要返回数据，直接给变量命名和赋值，在模板文件内可直接使用
 * 不需要加载前端模板，系统会自动根据路由加载前端模板
 * 模板加载规则：自动根据路由名加载对应模板，默认为 index.html （如果对应的路由没有模板文件，也会加载 index.html）
 */

global $_S; //Whotalk配置参数，数组形式，后台的所有设置项，可打印查看详细内容

$title = 'Whotalk插件开发演示';

if ($_W['action']=='hello'){
    /*
     * 如果路由的代码量过大，可以单独使用一个文件
     * 单独文件使用方法：$_W['action']=='hello' 等同于新建 /plugin/demo/web/hello.php 文件
     * 如果创建了单独文件，此处的代码将会失效
     */
    die('Hello Whotalk!');
}elseif ($_W['action']=='setting'){
    $title = '插件设置 - '.$title;
    //插件参数设置的保存由另外的代码处理，此处无需做其它操作
}else{
    $variable = 'Hello Whotalk!';
}

/*
 * 文件执行结束后，会自动根据路由加载前端模板文件进行编译
 * 前端模板文件在 /plugin/demo/template/admin/ 文件夹下
 * 可直接在对应的模板文件内使用上面声明的变量
 */
