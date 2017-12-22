<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/9/20
 * Time: 15:27
 */
namespace Controller\Home;

use Core\Model;

class IndexController extends \Core\Controller
{
    public function index()
    {//显示标题数据,调用方法传递视图
        $categorymodel=new \Model\CategoryModel();
        $cateList=$categorymodel->getAll(array ('pid'=>0));
        $this->smarty->assign('cateList',$cateList);

        //显示文章数据
        $articlemodel=new \Model\ArticleModel();

        //当前页
        $pageno=isset($_GET['pageno'])?(int)$_GET['pageno']:1;
        //创建分页对象
        $pageObj= new \Libs\Page($pageno,4,$articlemodel->getCount());

        //调用方法
        $limit=array('startno'=>$pageObj->startno,'pagesize'=>$pageObj->pagesize);





        $articleList=$articlemodel->getAll(array('display'=>1),$limit);


        //传递给视图
        $this->smarty->assign('articlepage',$pageObj->show());


        $this->smarty->assign('articleList',$articleList);

        $this->smarty->display('index.html');
    }
}