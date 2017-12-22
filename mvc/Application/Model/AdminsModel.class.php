<?php
namespace Model;

//定义管理员模型
class AdminsModel extends \Core\Model
{
    /**
     * 根据用户名和密码查询用户信息
     */

    public function  getUserinfo($uname,$pwd)
    {   $uname=addslashes($uname);
        $sql="select * from admins where uname='$uname' and pwd='$pwd'";

        return $this->mypdo->fetchOne($sql);
    }
}