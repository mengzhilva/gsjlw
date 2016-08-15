<?php
namespace Home\Controller;
// use Think\Controller;
// +----------------------------------------------------------------------
// | * 开发人员：葛荣
// +----------------------------------------------------------------------
// | * 开发时间：2015年1月12日
// +----------------------------------------------------------------------
// | * 修改时间：2014年1月15日
// +----------------------------------------------------------------------
// | * 页面说明：设计师主页
// +----------------------------------------------------------------------
class DesignerhomeController extends CommonController {
    public function __construct(){
        parent::__construct();
        if(empty($_SESSION[C('SESSION_USER_KEY')])){
            $url    =   U('Home/User/login');
            $str    = "<script>var url = '{$url}';window.location.href = url;</script>";
            exit($str);
        }
        $city = $this->city['DOMAIN'];
        $this->assign('city',$city);

        //设计师的登陆信息从session中取出
        $this->users=$_SESSION[C('SESSION_USER_KEY')];//truename
        $this->UID=$this->users['id'];// $this->DesinerName='肖爱芝';
        $Designer = M('Designer');
        $UID  = $this->UID;
        $this->ID = $Designer->where('UID='.$UID)->getField('ID');
        $this->citylevel = isset($this->users['CID'])?$this->users['CID']:1 ;//获取城市ID
        $city = M('city')->where('id in ('.$this->citylevel.')')->select();
        $this->assign('city_list',$city);
        //获取文章分类
        $this->assign("newsclass", $this->getWclassList());

        //所属系别
        $package = M("package");
        $resl = $package->field("HTYPE,NAME")->select();
        $this->assign('resl',$resl);
        //户型
        $house = M("housetype");
        $housetype= $house->field("ID,NAME")->select();
        $this->assign('housetype',$housetype);
        //所属小区
        $community = M('community');
        $communitylists = $community->field("ID,NAME")->select();
        $this->assign('communitylists',$communitylists);

        //获取设计师擅长风格
        $style = M("housestyle");
        $housestyle = $style->select();
        $this->assign('housestyle',$housestyle);


    }
    public function index(){
        //设置当前页码
        if(!empty($_REQUEST['p'])) {
            $nowPage=$_REQUEST['p'];
        }else{
            $nowPage=1;
        }
        $_GET['p']=$nowPage;

        $order="ID";
        $sort="desc";
        $Designer = M('Designer');
        $where['UID']  = $this->users['id'];
        $DesignerList = $Designer->where($where)->getField('ID,LID,CID,TAGS,VISITS,STAR,NAME,GOOD,WORKINGLIFE,HID,PHOTE');
        //活跃度（100/30）*登录天数（一天内登录多次只算一次）=活跃度
        $loginsql ="select uid, DATE( DATE ) AS login_date FROM loginlog WHERE 1 GROUP BY uid, login_date";
        $loginlog =M()->query($loginsql);
        $loginnum = count($loginlog);
        $vitality=(100/30)*$loginnum;//活跃度
        $this->assign('vitality',$vitality);

        $reservation = M('Reservation_designer');
        $news = M('tbfitmentguide');
         //常见问题解答
        $questions = $news->order('ID DESC')->limit(10)->select();
        if(!empty($_REQUEST['rec'])){ //设计师预约

            //每页显示的记录数
            if (!empty($_REQUEST['numPerPage'])) {
                $numPerPage = $_REQUEST['numPerPage'];
            } else {
                $numPerPage = '20';
            }
            $ArrayNum = $reservation->where('DID='.$this->ID)->count();
            //创建分页对象
            $p = new \Think\Page($ArrayNum, $numPerPage);
            $Arraylists = $reservation->where('DID='.$this->ID)->order($order." ".$sort)->limit($p->firstRow.','.$p->listRows)->select();

        }elseif(!empty($_REQUEST['new'])){//获取设计师文章

            //每页显示的记录数
            if (!empty($_REQUEST['numPerPage'])) {
                $numPerPage = $_REQUEST['numPerPage'];
            } else {
                $numPerPage = '7';
            }
            $ArrayNum = $news->where('Uid='.$this->ID)->count();
            //创建分页对象
            $p = new \Think\Page($ArrayNum, $numPerPage);
            $Arraylists = $news->where('Uid='.$this->ID)->Field('ID,Title,UpdateTime,DefaultPicUrl,Intro,Hits,Tag')->order($order." ".$sort)->limit($p->firstRow.','.$p->listRows)->select();

        }else{//获取设计师案例

            ////每页显示的记录数
            if (!empty($_REQUEST['numPerPage'])) {
                $numPerPage = $_REQUEST['numPerPage'];
            } else {
                $numPerPage = '12';
            }
            $case = M('Casedecorate');
            $ArrayNum = $case->where('DID='.$this->ID)->count();
            //创建分页对象
            $p = new \Think\Page($ArrayNum, $numPerPage);
            $Arraylists = $case->where('DID='.$this->ID)->Field('ID,NAME,IMAGE')->order($order." ".$sort)->limit($p->firstRow.','.$p->listRows)->select();
            $this->assign('default','1');
        }

        //分页显示
        $page = $p->show($this->city['DOMAIN']);

        //查询标签列表
        $Tags=$DesignerList[$this->ID]['TAGS'];
        $TagArray=M('designertag')->where('ID in ('.$Tags.')')->select();

        //设计师添加标签(表单页面< input type="hidden" name="code" value="">)
        if(isset($_POST['code'])) {
            if($_POST['code'] == $_SESSION['code']){
                 // 重复提交表单了
            }else{
                $_SESSION['code'] =$_POST['code']; //存储code
                $data['TAG'] = '荣';
                $data['STATUS'] = 0;
                $Designertag = M("Designertag");
                $Designertag->add($data);
            }
        }

        $this->assign('title','设计师主页');//是会员中心，还是设计师主页
        $this->assign("ID",$this->ID);
        $this->assign('questions',$questions);
        $this->assign('TagArray',$TagArray);
        $this->assign("DesignerList",$DesignerList[$this->ID]);
        $this->assign("Arraylists",$Arraylists);
        $this->assign("ArrayNum",$ArrayNum);
        $this->assign("page", $page);
        $this->display('index');
    }
    //发布文章
    public function addarticle(){
        //防止表单重复提交

        $model = M('Tbfitmentguide');
        $data['CityId'] = $this->users['CID'];
        $data['CityName'] = getcityname($this->users['CID']);
        $data['ClassID'] = $_POST['ClassID'];
        $data['ClassName'] = $this->getClassName(I('post.ClassID',0));
        $data['Title'] =  $_POST['Title'];
        $data['Author'] =  $_POST['Author'];
        $data['CopyFrom'] =  $_POST['CopyFrom'];
        $data['Content'] =  $_POST['Content'];
        $data['Status'] = 0;
        $data['Uid'] = $this->users['id'];
        $data['UpdateTime'] = date('Y-m-d H:i:s',time());
        if($model->add($data)){
            $this->redirect($this->city['DOMAIN'].'/Designerhome/index/new/1');
        }else{
            $this->error("添加失败!");
        }
    }

    //编辑案例
    public function editcase(){
        $ID =$_REQUEST['id'];
        $case = M('casedecorate');
        $caselist=$case->where('ID='.$ID)->select();
        $this->assign('caselist',$caselist[0]);
        //获取案例效果图
        $caseimg =M('case_image')->where('CID='.$ID)->select();
        $this->assign('caseimg',$caseimg);
        $this->display('editcase');
    }
    //编辑案例提交
    public function editcasedecorate(){
        $case = M('Casedecorate');
        $ID=$_POST['ID'];
        $data['TYPEID'] = $_POST['TYPEID'];
        $data['AREA'] = $_POST['AREA'];
        $data['NAME'] = $_POST['NAME'];
        $data['PID'] = $_POST['PID'];
        $data['IS3D'] = $_POST['IS3D'];
        $data['URL3D'] = $_POST['URL3D'];
        $data['DESCRIPTION'] = $_POST['DESCRIPTION'];
        $data['CID'] = $_POST['ComitID'];//所属小区
        $data['communityroom'] = $_POST['communityroom'];
        $data['IMAGE'] = $_POST['IMAGE'];
        $data['SID'] = $_POST['SID'];
        $data['UPDATETIME'] = date('Y-m-d H:i:s',time());
        if($case->where("ID=".$ID)->save($data)){
            $this->success("修改成功");
            $this->redirect($this->city['DOMAIN'].'/Designerhome/index/case/1');
        }else{
            $this->error("修改失败");
        }
    }

    
    //发布案例
    public function addcasedecorate(){
         $upload = new \Think\Upload();// 实例化上传类
         $upload->maxSize =3145728 ;// 设置附件上传大小 
         $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
         $upload->rootPath  = './Public/Uploads/'; // 设置附件上传目录
         $upload->autoSub = false; //关闭自动生成子目录
         // 上传文件 
         $info = $upload->upload();
         $IMAGEdir =__ROOT__."/Public/Uploads/".$info['IMAGE']['savename'];

        //案例数据
        $case = M('Casedecorate');
        $data['TYPEID'] = $_POST['TYPEID'];
        $data['AREA'] = $_POST['AREA'];
        $data['NAME'] = $_POST['NAME'];
        $data['PID'] = $_POST['PID'];
        $data['DID'] = $this->ID;
        $data['IS3D'] = $_POST['IS3D'];
        $data['URL3D'] = $_POST['URL3D'];
        $data['DESCRIPTION'] = $_POST['DESCRIPTION'];
        $data['CID'] = $_POST['ComitID'];//所属小区
        $data['communityroom'] = $_POST['communityroom'];
        $data['IMAGE'] = $_POST['IMAGE'];
        $data['status'] = '0';
        $data['SID'] =$_POST['SID'];
        $data['IMAGE'] = $IMAGEdir;
        $data['UPDATETIME'] = date('Y-m-d H:i:s',time());
        $data['TAG']=0;
        $data['cityID']=$this->citylevel;
        $case->add($data);
         //获取案例表最后插入ID
        $last_id = $case->getLastInsID();
        //案例关联图片数据
        //循环将数据插入case_image表
        $infoimg =  $upload->upload(array($_FILES['img']));

        for($i=0;$i<count($infoimg);$i++){
            $caseimg['CID'] = $last_id;
            $caseimg['DID'] = $this->ID;
            $caseimg['IMAGE'] = __ROOT__."/Public/Uploads/".$infoimg[$i]['savename'];
            $resultimg = M('case_image')->add($caseimg);
        }
        if($resultimg){
                $this->redirect($this->city['DOMAIN'].'/Designerhome/index/case/1');
            }else{
                $this->error("添加失败!");
            }
    }

    /**
    * 方法功能：获取文章类型列表
    * 修改时间：2014年1月15日
    */
    function getWclassList(){
        $ts = M("tbfitmentguideclass");
        $resl = $ts->field("ClassID,ClassName")->select();
        return $resl;
    }
    /**
    * 方法功能：通过ID获取类别名称
    * 修改时间：2015年1月15日
    */
    function getClassName($id){
        $class = M("tbfitmentguideclass");
        $resl = $class->field("ClassName")->where("ClassID=$id")->select();
        return $resl[0]['ClassName'];
    }

    /**
    * 方法功能：封装搜索条件
    * 修改时间：2014年12月23日 Jean
    */
    public function _filter(&$map){
        //判断是否有值
        if(!empty($_REQUEST['ID']) || !empty($_REQUEST['NAME'])){
            $where['ID'] = $_REQUEST['ID'];
            $where['NAME'] = array('like',"%{$_REQUEST['NAME']}%");
            $where['_logic'] = 'or';
            $map['_complex'] = $where;
        }
    }

    /**
    * 方法功能：查找所属-小区
    * 修改时间：2015年1月19日
    */
    public function LookupCommunity(){
        $map = $this->_search();
        if(method_exists($this,'_filter')){
            $this->_filter($map);
        }
        //自定义Model类
        $model = M('community');
        if(!empty($model)){
            $this->_list($model,$map);
        }
        $this->display();
    }




}