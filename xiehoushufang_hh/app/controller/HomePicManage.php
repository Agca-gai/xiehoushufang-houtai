<?php
namespace app\controller;
use app\model\User;
use think\db\Query;
use think\facade\Request;
use think\facade\Db;


class HomePicManage {
    public function getTableData() {
        if(Request::method()){
            // $userdata = Request::param();
            // return json($userdata);
            // // dump($userdata);
            $gettabledata = Request::param();
            $currentPage = $gettabledata['currentPage'];
            $pageSize = $gettabledata['pageSize'];
            if(Request::method()){
                $operadata = Db::table('homePicManage')->where('isDelete', 0)->order('paixun', 'ASC')->withoutField('isDelete')->limit(($currentPage-1)*$pageSize, $pageSize)->select();
                $pageTotal = Db::table('homePicManage')->where('isDelete', 0)->count();
                $returndata = array('pageTotal'=> $pageTotal, 'data'=> $operadata);
                return $returndata;
            }
        }
    }
    public function addviewImageData() {
        if(Request::method()){
            $gettabledata = Request::param();
            $ManData = Db::table('homePicManage');
            $randStr = str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890');
            $rand = substr($randStr,0,6);
            $insertdata = [
                'HomePicId'         =>   $rand,
                'paixun'     =>   $gettabledata['paixun'],
                'HomePicUrl'      =>   $gettabledata['HomePicUrl'],
                'HomePicState'    =>   $gettabledata['HomePicState'],
                'CreateTime'    =>   $gettabledata['CreateTime'],
                'EndTime' =>   $gettabledata['EndTime'],
                'CommId'    =>   $gettabledata['CommId'],
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
                        $res = Db::table('homePicManage')->where('HomePicId', $value)->save(['HomePicState'=>0]);
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
                        $res = Db::table('homePicManage')->where('HomePicId', $value)->save(['HomePicState'=>1]);
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
                    $res = Db::table('homePicManage')->where('HomePicId', $value)->save(['isDelete'=>1]);
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
            $HomePicState = $gettabledata['HomePicState'];
            if ($HomePicState != '') {
                $res = Db::table('homePicManage')->where('isDelete', 0)->where('HomePicState', $HomePicState)->limit(($currentPage-1)*$pageSize, $pageSize)->select();
                $count = Db::table('homePicManage')->where('isDelete', 0)->where('HomePicState', $HomePicState)->count();
            } else {
                $res = Db::table('homePicManage')->where('isDelete', 0)->limit(($currentPage-1)*$pageSize, $pageSize)->select();
                $count = Db::table('homePicManage')->where('isDelete', 0)->count();
            }
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