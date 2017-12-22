<?php
namespace Core;

//定义基础控制器
class Controller
{
    /**
     * 保存Smarty对象
     * @var object
     */
    protected $smarty;
    
    /**
     * 初始化数据
     */
    public function __construct()
    {
        $this->initsession();
        $this->initSmarty();
        $this->initXss();
        //1.创建smarty对象

    }
    private function initsession()
    {
        session_start();
    }


    /**
     * c初始化smarty
     */



    private function initSmarty()
    {
        $this->smarty = new \Smarty;
        //2.1设置模板路径
        $this->smarty->setTemplateDir(VIEW_PATH . PLATFORM_NAME . DS . CONTROLLER_NAME . DS);
        //2.2设置编译文件到Application下面的view_c
        $this->smarty->setCompileDir(APP_PATH . 'View_c');

    }





    private function initXss()
    {
        $this->xss= new \HTMLPurifier();
    }
    /**
     * 操作成功提示
     * @param string  $url   跳转地址
     * @param string  $msg   信息
     * @param int     $time  跳转时间
     */
    public function success($url, $msg = '操作成功', $time = 3)
    {
        $this->jump($url, $time, 'success', $msg);
    }
    /**
     * 操作失败提示
     * @param string  $url   跳转地址
     * @param string  $msg   信息
     * @param int     $time  跳转时间
     */
    public function error($url, $msg = '操作失败', $time = 3)
    {
        $this->jump($url, $time, 'error', $msg);
    }
    
    /**
     * 跳转方法
     * @param string  $url   跳转地址
     * @param int     $time  跳转时间
     * @param string  $state 状态：success-成功,error-失败
     * @param string  $msg   信息
     */
    public function jump($url, $time = 3, $state, $msg)
    {
        echo <<<STR
    <!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="refresh" content="{$time};URL={$url}">
<title>提示页面</title>
<style type="text/css">
#img{text-align:center;margin-top:50px;margin-bottom:20px;}
.info{text-align:center;font-size:24px;font-family:'微软雅黑';font-weight:bold;}
#success{color:#060;}
#error{color:#F00;}
</style>
</head> 
<body>
    <div id="img"><img src="./Public/img/{$state}.png" width="160" height="200" /></div>
    <div id='{$state}' class="info">{$msg}</div>
</body>
</html>    
STR;
        die; //终止脚本执行
    }
    
}