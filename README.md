# Whotalk插件开发文档

此文档为网络文档，最新文档请查阅：[https://www.yuque.com/shenwa/whotalk/lnctag](https://www.yuque.com/shenwa/whotalk/lnctag)
## 参考资料
1、微擎模块开发手册：[https://wiki.w7.cc/chapter/35?id=1535](https://wiki.w7.cc/chapter/35?id=1535)
2、Whotalk常用公共函数详解：
3、Whotalk插件开发演示.zip：请[进入售后群](https://qm.qq.com/cgi-bin/qm/qr?k=3RMYrjypE25dfm6xvtVDaMnGdIJhe5r7&jump_from=webapi)群文件下载
 


## 前言
Whotalk插件的开发遵循MVC开发框架，且界面模板的渲染和编译沿用微擎与discuz通用的语法标签。Whotalk插件开发的优势是结合了微擎框架自带的组件和Whotalk开发的自动化编译工具，及装即用，无需做文件加载和编译工作，绝大部分时候可以直接引用，避免了代码的冗余和串行。
Whotalk自带[MUI](https://dev.dcloud.net.cn/mui/)前端界面框架，如未满足您的界面要求，在开发插件的可以将前端框架单独含在框架内使用，或者参考新版Whotalk前端使用Vue+的方式，以接口的形式读取数据，渲染到您自己开发的前端界面中。
 
## Whotalk插件开发
### 一、插件路径
Whotalk所有插件都存放在 **/addons/xfy_whotalk/plugin/** 目录下，每个插件一个目录，以插件标识（identity）命名，仅限英文小写字母及数字，必须以英文开头。


### 二、目录结构
![image.png](https://cdn.nlark.com/yuque/0/2020/png/1333431/1607418447401-df4d0229-4e09-49b6-b79e-ee7d35c931e1.png#align=left&display=inline&height=317&margin=%5Bobject%20Object%5D&name=image.png&originHeight=317&originWidth=501&size=11022&status=done&style=none&width=501)
完整的插件目录结构

```
/addons/xfy_whotalk/plugin/identity/
											/mobile/													//前台控制器文件夹
            			 					 /xxx.php										//前台控制器文件
            					/static/													//静态资源文件夹，根据需要自定义
            			 					 /css/
                   					 /font/
                   					 /images/
                   					 /js/
            					/template/												//前端界面模板文件夹
            				 				 /admin/									//后台前端模板文件夹
                     			 				 /xxx.html
                     				 /mobile/									//前台前端模板文件夹（默认为PC端）
                     							 /touch/						//前台前端模板文件夹（移动端，若插件不区分终端请忽略）
                            			 /xxx.html
            					/web/															//后台控制器文件夹
            							/xxx.php
            					/identity.mod.php									//插件模型文件，其中 identity 为插件标识
            					/manifest.php											//插件安装文件
```
插件目录结构说明，其中 **identity** 为插件标识 

### 三、安装文件
插件安装文件的结构必须根据官方提供的示例样板进行编写，否则无法正常识别与安装。
```php
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
```
安装文件样板示例

### 四、插件模型（M）
插件仅支持一个模型文件，且为必须包含的文件，可将所有模型写在该文件内，并在Whotalk内的任意文件通过如下代码调用该模型下的方法。
```php

p('identity')->foo();								//其中 identity 为插件标识，foo为方法名

```
插件模型方法调用示意，**调用前不需要引用模型文件**
```php
<?php
defined('IN_IA') or exit('Access Denied');

/*
 * 插件核心模型类名命名规范：Identity_Model ，其中Identity代表插件标识，首字母大写
 * 创建好类后，可以在里面任意编写方法，并在Whotalk内任何文件调用该方法，无需加载文件和构造类
 */

class Demo_Model extends Xfy_whotalkModuleSite {

    /*
     * 编写好方法后，可以在Whotalk内任何文件调用该方法，无需加载文件或构造类
     * 如下例的方法在Whotalk内其它文件调用方法：p('demo')->foo();，其中 demo 为插件标识
     */
    public function foo($page=1){
        return $page+1;
    }

    public function getSettingN(){
        global $_S;
        return $_S['plugins']['demo']['settingn'];
    }

}
```
模型文件样板示例

### 五、控制器（C）
#### 1、前台控制器
```php
<?php
defined('IN_IA') or exit('Access Denied');

class Index_Controller extends Xfy_whotalkModuleSite {

    public function main(){
        global $_W,$_S;
        $return = array('title'=>'首页 - Whotalk演示插件','setting1'=>$_S['plugins']['demo']['setting1'],'qrcodeurl'=>'');
        $return['setting2'] = $_W['pluginset']['setting2'];
        $return['settingn'] = p('demo')->getSettingN();
        if ($_W['os']!='mobile'){
            $return['qrcodeurl'] = $_W['siteroot'].'app/'.substr(murl('utility/wxcode',array('do'=>'qrcode','text'=>$_W['siteurl'])),2);
        }
        return $return;
    }

    public function test(){
        global $_GPC;
        wmessage(lang('msg_operation_success').':'.$_GPC['op'],wmurl('demo'),'success');
    }

}
```
前台控制器样板示例
**控制器与方法：**
前台控制器需申明类和方法，并且需要返回数据或返回操作结果。通过URL参数访问不同的方法，默认控制器为Index_Controller，默认方法为 main()。例如（以下的identity为插件标识）：
> URL为 /app/index.php?i=x&c=entry&m=xfy_whotalk&do=mobile&r=identity
> 执行的是 /plugin/identity/mobile/index.php 文件里面 Index_Controller 控制器的 main() 方法，
> 而 &r=identity.test 执行的是 index.php 文件里面 Index_Controller 控制器的 test() 方法。

如果方法的代码量过大，还可以将方法单独作为一个文件来编写，例如上述的 &r=identity.test，还可以在 /plugin/identity/mobile/ 文件夹下新建一个 test.php文件，这时候控制器名为 Test_Controller，方法为 main()。

**访问链接：**
前台控制器的访问链接统一通过通用函数 wmurl('identity/test') 来生成，具体用法请参考《常用公共函数详解》中对 wmurl() 函数的说明。

**访问方式：**
前台控制器支持接口访问方式和页面访问方式，当通过接口访问时，会将每个方法返回的数据以JSON格式直接输出；当通过页面链接方式访问时，系统会自动读取对应的界面模板来编译并直接显示页面，而这时，方法返回的数据将自动转换为变量，并可直接在模板内引用，具体的引用规则会在下一节详细说明。如需以接口的方式访问控制器，则直接将URL里的 &do=mobile 修改为 &do=api ，或者在生成链接时给定api参数，具体方法请参考《常用公共函数详解》中对 wmurl() 函数的说明。
#### 2、后台控制器
```php
<?php
defined('IN_IA') or exit('Access Denied');

global $_S; //Whotalk配置参数，数组形式，后台的所有设置项，可打印查看详细内容

$title = 'Whotalk插件开发演示';

if ($_W['action']=='hello'){
    die('Hello Whotalk!');
}elseif ($_W['action']=='setting'){
    $title = '插件设置 - '.$title;
    //插件参数设置的保存由另外的代码处理，此处无需做其它操作
}else{
    $variable = 'Hello Whotalk!';
}

```
后台控制器样板示例
后台控制器无需声明类和方法，将直接运行文件，根据 $_W['action'] 区分路由，也可以将不同路由写在不同的文件内，优先读取单独文件。例如：
> URL为 /web/index.php?c=site&a=entry&m=xfy_whotalk&do=web&r=identity
> 执行的是 /plugin/identity/web/index.php 文件
> 而 &r=identity.test 执行的是 test.php 文件或 index.php里的 if ($_W['action']=='test') 代码块

### 

### 六、界面模板（V）
用户所看到的界面是后端数据渲染到HTML5页面后的展现结果，而在渲染时，为了便于将后端开发工作与前端界面开发工作分离，我们使用特定的语法来将后端读取的数据与HTML编写在一起，变成了特有的模板文件，当用户访问时，系统会将模板文件自动编译为将HTML5与PHP混合的可执行文件，来将页面展现给用户。
具体的模板语法请参考：[https://wiki.w7.cc/chapter/35?id=577](https://wiki.w7.cc/chapter/35?id=577)
这里只说明模板的引用规则：
1、模板文件存放于插件根目录下的 /template/ 文件夹内；
2、模板文件名与路由名一致，由英文小写字母及数字组成，必须以英文开头；
3、前台模板存放于** /template/mobile/ **文件夹内，如果前端需区分PC端和移动端，请在 **/template/mobile/ **文件夹内创建** /touch/** 文件夹，目录结构与 mobile 文件夹一致，文件一一对应。
4、后台模板存放于 **/template/admin/ **文件夹内。
**注：运行完控制器后，系统会自动编译指定的模板，无需其它操作。**
**注：强烈建议使用前后端完全分离的方式开发插件，以接口的形式访问控制器获取数据来渲染到页面，可以自行在插件内设计前端框架逻辑。****


## 常见问题












































