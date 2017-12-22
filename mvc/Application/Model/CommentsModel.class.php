<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/9/21
 * Time: 9:07
 */
namespace Model;
class CommentsModel extends \Core\Model
{
    public function add($uid,$aid,$pid,$content)
    {
        $time=time();
        $sql="insert into commnets values (null,$uid,$aid,$pid,'$content',$time,$time,1)";
        return $this->mypdo->exec($sql);
    }
}