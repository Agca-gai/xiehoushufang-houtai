<?php
namespace app\controller;
use app\model\User;
use think\db\Query;
use think\facade\Request;
use think\facade\Db;


class CommDetailManage {
    public function getTableData() {
        if(Request::method()){
            $gettabledata = Request::param();
            $currentPage = $gettabledata['currentPage'];
            $pageSize = $gettabledata['pageSize'];
            if(Request::method()){
                $operadata = Db::table('commImage')->where('isDelete', 0)->withoutField('isDelete')->limit(($currentPage-1)*$pageSize, $pageSize)->select();
                $pageTotal = Db::table('commImage')->where('isDelete', 0)->count();
                $returndata = array('pageTotal'=> $pageTotal, 'data'=> $operadata);
                return $returndata;
            }
        }
    }
    public function addviewImageData() {
        if(Request::method()){
            $gettabledata = Request::param();
            $ManData = Db::table('commImage');
            $randStr = str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890');
            $rand = substr($randStr,0,6);
            $insertdata = [
                'CommImageId'         =>   $rand,
                'CommImageUrl'      =>   $gettabledata['CommImageUrl'],
                'CommImageState'    =>   $gettabledata['CommImageState'],
                'CommId'    =>   $gettabledata['CommId'],
                'CommName'    =>   $gettabledata['CommName'],
                'isDelete'    =>   0
            ];
            $flag = $ManData->insert($insertdata);
            if ($flag) {
                $data = array('code'=> 0, 'mes'=> '新增成功！');
                return json($data);
            } else {
                $data = array('code'=> 1, 'mes'=> '新增失败！');
                return json($data);
            }
        }
    }
    public function updateViewPageState() {
        if(Request::method()){
            $gettabledata = Request::param();
            // return $gettabledata['array'];
            $getdata = $gettabledata['array'];
            $state = $gettabledata['state'];
            // return $data;
            $data = array();
            $data = $getdata;
            if ($state == 0) {
                if (is_array($data)) {
                    foreach ($data as $value) {
                        // return $value;
                        $res = Db::table('commImage')->where('CommImageId', $value)->save(['CommImageState'=>0]);
                        if ($res) {
                            $success = 1;
                        } else {
                            $success = 0;
                        }
                    }
                    if($success) {
                        $delete = array('code'=> 0, 'mes'=> '启用成功！');
                        return json($delete);
                    } else {
                        $delete = array('code'=> 1, 'mes'=> '启用失败！');
                        return json($delete);
                    }
                }
            } else {
                if (is_array($data)) {
                    foreach ($data as $value) {
                        // return $value;
                        $res = Db::table('commImage')->where('CommImageId', $value)->save(['CommImageState'=>1]);
                        if ($res) {
                            $success = 1;
                        } else {
                            $success = 0;
                        }
                    }
                    if($success) {
                        $delete = array('code'=> 0, 'mes'=> '禁用成功！');
                        return json($delete);
                    } else {
                        $delete = array('code'=> 1, 'mes'=> '禁用失败！');
                        return json($delete);
                    }
                }
            }
        }
    }
    public function deleteViewPage() {
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
                    $res = Db::table('commImage')->where('CommImageId', $value)->save(['isDelete'=>1]);
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
    public function reachViewPage() {
        if(Request::method()){
            $gettabledata = Request::param();
            $currentPage = $gettabledata['currentPage'];
            $pageSize = $gettabledata['pageSize'];
            $CommImageState = $gettabledata['CommImageState'];
            $CommId = $gettabledata['CommId'];
            $whereMid = $whereManName  = '';
            if ($CommId != '' || $CommId != null) {
                $whereMid = "and CommId like '%" .$CommId. "%' "; 
            }
            if ($CommImageState != '' || $CommImageState != null) {
                $whereManName = "and CommImageState like '%" .$CommImageState. "%' "; 
            }
            $res = Db::query("SELECT * From commImage Where isDelete = 0 "
            .$whereMid .$whereManName
            ."LIMIT " . ($currentPage - 1)*$pageSize .','. $pageSize);
            $count = sizeof(Db::query("SELECT * From commImage Where isDelete = 0 "
            .$whereMid .$whereManName));
            if ($res) {
                $reach = array('code'=> 0, 'mes'=> '查询成功！', 'data'=> $res, 'pageTotal'=> $count);
                return json($reach);
            }else {
                $reach = array('code'=> 1, 'mes'=> '没有查询到相关信息！', 'data'=> $res, 'pageTotal'=> 0);
                return json($reach);
            }
        }
    }
}