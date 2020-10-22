<?php
namespace app\controller;
use app\model\User;
use think\facade\Request;
use think\facade\Db;

class CommData {
    public function getTableData() {
        if(Request::method()){
            // $userdata = Request::param();
            // return json($userdata);
            // // dump($userdata);
            $gettabledata = Request::param();
            $currentPage = $gettabledata['currentPage'];
            $pageSize = $gettabledata['pageSize'];
            if(Request::method()){
                $userdata = Db::table('commData')->where('isDelete', 0)->withoutField('isDelete')->limit(($currentPage-1)*$pageSize, $pageSize)->select();
                $pageTotal = Db::table('commData')->where('isDelete', 0)->count();
                $returndata = array('pageTotal'=> $pageTotal, 'data'=> $userdata);
                return $returndata;
            }
        }
    }
    public function reachCommData() {
        if(Request::method()){
            $gettabledata = Request::param();
            $currentPage = $gettabledata['currentPage'];
            $pageSize = $gettabledata['pageSize'];
            $CommId = $gettabledata['CommId'];
            $CommName = $gettabledata['CommName'];
            $OneLevel = $gettabledata['OneLevel'];
            $TwoLevel = $gettabledata['TwoLevel'];
            $CommState = $gettabledata['CommState'];

            $whereMid = $whereManName = $whereStoreProvince = $whereStoreCity = $whereStoreQu = '';
            if ($CommId != '' || $CommId != null) {
                $whereMid = "and CommId like '%" .$CommId. "%' "; 
            }
            if ($CommName != '' || $CommName != null) {
                $whereManName = "and CommName like '%" .$CommName. "%' "; 
            }
            if ($OneLevel != '' || $OneLevel != null) {
                $whereStoreProvince = "and OneLevel like '%" .$OneLevel. "%' "; 
            }
            if ($TwoLevel != '' || $TwoLevel != null) {
                $whereStoreCity = "and TwoLevel like '%" .$TwoLevel. "%' "; 
            }
            if ($CommState != '' || $CommState != null) {
                $whereStoreQu = "and CommState like '%" .$CommState. "%' "; 
            }
            $res = Db::query("SELECT * From commData Where isDelete = 0 "
            .$whereMid .$whereManName .$whereStoreProvince .$whereStoreCity .$whereStoreQu
            ."LIMIT " . ($currentPage - 1)*$pageSize .','. $pageSize);
            $count = sizeof(Db::query("SELECT * From commData Where isDelete = 0 "
            .$whereMid .$whereManName .$whereStoreProvince .$whereStoreCity .$whereStoreQu));
            if ($res) {
                $reach = array('code'=> 0, 'mes'=> '查询成功！', 'data'=> $res, 'pageTotal'=> $count);
                return json($reach);
            }else {
                $reach = array('code'=> 1, 'mes'=> '没有查询到相关信息！', 'data'=> $res, 'pageTotal'=> 0);
                return json($reach);
            }
        }
    }
    public function findoneLevel() {
        if(Request::method()){
            $gettabledata = Request::param();
            $res = Db::table('oneLevel')->where('isDelete', 0)->select();
            if ($res) {
                $reach = array('code'=> 0, 'mes'=> '查询成功！', 'data'=> $res);
                return json($reach);
            }else {
                $reach = array('code'=> 1, 'mes'=> '查询失败！');
                return json($reach);
            }
        }
    }
    public function findtwoLevel() {
        if(Request::method()){
            $gettabledata = Request::param();
            $OneLevelCode = $gettabledata['OneLevelCode'];
            if ($OneLevelCode) {
                $res = Db::table('twoLevel')->where('OneLevelCode', $OneLevelCode)->select();
                if ($res) {
                    $reach = array('code'=> 0, 'mes'=> '查询成功！', 'data'=> $res);
                    return json($reach);
                }else {
                    $reach = array('code'=> 1, 'mes'=> '查询失败！');
                    return json($reach);
                }
            } else {
                $reach = array('code'=> 0, 'mes'=> '请选择一级分类！', 'data'=> '');
                return json($reach);
            }
        }
    }
    public function findQu() {
        if(Request::method()){
            $gettabledata = Request::param();
            $citycode = $gettabledata['citycode'];
            $res = Db::table('area')->where('citycode', $citycode)->select();
            if ($res) {
                $reach = array('code'=> 0, 'mes'=> '查询成功！', 'data'=> $res);
                return json($reach);
            }else {
                $reach = array('code'=> 1, 'mes'=> '查询失败！');
                return json($reach);
            }
        }
    }
    public function getProCityQu() {
        if(Request::method()){
            // $gettabledata = Request::param();
            $province = Db::table('province')->select();
            $city = Db::table('city')->select();
            $area = Db::table('area')->select();

            if ($province) {
                $reach = array('code'=> 0, 'mes'=> '查询成功！', 'province'=> $province, 'city'=> $city, 'area'=> $area);
                return json($reach);
            }else {
                $reach = array('code'=> 1, 'mes'=> '查询失败！');
                return json($reach);
            }
        }
    }
    public function getStoreData() {
        if(Request::method()){
            $gettabledata = Request::param();
            $StoreId = $gettabledata['StoreId'];
            if ($StoreId) {
                $StoreData = Db::table('storeManage')->where('isDelete', 0)->where('StoreId', $StoreId)->select();
            } else {
                $StoreData = Db::table('storeManage')->where('isDelete', 0)->select();
            }
            if ($StoreData) {
                $reach = array('code'=> 0, 'mes'=> '查询成功！', 'StoreData'=> $StoreData);
                return json($reach);
            }else {
                $reach = array('code'=> 1, 'mes'=> '查询失败！');
                return json($reach);
            }
        }
    }
    public function addCommData() {
        if(Request::method()){
            $gettabledata = Request::param();
            $CommId = $gettabledata['CommId'];
            $StoreData = Db::table('commData');
            $panduanMid = $StoreData->where('CommId', $CommId)->find();
            $randStr = str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890');
            $rand = substr($randStr,0,6);
            $insertdata = [
                'CommId'         =>   $gettabledata['CommId'],
                'CommName'     =>   $gettabledata['CommName'],
                'CommPrice'      =>   $gettabledata['CommPrice'],
                'CommSell'    =>   $gettabledata['CommSell'],
                'CommSellSum'    =>   0,
                'OneLevel' =>   $gettabledata['OneLevel'],
                'TwoLevel' =>   $gettabledata['TwoLevel'],
                'CommMessage' =>   $gettabledata['CommMessage'],
                'CommIntroduce' =>   $gettabledata['CommIntroduce'],
                'CommState' =>   $gettabledata['CommState'],
                'CreateTime' =>   date("Y-m-d H:i:s"),
                'PageView' =>   0,
                'StoreId' =>   $gettabledata['StoreId'],
                'StoreName' =>   $gettabledata['StoreName'],
                'CommInventory'    =>   $gettabledata['CommInventory'],
                'CommImage'    =>   $gettabledata['CommImage'],
                'CommRank'    =>   0,
                'isDelete'    =>   0
            ];
            if(isset($panduanMid)) {
                $data = array('code'=> 2, 'mes'=> '商品编号已存在！请重新输入！');
                return json($data);
            } else {
                $flag = $StoreData->insert($insertdata);
                if ($flag) {
                    $data = array('code'=> 0, 'mes'=> '新增成功！');
                    return json($data);
                } else {
                    $data = array('code'=> 1, 'mes'=> '新增失败！');
                    return json($data);
                }
            }
        }
    }
    public function updateCommData() {
        if(Request::method()){
            $gettabledata = Request::param();
            $CommId = $gettabledata['CommId'];
            $updatedata = [
                'CommId'         =>   $gettabledata['CommId'],
                'CommName'     =>   $gettabledata['CommName'],
                'CommPrice'      =>   $gettabledata['CommPrice'],
                'CommSell'    =>   $gettabledata['CommSell'],
                // 'CommSellSum'    =>   0,
                'OneLevel' =>   $gettabledata['OneLevel'],
                'TwoLevel' =>   $gettabledata['TwoLevel'],
                'CommMessage' =>   $gettabledata['CommMessage'],
                'CommIntroduce' =>   $gettabledata['CommIntroduce'],
                'CommState' =>   $gettabledata['CommState'],
                'CreateTime' =>   $gettabledata['CreateTime'],
                // 'PageView' =>   0,
                'StoreId' =>   $gettabledata['StoreId'],
                'StoreName' =>   $gettabledata['StoreName'],
                'CommInventory'    =>   $gettabledata['CommInventory'],
                'CommImage'    =>   $gettabledata['CommImage'],
                // 'CommRank'    =>   0,
                'isDelete'    =>   0
            ];
            $updatedata2 = [
                'CommId'         =>   $gettabledata['CommId'],
                'CommName'     =>   $gettabledata['CommName'],
                'CommSell'      =>   $gettabledata['CommSell'],
                'CommImage'      =>   $gettabledata['CommImage'],
            ];
            if(isset($CommId)) {
                $StoreData = Db::table('commData');
                $panduanMid = $StoreData->where('CommId', $CommId)->update($updatedata);
                Db::table('hotCommManage')->where('CommId', $CommId)->update($updatedata2);
                if (isset($panduanMid)) {
                    $update = array('code'=> 0, 'mes'=> '更新成功！');
                    return json($update);
                } else {
                    $update = array('code'=> 1, 'mes'=> '更新失败！');
                    return json($update);
                }
            } else {
                $update = array('code'=> 1, 'mes'=> '找不到该用户！');
                return json($update);
            }
        }
    }
    public function deleteCommData() {
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
                    $res = Db::table('commData')->where('CommId', $value)->save(['isDelete'=>1]);
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