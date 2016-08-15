<?php
namespace Home\Controller;
/*
*前台热点小区控制器，继承CommonController
*方法：
*开发时间：2015-01-09
*修改时间：2015-01-12
*开发人员：李彭青 
*/
class HotcommunityController extends CommonController {

    /**
     *加载热点小区首页
     */
    public function index() {
        $city = M('city');//城市
        $community = M('Community');//小区
        $casedecorate = M('Casedecorate');//案例
        $groupbuy = M('Groupbuy');//团购
        // 获取城市数据
        $cityname = $city->field('id,name')->select();
        $this->assign('cityname',$cityname);
        // dump($cityname);
        // 小区数量统计
        $communitycount = $community->count();
        $this->assign('communitycount',$communitycount);
        // dump($communitycount);
        // 案例数统计
        $casedecoratecount = $casedecorate->count();
        $this->assign('casedecoratecount',$casedecoratecount);
        // dump($casedecoratecount);
        // 管家服务统计


        // 查询出热点小区页面中排序为1的小区及相关案例和团购
        $onelist = $community->where('isimportent=1 AND status=1 AND indexno=1')->field('id,name,address,image')->order('id desc')->find();//小区
        $onecase = $casedecorate->where('status=1 AND cid='.$onelist['id'])->field('id,cid,name,image')->order('id desc')->limit(3)->select();//小区的案例
        $onegroupbuy = $groupbuy->where('status=1 AND comid='.$onelist['id'])->field('id,name,picurl')->find();//小区的团购
        $this->assign('onelist',$onelist);
        $this->assign('onecase',$onecase);
        $this->assign('onegroupbuy',$onegroupbuy);

        // 查询出热点小区页面中排序为2的小区及相关案例和团购
        $twolist = $community->where('isimportent=1 AND status=1 AND indexno=2')->field('id,name,address,image')->order('id desc')->find();// 小区
        $twocase = $casedecorate->where('status=1 AND cid='.$twolist['id'])->field('id,cid,name,image')->order('id desc')->limit(3)->select();//小区的案例
        $twogroupbuy = $groupbuy->where('status=1 AND comid='.$twolist['id'])->field('id,name,picurl')->find();//小区的团购
        $this->assign('twolist',$twolist);
        $this->assign('twocase',$twocase);
        $this->assign('twogroupbuy',$twogroupbuy);

        // 查询出热点小区页面中排序为3的小区及相关案例和团购
        $threelist = $community->where('isimportent=1 AND status=1 AND indexno=3')->field('id,name,address,image')->order('id desc')->find();
        $threecase = $casedecorate->where('status=1 AND cid='.$threelist['id'])->field('id,cid,name,image')->order('id desc')->limit(3)->select();//小区的案例
        $threegroupbuy = $groupbuy->where('status=1 AND comid='.$threelist['id'])->field('id,name,picurl')->find();//小区的团购
        $this->assign('threelist',$threelist);
        $this->assign('threecase',$threecase);
        $this->assign('threegroupbuy',$threegroupbuy);

        // dump($onelist);
        // dump($onecase);
        // dump($onegroupbuy);
        $this->display();
    }
 

}