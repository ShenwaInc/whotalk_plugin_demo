<?php
defined('IN_IA') or exit('Access Denied');

/*
 * 前台控制器需申明类和函数，并且需要返回数据或返回操作结果
 * 返回数据直接返回数组即可，页面路由的返回数组内需包含 title 元素
 * 返回操作结果请使用 wmessage() 或者 message() 函数
 * 路由类命名规范： Route_Controller ，其中Route代表路由名，默认是Index，首字母大写
 * 如通过API的方式访问，将直接以JSON格式输出返回的数据
 * 如通过页面链接的方式访问，将自动读取前端模板进行编译并显示页面
 * 前端模板文件在 /plugin/demo/template/mobile/ 文件夹下
 * 其中的 touch 文件夹为移动端模板文件，touch外为PC端模板文件，结构一致，文件一一对应
 * 在模板中可直接使用下列方法返回的数据，如返回 array('title'=>'首页...'); 在模板中可以直接使用 $title 变量
 */

class Index_Controller extends Xfy_whotalkModuleSite {

    /*
     * 前台控制器默认路由的函数
     */
    public function main(){
        global $_W,$_S;
        /*
         * $_S['plugins']['demo']['setting1'] 等同于 $_W['pluginset']['setting1']
         * 但 $_W['pluginset']['setting1'] 仅限在插件内使用，$_S['plugins']['demo']['setting1']在Whotalk内任意文件内均可使用
         * 其中的 demo 为插件标识
         */
        $return = array('title'=>'首页 - Whotalk演示插件','setting1'=>$_S['plugins']['demo']['setting1'],'qrcodeurl'=>'');
        $return['setting2'] = $_W['pluginset']['setting2'];
        $return['settingn'] = p('demo')->getSettingN();
        if ($_W['os']!='mobile'){
            $return['qrcodeurl'] = $_W['siteroot'].'app/'.substr(murl('utility/wxcode',array('do'=>'qrcode','text'=>$_W['siteurl'])),2);
        }
        return $return;
    }

    /*
     * 如果路由代码量过大，可以使用单独文件
     * 单独文件使用规则：下方的 test() 等同于新建 /plugin/demo/mobile/test.php
     * 单独文件的结构与此文件相同，相应的类名为 Test_Controller，将执行类里的 main() 方法
     * 如使用单独文件，则下方方法将失效
     */
    public function test(){
        global $_GPC;
        wmessage(lang('msg_operation_success').':'.$_GPC['op'],wmurl('demo'),'success');
    }

}