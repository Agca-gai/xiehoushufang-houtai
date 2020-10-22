<?php
namespace app\controller;
use app\model\User;
use think\facade\Request;
use think\facade\Db;

class CommClassify {
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
    public function addCommClassify() {
        if(Request::method()){
            $gettabledata = Request::param();
            $WhatClassify = $gettabledata['WhatClassify'];
            $LevelCode = $gettabledata['LevelCode'];
            $FaterLevelCode = $gettabledata['FaterLevelCode'];
            if ($WhatClassify == 1) {
                $OneData = Db::table('oneLevel');
                $panduanMid = $OneData->where('OneLevelCode', $LevelCode)->find();
                $insertdata = [
                    'OneLevelCode'         =>   $gettabledata['LevelCode'],
                    'OneLevelName'     =>   $gettabledata['LevelName'],
                    'WhatClassify'      =>   $gettabledata['WhatClassify'],
                    'Oneremarks' =>   $gettabledata['remarks'],
                ];
                if(isset($panduanMid)) {
                    $data = array('code'=> 2, 'mes'=> '分类编号已存在！请重新输入！');
                    return json($data);
                } else {
                    $flag = $OneData->insert($insertdata);
                    if ($flag) {
                        $data = array('code'=> 0, 'mes'=> '新增成功！');
                        return json($data);
                    } else {
                        $data = array('code'=> 1, 'mes'=> '新增失败！');
                        return json($data);
                    }
                }
            } else {
                $TwoData = Db::table('twoLevel');
                $panduanMid = $TwoData->where('TwoLevelCode', $LevelCode)->find();
                $insertdata = [
                    'TwoLevelCode'         =>   $gettabledata['LevelCode'],
                    'TwoLevelName'     =>   $gettabledata['LevelName'],
                    'WhatClassify'      =>   $gettabledata['WhatClassify'],
                    'OneLevelCode'    =>   $gettabledata['FaterLevelCode'],
                    'Tworemarks' =>   $gettabledata['remarks'],
                ];
                if(isset($panduanMid)) {
                    $data = array('code'=> 2, 'mes'=> '分类编号已存在！请重新输入！');
                    return json($data);
                } else {
                    $flag = $TwoData->insert($insertdata);
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
    }
    public function updateCommClassify() {
        if(Request::method()){
            $gettabledata = Request::param();
            $LevelCode = $gettabledata['LevelCode'];
            $WhatClassify = $gettabledata['WhatClassify'];
            if ($WhatClassify == 1) {
                $updatedata = [
                    'OneLevelCode'         =>   $gettabledata['LevelCode'],
                    'OneLevelName'     =>   $gettabledata['LevelName'],
                    'WhatClassify'      =>   $gettabledata['WhatClassify'],
                    'Oneremarks' =>   $gettabledata['remarks'],
                ];
                if(isset($LevelCode)) {
                    $StoreData = Db::table('oneLevel');
                    $panduanMid = $StoreData->where('OneLevelCode', $LevelCode)->update($updatedata);
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
            } else {
                $updatedata = [
                    'TwoLevelCode'         =>   $gettabledata['LevelCode'],
                    'TwoLevelName'     =>   $gettabledata['LevelName'],
                    'WhatClassify'      =>   $gettabledata['WhatClassify'],
                    'OneLevelCode'    =>   $gettabledata['FaterLevelCode'],
                    'Tworemarks' =>   $gettabledata['remarks'],
                ];
                if(isset($LevelCode)) {
                    $StoreData = Db::table('twoLevel');
                    $panduanMid = $StoreData->where('TwoLevelCode', $LevelCode)->update($updatedata);
                    if (isset($panduanMid)) {
                        $update = array('code'=> 0, 'mes'=> '更新成功！');
                        return json($update);
                    } else {
                        $update = array('code'=> 1, 'mes'=> '更新失败！');
                        return json($update);
                    }
                } else {
                    $update = array('code'=> 1, 'mes'=> '找不到该分类！');
                    return json($update);
                }
            }

        }
    }
    public function deleteCommClassify() {
        if(Request::method()){
            $gettabledata = Request::param();
            // return $gettabledata['array'];
            $WhatClassify = $gettabledata['WhatClassify'];
            $OneLevelCode = $gettabledata['OneLevelCode'];
            if ($WhatClassify == 1) {
                $res = Db::table('oneLevel')->where('OneLevelCode', $OneLevelCode)->save(['isDelete'=>1]);
                if($res) {
                    $delete = array('code'=> 0, 'mes'=> '删除成功！');
                    return json($delete);
                } else {
                    $delete = array('code'=> 1, 'mes'=> '删除失败！');
                    return json($delete);
                }
            } else {
                $TwoLevelCode = $gettabledata['TwoLevelCode'];
                $res = Db::table('twoLevel')->where('TwoLevelCode', $TwoLevelCode)->save(['isDelete'=>1]);
                if($res) {
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