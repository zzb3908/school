<?php
namespace app\index\controller;

class IndexController
{
    public function indexAction()
    {
        dump(config('api_host'));
    }

    public function helloAction($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
