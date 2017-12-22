<?php
namespace Libs;

//定义分页类
class Page
{
    private $pageno; 	//当前页
    private $pagesize;  //每页条数
    private $dataTotal; //总条数

    private $startno; 	//起始位置
    private $prevno; 	//上一页
    private $nextno; 	//下一页
    private $pageTotal; //总页数

    /**
     * 返回获取指定没有权限的属性值
     * @param  string $name 属性名
     * @return string
     */
    public function __get($name)
    {
        return $this->$name;
    }

    /**
     * 初始化数据
     * @param int $pageno    当前页
     * @param int $pagesize  每页条数
     * @param int $dataTotal 总条数
     */
    public function __construct($pageno, $pagesize, $dataTotal)
    {
        $this->initParam($pageno, $pagesize, $dataTotal);  	//初始化默认参数
        $this->initStartno();	 //初始化起始位置
        $this->initPageTotal(); //初始化总页数
        $this->initPrevno(); 	//初始化上一页
        $this->initNextno(); 	//初始化下一页
    }

    /**
     * 初始化总页数
     * @return [type] [description]
     */
    private function initPageTotal()
    {
        $this->pageTotal = ceil($this->dataTotal / $this->pagesize);
    }

    /**
     * 初始化下一页
     * @return [type] [description]
     */
    private function initNextno()
    {
        $nextno = $this->pageno < $this->pageTotal ? $this->pageno + 1 : $this->pageTotal;
        $this->nextno = $nextno;
    }

    /**
     * 初始化上一页
     * @return [type] [description]
     */
    private function initPrevno()
    {
        $this->prevno = $this->pageno > 1 ? $this->pageno - 1 : 1;
    }

    /**
     * 初始化起始位置
     * @return [type] [description]
     */
    private function initStartno()
    {
        $this->startno = ($this->pageno - 1) * $this->pagesize;
    }

    /**
     * 初始化默认参数
     * @param int $pageno    当前页
     * @param int $pagesize  每页条数
     * @param int $dataTotal 总条数
     */
    private function initParam($pageno, $pagesize, $dataTotal)
    {
        $this->pageno = $pageno;
        $this->pagesize = $pagesize;
        $this->dataTotal = $dataTotal;
    }

    /**
     * 创建跳转网址（框架中）
     * @param  int   $pageno  跳转页码
     * @param  array $params  跳转条件
     * @return string
     */
    private function createUrl($pageno, $params = array())
    {
        //1.默认路由
        //$url = "./index.php?p=平台名&c=控制器名&a方法名&pageno=".$pageno;
        $url = "./index.php?p=".PLATFORM_NAME."&c=". CONTROLLER_NAME ."&a=".ACTION_NAME."&pageno=".$pageno;
        //2.给路由增加参数
        foreach ($params as $key=>$val) {
            $url .= "&$key=$val";
        }
        return $url;
    }

    /**
     * 显示分页
     * @return [type] [description]
     */
    public function show($params = array())
    {
        $str = "<a href='".$this->createUrl(1, $params)."'>首页</a>&nbsp;&nbsp;";
        $str .= "<a href='".$this->createUrl($this->prevno, $params)."'>上一页</a>&nbsp;&nbsp;";
        $str .= "<a href='".$this->createUrl($this->nextno, $params)."'>下一页</a>&nbsp;&nbsp;";
        $str .= "<a href='".$this->createUrl($this->pageTotal, $params)."'>尾页</a>";
        return $str;
    }
}
