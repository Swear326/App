<?php
namespace Controller\Admin;

class IndexController extends BaseController
{


    public function index()
    {
        $this->smarty->display('index.html');
    }
    public function welcome()
    {
        $this->smarty->display('welcome.html');
    }


}
