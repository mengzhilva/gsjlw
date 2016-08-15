<?php
namespace Admin\Controller;
/*
*小区团购管理控制器，继承CommonController
*方法：
*       
*作者：李彭青 
*/

class GroupbuyController extends CommonController {

    private $cityarea;
    private $citylevel;
    public function __construct(){
        parent::__construct();

        // 输出小区户型数据
        $housetype= M("housetype")->select();
        $this->assign('housetype',$housetype);

        $this->cityarea = M('Cityarea');
        
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

        // 如果团购名称gname有值则做封装
        if( !empty($_REQUEST['gname']) ){
            $where['name']  = array('like', "%{$_REQUEST['gname']}%");
            $where['_logic'] = 'or';
            $map['_complex'] = $where;
            
        }

        // 只显示拥有城市权限的小区
        $map['cid'] = array('in',$this->citylevel);

        // 如果城市名称(CID)有值则进行封装搜索
        if(!empty($_REQUEST['CID'])){
           $map['cid'] = array('in',$_REQUEST['CID']);
        }
     

    }

    /*
     *加载显示页面
     */
    public function index(){

        // 此方法权限ID为102
        $this->checkLevel(102);
        parent::index();
    }

    /*
     *使用CommonController回调函数_tigger_list,用于数据加工
     */
    public function _tigger_list(&$list){

        // 将list数据遍历后作相应处理
        foreach($list as &$v){
            
            // 处理输出审核状态
            switch($v['status']){
                case 0:
                    $v['status'] = '未审核';
                    break;
                case 1:
                    $v['status'] = '审核通过';
                    break;
                case -1:
                    $v['status'] = '审核不通过';
                    break;
            }

            // 处理输出是否结束，当手工结束或超过结束时间时为是
            if(($v['isend'] == 1)  || ($v['endtime'] < time())){
                $v['end'] = "是";
            }else{
                $v['end'] = "否";
            }

        }

        // 将处理后的数据返回
        return $list;
    }
    
    /*
     *加载添加团购页面
     */
    public function add(){

        // 此方法权限ID为103
        $this->checkLevel(103);
        parent::add();
    }

    /*
     *执行添加小区团购的数据写入
     */
    public function insert(){

        // 此方法权限ID为103
        $this->checkLevel(103);
        // 对POST数据进行处理
        $_POST['addtime'] = time();
        $_POST['updatetime'] = time();
        $_POST['endtime'] = strtotime($_POST['endtime']);
        $_POST['cid'] = $_POST['obj_CID'];
        $_POST['aid'] = $_POST['obj_AID'];
        $_POST['comid'] = $_POST['obj_ID'];
        $_POST['comname'] = $_POST['obj_NAME'];
        $_POST['deid'] = $_POST['objdes_ID'];
        $_POST['dename'] = $_POST['objdes_NAME'];
        parent::insert();
    }

    /*
     *使用CommonController回调函数_tigger_insert,用于insert方法执行成功后执行其他数据操作
     */
    public function _tigger_insert(&$model){

        // 获取上次数据插入的ID
        $insertid = ($model->id);

        // 实例化pricelevel(价格等级表)
        $model = M("pricelevel");

        // 获取添加团购级别的数组数据
        $llname = $_POST['llname'];
        $llnumber= $_POST['llnumber'];
        $lllevel= $_POST['lllevel'];
        $llprice= $_POST['llprice'];

        // 循环将数据写入pricelevel表中
        for($i = 0;$i < count($llname); $i++){

            $name = $llname[$i];
            $number = $llnumber[$i];
            $level = $lllevel[$i];
            $price = $llprice[$i];
            $data = array('name'=>$name,'number'=>$number,'level'=>$level,'price'=>$price,'groupbuyid'=>$insertid);
            $level = $model->add($data);
            if(!$level){
                $this->error(L('新增价格等级失败').$model->getLastSql());
            }
        }
    }

    /*
     *加载上传图片对话框页面
     */
    public function upindex(){
        $this->display();
    }

    /*
     *加载小区团购编辑页面
     */
    public function edit(){

        // 此方法权限ID为104
        $this->checkLevel(104);

        // 实例化pricelevel表，通过get.id小区团购数据ID来获取此团购的价格等级数据
        $level = M("pricelevel");
        $id = I('get.id',0,'intval');
        $levelvo = $level->where('groupbuyid='.$id)->order('level asc')->select();
        if($levelvo){
            $this->assign('levelvo', $levelvo);
        }else{
            $this->error(L('获取价格等级失败！'));
        }
        parent::edit(); 
    }

    /*
     *执行编辑小区团购的数据更新
     */
    public function update(){

        // 此方法权限ID为104
        $this->checkLevel(104);

        // 判断此次编辑小区团购时如果未更新图片，将此字段删除以防覆盖表中数据
        if(empty($_POST['picurl'])){
            unset($_POST['picurl']);
        }
        $_POST['addtime'] = time();
        $_POST['updatetime'] = time();
        $_POST['endtime'] = strtotime($_POST['endtime']);
        // $_POST['cid'] = $_POST['obj_CID'];
        $_POST['cid'] = I('post.obj_CID',0,'intval');
        // $_POST['aid'] = $_POST['obj_AID'];
        $_POST['aid'] = I('post.obj_AID',0,'intval');
        // $_POST['comid'] = $_POST['obj_ID'];
        $_POST['comid'] = I('post.obj_ID',0,'intval');
        // $_POST['comname'] = $_POST['obj_NAME'];
        $_POST['comname'] = I('post.obj_NAME');
        // $_POST['deid'] = $_POST['objdes_ID'];
        $_POST['deid'] = I('post.objdes_ID',0,'intval');
        // $_POST['dename'] = $_POST['objdes_NAME'];
        $_POST['dename'] = I('post.objdes_NAME');
        parent::update();
    }

    /*
     *使用CommonController回调函数_tigger_update,用于update方法执行成功后执行其他数据操作
     */
    public function _tigger_update(&$model){

        // $updataid = $_POST['id'];
        $updataid = I('post.id',0,'intval');

        // 实例化pricelevel价格等级表
        $prm = M("pricelevel");

        // 获取添加团购级别的数组数据
        $llname = $_POST['llname'];
        $llnumber= $_POST['llnumber'];
        $lllevel= $_POST['lllevel'];
        $llprice= $_POST['llprice'];
        
        // 循环将数据写入pricelevel表中
        for($i = 0;$i < count($llname); $i++){

            $name = $llname[$i];
            $number = $llnumber[$i];
            $level = $lllevel[$i];
            $price = $llprice[$i];
            $data = array('name'=>$name,'number'=>$number,'level'=>$level,'price'=>$price,'groupbuyid'=>$updataid);
            $level = $prm->add($data);
            if(!$level){
                $this->error(L('修改价格等级失败').$prm->getLastSql());
                
            }

        }
        // 此团购已经存在的价格级别数据ID数据
        $llid = $_POST['llid'];
        $id = implode(',',$llid);
        // 将旧的数据删除
        if(isset($id)){
            if(false == $prm->delete($id)){
                $this->error(L('删除pricelevel失败'));
                exit;
            }
        }
    }
    
    /*
     *加载小区团购审核页面
     */
    public function check(){

        // 此方法权限ID为106
        $this->checkLevel(106);
        // 获取需要审核数据的ID，并取出相应数据
        $id = I('get.id',0,'intval');
        $groupbuystatus = M('groupbuy')->where('id='.$id)->field('id,status,statusdescription')->find();
        $this->assign('groupbuystatus',$groupbuystatus);
        $this->display("check");
    }

    /*
     *执行小区团购审核后的数据更新
     */
    public function chupdate(){

        // 此方法权限ID为106
        $this->checkLevel(106);
        parent::update();
    }

    /*
     *图片上传，与编辑器中的图片上传共用
     */
    public function doUpload(){

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
        // $res['type']="";
        if(!$info){
           // 上传错误提示错误信息
           $res['err']="上传失败：原因:".$upload->getError();
        }else{
           foreach($info as $upfile){
               // 上传成功
               $res['msg']=__ROOT__."/Public/Uploads/".$upfile['savename'];
               $res['state']=200;
           }
        }
        // 将数据以ajax返回
        $this->ajaxReturn($res);
    }

    /*
     *执行删除操作，支持单个删除
     */
    public function del() {

        // 此方法权限ID为105
        $this->checkLevel(105);

        //删除指定记录
        $model = M(CONTROLLER_NAME);
        if (!empty($model)) {
            $pk = $model->getPk();
            $id = $_REQUEST[$pk];
            if (isset($id)) {
                //批量删除
                $condition = array($pk => array('in', explode(',', $id)));
                if (false !== $model->where($condition)->delete()) {
                    // 将团购的价格级别表中对应数据进行删除
                    $groupbuyid = array('groupbuyid' => array('in',explode(',', $id)));
                    $prm = M('pricelevel')->where($groupbuyid)->delete();
                    $this->success(L('删除成功'));
                } else {
                    $this->error(L('删除失败'));
                }
            } else {
                $this->error('非法操作');
            }
        }
    }    

    /*
     *执行删除操作，支持多个删除
     */
    public function adel() {
        
        // 此方法权限ID为105
        $this->checkLevel(105);

        $model = M(CONTROLLER_NAME);
        if (!empty($model)) {
            $id = $_REQUEST['ids']; 
            if (isset($id) && is_string($id)) {
                //批量删除
                $condition = array('id' => array('in', $id));
                if (false !== $model->where($condition)->delete()) {

                    // 将团购的价格级别表中对应数据进行删除
                    $groupbuyid = array('groupbuyid' => array('in',explode(',', $id)));
                    $prm = M('pricelevel')->where($groupbuyid)->delete();

                    $this->success(L('删除成功'));
                } else {
                    $this->error(L('删除失败'));
                }
            } else if (is_array($id)){
                 //批量删除
                $condition = array('id'=> array('in', explode(',', $id)));
                if (false !== $model->where($condition)->delete()) {

                    // 将团购的价格级别表中对应数据进行删除
                    $groupbuyid = array('groupbuyid' => array('in',explode(',', $id)));
                    $prm = M('pricelevel')->where($groupbuyid)->delete();
                    
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