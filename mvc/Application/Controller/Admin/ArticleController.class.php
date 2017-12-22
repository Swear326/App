<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/9/16
 * Time: 20:01
 */
namespace Controller\Admin;
 class ArticleController extends BaseController
 {
     public function update()
     {
         if (IS_POST){
             $id=$_POST['id'];
             $cid=(int)$_POST['cid'];
             $title=$_POST['title'];
//             $img=$_POST['img'];
             $author=$_POST['author'];
             $desc=$_POST['desc'];
             $content=$_POST['content'];
             $is_tuijian=$_POST['is_tuijian'];
             $display=$_POST['display'];
             //修改图片功能，要给图片设置一个默认图片,并且判断一下,新上床的图片的真实类型
             $img=empty($_FILES['img']['name'])?$_POST['img2']:'';


             if (!empty($_FILES['img']['name'])){
                //调用上传文件类 上传图片(重写$img值)
                 $img=\Libs\UploadFile::upload($_FILES['img']);
                 if(!$img){
                     $this->error('index.php?p=admin&c=article&a=update&id=$id','上传图片失败',1);
                 }
             }





             //创建对象调用方法
             $articlemodel=new \Model\ArticleModel();
             $rs=$articlemodel->update($id, $cid, $title, $img, $author, $desc, $content, $is_tuijian, $display);
             if ($rs) {
                 $this->success('./index.php?p=admin&c=article&a=index', '修改成功', 1);
             } else {
                 $this->error('./index.php?p=admin&c=article&a=update&id=$id', '修改失败', 1);
             }
         }else{
             $id=$_GET['id'];
             $catemodel=new \Model\CategoryModel();
             $data=$catemodel->getAll();
             \Libs\Tools::getTree($data);
             $this->smarty->assign('cateList',$GLOBALS['tree']);
             //实例化获取一条数据
             $articlemodel=new \Model\ArticleModel();

             $articleInfo=$articlemodel->getOne($id);
             $this->smarty->assign('articleInfo',$articleInfo);
             $this->smarty->display('update.html');
         }
     }
     public function add()
     {
         if (IS_POST){
             //接受数据
             $cid=(int)$_POST['cid'];
//             $title=$_POST['title'];
             $title=$this->xss->purify($_POST['title']);

//             $img=$_POST['img'];
             $author=$_POST['author'];
             $desc=$_POST['desc'];
             $content=$_POST['content'];
             $is_tuijian=$_POST['is_tuijian'];
             $display=$_POST['display'];
             //添加图片功能实现,判断图片的真实类型
             $img='';
             if (!empty($_FILES['img'])){
                 $img=\Libs\UploadFile::upload($_FILES['img']);
                 if (!$img){
                     $this->error("index.php?p=admin&c=article&a=add",\Libs\UploadFile::getMessage(),1);
                 }
             }


             //创建对象
             $articlemodel= new \Model\ArticleModel();
             $rs=$articlemodel->add($cid,$title,$img,$author,$desc,$content,$is_tuijian,$display);
             //判断
             if ($rs) {
                 $this->success('./index.php?p=admin&c=article&a=index', '添加成功', 3);
             } else {
                 $this->error('./index.php?p=admin&c=article&a=add', '添加失败', 2);
             }

         }else{
             $catemodel=new \Model\CategoryModel();
             $data=$catemodel->getAll();
             \Libs\Tools::getTree($data);
             $this->smarty->assign('cateList',$GLOBALS['tree']);
             $this->smarty->display('add.html');
         }
     }
     public function index()
     {
         //实例化对象传数据给视图
         $articlemodel= new \Model\ArticleModel();
         $articleList=$articlemodel->getAll();
         $this->smarty->assign('articleList',$articleList);
         $this->smarty->display('index.html');
     }
     public function delete()
     {
         $id=$_GET['id'];
         $articlemodel= new \Model\ArticleModel();
         $rs=$articlemodel->delete($id);
         if ($rs) {
             $this->success('./index.php?p=admin&c=article&a=index', '删除成功', 3);
         } else {
             $this->error('./index.php?p=admin&c=article&a=index', '删除失败', 2);

         }
     }
 }