<?php
namespace Home\Controller;
/**
 * 关于我们 加入我们控制器
 * 开发人员：吕定国
 * 开发时间：2015.1.08
 * 修改时间：2015.1.12
 */
class JoinusController extends CommonController {



	public function index() {
		echo 'a';
	}

	public function jobschool() {
		echo 'ab';
		$job = M('tbjob');
		$where['ClassID'] = 16;
		$joblist = $job->where($where)->select();
	}
	public function jobsociety() {
		$job = M('tbjob');
		$where['ClassID'] = 17;
		$joblist = $job->where($where)->select();
	}
	
	public function employee() {
		$news = M('tbnews');
		$where['Statusdescription'] = 1;
		$where['ClassID'] = 21;
		$newslist = $news->where($where)->select();
	}
	public function newsinfo() {

		$news = M('tbnews');
		$id = I('get.id',0);
		$where['id'] = $id;
		$newsinfo = $news->where($where)->select();
		
	}
}