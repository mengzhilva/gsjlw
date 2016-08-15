<?php
namespace Admin\Controller;

// 小区好邻居控制器
class CommunityhljController extends CommonController {

    //加载小区好邻居列表页
    public function index() {
        //列表过滤器，生成查询Map对象
        $map = $this->_search();
        if(method_exists($this, '_filter')) {
            $this->_filter($map);
        }
        //判断采用自定义的Model类
        $model = D('community_hlj');
        
        if(!empty($model)) {
            $this->_list($model, $map);
        }
        $this->display();
        return;
    }

    // 使用 CommonController回调函数_tigger_list，用于数据加工
    public function _tigger_list(&$list){

        // 将list数据遍历后作相应处理
        foreach($list as &$v){

            // 将关联的城市id，小区id，用户id，替换名称
            $v[] = M()->table(array("city"=>"ct","community"=>"com","users"=>"u"))->field("ct.name ctname,com.NAME comname,u.truename utruename")->where("ct.ID={$v['CID']} AND com.ID={$v['C_ID']} AND u.id={$v['UID']}")->find();

            // 将数据库中datetime的时间转化为时间戳
            $v['updatetime'] = strtotime($v['UPDATETIME']);
            $v['madedate'] = strtotime($v['MADEDATE']);

            // 处理输出审核状态
            switch($v['STATUS']){
                case 0:
                    $v['status'] = '待审核';
                    break;
                case 1:
                    $v['status'] = '审核通过';
                    break;
                case -1:
                    $v['status'] = '审核不通过';
                    break;
            }

            // 处理输出好邻居计划
            switch($v['ISHLJ']){
                case 0:
                    $v['ishlj'] = '否';
                    break;
                case 1:
                    $v['ishlj'] = '是';
                    break;
            }

            // 处理输出十年质保
            switch($v['ISTEN']){
                case 0:
                    $v['isten'] = '否';
                    break;
                case 1:
                    $v['isten'] = '是';
                    break;
            }
        }

        // dump($list);
            return $list;
    }


    
}