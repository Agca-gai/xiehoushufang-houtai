<?php
namespace app\controller;
use app\model\User;
use think\facade\Request;
use think\facade\Db;

class OrderManage {
    public function getTableData() {
        if(Request::method()){
            // $userdata = Request::param();
            // return json($userdata);
            // // dump($userdata);
            $gettabledata = Request::param();
            $currentPage = $gettabledata['currentPage'];
            $pageSize = $gettabledata['pageSize'];
            if(Request::method()){
                $userdata = Db::table('orderManage')->where('isDelete', 0)->withoutField('isDelete')->limit(($currentPage-1)*$pageSize, $pageSize)->select();
                $pageTotal = Db::table('orderManage')->where('isDelete', 0)->count();
                $returndata = array('pageTotal'=> $pageTotal, 'data'=> $userdata);
                return $returndata;
            }
        }
    }
    public function getxiangqingTableData() {
        if(Request::method()){
            // $userdata = Request::param();
            // return json($userdata);
            // // dump($userdata);
            $gettabledata = Request::param();
            $OrderId = $gettabledata['OrderId'];
            if(Request::method()){
                $userdata = Db::table('orderDetail')->where('OrderId', $OrderId)->select();
                $returndata = array('data'=> $userdata);
                return $returndata;
            }
        }
    }
    public function reachOrderData() {
        if(Request::method()){
            $gettabledata = Request::param();
            $currentPage = $gettabledata['currentPage'];
            $pageSize = $gettabledata['pageSize'];
            $OrderId = $gettabledata['OrderId'];
            $UserName = $gettabledata['UserName'];
            $Phone = $gettabledata['Phone'];
            $OrderState = $gettabledata['OrderState'];

            $whereMid = $whereManName = $whereStoreProvince = $whereStoreCity = '';
            if ($OrderId != '' || $OrderId != null) {
                $whereMid = "and OrderId like '%" .$OrderId. "%' "; 
            }
            if ($UserName != '' || $UserName != null) {
                $whereManName = "and UserName like '%" .$UserName. "%' "; 
            }
            if ($Phone != '' || $Phone != null) {
                $whereStoreProvince = "and Phone like '%" .$Phone. "%' "; 
            }
            if ($OrderState != '' || $OrderState != null) {
                $whereStoreCity = "and OrderState like '%" .$OrderState. "%' "; 
            }
            $res = Db::query("SELECT * From orderManage Where isDelete = 0 "
            .$whereMid .$whereManName .$whereStoreProvince .$whereStoreCity
            ."LIMIT " . ($currentPage - 1)*$pageSize .','. $pageSize);
            $count = sizeof(Db::query("SELECT * From orderManage Where isDelete = 0 "
            .$whereMid .$whereManName .$whereStoreProvince .$whereStoreCity));
            if ($res) {
                $reach = array('code'=> 0, 'mes'=> '查询成功！', 'data'=> $res, 'pageTotal'=> $count);
                return json($reach);
            }else {
                $reach = array('code'=> 1, 'mes'=> '没有查询到相关信息！', 'data'=> $res, 'pageTotal'=> 0);
                return json($reach);
            }
        }
    }

    public function updateOrderData() {
        if(Request::method()){
            $gettabledata = Request::param();
            // return $gettabledata['array'];
            $getdata = $gettabledata['array'];
            $OrderState = $gettabledata['OrderState'];
            // return $data;
            $data = array();
            $data = $getdata;
            if (is_array($data)) {
                foreach ($data as $value) {
                    // return $value;
                    $res = Db::table('orderManage')->where('OrderId', $value)->save(['OrderState'=>$OrderState]);
                    if ($res) {
                        $success = 1;
                    } else {
                        $success = 0;
                    }
                }
                if($success) {
                    $delete = array('code'=> 0, 'mes'=> '修改成功！');
                    return json($delete);
                } else {
                    $delete = array('code'=> 1, 'mes'=> '修改失败！');
                    return json($delete);
                }
            }
        }
    }
    public function deleteStoreData() {
        if(Request::method()){
            $gettabledata = Request::param();
            // return $gettabledata['array'];
            $getdata = $gettabledata['array'];
            // return $data;
            $data = array();
            $data = $getdata;
            if (is_array($data)) {
                foreach ($data as $value) {
                    // return $value;
                    $res = Db::table('storeManage')->where('StoreId', $value)->save(['isDelete'=>1]);
                    if ($res) {
                        $success = 1;
                    } else {
                        $success = 0;
                    }
                }
                if($success) {
                    $delete = array('code'=> 0, 'mes'=> '删除成功！');
                    return json($delete);
                } else {
                    $delete = array('code'=> 1, 'mes'=> '删除失败！');
                    return json($delete);
                }
            }
        }
    }
}