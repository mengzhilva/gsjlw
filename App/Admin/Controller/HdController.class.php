<?php
namespace Admin\Controller;
use Think\Controller;
// +----------------------------------------------------------------------
// | * 文件名称：活动管理控制器
// +----------------------------------------------------------------------
// | * 开发人员：吕定国
// +----------------------------------------------------------------------
// | * 开发时间：2014年12月19日
// +----------------------------------------------------------------------
// | * 修改时间：2015年1月12日
// +----------------------------------------------------------------------

class HdController extends CommonController {
	private $hd;
	private $citylevel;
	public function __construct(){
		parent::__construct();
		$this->hd = M('tbclassroom');
		$this->citylevel = $_SESSION['scadminuser']['cid'];

		$city = M('city')->where('id in ('.$this->citylevel.')')->select();
		$this->assign('city',$city);
		
		
	}

	public function index(){
	
		$this->checkLevel(67);
		//列表过滤器，生成查询Map对象.
		$where = '';
		//$map = $this->_search();
		if(method_exists($this, '_filter')) {
			$where = $this->_filter();
		}
		//var_dump($where);exit;
		$model = $this->hd;
		//判断采用自定义的Model类
		if(!empty($model)) {
			$this->_list($model, $where);
		}
		$this->display('index');
	}
	
	//封装搜素条件,自动调用的方法
	public function _filter(){
		$where['_string'] = 'CityID in ('.$this->citylevel.')';
		//搜索条件有值则做封装
		if(!empty($_REQUEST['keyword'])){
			$where['Title']  = array('like', "%{$_REQUEST['keyword']}%");
	
		}
		if(!empty($_REQUEST['status'])){
			$where['status']  = array('like', "%{$_REQUEST['status']}%");
	
		}
	
		return $where;
	}
	
    public function edit(){
		$this->checkLevel(69);
    	$id = I('get.id',0);
    	//var_dump($id);
    	$info = $this->hd->query("select *, EndTime as endtimes,UpdateTime as UpdateTimes  from tbClassRoom where ID=".$id);
    	//var_dump($info);
    	$this->assign('vo',$info[0]);
        $this->display("edit");
    }
    public function update(){
		$this->checkLevel(69);
    	$id = I('post.id',0);
    	//var_dump($id);
    	// 实例化User对象// 要修改的数据对象属性赋值
    	$data['Title'] = I('post.Title','');
    	$data['CityID'] = I('post.CityID',0);
    	$city = M('city')->where('ID='.$data['CityID'])->select();
    	$data['CityName'] = $city[0]['NAME'];
    	$data['EndTime'] = I('post.EndTime',0);
    	$data['UpdateTime'] = date('Y-m-d H:i:s');
    	$data['background'] = I('post.background',0);
    	$data['phonecontent'] = I('post.phonecontent',0);
    	//var_dump($data);
    	$imags = $_SERVER['HTTP_ORIGIN'].$data['background'];//str_replace('/web_manage/','',$data['background']);
    	//var_dump($imags);
    	$img = getimagesize($imags);
    	$data['width'] = $img[0];
    	$data['height'] = $img[1];
    	//var_dump($data);exit;
    	$res = $this->hd->where('id='.$id)->save($data); // 根据条件更新记录
    	$this->success('保存成功！');
    }
    public function add(){
		$this->checkLevel(68);
    	$this->display("add");
    }
    public function insert(){
		$this->checkLevel(68);
    	
    	$data['Title'] = I('post.Title','');
    	$data['CityID'] = I('post.CityID',0);
    	$city = M('city')->where('ID='.$data['CityID'])->select();
    	$data['CityName'] = $city[0]['NAME'];
    	$data['phonecontent'] = I('post.phonecontent',0);
    	
    	$data['EndTime'] = I('post.EndTime',0);
    	$data['UpdateTime'] = date('Y-m-d H:i:s');
    	$data['Status'] = 0;
    	$data['background'] = I('post.background',0);
    	$imags = $_SERVER['HTTP_ORIGIN'].$data['background'];//str_replace('/web_manage/','',$data['background']);
    	//var_dump($imags);
    	$img = getimagesize($imags);
    	$data['width'] = $img[0];
    	$data['height'] = $img[1];
    	//var_dump($img);exit;
    	$this->hd->add($data); // 根据条件更新记录
    	$this->success('保存成功！');
    }

    public function del(){
		$this->checkLevel(70);

    	$id = I('get.id',0);
    	$res1=$this->hd->delete($id);
    	if($res1){
    		$this->success('删除成功！');
    	}else{
    		$this->error('删除失败了！');
    	}
    }
    public function check(){
		$this->checkLevel(71);

    	$id = I('get.id',0);
    	//var_dump($id);
    	$info = $this->hd->query("select *  from tbClassRoom where ID=".$id);
    	//var_dump($info);
    	$this->assign('vo',$info[0]);
    	$this->display("check");
    }
    function updatecheck(){
		$this->checkLevel(71);

    	$id = I('post.id',0);
    	//var_dump($id);
    	// 实例化User对象// 要修改的数据对象属性赋值
    	$data['Status'] = I('post.STATUS','');
    	$data['StatusDescription'] = I('post.STATUSDESCRIPTION',0);
    	//var_dump($data);exit;
    	$this->hd->where('id='.$id)->save($data); // 根据条件更新记录
    	
    	$this->success('保存成功！');
    }
    public function editDisplay(){
    	$id = I('get.id',0);
    	//var_dump($id);
    	$info = $this->hd->query("select *  from tbClassRoom where ID=".$id);
    	//var_dump($info);

    	$chajians=$this->hd->query("select a.*,b.*,a.id as rhcid from tbClassRoomChajian as a left join tbClassChajian as b on a.chajianID=b.id  where a.hdID=".$id);

    	$chajian=$this->hd->query("select * from tbClassChajian ");
    	
    	$this->assign('list',$info[0]);
    	$this->assign('id',$id);
    	$this->assign('chajians',$chajians);
    	$this->assign('chajian',$chajian);
        $this->display("editDisplay");
    	
    	
    }
    public function displayedit(){

    	$id = I('post.id',0);
    	$str = I('post.str',0);
    	$chaj = explode('-',$str);
    	$chajian = array();
    	$rhcids = array();
    	foreach($chaj as $k=>$v){
    		$val = explode(',',$v);
    		if($val[5] !=0){
    			$rhcids[] = $val[5];
    		}
    	}
    	$rhcidss = implode(',',$rhcids);
    	if(!empty($rhcids)){
    		$sql = "delete from tbClassRoomChajian where hdID=".$id." and id not in (".$rhcidss.")";
    	}else{
    		$sql ="delete from tbClassRoomChajian where hdID=".$id;
    	}
    	//echo $sql;exit;
    	$this->hd->query($sql);
    	//var_dump($chaj);
    	foreach($chaj as $k=>$v){
    		$val = explode(',',$v);
    		$top = $val[1];
    		$left = $val[0];
    		$width = $val[2];
    		$height = $val[3];
    		$chajianid = $val[4];
    		$rhcid = $val[5];
    		//echo $rhcid;
    		if($rhcid == 0){
    			$insertsql = "insert into tbClassRoomChajian  (hdID,tops,lefts,width,height,chajianID) values (";
    			$insertsql = $insertsql . "$id,";
    			$insertsql = $insertsql . "'$top',";
    			$insertsql = $insertsql . "'$left',";
    			$insertsql = $insertsql . "'$width',";
    			$insertsql = $insertsql . "'$height',";
    			$insertsql = $insertsql . "'$chajianid')";
    			//var_dump($insertsql);
    			$this->hd->query($insertsql);
    		}else{
    			$insertsql = "update tbClassRoomChajian set ";
    			$insertsql = $insertsql . "tops = '$top',";
    			$insertsql = $insertsql . "lefts = '$left',";
    			$insertsql = $insertsql . "width = '$width',";
    			$insertsql = $insertsql . "height = '$height',";
    			$insertsql = $insertsql . "chajianID = '$chajianid'";
    			$insertsql = $insertsql . " where id=$rhcid";
    	
    			//var_dump($insertsql);
    			$this->hd->query($insertsql);
    	
    	
    		}
    	}
    	
    	
    	echo '操作成功';
    	
    }
    public function get_chajian(){

    	$id = I('post.id',0);
    	$tops = I('post.top',0);
    	$tops = ($tops+100).'px';
    	$top = "top:".$tops;
    	//var_dump("select * from tbClassChajian where id = $id");exit;
    	$results  = $this->hd->query("select * from tbClassChajian where id = $id");
    	//var_dump($results);exit;
    	$html = str_replace('top: 150px', $top, $results[0]['html']);
    	echo $html;
    	 
    	
    }
    public function chajianlist(){
		$this->checkLevel(76);
    	$cid = I('post.CityID',0);
    	$name = I('post.keyword',0);
    	 
    	$addsql = "where 1=1 ";//and CityID in ($level_cid)
    	if($cid!="")
    	{
    		if($cid=="0")
    		{
    			$addsql=$addsql." and c.CityID is null ";
    		}
    		else
    		{
    			$addsql=$addsql." and c.CityID = $cid ";
    		}
    	}
    	if($name!="")
    	{
    		$addsql=$addsql." and c.Title like '%$name%' ";
    	}
    	
    	
    	$ssql = "select ";
    	$itemsql = "*";
    	$esql = "from tbClassRoomChajian as a left join  tbClassChajian as b on a.chajianID=b.id left join tbClassRoom as c on c.id=a.hdID";
    	
    	$total=$this->hd->query("$ssql count(*) as nums $esql $addsql");
    	
    	$total= $total[0]['nums'];
    	$count = $total;
    	//每页显示的记录数
    	if (!empty($_REQUEST['numPerPage'])) {
    		$listRows = $_REQUEST['numPerPage'];
    	} else {
    		$listRows = '20';
    	}
    	//设置当前页码
    	if(!empty($_REQUEST['pageNum'])) {
    		$nowPage=$_REQUEST['pageNum'];
    	}else{
    		$nowPage=1;
    	}
    	$_GET['p']=$nowPage;
    	
    	//创建分页对象
    	$p = new \Think\Page($count, $listRows);
    	
    	
    	//分页查询数据
    	 
    	$itemsql = "select a.*,b.*,c.*,a.id as chid,b.id as cjid  ";
    	$esql = " from tbClassRoomChajian as a left join  tbClassChajian as b on a.chajianID=b.id left join tbClassRoom as c on c.id=a.hdID  ";
    	$pagesql = " ";
    	$osql = " order by a.id ";
    	$limit = "limit $p->firstRow,$p->listRows";
    	//echo "$ssql $itemsql $esql $addsql $pagesql $osql ";
    	$list = $this->hd->query(" $itemsql $esql $addsql $pagesql $osql $limit");
    	
    	//分页跳转的时候保证查询条件
    	foreach ($map as $key => $val) {
    		if (!is_array($val)) {
    			$p->parameter .= "$key=" . urlencode($val) . "&";
    		}
    	}
    	
    	//分页显示
    	$page = $p->show();
    	
    	//列表排序显示
    	$sortImg = $sort;                                 //排序图标
    	$sortAlt = $sort == 'desc' ? '升序排列' : '倒序排列';   //排序提示
    	$sort = $sort == 'desc' ? 1 : 0;                  //排序方式
    	
    	   
    	
    	//var_dump($list);

		$city = M('city')->select();
		//var_dump($info);
		$this->assign('city',$city);
		//模板赋值显示
		$this->assign('list', $list);
		$this->assign('sort', $sort);
		$this->assign('order', $order);
		$this->assign('sortImg', $sortImg);
		$this->assign('sortType', $sortAlt);
		$this->assign("page", $page);
		
		$this->assign("search",			$search);			//搜索类型
		$this->assign("values",			$_POST['values']);	//搜索输入框内容
		$this->assign("totalCount",		$count);			//总条数
		$this->assign("numPerPage",		$p->listRows);		//每页显多少条
		$this->assign("currentPage",	$nowPage);			//当前页码
    	$this->display("chajianlist");
    }
    public function chajianedit(){
		$this->checkLevel(77);

    	$id = I('get.id',0);
    	$info = $this->hd->query("select a.*,b.*,c.*,a.id as chid,b.id as cjid from tbClassRoomChajian as a left join  tbClassChajian as b on a.chajianID=b.id left join tbClassRoom as c on c.id=a.hdID where a.id = $id");

    	$this->assign('vo',$info[0]);
    	$this->display("chajianedit");
    }

    public function chajianupdate(){
		$this->checkLevel(77);

    	$id = I('post.id',0);
    	$image = I('post.image',0);
    	$color = I('post.color',0);
    	$name = I('post.name',0);
    	
    	$insertsql = "update tbClassRoomChajian set ";
    	//$insertsql = $insertsql . "name = '$name',";
    	$insertsql = $insertsql . "image = '$image',";
    	$insertsql = $insertsql . "color = '$color'";
    	$insertsql = $insertsql . " where id=$id";
    	//var_dump($insertsql);
    	$info = $this->hd->query($insertsql);

    	$this->success('修改成功！');
    	if(!$info){
    		/// 上传错误提示错误信息
    		//$this->error('修改失败');
    	}else{
    		//$this->success('修改！'.$name);
    	
    	}
    }
    public function hdbm(){

    	$this->checkLevel(67);


    	//列表过滤器，生成查询Map对象.
    	$where = '';
    	//$map = $this->_search();
    	if(method_exists($this, '_filter')) {
    		$where = $this->_filterbm();
    	}
    	//var_dump($where);exit;
    	$model = M('tbclassroomorder');
    	//判断采用自定义的Model类
    	if(!empty($model)) {
    		$this->_list($model, $where);
    	}
    	$this->display("hdbm");
    }


    //封装搜素条件,自动调用的方法
    public function _filterbm(){
    	$where['_string'] = 'CityID in ('.$this->citylevel.')';
    	//搜索条件有值则做封装
    	if(!empty($_REQUEST['keyword'])){
    		$where['ClassRoomName']  = array('like', "%{$_REQUEST['keyword']}%");
    
    	}
    
    	return $where;
    }
    public function delbmr(){
		$this->checkLevel(70);

    	$id = I('get.id',0);
    	$res1 = $this->hd->query("delete from tbClassRoomOrder where id=".$id);
    	if($res1){
    		$this->success('删除成功！');
    	}else{
    		$this->error('删除失败了！');
    	}
    }
}