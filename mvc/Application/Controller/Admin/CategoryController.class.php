<?php
namespace Controller\Admin;
//创建列表类
class CategoryController extends BaseController
{
    public function update()
    {
        if (IS_POST){
            $id=(int)$_POST['id'];
            $pid=(int)$_POST['pid'];
            $name=$_POST['name'];
            $sort=(int)$_POST['sort'];
            //创建对象
            $catemodel= new \Model\CategoryModel();
            //修改的类不能是自己
            if ($id==$pid){
                $this->error('./index.php?p=admin&c=category&a=updata&id=$id','父类不能是自己',2);
            }
            $cateList=$catemodel->getAll();
            //2.不能是自己的子级
            \Libs\Tools::getTree($cateList,$id);
            //判端否有子级,有则放在$son数组中
            if(!empty($GLOBALS['tree'])){
                $son=array();
                //遍历子集保存在$son中
                foreach ($GLOBALS['tree'] as $cate){
                    $son[]=$cate['id'];
                }
                //判断当前选的类是否是子集
                if (in_array($pid,$son)){
                    $this->error('./index.php?p=admin&c=category&a=update&id=$id', '父类不能是自己的子级', 2);

                }
            }
            $rs=$catemodel->update($id,$pid,$name,$sort);
            //判断
            if ($rs){
                $this->success('./index.php?p=admin&c=category&a=index','修改成功',1);

            }else{
                $this->error('./index.php?p=admin&c=category&a=updata&id=$id','修改失败',1);

            }





        }else{
            //拿数据在静态页面显示
            $id=$_GET['id'];
            //获取分类列表数据
            $catemodel=new \Model\CategoryModel();
            $cateList=$catemodel->getAll();
            //树列化后传递数据
            \Libs\Tools::getTree($cateList);
            $this->smarty->assign('cateList',$GLOBALS['tree']);
            //获取一条数据在静态页面做默认值显示
            $data=$catemodel->getOne($id);
            $this->smarty->assign('data',$data);
            $this->smarty->display('update.html');
        }
    }
    //定义添加的方法
    public function add()
    {
        if (IS_POST){
            //获取数据
            $pid=(int)$_POST['pid'];
            $name=$_POST['name'];
            $sort=(int)$_POST['sort'];
            //实例化对象调用add方法
            $catemodel=new \Model\CategoryModel();
            $rs=$catemodel->add($pid,$name,$sort);
            //判断
            if ($rs){
                $this->success('index.php?p=admin&c=category&a=index','添加成功',1);
            }else{
                $this->error('index.php?p=admin&c=category&a=add','添加失败',1);
            }



        }else{
            //获取列表数据,在添加也静态下显示
            //1.实例化对象获取数据
            $catemodel=new \Model\CategoryModel();
            $cateList=$catemodel->getAll();
            //树列化数据传递给视图
            \Libs\Tools::getTree($cateList);
            //通过全局变量在给视图传递数据
            $this->smarty->assign('cateList',$GLOBALS['tree']);
            $this->smarty->display('add.html');
        }

    }
    //定义删除方法
    public function delete()
    {
        //1.获得数据
        $id=$_GET['id'];
        //1.实例化对象,
        $catemodel=new \Model\CategoryModel();
        $rs=$catemodel->delete($id);
        //判断
        if ($rs){
            $this->success('index.php?p=admin&c=category&a=index','删除成功',1);
        }else{
            $this->error('index.php?p=admin&c=category&a=index','删除失败',1);
        }
    }
    //利用smarty和无限递归方法,分类数
    public function index()
    {
        //实例化对象获得数据,显示数据库categorys的数据
        $catemodel=new \Model\CategoryModel();
        $cate=$catemodel->getAll();
        /////重重重点.因为存在父子级的关系,所有需要将数据格式化为分类树
        \Libs\Tools::getTree($cate);
      //用smarty方法传递数据给视图
        $this->smarty->assign('cateList',$GLOBALS['tree']);
        $this->smarty->display('index.html');
    }
}