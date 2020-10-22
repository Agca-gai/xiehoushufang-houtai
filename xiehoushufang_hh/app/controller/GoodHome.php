<?php
namespace app\controller;

use think\facade\Db;

class GoodHome {
    public function getNavigatorList() {
        $data = Db::table('navbar')->select();
        // dump($data);
        return json(['list' => $data]);
    }
}