<?php
namespace Home\Controller;
use Think\Controller;
// +----------------------------------------------------------------------
// | * 文件名称：实创家居前端-【案例部分】功能开发(CasedecorateController.class.php)
// +----------------------------------------------------------------------
// | * 开发人员：刘东让
// +----------------------------------------------------------------------
// | * 开发时间：2015年1月8日
// +----------------------------------------------------------------------
// | * 修改时间：2015年1月日
// +----------------------------------------------------------------------
class CasedecorateController extends CommonController{

	private $citylevel;

	public function __construct(){
		
		parent::__construct();

		$this->citylevel = $_SESSION['scadminuser']['cid'] = 1;

		$style= M('housestyle')->distinct(true)->field('FONTNAME')->select();
		$kong = M('housefunction')->distinct(true)->field('FONTNAME')->select();
		$hu   = M('housetype')->field('ID,NAME')->select();

		$city   = M('city')->field('ID,NAME')->select();
		$h_style= M('housestyle')->field('ID,BACKNAME')->select();
		$h_kong = M('housefunction')->field('ID,NAME')->select();
		$package= M('package')->field('ID,NAME')->select();

		$designer = M("designer")->field('ID,NAME')->where("CID='".$this->citylevel."'")->select();
		$community= M("community")->field('ID,NAME')->where("CID='".$this->citylevel."'")->select();

		$this->assign('designer',$designer);
		$this->assign('community',$community);
		
		$this->assign('city_list',$city);
		$this->assign('hstyle_list',$h_style);
		$this->assign('hkong_list',$h_kong);
		$this->assign('package_list',$package);

		$this->assign('hu_list',$hu);
		$this->assign('kong_list',$kong);
		$this->assign('style_list',$style);

	}


	/*
	* 方法功能：展示案例首页信息列表
	* 修改时间：2015年1月7日 Jean
	*/
	function case_pic_list(){
		$case = M("Casedecorate");
		$c_list = $case->field("NAME,PID,AREA,TYPEID,DID,ZAN")->limit(9)->select();
		$this->assign("cp_list",$c_list);
		$this->display();
	}

	/*
	* 方法功能：展示案例首页信息列表
	* 修改时间：2015年1月7日 Jean
	*/
	function _filter(&$map){
		
		//案例风格
		$houseStyle = I('get.houseStyle','');
		if(!empty($houseStyle)){
			$sids = M('housestyle')->field('ID')->where("FONTNAME='$houseStyle'")->select();
			foreach($sids as $val){
				$sid_arr[] = $val['ID'];
			}
			$map['casedecorate.SID'] = array('in',$sid_arr);
		}
		
		
		//案例空间
		$houseKong = I('get.houseKong','');
		if(!empty($houseKong)){
			$kids = M('housefunction')->field('ID')->where("FONTNAME='$houseKong'")->select();
			foreach($kids as $val){
				$kid_arr[] = $val['ID'];
			}
			$map['case_image.HID'] = array('in',$kid_arr);
		}
		
		//$map['case_image.HID'] = array('in','3,4,5,6');

		//案例户型
		$houseHu = I('get.houseHu','');
		if(!empty($houseHu)){
			$hids = M('housetype')->field('ID')->where("NAME='$houseHu'")->select();
			foreach($hids as $val){
				$hid_arr[] = $val['ID'];
			}
			$map['casedecorate.TYPEID'] = array('in',$hid_arr);
		}
		
		
		//案例面积
		$area = I('get.houseArea','');
		if(!empty($area)){
			switch($area){
				case '60':
					$map['casedecorate.AREA'] = array('between','1,60');
					break;
				case '60-90':
					$map['casedecorate.AREA'] = array('between','60,90');
					break;
				case '90-120':
					$map['casedecorate.AREA'] = array('between','90,120');
					break;
				case '120-160':
					$map['casedecorate.AREA'] = array('between','120,160');
					break;
				case '160-200':
					$map['casedecorate.AREA'] = array('between','160,200');
					break;
				case '200':
					$map['casedecorate.AREA'] = array('between','200,10000');
					break;
				
			}
		}
		
		
		//案例类型：3D 非3D
		$caseClass = I('get.caseClass','');
		if($caseClass==1){
			$map['IS3D'] = $caseClass;
		}else{
			$map['IS3D'] = 0;
		}
		
	}


	/*
	* 方法功能：展示案例列表信息  18701187022  
	* 修改时间：2015年1月7日 Jean 
	*/
	function index(){
		$map = array();
		
		if(method_exists($this, '_filter')) {
            $this->_filter($map);
        }
		//var_dump($map);

		//处理页码
		//$p=1;
		if(!empty($_POST['pageNum'])){
			$p=$_POST['pageNum'];
		}
		//$_GET['p']=$p;
		$_GET['p'] = I('get.p',1);
		
		//处理每页条数：
		//$numPerPage=18;
		if(!empty($_POST['numPerPage'])){
			$numPerPage=$_POST['numPerPage'];
		}else{
			$numPerPage=9;
		}

		//排序处理
		$sechType = I('get.sechType','default');
		switch($sechType){
			case 'default':
				$order="casedecorate.ID";
				$sort ="asc";
				break;
			case 'hot':
				$order="casedecorate.HITS";
				$sort ="desc";
				break;
			case 'new':
				$order="casedecorate.ID";
				$sort ="desc";
				break;
			default:
				$order="casedecorate.ID";
				$sort ="asc";
		}
		
		$mod = M("casedecorate");
		//$select = $mod->where($map)->select();
		$select = $mod->field("distinct(casedecorate.ID)")->join('case_image on casedecorate.ID=case_image.CID')->where($map)->select();
		$total = count($select);
		//echo $total;

		$page = new \Think\Page($total,$numPerPage);

		//$list = $mod->where($map)->order($order." ".$sort)->limit($page->firstRow.','.$page->listRows)->select();
		
		$list = $mod->field("distinct(casedecorate.ID),casedecorate.NAME,casedecorate.PID,casedecorate.AREA,casedecorate.TYPEID,casedecorate.DID,casedecorate.ZAN,casedecorate.IS3D,casedecorate.HITS")->join('case_image on casedecorate.ID=case_image.CID')->where($map)->order($order." ".$sort)->limit($page->firstRow.','.$page->listRows)->select();
		echo $mod->getLastSql();

		//$list = $mod->field("ID,NAME,PID,AREA,TYPEID,DID,ZAN,IS3D,HITS")->join('case_image on casedecorate.ID=case_image.CID')->where($map)->order($order." ".$sort)->limit($page->firstRow.','.$page->listRows)->select();
		//var_dump($list);

		//分页显示
        $pages = $page->show();

		//页码信息的设置
		$this->assign("totalCount",$total); //总数据条数
		$this->assign("numPerPage",$numPerPage); //每页数据条数
		$this->assign("currentPage",$p); //当前页码

		$this->assign("page",$pages);
		$this->assign("list",$list);
		
		$this->display();
	}

	/*
	* 方法功能：展示案例详情信息
	* 修改时间：2015年1月12日 Jean
	*/
	function detail(){
		$id = I('get.ID','0');
		if(!empty($id)){
			$mod = M("casedecorate");
			$details = $mod->field("NAME,DID,CID,TAG,IS3D,URL3D")->where("ID=$id")->select();
			if($details[0]['IS3D']==0){
				$case_img= M('case_image')->where("CID=$id")->select();
				
				//var_dump($case_img);
				//获取五种不同风格的类型
				$styles = $this->get_5styles($details[0]['SID']);
				$this->assign("5styles",$styles);
				$this->assign("case_image",$case_img);
			}
			
			//var_dump($details);
			//获取上一个和下一个
			$this->next_prev($id);
			$this->assign("detail_info",$details[0]);
			
			//$this->display();
		}
	}

	/*
	* 方法功能：提交免费户型设计信息
	* 修改时间：2015年1月13日 Jean
	*/
	function sub_fhdesign(){
		$exist = $this->yan_phone_exists($_POST['PHONE']);
		if($exist==1){
			echo 2;exit;
		}
		$FHD = D("Freehousedesign");
		if(!$FHD->create()){
			exit($FHD->getError());
		}else{
			$FHD->UPDATETIME = date("Y-m-d H:i:s");
			if($FHD->add()){
				echo 1;
			}else{
				echo 0;
			}
		}
	}
	
	/*
	* 方法功能：提交免费户型设计信息
	* 修改时间：2015年1月13日 Jean
	*/
	function yan_phone_exists($phone){
		$result=M('freehousedesign')->where("PHONE=$phone")->select();
		if($result){
			return 1;
		}else{
			return 0;
		}
	}

	/*
	* 方法功能：测试自动验证
	* 修改时间：2015年1月13日 Jean
	*/
	function test_validate(){
		$FH = D("freehousedesign"); 
		if (!$FH->create()){     
			exit($FH->getError());
		}else{    
			echo "sss";
			$FH->add();
		}
	}

	/*
	* 方法功能：测试自动验证
	* 修改时间：2015年1月13日 Jean
	*/
	function next_prev($id){
		$IDS = M("casedecorate")->field("ID")->select();
		foreach($IDS as $val){
			$arr[] = $val['ID'];
		}
		$key = array_keys($arr,$id);
		$max = count($arr)-1;
		$key = $key[0];
		$prev = ($key>0)?($key-1):0;
		$next = ($key<$max)?($key+1):$max;
		echo "上一个".$arr[$prev]."--&&&--下一个".$arr[$next];
	}

	/*
	* 方法功能：获取五个不同装修风格的案例
	* 修改时间：2015年1月14日 Jean
	*/
	function get_5styles($sid){
		//先设置了个默认值
		$s_arr[] = $sid=5;
		$style = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29);
		
		$stl_diff = array_diff($style,$s_arr);
		$choose= array_rand($stl_diff,5);
		
		foreach($choose as $val){
			$res = M("casedecorate")->field("ID,NAME")->where("SID=$val")->find();
			$arr[] = $res;
		}
		var_dump($arr);
	}

	/*
	* 方法功能：提交会员发布的案例
	* 修改时间：2015年1月14日 Jean
	*/
	function process_subcase(){
		$ID=$this->insert_case();
		if(!empty($ID)){
			$datas['CID'] = $ID;
			$datas['IMAGE'] = $_POST['IMG'];
			foreach($datas['IMAGE'] as $val){
				$in_arr['IMAGE'] = $val;
				$result = M('case_image')->data($in_arr)->add();
				if(!$result){
					$this->error("操作失败");
				}
			}
			echo "恭喜您，发布成功！";
		}
	}

	/*
	* 方法功能：提交会员发布的案例
	* 修改时间：2015年1月14日 Jean
	*/
	function insert_case(){
		$data['NAME'] = I('post.NAME','');
		$data['cityID'] = I('post.cityID',0);
		$data['PID'] = I('post.PID',0);
		$data['AREA'] = I('post.AREA',0);
		$data['IS3D'] = I('post.IS3D',0);
		$data['URL3D'] = I('post.URL3D','');
		$data['TYPEID'] = I('post.TYPEID',0);
		$data['DID'] = I('post.DID',0);
		$data['CID'] = I('post.CID',0);
		$data['SID'] = I('post.SID',0);
		$data['IMAGE'] = I('post.IMAGE','');
		$data['DESCRIPTION'] = I('post.DESCRIPTION','');
		
		$result = M('casedecorate')->data($data)->add();
		if($result){
			return $result;
		}
	}

	/*
	* 方法功能：用户加入社区动作
	* 修改时间：2015年1月15日 Jean
	*/
	function join_commuinty(){
		$data['comid'] = I("get.uid",0);
		$data['userid'] = I("get.cid",0);
		if(!empty($data['comid']) && !empty($data['userid'])){
			echo M("communityusers")->add($data)?1:0;
		}
	}




}
