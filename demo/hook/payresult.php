<?php
defined('IN_IA') or exit('Access Denied');

class Demo_Payresult extends Xfy_whotalkModuleSite {

    public function main($params=array()){
        //to do something
        //系统支付完成后会触发此处代码
        print_r($params);
    }

}