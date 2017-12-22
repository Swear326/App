<?php
namespace Core;

//定义框架核心类，驱动框架运行
class Framework
{
    /**
     * 框架跑起来!
     */
    public static function run()
    {
        self::initConfig(); //初始化配置文件信息
        self::initConst(); //初始化常量
        self::initXss();//初始化xss过略类,必须写在autoload前面
        self::initSmarty(); //初始化引入smarty类
        self::autoLoad();  //自动加载
        self::dispatch();  //分配路由
    }
    public static function initXss()
    {
        require LIB_PATH.'Xss'.DS.'HTMLPurifier.includes.php';
    }


    /**
     * 初始化引入smarty类
     */
    private static function initSmarty()
    {
        require LIB_PATH . 'Smarty' . DS . 'Smarty.class.php';
    }
    
    /**
     * 初始化配置文件信息
     */
    private static function initConfig()
    {
        $GLOBALS['config'] = require getcwd() . DIRECTORY_SEPARATOR . 'Application' 
               . DIRECTORY_SEPARATOR  . 'Config' . DIRECTORY_SEPARATOR . 'Config.php';
    }
    
    /**
     * 初始化常量
     */
    private static function initConst()
    {
        // 定义系统常量
        define("DS", DIRECTORY_SEPARATOR);       //目录分隔符（Linux兼容处理）
        define("ROOT_PATH", getcwd() . DS);                    //定义项目根目录
        define("APP_PATH", ROOT_PATH . 'Application' . DS);    //定义应用目录
        define("CONFIGS_PATH", APP_PATH . 'Config' . DS);      //定义配置文件目录
        define("FRAMEWORK_PATH", ROOT_PATH . 'Framework' . DS);//定义框架目录
        define("CORE_PATH", FRAMEWORK_PATH . 'Core' . DS);     //定义框架核心文件目录
        define("LIB_PATH",  FRAMEWORK_PATH . 'Libs' . DS);     //定义扩展类库目录

        define("CONTROLLER_PATH", APP_PATH . 'Controller' . DS); //定义控制器目录
        define("MODEL_PATH", APP_PATH . 'Model' . DS);  	 //定义模型目录
        define("VIEW_PATH", APP_PATH . 'View' . DS);             //定义视图目录

        //定义平台名称
        $platform = isset($_GET['p']) ? ucfirst(strtolower($_GET['p'])) : $GLOBALS['config']['default']['p'];
        define("PLATFORM_NAME", $platform);
        //定义当前控制器名
        $controllerName = isset($_GET['c']) ?  ucfirst(strtolower($_GET['c'])) :$GLOBALS['config']['default']['c'];
        define("CONTROLLER_NAME", $controllerName);
        //定义当前方法名
        $actionName = isset($_GET['a']) ? strtolower($_GET['a']) : $GLOBALS['config']['default']['a'];
        define("ACTION_NAME", $actionName);

        define('NOW_TIME', $_SERVER['REQUEST_TIME']);
        define('REQUEST_METHOD', $_SERVER['REQUEST_METHOD']);
        define('IS_GET', REQUEST_METHOD == 'GET' ? true : false);
        define('IS_POST', REQUEST_METHOD == 'POST' ? true : false);

        //定义项目图片路径常量
//        define('__IMG__',$GLOBALS['config']['__IMG__']);
    }

    
    /**
     * 将指定的方法注册成为自动加载方法（放到指定空间中）
     */
    private static function autoload()
    {
        spl_autoload_register('self::autoLoadClass');
    }
    
    /**
     * 自动加载
     */
    private static function autoLoadClass($className)
    {
        $type = dirname($className);
        $lastClassName = basename($className);
        if ($type == 'Core' || $type == 'Libs') {
            require "./Framework/$type/$lastClassName.class.php";
        } else if ($type == 'Model') {
            require "./Application/Model/$lastClassName.class.php";
        } else {
            require "./Application/$className.class.php";
        }
    }
    
    /**
     * 分配路由
     */
    private static function dispatch()
    {
        $controllerName = "\Controller\\".PLATFORM_NAME."\\".CONTROLLER_NAME."Controller";
        $obj = new $controllerName;
        //4.调用方法
        $actionName = ACTION_NAME;
        $obj->$actionName();
    }
    
    
}
