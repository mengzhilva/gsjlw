<?php
namespace Admin\Controller;
/*
*小区管理控制器，继承CommonController
*方法：
*       
*作者：李彭青 
*/

class CommunityController extends CommonController {

    // private $cityarea;
    private $citylevel;
    public function __construct(){
        parent::__construct();

        // 输出小区户型数据
        $housetype= M("housetype")->select();
        $this->assign('housetype',$housetype);

        // $this->cityarea = M('Cityarea');

        // 获取当前用户所拥有权限的城市ID
        $this->citylevel = $_SESSION['scadminuser']['cid'];

        // 获取拥有权限城市的数据
        $city = M('city')->where('id in ('.$this->citylevel.')')->select();
        $this->assign('city',$city);
    }

    /*
     *封装搜索条件，自动调用的方法
     */
    public function _filter(&$map){
        
        // 如果小区名称name有值则进行封装搜索
        if(!empty($_REQUEST['name']) ){
            $where['NAME']  = array('like', "%{$_REQUEST['name']}%");
            $where['_logic'] = 'or';
            $map['_complex'] = $where;
        }
        
        // 只允许显示拥有城市权限的小区
        $map['CID'] = array('in',$this->citylevel);

        // 如果城市名称(CID)有值则进行封装搜索
        if(!empty($_REQUEST['CID'])){
           $map['CID'] = array('in',$_REQUEST['CID']);
        }
       
    }

    /*
     *加载显示页面
     */
    public function index(){
        
        // 此方法权限ID为25
        $this->checkLevel(25);
        parent::index();
    }

    /*
     *使用CommonController回调函数_tigger_list,用于数据加工
     */
    public function _tigger_list(&$list){

        // 将list数据遍历后作相应处理
        foreach($list as &$v){
            
            // 将数据库中datetime的时间转化为时间戳
            $v['completedate'] = strtotime($v['COMPLETEDATE']);
            $v['updatetime'] = strtotime($v['UPDATETIME']);

            // 处理输出审核状态
            switch($v['STATUS']){
                case 0:
                    $v['status'] = '未审核';
                    break;
                case 1:
                    $v['status'] = '审核通过';
                    break;
                case -1:
                    $v['status'] = '审核未通过';
                    break;
            }

            // 处理输出是否是重点小区
            switch($v['IsImportent']){
                case 0:
                    $v['isimportent'] = '否';
                    break;
                case 1:
                    $v['isimportent'] = '是';
                    break;
            }
        }

        // 将处理后的数据返回
        return $list;
    }
    
    /*
     *加载添加小区页面
     */
    public function add(){

        // 此方法权限ID为26
        $this->checkLevel(26);
        $this->display("add");
    }

    /*
     *DWZ下拉列表城市级联(联动菜单)
     */
    public function getcitya(){

        // 接收级联菜单中城市的ID
        $data['CID'] = $_GET['cid'];

        // 获取cityarea表中CID为$_GET['cid']的区域名称并按ID正序排列
        $cityaname = M("cityarea")->where($data)->order('ID ASC')->select();

        // 组合级联菜单所需要的数据格式
        $caaname = '[["","选择区域"]';
        foreach($cityaname as $v){
            $caaname .= ',["'.$v['ID'].'","'.$v['NAME'].'"]';
        }
        $caaname .= ']';
        exit($caaname);
        
    }

    /*
     *执行添加小区的数据写入
     */
    public function insert(){

        // 此方法权限ID为26
        $this->checkLevel(26);

        // 检查community表中是否已经存在此小区
        $map['NAME'] = $_POST['NAME'];
        $map['CID'] = $_POST['CID'];
        $map['AID'] = $_POST['AID'];
        $check = M('community')->where($map)->select();

        // 判断小区如果存在将不再写入并提示，否则执行写入操作
        if(!empty($check)){
            $this->error('此小区已经添加!');
        }else{
            $_POST['UPDATETIME'] = date('Y-m-d',time());
            $housetype = $_POST['HOUSETYPES'];
            $_POST['HOUSETYPES'] = implode(",",$housetype);
            $_POST['NAME'] = I('post.NAME');
            $_POST['ADDRESS'] = I('post.ADDRESS');
            $_POST['PROPERTY'] = I('post.PROPERTY');
            $_POST['DESCRIPTION'] = I('post.DESCRIPTION');
            $_POST['STATUS'] = 0;
            $_POST['list'] = I('post.list');
            parent::insert();
        }
    }

    /*
     *加载上传图片对话框页面
     */
    public function upindex(){
        $this->display();
    }

    /*
     *加载小区编辑页面
     */
    public function edit(){

        // 此方法权限ID为27
        $this->checkLevel(27);
        parent::edit();
    }

    /*
     *执行编辑小区的数据更新
     */
    public function update(){

        // 此方法权限ID为27
        $this->checkLevel(27);
        // 判断此次编辑小区时如果未更新图片，将此字段删除以防覆盖表中数据
        if(empty($_POST['QQ'])){
            unset($_POST['QQ']);
        }
        // 同上
        if(empty($_POST['Weixin'])){
            unset($_POST['Weixin']);
        }
        // 同上
        if(empty($_POST['Image'])){
            unset($_POST['Image']);
        }

        $_POST['UPDATETIME'] = date('Y-m-d',time());
        $housetype = $_POST['HOUSETYPES'];
        $_POST['HOUSETYPES'] = implode(",",$housetype);
        $_POST['NAME'] = I('post.NAME');
        $_POST['ADDRESS'] = I('post.ADDRESS');
        $_POST['PROPERTY'] = I('post.PROPERTY');
        $_POST['DESCRIPTION'] = I('post.DESCRIPTION');
        $_POST['list'] = I('post.list');
        parent::update();
    }

    /*
     *加载小区审核页面
     */
    public function check(){

        // 此方法权限ID为29
        $this->checkLevel(29);

        // 获取需要审核数据的ID，并从表中获取相应信息
        $data['ID']= $_GET['ID'];
        $communitystatus = M('community')->where($data)->field('ID,STATUS,STATUSDESCRIPTION')->find();
        $this->assign('communitystatus',$communitystatus);
        $this->display("check");
    }

    /*
     *执行小区审核后的数据更新
     */
    public function chupdate(){

        // 此方法权限ID为29
        $this->checkLevel(29);
        parent::update();
    }

    /*
     *图片上传，与编辑器中的图片上传共用
     */
    public function doUpload(){

        $type = I('post.type','');//获取上传图片属性 qq,weixin,image
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize =3145728 ;// 设置附件上传大小 
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  = './Public/Uploads/'; // 设置附件上传目录
        $upload->autoSub = false; //关闭自动生成子目录
         
         // 上传文件 
         $info = $upload->upload();
         //准备响应信息
         $res=array("err"=>"","msg"=>"");
         $res['state']=0;
         
         if(!$info){
            // 上传错误提示错误信息
            $res['err']="上传失败：原因:".$upload->getError();
         }else{
            foreach($info as $upfile){
                // 上传成功
                $res['msg']=__ROOT__."/Public/Uploads/".$upfile['savename'];
                $res['state']=200;
                $res['type']= $type;
            }
         }
         // 将数据以ajax返回
         $this->ajaxReturn($res);
    }

    /*
     *执行删除操作，支持单个删除和多个删除数据
     */
    public function del() {

        // 此方法权限ID为28
        $this->checkLevel(28);

        $model = M(CONTROLLER_NAME);
        if(!empty($model)) {

            // 获取数据ID
            $id = $_REQUEST['ids']; 
            if (isset($id) && is_string($id)) {
                //批量删除，以,分隔的ID或单个ID
                $condition = array('ID' => array('in', $id));
                if (false !== $model->where($condition)->delete()) {
                    $this->success(L('删除成功'));
                } else {
                    $this->error(L('删除失败'));
                }
            } else if (is_array($id)){
                 //批量删除，数组方式
                $condition = array('ID'=> array('in', explode(',', $id)));
                if (false !== $model->where($condition)->delete()) {
                    $this->success(L('删除成功'));
                } else {
                    $this->error(L('删除失败'));
                }
            }else {
                $this->error('非法操作');
            }
        }
    }



}