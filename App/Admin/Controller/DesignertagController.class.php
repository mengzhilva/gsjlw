<?php
namespace Admin\Controller;

class DesignertagController extends CommonController {
  public function __construct(){
      parent::__construct();
        //权限判断
        $this->checkLevel(72);
    }
    public function add(){
        $this->checkLevel(73);
        $this->display("add");
    }
   public function check(){
    $this->checkLevel(74);
   	$id= trim($_GET['ID']);
    $Tag = M('designertag')->where('ID='.$id)->field('ID,STATUS,TAG')->find();
    $this->assign('Tag',$Tag);

    $this->display("check");
   }
   public function checkUp(){
   	$id= $_POST['ID'];
   	$data['STATUS'] = $_POST['STATUS'];
   	$Tag = M('Designertag')->where('ID='.$id)->save($data);
   	$this->success('审核成功！');
   }
   // public function update(){
   // 	$id=$_POST['id'];
   // 	$data['TAG'] = trim($_POST['TAG']);
   // 	$Tag = M('designertag')->where('ID='.$id)->save($data);
   // 	$this->success('编辑成功！');
   // }
   public function insert(){
    $_POST['STATUS'] = '0';//默认为新增
   	parent::insert();
   }
   public function del() {
        $this->checkLevel(75);
        $id = $_REQUEST['ids'];
        $model = M('Designertag');
        if (isset($id) && is_string($id)) {
            //批量删除
            $condition = array('ID' => array('in', $id));
            if (false !== $model->where($condition)->delete()) {
                $this->success(L('删除成功'));
            } else {
                $this->error(L('删除失败'));
            }
        } else if (is_array($id)){
            //批量删除
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