<?php
namespace Admin\Controller;

class DesignermediahonorController extends CommonController {
  //封装搜素条件,自动调用的方法
    public function _filter(&$map){
        //搜索条件有值则做封装
        if(!empty($_REQUEST['DID'])){
            $where['DID']  = array('like',"%{$_REQUEST['DID']}%");
            $where['_logic'] = 'or';
            $map['_complex'] = $where; 
        }
        //查询城市
        $DID=$_REQUEST['DID'];
        $designername = getdesignername($DID);
     
        $this->assign('DID',$DID);
        $this->assign('designername',$designername);
    }
    public function __construct(){
      parent::__construct();
      $this->checkLevel(87);
      //查询城市
      $DesignerArray = M("Designer");
      $Designers = $DesignerArray->select();
      $this->assign('Designers',$Designers);
    }

  public function add(){
    $this->checkLevel(88);
    $cityname = M("city")->select();
    $this->assign('cityname',$cityname);
    $this->display("add");
  }

   public function edit(){
     $this->checkLevel(89);
      $cityname = M("city")->select();
      $this->assign('cityname',$cityname);
      parent::edit();
    }
  
  public function update() {
     parent::update();
  }
  public function del() {
       $this->checkLevel(90);
       $id = $_REQUEST['ids'];print_r($$_REQUEST['ids']);
        $model = M('Designermediahonor');
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