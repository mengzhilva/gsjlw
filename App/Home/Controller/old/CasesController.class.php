<?php
namespace Home\Controller;
// use Think\Controller;
// +----------------------------------------------------------------------
// | * 开发人员：葛荣
// +----------------------------------------------------------------------
// | * 开发时间：2015年1月9日
// +----------------------------------------------------------------------
// | * 修改时间：2014年1月9日
// +----------------------------------------------------------------------
// | * 页面说明：设计师
// +----------------------------------------------------------------------
class CasesController extends CommonController {
    public function __construct(){
        parent::__construct();

        //当前城市使用，$this->city['ID']
  

        //获取用户登陆的信息
        $this->user = $_SESSION[C('SESSION_USER_KEY')];

        $city = M("city");
        $cityname = $city->select();
        //擅长风格
        $style = M("housestyle");
        $housestyle = $style->select();

        //户型
        $type = M('housetype');
        $housetype = $type->select();

        //查询设计师级别
        $level = M("designerlevel");
        $levelname = $level->select();
        $this->assign('levelname',$levelname);

        $this->assign('housestyle',$housestyle);
        $this->assign('housetype',$housetype);
        $this->assign('cityname',$cityname);
    }
    //设计师首页
    public function  index(){

        //显示分类名称
        $level = M('designerlevel')->select();
        $housetype=M('housetype')->select();
        $housestyle=M('housestyle')->limit(6)->select();
        $package=M('package')->where('VTYPE='.$this->city['PTYPE'])->select();

        $housestyletitle='设计风格';
        $housetypetitle='房屋户型';
        $houseareatitle='房屋面积';
        $packagetitle='套餐类型';
        $order = 'id desc';
        $where['status'] = 1;
        // $where['cityID'] = $this->city['ID'];
        if(!empty($_REQUEST['housestyle'])){
            $where['SID'] = $_REQUEST['housestyle'];
            $housestyletitle=M('housestyle')->where('ID='.$_REQUEST['housestyle'])->getField('FONTNAME');
        }
        if(!empty($_REQUEST['housetype'])){
            $where['TYPEID'] = $_REQUEST['housetype'];
            $housetypetitle=M('housetype')->where('ID='.$_REQUEST['housetype'])->getField('NAME');
        }
        if(!empty($_REQUEST['housearea'])){
            switch ($_REQUEST['housearea']) {
                case '60':
                    $where['AREA'] =  array('LT','60');
                    $houseareatitle=$_REQUEST['housearea'].'㎡以下';
                    break;
                case '60-90':
                    $where['AREA'] =  array('BETWEEN','60 , 90');
                    $houseareatitle=$_REQUEST['housearea'].'㎡';
                    break;
                case '90-120':
                    $where['AREA'] =  array('BETWEEN','90 , 120');
                    $houseareatitle=$_REQUEST['housearea'].'㎡';
                    break;
                case '120-160':
                    $where['AREA'] =  array('BETWEEN','120 , 160');
                    $houseareatitle=$_REQUEST['housearea'].'㎡';
                    break;
                case '160-200':
                    $where['AREA'] =  array('BETWEEN','160 , 200');
                    $houseareatitle=$_REQUEST['housearea'].'㎡';
                    break; 
                case '200':
                    $where['AREA'] =  array('GT','200');
                    $houseareatitle=$_REQUEST['housearea'].'㎡以上';
                    break;           
                default:
                    # code...
                    break;
            }

        }
        if(!empty($_REQUEST['package'])){
            $where['PID'] = $_REQUEST['package'];
            $packagetitle=M('package')->where('VTYPE= ' . $this->city['PTYPE'] . ' and HTYPE='.$_REQUEST['package'])->getField('NAME');
        }
        /************设计师分页开始******************************/
        //设置当前页码
        if(!empty($_REQUEST['p'])) {
            $nowPage=$_REQUEST['p'];
        }else{
            $nowPage=1;
        }
        $_GET['p']=$nowPage;
        //每页显示的记录数
        if (!empty($_REQUEST['numPerPage'])) {
            $numPerPage = $_REQUEST['numPerPage'];
        } else {
            $numPerPage = '5';
        }
        $casenum = M('casedecorate')->where($where)->select();
        $total = count($casenum);
        //创建分页对象
        $p = new \Think\Page($total, $numPerPage);

        $where['_logic'] = 'AND';
        $map['_complex'] = $where;

        $lists = M('')->table('casedecorate c')->field('c.*,(select NAME from community where ID=c.CID) as cname,(select NAME from housetype where ID=c.TYPEID) as tname')->where($map)->order($order)->limit($p->firstRow.','.$p->listRows)->select();
        // echo $this->$lists->getLastSql();
        //exit();
        //$page =  $p->show($this->city['DOMAIN']);
         $page =  $p->show();

        /************设计师分页结束******************************/
        $title="案例列表";
        $this->assign('title',$title);
        $this->assign('housestyletitle',$housestyletitle);
        $this->assign('housetypetitle',$housetypetitle);
        $this->assign('houseareatitle',$houseareatitle);
        $this->assign('packagetitle',$packagetitle);
        $this->assign('housetype',$housetype);
        $this->assign("housestyle", $housestyle);
        $this->assign("package", $package);
        $this->assign("lists", $lists);
        $this->assign("page", $page);
        
        $this->display();
    }
    //详情页
    public function info() {

        //$model = M('designer');
        $id = I('get.id',0);
        //$where['ID'] = $id;
        //$show = $model->where($where)->find();
        //$prev = $model->where('ID < '.$id)->order('id desc')->find();
        //$next = $model->where('ID > '.$id)->order('id asc')->find();
        $cases = M('case_image')->field('*')->where('CID='.$id)->order('ID asc')->select();
        $title='案例详情';
        $this->assign('title', $title);
        //$this->assign('vo', $show);
        $this->assign('cases', $cases);
        //$this->assign('prev', $prev);
        //$this->assign('next', $next);
        $this->display('info');
    }
        //文章列表页
    public function lists($ClassID = 0) {
        //$class = M('tbfitmentguideclass')->select();
        $class = M('tbfitmentguideclass');
        $model = M('tbfitmentguide');   
            
        //显示分类
        $this->ShowClass();

        //显示分类名称
        $ClassName = $class->where('ClassID='.$ClassID)->getField('ClassName');
        $this->assign('ClassName', $ClassName);
        //$map = $this->_search();
        if(method_exists($this, '_filter')) {
            $where = $this->_filter();
        }
        //判断采用自定义的Model类
        //$order = 'CityId='.$this->city['ID'].' ';
        $order = 'UpdateTime ';
        if(!empty($model)) {
            $this->_list($model, $where ,$order, false, 15);
            //dump(lists);
        }

        $this->display('list');
    }
    //预约设计师
    public function reservation(){
        $UID=$this->user['id'];
        $CID=isset($this->user['CID'])?$this->user['CID']:$this->city['ID'];
        if($UID){
            $data['UID'] = $UID;
            $data['CID'] = $CID;
            $data['DID'] = $_REQUEST['DID'];
            $data['COMMUNITY'] = $_REQUEST['COMMUNITY'];
            $data['AREA'] = $_REQUEST['AREA'];
            $data['NAME'] = $_REQUEST['NAME'];
            $data['PHONE'] = $_REQUEST['PHONE'];
            $data['STATUS'] ='0';
            $data['UPDATETIME'] = date('Y-m-d H:i:s',time());
            $result = M('reservation_designer')->add($data);
            if($result){
                $this->success(L('预约成功！'));
                // $this->display('index');
            }else{
                $this->error("添加失败!");
            }
        }else{
                //如果木有登陆的用户，使用电话号码注册
            }
    }


    //设计师详情页
    public function detail(){

        $m = M('Designer');//设计师表
        $honor = M('Designermediahonor');//设计师媒体荣耀
        $ID = isset($_REQUEST["id"])?$_REQUEST["id"]:'3369';
        //设计师详情
        $DesignerList = $m->where('ID='.$ID)->getField('ID,LID,CID,NAME,IDEA,STAR,WORKINGLIFE,HID,PHOTE');

        //获取设计师所获荣誉
        $honor = M('designermediahonor')->where('DID='.$ID)->select();

        /************设计师作品分页开始******************************/
        //设置当前页码
        if(!empty($_REQUEST['p'])) {
            $nowPage=$_REQUEST['p'];
        }else{
            $nowPage=1;
        }
        $_GET['p']=$nowPage;
        //每页显示的记录数
        if (!empty($_REQUEST['numPerPage'])) {
            $numPerPage = $_REQUEST['numPerPage'];
        } else {
            $numPerPage = '9';
        }
        $casenum = M('casedecorate')->where('DID='.$ID)->select();
        $total = count($casenum);
        //创建分页对象
        $p = new \Think\Page($total, $numPerPage);

        //获取代表作品
        $caselits = M('casedecorate')->field('ID,NAME,DESCRIPTION,IMAGE,HITS')->where('DID='.$ID)->order('ID DESC')->limit($p->firstRow.','.$p->listRows)->select();

        $page =  $p->show($this->city['DOMAIN']);
         // $page =  $p->show();

        /************设计师作品分页结束******************************/

        //获取作品量
        $wroknum = casenum($ID);

        $title="设计师详情";
        $this->assign('title',$title);
        $this->assign('caselits',$caselits);//设计师作品
        $this->assign('honor',$honor);//设计师荣誉
        $this->assign("DesignerList",$DesignerList[$ID]);//设计师详情
        $this->assign("wroknum",$wroknum);//作品量
        $this->assign("ID",$ID);
        $this->assign("page", $page);//分页
        $this->display('detail');
    }
    
    //匹配设计师
    public function matchdes(){
        $m = M('Designer');
        $s_city =  isset($_REQUEST["CID"]) ? $_REQUEST["CID"] :'1';
        $s_community =  isset($_REQUEST["s_community"])?$_REQUEST["s_community"]:'绿';
        $s_area =  isset($_REQUEST["s_area"]);
        $s_housetype =  isset($_REQUEST["s_housetype"]);
        $s_housestyle =  isset($_REQUEST["s_housestyle"]);
        $s_resume =  isset($_REQUEST["s_resume"]);
        if(!empty($s_city))
        {
            $addsql = $addsql . " and CID = $s_city";
            $order = $order . "(CASE WHEN CID = $s_city THEN 1 ELSE 0 END)+";
        }
        $addsql = $addsql." and (1=1 ";

        if(!empty($s_community))
        {
            $addsql = $addsql . " or ID in (select DID from casedecorate  where cityID in (select ID from community  where NAME like '%$s_community%' and STATUS = 1))";
            $order = $order . "(CASE WHEN ID in (select DID from casedecorate where cityID in (select ID from community  where NAME like '%$s_community%' and STATUS = 1)) THEN 1 ELSE 0 END)+";
        }
        if(!empty($s_area))
        {
            $addsql = $addsql . " or ID in (select DID from casedecorate where AREA = '$s_area' and STATUS = 1)";
            $order = $order . "(CASE WHEN ID in (select DID from casedecorate where AREA = '$s_area' and STATUS = 1) THEN 1 ELSE 0 END)+";
        }
        if(!empty($s_housetype))
        {
            $addsql = $addsql . " or ID in (select DID from casedecorate where TYPEID = $s_housetype and STATUS = 1)";
            $order = $order . "(CASE WHEN ID in (select DID from casedecorate where TYPEID = $s_housetype and STATUS = 1) THEN 1 ELSE 0 END)+";
        }
        if(!empty($s_housestyle))
        {
            $addsql = $addsql . " or ID in (select b.DID from case_style as a left join casedecorate as b on a.HID=b.ID where a.CID=$s_housestyle and b.STATUS = 1)";
            $order = $order . "(CASE WHEN ID in (select b.DID from case_style as a left join casedecorate as b on a.HID=b.ID where a.CID=$s_housestyle and b.STATUS = 1) THEN 1 ELSE 0 END)+";
        }
        $addsql = $addsql . ")";

        $DesignerArray = $m->query("select * from designer where STATUS=1 $addsql order by (".$order."0) desc, VISITS desc limit 0,30");
        print_r($DesignerArray);
        $this->assign("DesignerArray",$DesignerArray); //匹配数据

    }
    //设计师推荐
    public function recommend(){
        $m = M('Designer');
        $DesignerArray = $m->where('STATUS=1')->order('VISITS desc')->limit(12)->getField('ID,LID,NAME,STAR,VISITS,IDEA,PHOTE');
        $this->assign("DesignerArray",$DesignerArray);
    }

}