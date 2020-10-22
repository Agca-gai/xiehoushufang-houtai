<?php
namespace app\controller;
use app\BaseController;

class Test extends BaseController { // extends BaseController 控制器验证功能request  app
    public function index() {
        return '123 + 方法名 '. $this->request->action(). ',当前路径: '.$this->app->getBasePath();
    }

    public function hello($name = '') {
        return 'hello 888' . $name;
    }

    public function arrayOut() {
        $data = ['a'=>1, 'b'=>2, 'c'=>3];
        return json($data);
    }
}