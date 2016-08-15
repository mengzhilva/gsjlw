<?php
namespace Admin\Controller;

// 好邻居留言控制器
class ReservationhljController extends CommonController {

    //加载小区好邻居留言列表页
    public function index() {
        //列表过滤器，生成查询Map对象
        $map = $this->_search();
        if(method_exists($this, '_filter')) {
            $this->_filter($map);
        }
        //判断采用自定义的Model类
        $model = D('reservation_hlj');
        
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

            // 将关联的城市id，替换名称

            $v['ctname'] = M('city')->where("ID='{$v['CID']}'")->getField('NAME');

            // 将数据库中datetime的时间转化为时间戳
            $v['time'] = strtotime($v['time']);
            

            // 处理输出审核状态
            switch($v['Dealstate']){
                case 0:
                    $v['dealstate'] = '未处理';
                    break;
                case 1:
                    $v['dealstate'] = '已处理';
                    break;
            }

        }

        // dump($list);
        return $list;
    }

}