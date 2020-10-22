<?php

namespace app\controller;

use app\BaseController;
use think\facade\Filesystem;
use think\facade\Request;

class Upload2 extends BaseController
{
  public function upload()
  {
    $file = Request::file('image');
    $uploadImg = Filesystem::putFile('topic', $file);
    // dump($uploadImg);
    if ($uploadImg) {
      // $uploadImg ="http://127.0.0.1/ziyuan/runtime/storage/" .$uploadImg;
      // $uploadImg ="http://www.ziyuan.com/runtime/storage/" .$uploadImg;
      $uploadImg = "https://www.lingk.club/xiehoushufang_hh/runtime/storage/" . $uploadImg;
      return json($uploadImg);
    } else {
      $result = ['code' => 0, 'msg' => '上传失败'];
      return json($result);
    }
    // dump($uploadImg);
  }
}
