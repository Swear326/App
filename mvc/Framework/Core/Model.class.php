<?php
namespace Core;

//定义基础模型
class Model
{
    /**
     * 保存mypdo对象
     * @var [type]
     */
    protected $mypdo;

    public function __construct()
    {
        $array = array(
            'host' => $GLOBALS['config']['database']['host'],
            'user' => $GLOBALS['config']['database']['user'],
            'pwd' => $GLOBALS['config']['database']['pwd'],
            'port' => $GLOBALS['config']['database']['port'],
            'charset' => $GLOBALS['config']['database']['charset'],
            'dbname' => $GLOBALS['config']['database']['dbname']
        );
        $this->mypdo =  MySQLPDO::getInstance($array);
    }
}
