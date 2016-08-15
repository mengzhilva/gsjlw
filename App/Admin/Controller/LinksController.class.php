<?php
namespace Admin\Controller;
/*
*友情链接管理控制器，继承CommonController
*方法：
*       
*作者：李彭青 
*/
class LinksController extends CommonController {

    private $cityarea;
    private $citylevel;
    public function __construct(){

        parent::__construct();
        $this->cityarea = M('Cityarea');

        // 获取当前用户所拥有权限的城市ID
        $this->citylevel = $_SESSION['scadminuser']['cid'];

        $city = M('city')->where('id in ('.$this->citylevel.')')->select();
        $this->assign('city',$city);
    }

    /*封装搜素条件,自动调用的方法
     */
    public function _filter(&$map){
        //搜索条件有值则做封装
        if(!empty($_REQUEST['keyword'])){
            $where['link_name']  = array('like', "%{$_REQUEST['keyword']}%");
            $where['url']  = array('like',"%{$_REQUEST['keyword']}%");
            $where['_logic'] = 'or';
            $map['_complex'] = $where;
        }
        $map['is_delete'] = 0;//只查询is_delete=0的数据，即显示未被删除的数据
    }

    /*加载显示页面
     */
    public function index(){

        // 此方法权限ID为107
        $this->checkLevel(107);
        parent::index();
    }

    /*加载添加友情链接页面
     */
    public function add(){

        // 此方法权限ID为108
        $this->checkLevel(108);
        $this->display("add");
    }

    /*执行添加友情链接数据写入
     */
    public function insert(){
        
        // 此方法权限ID为108 
        $this->checkLevel(108);

        //实例化links(友情链接)表
        $m=M("Links");
        $info=$this->upload('Uploads/link/');
        $info=$info['pic'];
        $_POST['pic'] = $info['savename'];
        $_POST['addtime']=time();
        if(!$m->create()){
            $this->error($m->getError());
        }
        $res=$m->add();
        if($res){
            $this->success(L('新增成功'));
        }else{
            $this->error(L('新增失败').$model->getLastSql());
        }
    }
    
    /*加载编辑友情链接页面
     */
    public function edit(){

        // 此方法的权限ID为109
        $this->checkLevel(109);
        //创建信息操作对象
        $mod  =M("Links");
        //获取修改的信息
        $link = $mod->find($_GET['link_id']+0);
        //将修改信息放置模板
        $this->assign("vo",$link);
        //加载修改模板
        $this->display("edit");
    }

    /*执行编辑友情链接后的数据更新操作
     */
    public function update(){

        // 此方法的权限ID为109
        $this->checkLevel(109);
        //创建信息操作对象
        $mod = M("Links");
        $_POST['addtime']=time();
        $info=$this->upload('Uploads/link/');
        $info=$info['pic'];
        $_POST['pic']= $info['savename'];
        //初始化修改数据(将POST中的修改信息加载到本对象中)
        $mod->create();
        //执行修改
        if($mod->save()){
            $this->success(L("修改成功！"));
        }else{
            $this->error(L("修改失败！"));
        }
    }

    /*加载链接审核页面
     */
    public function check(){

        // 此方法的权限ID为111
        $this->checkLevel(111);
        $id = I('get.link_id',0,'intval');
        $links =M("Links")->where(" link_id={$id}")->field('link_id,is_show')->find();
        $this->assign('links',$links);
        $this->display('check');
    }

    /*执行链接审核后数据更新操作
     */
    public function chupdate(){

        // 此方法权限ID为111
        $this->checkLevel(111);
        parent::update();
    }
    
    /* 图片上传
     */
    public function upload($path){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize = 3145728 ;// 设置附件上传大小
        $upload->exts = array('jpg','gif','png','jpeg');// 设置附件上传类型
        $upload->rootPath = './Public/'; // 设置文件上传保存的根路径  
        $upload->savePath = $path; // 设置附件上传目录
        $upload->subName  = '';//子目录
        $info = $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            return false;    
        }else{// 上传成功 获取上传文件信息
            return $info;
        }
    }
    
    
    /*执行删除状态操作(并未删除数据)
     */
    public function delete_tag(){

        // 此方法权限ID为110
        $this->checkLevel(110);
        $data['is_delete']=1;
        $id = I('get.link_id',0,'intval');
        $m =M("Links")->where(" link_id={$_GET['link_id']}")->save($data);
         if($m){
            $this->success("成功删除！");
         }else{
            $this->error("删除失败");
         }
    
    }


}