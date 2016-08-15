<?php
namespace Home\Controller;
/**
 * 首页控制器
 * 开发人员：吕定国
 * 开发时间：2015.1.08
 * 修改时间：2015.1.12
 */
class PackageController extends CommonController {
	
	public function __initialize(){
		parent::__intialize();
	}

	public function index() {
		//幻灯片
		$where = array();
		$where['CID'] = $this->city['ID'];
		$where['SID'] = 9;
		$hdp = M('slideshow')->where($where)->limit(5)->select();

		
		//套餐价格
		$package3=M('package')->where('HTYPE = 100  and VTYPE =' .$this->city['PTYPE'])->find();
		$package6=M('package')->where('HTYPE = 200  and VTYPE =' .$this->city['PTYPE'])->find();
		$package9=M('package')->where('HTYPE = 300  and VTYPE =' .$this->city['PTYPE'])->find();
		$price['p3']=M('package_city_config')->where('PID = '. $package3['ID'] .'  and CID =' .$this->city['ID'])->getField('Price');
		$price['p6']=M('package_city_config')->where('PID = '. $package6['ID'] .'  and CID =' .$this->city['ID'])->getField('Price');
		$price['p9']=M('package_city_config')->where('PID = '. $package9['ID'] .'  and CID =' .$this->city['ID'])->getField('Price');

		//套餐空间
		$title="整体家装_实创装饰";
		$pid=$package3['ID'];
		switch ($_REQUEST['ptype']) {
			case '3':
				$pid=$package3['ID'];
				$title=$package3['NAME'] . "_整体家装_实创装饰";
				break;
			case '6':
				$pid=$package6['ID'];
				$title=$package6['NAME'] . "_整体家装_实创装饰";
				break;
			case '9':
				$pid=$package9['ID'];
				$title=$package9['NAME'] . "_整体家装_实创装饰";
				break;
			default:
				# code...
				break;
		}
		//3系
		$pccidp3=M('package_city_config')->where('PID = '. $pid .'  and CID =' .$this->city['ID'])->getField('ID');
		$pccrp3=M('package_city_config_itemroom')->where('PCCID = '. $pccidp3)->select();

		//套餐主材
		//3系
		$pccbp3=M('package_city_config_itembrand')->where('PCCID = '. $pccidp3)->select();		
		// //var_dump($slds);
		// //效果图
		// $wherexg['cityID'] = $this->city['ID'];
		// $wherexg['IS3D'] = 0;
		// $wherexg['status'] = 1;
		// $xg = M("casedecorate")->where($wherexg)->limit(10)->select();
		// //var_dump($xg);
		// //设计师
		// $whered['CID'] = $this->city['ID'];
		// $whered['STATUS'] = 1;
		// $designer = M("designer")->where($whered)->limit(20)->select();
		// //var_dump($designer);
		// $title = "首页";
		// //模板赋值显示
		// $this->assign('title', $title);
		$this->assign('hdp', $hdp);
		$this->assign('ptype', $ptype);
		$this->assign('price', $price);
		$this->assign('pccrp3', $pccrp3);
		$this->assign('pccbp3', $pccbp3);
		// $this->assign('xg', $xg);
		// $this->assign('designer', $designer);
		
		$this->assign('title', $title);
		$this->display();
		return;
	}



	//3系
	public function three(){
		$this->city = 5;
		if($_REQUEST['k']=='pz'){
			$pc = $this->get_config_by_city('1',$this->city);
			$this->assign("pc",$pc);
		}
		$this->assign("k",$_REQUEST['k']);
		$this->display();
	}
	//6系
	public function six(){
		$this->city = 5;
		if($_REQUEST['k']=='pz'){
			$pc = $this->get_config_by_city('2',$this->city);
			$this->assign("pc",$pc);
		}
		$this->assign("k",$_REQUEST['k']);
		$this->display();
	}
	//9系
	public function nine(){
		$this->city = 5;
		if($_REQUEST['k']=='pz'){
			$pc = $this->get_config_by_city('3',$this->city);
			$this->assign("pc",$pc);
		}
		$this->assign("k",$_REQUEST['k']);
		$this->display();
	}



	//s3系
	public function sthree(){
		$this->city = 15;
		if($_REQUEST['k']=='pz'){
			$pc = $this->get_config_by_city('4',$this->city);
			$this->assign("pc",$pc);
		}
		$this->assign("k",$_REQUEST['k']);
		$this->display();
	}
	//s6系
	public function ssix(){
		$this->city = 15;
		if($_REQUEST['k']=='pz'){
			$pc = $this->get_config_by_city('5',$this->city);
			$this->assign("pc",$pc);
		}
		$this->assign("k",$_REQUEST['k']);
		$this->display();
	}
	//s9系
	public function snine(){
		$this->city = 15;
		if($_REQUEST['k']=='pz'){
			$pc = $this->get_config_by_city('6',$this->city);
			$this->assign("pc",$pc);
		}
		$this->assign("k",$_REQUEST['k']);
		$this->display();
	}


	//通过判断城市调出各个系的配置数据
	public function get_config_by_city($n,$city){
		$mod = M("");
		$pcity = M("package_city_config");

		//获取套装概述与适应人群
		$pc_res = $pcity->field("IMAGE,DESCRIPTION")->where("CID=$city and PID=$n")->select();
		
		//获取空间项目
		$pr_res =$mod->table('package_city_config as city,package_city_config_itemroom as room')->where("city.ID=room.PCCID and city.CID=$city and city.PID=$n")->select();
		foreach($pr_res as $key=>$val){
			$r_data[$key]['NAME'] = $val["NAME"];
			$r_data[$key]['DESC'] = $val["DESCRIPTION"];
		}

		//获取品牌展示
		$pb_res = $mod->table('package_city_config as city,package_city_config_itembrand as brand')->where("city.CID=brand.PCCID and city.CID=$city and city.PID=$n")->select();
		foreach($pb_res as $key=>$val){
			$b_data[$key]['NAME'] = $val["NAME"];
			$b_data[$key]['DESC'] = $val["DESCRIPTION"];
		}

		return array(
			"p" => $pc_res[0],
			"r" => $r_data,
			"b" => $b_data
		);

	}
	
}