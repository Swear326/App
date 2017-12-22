<?php
namespace Controller\Admin;
//创建后台公共控制器,其他控制器继承
class BaseController extends \Core\Controller
{
    public function __construct()
    {
        //必须让父类元素
        parent::__construct();

        //判断用户是否非法操作
//        session_start();
        if(empty($_SESSION['userinfo'])){
            $this->error('index.php?p=admin&c=login&a=login','登录后在操作',1);
        }

    }

}