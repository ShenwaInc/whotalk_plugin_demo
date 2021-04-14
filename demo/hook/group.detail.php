<?php
defined('IN_IA') or exit('Access Denied');

class Demo_Group_detail extends Xfy_whotalkModuleSite {

    public function main($data=array()){
        //to do something
        //用户查看群组资料时会触发此处代码
        //$data参数传递的就是这个接口默认返回的数据
        //您可以执行一些操作后，将处理或未处理的数据返回去
        //您必须要返回这个数据，客户端接收到的数据就是您返回的数据
        return $data;
    }

}