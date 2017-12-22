<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/9/16
 * Time: 20:08
 */
namespace Model;
class ArticleModel extends \Core\Model
{
    //定义更新的方法
    public function update($id, $cid, $title, $img, $author, $desc, $content, $is_tuijian, $display)
    {   $time=time();
        $sql="update articles set 
        cid=$cid,
        title=$title,
        img=$img,
        author=$author,
        `desp`=$desc,
        content=$content,
        is_tuijian=$is_tuijian,
        display=$display,
        updated_time=$time
        where id=$id
      ";
        return $this->mypdo->exec($sql);
    }
    //定义获取一条数据的方法
    public function getOne($id)
    {
        return $this->mypdo->fetchOne("select * from articles where id=$id");
    }

    //定义添加方法
    public function add($cid,$title,$img,$author,$desc,$content,$is_tuijian,$display)
    {
        $time=time();
        $sql="insert into articles (cid,title,img,author,`desc`,content,is_tuijian,display,created_time,updated_time)
 values ($cid,'$title','$img','$author','$desc','$content',$is_tuijian,$display,$time,$time)";

        return $this->mypdo->exec($sql);
    }

    //定义删除操作
    public function delete($id)
    {
        return $this->mypdo->exec("delete from articles where id=$id");
    }
    ///获取全部信息
    public function getAll($where=array(),$limit=array())
    {
        //条件
        $wherestr =" where 1=1";
        $wherestr .=isset($where['display'])? " and articles.display= {$where['display']}":'';
        $wherestr .=isset($where['cid'])? " and articles.cid in ({$where['cid']})":'';
//        print_r($wherestr);
       //限制
        $limit=isset($limit['startno'])? "limit  {$limit['startno']} , {$limit['pagesize']}" : '';
        $sql="select articles.*,categorys.name as cateName,admins.uname from articles
              left join categorys on articles.cid= categorys.id left join admins
              on articles.admin_id=admins.id $wherestr $limit ";
        return $this->mypdo->fetchAll($sql);
    }

    public function getCount($where=array())
    {
        $wherestr =" where 1=1";
        $wherestr .=isset($where['display'])? " and articles.display= {$where['display']}":'';
        $wherestr .=isset($where['cid'])? " and articles.cid in ({$where['cid']})":'';

        $tmp=$this->mypdo->fetchOne("select count(*) from articles $wherestr");

        return $tmp['count(*)'];
    }
}
