<?php
namespace app\controller;
use app\model\User;
use think\facade\Request;
use think\facade\Db;

class ClientManage {
    public function getTableData() {
        if(Request::method()){
            // $userdata = Request::param();
            // return json($userdata);
            // // dump($userdata);
            $gettabledata = Request::param();
            $currentPage = $gettabledata['currentPage'];
            $pageSize = $gettabledata['pageSize'];
            if(Request::method()){
                $userdata = Db::table('user')->limit(($currentPage-1)*$pageSize, $pageSize)->select();
                $pageTotal = Db::table('user')->count();
                $returndata = array('pageTotal'=> $pageTotal, 'data'=> $userdata);
                return $returndata;
            }
        }
    }
    public function reachUserData() {
        if(Request::method()){
            $gettabledata = Request::param();
            $fromselect = $gettabledata['fromselect'];
            $frominput = $gettabledata['frominput'];
            $currentPage = $gettabledata['currentPage'];
            $pageSize = $gettabledata['pageSize'];
            if ($fromselect === 'Sex' & $frominput === '男') {
                $frominput = 0;
            } else if ($fromselect === 'Sex' & $frominput === '女') {
                $frominput = 1;
            }
            $res = Db::table('user')->where($fromselect, 'like', '%'.$frominput.'%')->limit(($currentPage-1)*$pageSize, $pageSize)->select();
            $count = Db::table('user')->where($fromselect, 'like', '%'.$frominput.'%')->count();
            if ($fromselect === '') {
                $reach = array('code'=> 1, 'mes'=> '请选择查询区域！');
                return json($reach);
            } else if ($res) {
                $reach = array('code'=> 0, 'mes'=> '查询成功！', 'data'=> $res, 'pageTotal'=> $count);
                return json($reach);
            }else {
                $reach = array('code'=> 1, 'mes'=> '查询失败！');
                return json($reach);
            }
        }
    }
}