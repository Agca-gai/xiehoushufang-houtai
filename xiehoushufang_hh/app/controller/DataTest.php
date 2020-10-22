<?php
namespace app\controller;

use app\model\User;
use think\facade\Db; // 门面模式  静态调用Db

class DataTest {
    public function index() {
        // $user =  Db::table('user')->select(); // 筛选出所有数据
        // $user =  Db::connect('mysql')->table('user')->select(); // 筛选出所有数据  完整形式
        // 单条数据查询
        // $user = Db::table('user')->where('ad', 1722)->find(); // 如果希望只查询一条语句可以用find
        // return Db::getLastSql(); // 可以查询原生sql语句
        // $user = Db::table('user')->where('ad', 172)->findOrFail(); // 返回一个异常
        // $user = Db::table('user')->where('ad', 172)->findOrEmpty(); // 返回一个空

        // 数据集
        // $user =  Db::table('user')->select(); // 筛选出所有数据
        // $user =  Db::table('user')->where('ad', 17)->select(); // 如果没有则返回空
        // $user =  Db::table('user')->where('ad', 17)->selectOrFail(); // 如果没有则返回异常
        // $user =  Db::table('user')->select()->toArray(); // 可以将数据集转换成数组
        // dump($user);

        // 如果有前缀，可以把table替换成name  // $user =  Db::name('user')->select(); // 筛选出所有数据
        
        // 其他查询方式‘value()
        // $user = Db::table('user')->where('ad', 1722)->value('name'); // 
        // $user = Db::table('user')->column('name'); //指定一个字段 值可以有多个
        // $user = Db::table('user')->column('name', 'ad'); // 可以加ad作为一个索引
        // return json($user);

        // 如果数据量过大，可以分批处理，减少内存开销
        // Db::table('user')->chunk(1, function($users){
        //     foreach($users as $user) {
        //         dump($user);
        //     }
        //     echo 1;
        // });

        // 利用游标查询方法，可以大量减少内存开销，每次查询只读取一行
        // $cursor = Db::table('user')->cursor();
        // foreach($cursor as $user) {
        //     dump($cursor);
        // }

        // $user = Db::table('user')->order('ad', 'asc')->select(); //更多链式查询可以查看手册
        // dump($user);

        // 如果多次使用数据库查询，
        // 那么每次静态创建都会生成一个实例，造成浪费，我们可以把对象实例保存下来，再进行反复调用
        $userQuery = Db::table('user');
        $user1 = $userQuery->where('ad', 1722)->find();
        $user2 = $userQuery->select();
        dump($user1);
        dump($user2);
        // 当同一个对象实例第二次查询后，会保留第一次查询的值
        $data1 = $userQuery->order('ad', 'desc')->find();
        return Db::getLastSql();
        $data2 = $userQuery->select();
        return Db::getLastSql();

        // 使用removeOption()方法，可以清理上一次查询的值
        $userQuery->removeOption('order')->select();
    }
    public function demo() {
        $user =  Db::connect('demo')->table('user')->select(); // 筛选出所有数据
        return json($user);
    }
    public function getUser() {
        $user = User::select();
        return json($user);
    }

    public function insert() {
        $data = [
            'ad'       =>   '1743',
            'name'     =>    '邓家豪',
            'age'      =>    '15',
            'phone'    =>   '1812525'
        ];
        // return Db::table('user')->insert($data);  // 如果添加成功返回一个1
        // 如果添加含有一个不存在的字段会抛出异常
        // 可以使用strict(false)强行新增抛弃不存在的字段
        // Db::table('user')->strict(false)->insert($data);

        // 如果采用的数据库是mysql  可以支持replace写入
        // insert 和 replace 写入的区别，前面表示表中存在主键相同则报错，后者则修改
        Db::table('user')->replace()->insert($data);
        return Db::getLastSql();

        // 使用insertGetId()方法，可以在新增成功后返回当前数据Id
        return Db::table('user')->insertGetId($data);
    }
    // 插入多数组
    public function insertAll() {
        $dataAll = [
            [
                'ad'       =>   '1744',
                'name'     =>    '邓家豪',
                'age'      =>    '15',
                'phone'    =>   '1812525'
            ],
            [
                'ad'       =>   '1745',
                'name'     =>    '邓家豪',
                'age'      =>    '15',
                'phone'    =>   '1812525'
            ],
            [
                'ad'       =>   '1746',
                'name'     =>    '邓家豪',
                'age'      =>    '15',
                'phone'    =>   '1812525'
            ],
        ];
        Db::table('user')->insertAll($dataAll);
    }
    
    // save()方法是一个通用方法，可以自行判断是新增还是修改（更新）数据
    // save()方法判断是否为新增或修改的依据为，是否存在主键，不存在既新增
    // Db::table('user')->save($data);
}