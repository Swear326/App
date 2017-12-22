<?php
namespace  Model;
class UserModel extends \Core\Model
{
    public function getUserinfo($uname,$pwd)
    {
        return $this->mypdo->fetchOne("select * from users where uname='$uname' and pwd='$pwd'");
    }
}