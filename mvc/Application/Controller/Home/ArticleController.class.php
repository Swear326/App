<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/9/20
 * Time: 18:40
 */
namespace Controller\Home;

class ArticleController extends \Core\Controller
{
    public function index()
    {
        //获取当前文章的cid,文章的cid和文章列表的id一一应
        $cid=(int)$_GET['cid'];
        $articlemodel=new \Model\ArticleModel();
        //创建分页的查询条件,在来显示分页,上一页,首页
        //获取当前分类的所有子集
        //创建分裂树
        $catemodel=new \Model\CategoryModel();
        $cateList=$catemodel->getAll();
        $this->smarty->assign('cateList',$cateList);
        \Libs\Tools::getTree($cateList,$cid);
        //判断是否有子集
        $cidArr[]=$cid;
        if(!empty($GLOBALS['tree'])){
            foreach ($GLOBALS['tree'] as $cate){
                $cidArr[]=$cate['id'];
            }
        }


        $where['display']=1;
        $where['cid']=implode(',',$cidArr);


        //创建分页对象
        $pageno=isset($_GET['pageno'])?(int)$_GET['pageno']:1;//起始页
        //实例化page对象,传递三个参数,pageno,每页显示条数,总条数

        $pageObj=new \Libs\Page($pageno,4,$articlemodel->getCount($where));
        //分类文章的起始页,记住,获取的getAll是每一页分页的数据
        $limit['startno']= $pageObj->startno;
        $limit['pagesize']=$pageObj->pagesize;
        //获取数据
        $articleList=$articlemodel->getAll($where,$limit);


        //分类文章传递给视图
        $this->smarty->assign("articleList",$articleList);
        //上下页链接传递给视图
        $this->smarty->assign('articlepage',$pageObj->show(array('cid'=>$cid)));
        //加载视图
        $this->smarty->display('index.html');
    }
    public function detail()
    {
        //分类模型nav数据
        $cateModel=new \Model\CategorysModel;
        $cateList=$cateModel->getALL();
        $this->smarty->assign('cateList',$cateList);
        //文章数据article数据
        $id=$_GET['id'];
        $artmodel=new \Model\ArticleModel();
        $artInfo=$artmodel->getOne($id);
        $this->smarty->assign('artInfo',$artInfo);
        $this->smarty->display('detail.html');
    }
}