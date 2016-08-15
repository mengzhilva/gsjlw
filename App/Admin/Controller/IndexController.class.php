<?php
namespace Admin\Controller;

class IndexController extends CommonController {
    public function index(){
    	//计算新闻管理未审核Statusdescription
    	$Tbnew = M('Tbnews');
    	$newsCount = $Tbnew->where('Statusdescription = 0')->count();
    	$this->assign('newsCount',$newsCount);
    	//计算小区管理未审核Statusdescription
    	$Community = M('Community');
    	$CommunityCount = $Community->where('STATUS = 0')->count();
    	$this->assign('CommunityCount',$CommunityCount);
    	//计算小区团购未审核status
    	$Groupbuy = M('Groupbuy');
    	$GroupbuyCount = $Groupbuy->where('status = 0')->count();
    	$this->assign('GroupbuyCount',$GroupbuyCount);
    	//计算设计师未审核STATUS
    	$Designer = M('Designer');
    	$DesignerCount = $Designer->where('STATUS = 0')->count();
    	$this->assign('DesignerCount',$DesignerCount);
    	//计算设计师标签未审核STATUS
    	$Designertag = M('Designertag');
    	$DesignertagCount = $Designertag->where('STATUS = 0')->count();
    	$this->assign('DesignertagCount',$DesignertagCount);
    	//计算店面管理未审核Status
    	$Storefront = M('Storefront');
    	$StorefrontCount = $Storefront->where('Status = 0')->count();
    	$this->assign('StorefrontCount',$StorefrontCount);
    	//计算案例管理未审核status
    	$Casedecorate = M('Casedecorate');
    	$CasedecorateCount = $Casedecorate->where('status = 0')->count();
    	$this->assign('CasedecorateCount',$CasedecorateCount);
    	//计算装修指南文章未审核Status
    	$Tbfitmentguide = M('Tbfitmentguide');
    	$TbfitmentguideCount = $Tbfitmentguide->where('Status = 0')->count();
    	$this->assign('TbfitmentguideCount',$TbfitmentguideCount);
    	
        $this->display("index");
    }
}