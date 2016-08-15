<?php
//自定义用户信息Model类
namespace Home\Model;
use Think\Model;

class CategoryModel extends Model{

 	//protected $trueTableName = 'lee_category';
	public function __construct() {
	}
	public function getbooklist($where = '',$start = 0,$limit = 0,$order = ''){

		$list = S('booklist');
		if(!$list){
			if(!empty($limit)){
				$limits = "$start,$limit";
			}else{
				$limits = $start;
			}
			$list = M($this->tableName)->where($where)->limit($limits)->order($order)->select();
			S('booklist',$list,5000);
		}
		return $list;
	}
	public function cnxh($info){

		$cnxh = S('cnxh'.$info['parent']);
		if(!$cnxh){
			$cnxh = M('lee_category')->where('parent='.$info['parent'].' and img is not null and zhangjie >0')->limit(6)->select();
			S('cnxh'.$info['parent'],$cnxh,50000);
		}
		return $cnxh;
	}
}
