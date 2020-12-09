<?php
defined('IN_IA') or exit('Access Denied');

/*
 * 插件核心模型类名命名规范：Identity_Model ，其中Identity代表插件标识，首字母大写
 * 创建好类后，可以在里面任意编写方法，并在Whotalk内任何文件调用该方法，无需加载文件和构造类
 */

class Demo_Model extends Xfy_whotalkModuleSite {

    /*
     * 编写好方法后，可以在Whotalk内任何文件调用该方法，无需加载文件或构造类
     * 如下例的方法在Whotalk内其它文件调用方法：p('demo')->foo();，其中 demo 为模块标识
     */
    public function foo($page=1){
        return $page+1;
    }

    public function getSettingN(){
        global $_S;
        return $_S['plugins']['demo']['settingn'];
    }

}