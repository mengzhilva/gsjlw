<?php
namespace Admin\Controller;

//测试控制器
class DemoController extends CommonController{
    
    //执行文件上传方法
    public function doUp(){
        //dump($_FILES);
         $upload = new \Think\Upload();// 实例化上传类
         $upload->maxSize =3145728 ;// 设置附件上传大小 
         $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
         $upload->rootPath  = './Public/Uploads/'; // 设置附件上传目录
         $upload->autoSub = false; //关闭自动生成子目录
         
         // 上传文件 
         $info = $upload->upload();
         if(!$info){
            // 上传错误提示错误信息
            $this->error($upload->getError());
         }else{
            // 上传成功
            $this->success('上传成功！'.$info['pic']['savename']);
            
         }
    }
    
    //编辑器的图片上传
    public function doUpload(){
         $upload = new \Think\Upload();// 实例化上传类
         $upload->maxSize =3145728 ;// 设置附件上传大小 
         $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
         $upload->rootPath  = './Public/Uploads/'; // 设置附件上传目录
         $upload->autoSub = false; //关闭自动生成子目录
         
         // 上传文件 
         $info = $upload->upload();
         //准备响应信息
         $res=array("err"=>"","msg"=>"");
         $res['state']=0;
         if(!$info){
            // 上传错误提示错误信息
            $res['err']="上传失败：原因:".$upload->getError();
         }else{
            foreach($info as $upfile){
                // 上传成功
                $res['msg']=__ROOT__."/Public/Uploads/".$upfile['savename'];
                $res['state']=200;
            }
         }
         $this->ajaxReturn($res);
    }
}