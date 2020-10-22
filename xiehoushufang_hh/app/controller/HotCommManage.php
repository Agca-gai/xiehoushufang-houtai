<?php
namespace app\controller;
use app\model\User;
use think\db\Query;
use think\facade\Request;
use think\facade\Db;


class HotCommManage {
    public function getTableData() {
        if(Request::method()){
            // $userdata = Request::param();
            // return json($userdata);
            // // dump($userdata);
            $gettabledata = Request::param();
            $currentPage = $gettabledata['currentPage'];
            $pageSize = $gettabledata['pageSize'];
            if(Request::method()){
                $operadata = Db::table('hotCommManage')->where('isDelete', 0)->order('paixun', 'ASC')->withoutField('isDelete')->limit(($currentPage-1)*$pageSize, $pageSize)->select();
                $pageTotal = Db::table('hotCommManage')->where('isDelete', 0)->count();
                $returndata = array('pageTotal'=> $pageTotal, 'data'=> $operadata);
                return $returndata;
            }
        }
    }
    public function addHotCommData() {
        if(Request::method()){
            $gettabledata = Request::param();
            $ManData = Db::table('hotCommManage');
            $randStr = str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890');
            $rand = substr($randStr,0,6);
            $userdata = Db::table('commData')->where('isDelete', 0)->where('CommId', $gettabledata['CommId'])->find();
            $insertdata = [
                'HotCommId'         =>   $rand,
                'paixun'     =>   $gettabledata['paixun'],
                'CommId'    =>   $gettabledata['CommId'],
                'CommName'  =>   $userdata['CommName'],
                'CommSell'  =>   $userdata['CommSell'],
                'CommSellSum'  =>   $userdata['CommSellSum'],
                'CommImage'  =>   $userdata['CommImage'],
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
    public function updateHotComm() {
        if(Request::method()){
            $gettabledata = Request::param();
            $HotCommId = $gettabledata['HotCommId'];
            $ManData = Db::table('hotCommManage');
            $userdata = Db::table('commData')->where('isDelete', 0)->where('CommId', $gettabledata['CommId'])->find();
            $updatedata = [
                'paixun'     =>   $gettabledata['paixun'],
                'CommId'    =>   $gettabledata['CommId'],
                'CommName'  =>   $userdata['CommName'],
                'CommSell'  =>   $userdata['CommSell'],
                'CommSellSum'  =>   $userdata['CommSellSum'],
                'CommImage'  =>   $userdata['CommImage'],
                'isDelete'    =>   0
            ];
            $flag = $ManData->where('HotCommId', $HotCommId)->update($updatedata);
            if ($flag) {
                $data = array('code'=> 0, 'mes'=> '修改成功！');
                return json($data);
            } else {
                $data = array('code'=> 1, 'mes'=> '修改失败！');
                return json($data);
            }
        }
    }
    public function deleteHotComm() {
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
                    $res = Db::table('hotCommManage')->where('HotCommId', $value)->save(['isDelete'=>1]);
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
    public function reachHotComm() {
        if(Request::method()){
            $gettabledata = Request::param();
            $currentPage = $gettabledata['currentPage'];
            $pageSize = $gettabledata['pageSize'];
            $CommId = $gettabledata['CommId'];
            $CommName = $gettabledata['CommName'];

            $whereMid = $whereManName = '';
            if ($CommId != '' || $CommId != null) {
                $whereMid = "and CommId like '%" .$CommId. "%' "; 
            }
            if ($CommName != '' || $CommName != null) {
                $whereManName = "and CommName like '%" .$CommName. "%' "; 
            }
            $res = Db::query("SELECT * From hotCommManage Where isDelete = 0 "
            .$whereMid .$whereManName 
            ."LIMIT " . ($currentPage - 1)*$pageSize .','. $pageSize);
            $count = sizeof(Db::query("SELECT * From hotCommManage Where isDelete = 0 "
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