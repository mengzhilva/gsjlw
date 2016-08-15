<?php
namespace Home\Controller;
use Think\Controller;
/**
*前台添加小区控制器(test)，继承Controller
*方法：
*开发时间：2015-01-09
*修改时间：2015-01-12
*开发人员：李彭青 
*/
class AddcommunityController extends Controller{

    /**
     *加载添加小区页面
     */
    public function index() {
        $ctname = M('city')->field('id,name')->select();
        $this->assign('ctname',$ctname);
        $this->display();
    }

    /**
     *执行添加小区页面
     */
    // 执行添加小区页面
    private function insert() {
        $model = D('Community');
        unset( $_POST['ID']);
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
            // $housetype = $_POST['HOUSETYPES'];
            // $_POST['HOUSETYPES'] = implode(",",$housetype);
            $_POST['NAME'] = I('post.NAME');
            $_POST['ADDRESS'] = I('post.ADDRESS');
            $_POST['PROPERTY'] = I('post.PROPERTY');
            // $_POST['DESCRIPTION'] = I('post.DESCRIPTION');
            $_POST['STATUS'] = 0;
            // $_POST['list'] = I('post.list');
        }
        if (false === $model->create()) {
            $this->error($model->getError());
        }
        //保存当前数据对象
        if ($result = $model->add()) { //保存成功
            // 回调接口
            if (method_exists($this, '_tigger_insert')) {
                $model->id = $result;
                $this->_tigger_insert($model);
            }
            
            //成功提示
            $this->success(L('新增成功'));
        } else {
            //失败提示
            $this->error(L('新增失败').$model->getLastSql());
        }
    }

    /** 执行加入社区操作
     */
    private function uadd() {
        $data['comid'] = I('get.ID',0,'intval');
        $data['userid'] = $_SESSION['scusers']['id'];
        $communityusers = M('communityusers');
        if($communityusers->create()) {
            $usersres = $communityusers->add();
        }
    }

    /**上传图片
     */
    private function doUpload(){

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

}
?>