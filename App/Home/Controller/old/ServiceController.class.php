<?php
namespace Home\Controller;
// +----------------------------------------------------------------------
// | * 开发人员：葛荣
// +----------------------------------------------------------------------
// | * 开发时间：2015年1月22日
// +----------------------------------------------------------------------
// | * 修改时间：2014年1月22日
// +----------------------------------------------------------------------
// | * 页面说明：自助服务
// +----------------------------------------------------------------------
class ServiceController extends CommonController {
	protected  $user;	

	public function _initialize(){
		parent::_initialize();
		$this->user = $_SESSION[C('SESSION_USER_KEY')];

		$title = '自助服务-免费服务';
		$this->assign('title',$title);
	}
	public function index(){
		//免费服务
		$this->display('index');
	}

	public function service() {
		//自助服务
		$title = '自助服务-免费服务';

		$this->assign('title',$title);
		$this->display('bookself');
	}
	public function reservation(){
		//自助服务表单提交

		$data['TYPE'] = I('post.TYPE',0);
		$data['NAME'] = I('post.NAME',0);
		$data['PHONE'] = I('post.PHONE',0);
		$data['community'] = I('post.community',0);
		$data['area'] = I('post.area',0);
		$data['requirement'] =I('post.requirement',0);
		$data['CID'] = $this->city['ID'];
		$data['uid'] = $this->user['id'];
		$data['TIME'] = date('Y-m-d');

		$ResultArray = M("reservation_construction");
    	if($ResultArray->add($data)){
    			 $this->success(L('预约成功！'));
		} else {
			$this->error("申请失败！");
		}
	}

	public function company(){
		//合作企业
		$this->display('company');
	}
	//门店查询
	public function shoplist(){

		$shops =M('storefront')->field('ID,Address,TelePhone,StoreName')->where('Status=1 AND CID='.$this->city['ID'])->select();
		$this->assign('shops',$shops);
		$this->display('shoplist');
	}
	

}