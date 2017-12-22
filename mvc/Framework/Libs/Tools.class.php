<?php
namespace Libs;

class Tools
{
    /**
     * @param $categoryList
     * @param int $pid
     * @param int $level
     */
    public static function getTree($categoryList,$pid=0,$level=0)
    {
        //遍历所有分类数据挨个找子集
        foreach($categoryList as $category){
            //判断时候有子集
            if($category['pid']==$pid){
                $category['level']=$level;
                //将子集方放到php超全局变量$GLOBALS中
                $GLOBALS['tree'][]=$category;
                //继续获取分类子集
                self::getTree($categoryList,$category['id'],$level+1);
            }
        }

    }


}
