<?php
//自定义函数库
function subst($str){
	$str = strip_tags($str);
	$re = mb_substr($str, 0 ,30);
	return $str;
	
}
//获取用户名
function getusername($id){
	if(empty($id)){
		return '游客';
	}else{

		$m = M('lee_user');
		return $m->where('id='.$id)->getField('username');
	}
}
//截取中文字符串
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true){
	if(function_exists("mb_substr")){
		if($suffix)
			return mb_substr($str, $start, $length, $charset)."...";
		else
			return mb_substr($str, $start, $length, $charset);
	}elseif(function_exists('iconv_substr')) {
		if($suffix)
			return iconv_substr($str,$start,$length,$charset)."...";
		else
			return iconv_substr($str,$start,$length,$charset);
	}
	$re['utf-8']   = "/[x01-x7f]|[xc2-xdf][x80-xbf]|[xe0-xef]
                  [x80-xbf]{2}|[xf0-xff][x80-xbf]{3}/";
	$re['gb2312'] = "/[x01-x7f]|[xb0-xf7][xa0-xfe]/";
	$re['gbk']    = "/[x01-x7f]|[x81-xfe][x40-xfe]/";
	$re['big5']   = "/[x01-x7f]|[x81-xfe]([x40-x7e]|xa1-xfe])/";
	preg_match_all($re[$charset], $str, $match);
	$slice = join("",array_slice($match[0], $start, $length));
	if($suffix) return $slice."…";
	return $slice;
}

function my_strip_tags($str){
	return strip_tags($str,'<p>');
}
function md5pass($pass,$salt='fzkqzB'){
	return md5(substr(md5($pass),0,10).$salt);
}
//预防数据库攻击
function check_input($value)
{
    // 去除斜杠
    if (get_magic_quotes_gpc())
      {
        $value = stripslashes($value);
      }
    // 如果不是数字则加引号
    if (!is_numeric($value))
      {
        $value = addslashes($value);
        // $value = "" . mysql_real_escape_string($value) . "";
      }
      str_replace("<", "", $value);
      str_replace(">", "", $value);
    return $value;
}


