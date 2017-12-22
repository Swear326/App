<?php

namespace Controller\Admin;

//定义登录控制器
class LoginController extends \Core\Controller
{
    //新建登录方法
    public function login()
    {
        //判断是否提交数据
        if (!empty($_POST)) {
            //接受数据
            $uname = $_POST['uname'];
            $pwd = md5($_POST['pwd']);
            $code = $_POST['code'];
            //判断验证码是否正确
//            session_start();
            if (!\Libs\Captcha::checkVerify($code)) {
                $this->error('./index.php?p=admin&c=login&a=login', '验证码错误', 1);
            }
            //创建管理员模型对象
            $adminsmodel = new \Model\AdminsModel();
            //调用AdminModel里面的getUserinfo方法
            $rs = $adminsmodel->getUserinfo($uname, $pwd);
            //判断
            if ($rs) {
                //保存用户信息到session中
//                session_start();
                $_SESSION['userinfo'] = $rs;
                $this->success('./index.php?p=admin&c=index&a=index', '登录成功', 1);
            } else {
                $this->error('./index.php?p=admin&c=login&a=login', '登录失败', 1);
            }

        } else {
            //通过smarty的display方法加载视图
            $this->smarty->display('register.html');
        }


    }

    public function captcha()
    {
        //创建验证码对象
//        session_start();
        $captcha = new \Libs\Captcha(102, 35);
        //显示验证码
        $captcha->generalVerify();
    }
}