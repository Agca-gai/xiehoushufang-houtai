<?php
namespace app\model;
// model 跟数据库打交道
use think\Model;

class User extends Model { // 链接数据库
    protected $connection = 'mysql';
}