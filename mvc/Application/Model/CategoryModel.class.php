<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/9/16
 * Time: 11:36
 */
namespace Model;

class CategoryModel extends \Core\Model
{
    //定义更新方法
    public function update($id,$pid,$name,$sort)
    {
        $time=time();
        $sql="update categorys set pid=$pid,name='$name',sort=$sort,updated_time=$time where id=$id";
        return $this->mypdo->exec($sql);
    }
    //定义添加方法
    public function add($pid,$name,$sort)
    {   $time=time();
        $sql="insert into Categorys (pid,name,sort,created_time,updated_time) 
                              values ($pid,'$name',$sort,$time,$time)";
        return $this->mypdo->exec("$sql");
    }
    //获取一条数据
    public function getOne($id)
    {
        return$this->mypdo->fetchOne("select * from Categorys where id=$id");
    }
    //定义删除方法
    public function delete($id)
    {
        return $this->mypdo->exec("delete from Categorys where id=$id");
    }
    public function getAll($where=array())

    {
        $where=empty($where)?'':"where pid={$where['pid']}";
        return $this->mypdo->fetchAll("select * from Categorys $where");
    }
}