<?php

namespace app\controller;

use app\BaseController;
use League\Flysystem\File;
use think\facade\Filesystem;
use think\facade\Request;

class Upload extends BaseController
{
  public function upload()
  {
    $file = request()->file('image');
    try {
        // 验证
        validate(['image'=>[
            'fileSize' => 410241024,
            'fileExt' => 'jpg,jpeg,png,bmp,gif',
            'fileMime' => 'image/jpeg,image/png,image/gif', //这个一定要加上，很重要我认为！
        ]])->check(['image'=> $file]);
        // 上传到服务器
        $saveName = Filesystem::disk('public')->putFile('xingzouImg', $file);
        if($saveName) {
            $result = ['code' => 1, 'msg' => '上传成功'];
            return json($result);
        }
    } catch(\Exception $e) {
        $result = ['code' => 0, 'msg' => '上传失败'];
        return json($result);
    }
    // if ($uploadImg) {
    //   // $uploadImg ="http://127.0.0.1/ziyuan/runtime/storage/" .$uploadImg;
    //   // $uploadImg ="http://www.ziyuan.com/runtime/storage/" .$uploadImg;
    //   $uploadImg = "https://www.lingk.club/xiehoushufang_hh/runtime/storage/" . $uploadImg;
    //   return json($uploadImg);
    // } else {
    //   $result = ['code' => 0, 'msg' => '上传失败'];
    //   return json($result);
    // }
    // dump($uploadImg);
  }
}
