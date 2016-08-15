<?php
namespace Admin\Controller;

class StorefrontController extends CommonController {
  //封装搜素条件,自动调用的方法
    public function _filter(&$map){
        //搜索条件有值则做封装
        if(!empty($_REQUEST['CID'])){
            $where['CID']  = array('like',"%{$_REQUEST['CID']}%");
            $where['StoreName']  = array('like',"%{$_REQUEST['StoreName']}%");
            $where['_logic'] = 'or';
            $map['_complex'] = $where; 
        }
        //查询城市
        $CID=$_REQUEST['CID'];
        $cityna = getcityname($CID);
     
        $this->assign('MCID',$CID);
        $this->assign('cityna',$cityna);
    }
    public function __construct(){
      parent::__construct();
      $this->checkLevel(83);
      //查询城市
      $this->citylevel = $_SESSION['scadminuser']['cid'];
      $city = M("city");
      $cityname = $city->where('id in ('.$this->citylevel.')')->select();
      $this->assign('cityname',$cityname);
    }

  public function add(){
     $this->checkLevel(84);
    $this->display("add");
  }

  public function getcitya(){
        $cid=$_GET['cid'];
        $cityaname = M("cityarea")->where("CID=".$cid)->order('ID ASC')->select();
        $caaname = '[["","选择区域"]';
        foreach($cityaname as $v){
            $caaname .= ',["'.$v['ID'].'","'.$v['NAME'].'"]';
        }
        $caaname .= ']';
        exit($caaname);
        
    }
  public function insert(){
    $_POST['Status'] = '0';
    parent::insert();
   }
   public function edit(){
      $this->checkLevel(85);
      $cityname = M("city")->select();
      $this->assign('cityname',$cityname);
      parent::edit();
    }
  
  public function update() {
     parent::update();
  }
  public function del() {
        $this->checkLevel(86);
        $id = $_REQUEST['ids'];print_r($$_REQUEST['ids']);
        $model = M('Storefront');
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

    public function check(){
    $this->checkLevel(112);
    $id= trim($_GET['ID']);
    $Storefront = M('Storefront')->where('ID='.$id)->field('ID,Status,StoreName')->find();
    $this->assign('vo',$Storefront);

    $this->display("check");
   }
   public function checkUp(){

    $id= $_POST['ID'];
    $data['Status'] = $_POST['Status'];
    $Storefront = M('Storefront')->where('ID='.$id)->save($data);
        $this->success('审核成功！');
   }

}