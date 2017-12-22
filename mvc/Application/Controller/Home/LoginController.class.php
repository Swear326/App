<?php
namespace Controller\Home;

use Core\Controller;

class LoginController extends \Core\Controller
{
    public function login()
    {
        if (IS_POST){
            $uname=$_POST[uname];
            $pwd=md5($_POST['pwd']);
            //创建用户模型
            $userModel= new \Model\UsersModel();
            $rs=$userModel->getUserinfo($uname,$pwd);
            //判断
            if ($rs){
                $_SESSION['home_userinfo']=$rs;
                $this->success('index.php?p=home&c=login&a=index','登录成功',1);
            }else{
                $this->error('index.php?p=home&c=login&a=login','登录失败',1);
            }
        }
        $this->smarty->display('register.html');
    }
}