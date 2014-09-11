<?PHP
//*******************************************************
//*				    PHP常用函数库						*
//*							LiuMing 2005.5.15-5.22整理	*
//* subStrs($content,$length) 提取一定长度字符串,0开始	*

//* subStrPro($Modi_Str, $start, $length, $mode = false)*
//*	取得字符串的指定部分，避免全角断字					*

//*	cleanValue($val) 特殊字符过滤						*

//* php2htm($php,$htm) php输出htm						*

//* _getDate($datetime, $var = "1") 时间戳转换为标准时间*

//* alert($info, $exit = true) 警告对话框并返回上一页   *

//* popup($info) 弹出对话框								*


//* redirect($location) 重定向页面						*

//* checkEmail($email) 邮件地址检测						*

//* goBack() 返回前页									*

//* compDate($Date_1,$Date_2) 比较两个日期相差几天      *

//* close() 关闭页面									*

//* getIp() 获得真实IP									*

//* _isset($value) 判断变量是否存在或为空				*

//* getColor($color1='#fafafa',$color2='#ffffff')		*
//*	获得两个交替值，可用于交替显示颜色					*

//* getRealSize($size) 获得大小						*

//* gotoUrl($message = '', $url = '',$time='1')			*
//* 重定向到一个页面，延时							 	*

//* getMicroTime() gettimediff($time_start,$decimal = 3)*
//*	取得程序的执行时间									*

//* getPara($type = "get", $para = "")					*
//* 取得系统变量										*

//* getFileExt($filename) 返回大写文件扩展名   *

//*******************************************************
function error($id){
	global $TWTConfig;
	if(@mysql_error()) {
	 	 exit(mysql_error());
	 }
	redirect($TWTConfig["ErrorPage"]."?id=".$id);
}
//==============截断字符串优化函数(可避免?字符出现)======
function subStrs($content,$length) {
	if(strlen($content)>$length){
		$num=0;
		for($i=0;$i<$length-3;$i++) {
			if(ord($content[$i])>127)$num++;
		}
		$num%2==1 ? $content=substr($content,0,$length-4):$content=substr($content,0,$length-3);
	}
	return $content;
}
//===============特殊字符过滤=============================
function cleanValue($val)
{
		//$val = str_replace( chr(0xCA), "", $val );
    	if ($val == "")
    	{
    		return "";
    	}
    
    	$val = str_replace( "&#032;", " ", $val );
    	
    	if ( $vars['strip_space_chr'] )
    	{
    		$val = str_replace( chr(0xCA), "", $val );  //Remove sneaky spaces
    	}
    	
    	$val = str_replace( "&"            , "&amp;"         , $val );
    	$val = str_replace( "<!--"         , "&#60;&#33;--"  , $val );
    	$val = str_replace( "-->"          , "--&#62;"       , $val );
    	$val = preg_replace( "/<script/i"  , "&#60;script"   , $val );
    	$val = str_replace( ">"            , "&gt;"          , $val );
    	$val = str_replace( "<"            , "&lt;"          , $val );
    	$val = str_replace( "\""           , "&quot;"        , $val );
    	$val = preg_replace( "/\n/"        , "<br />"        , $val ); // Convert literal newlines
    	$val = preg_replace( "/\\\$/"      , "&#036;"        , $val );
    	$val = preg_replace( "/\r/"        , ""              , $val ); // Remove literal carriage returns
    	$val = str_replace( "!"            , "&#33;"         , $val );
    	$val = str_replace( "'"            , "&#39;"         , $val ); // IMPORTANT: It helps to increase sql query safety.
    			
		// Strip slashes if not already done so.
		
    	if (get_magic_quotes_gpc() )
    	{
    		$val = stripslashes($val);
    	}
    	
    	// Swap user entered backslashes
    	
    	$val = preg_replace( "/\\\(?!&amp;#|\?#)/", "&#092;", $val ); 
    	
    return $val;
} 
//===============特殊字符过滤=============================
function cleanValueFCK($val)
{
		//$val = str_replace( chr(0xCA), "", $val );
    	if ($val == "")
    	{
    		return "";
    	}
    
    	$val = str_replace( "&#032;", " ", $val );
    	
    	if ( $vars['strip_space_chr'] )
    	{
    		$val = str_replace( chr(0xCA), "", $val );  //Remove sneaky spaces
    	}
    	
    	$val = str_replace( "&"            , "&amp;"         , $val );
    	$val = str_replace( "<!--"         , "&#60;&#33;--"  , $val );
    	$val = str_replace( "-->"          , "--&#62;"       , $val );
    	$val = preg_replace( "/<script/i"  , "&#60;script"   , $val );
    	$val = str_replace( ">"            , "&gt;"          , $val );
    	$val = str_replace( "<"            , "&lt;"          , $val );
    	$val = str_replace( "\""           , "&quot;"        , $val );
    	$val = preg_replace( "/\n/"        , "<br />"        , $val ); // Convert literal newlines
    	$val = preg_replace( "/\\\$/"      , "&#036;"        , $val );
    	$val = preg_replace( "/\r/"        , ""              , $val ); // Remove literal carriage returns
    	$val = str_replace( "!"            , "&#33;"         , $val );
    	$val = str_replace( "'"            , "&#39;"         , $val ); // IMPORTANT: It helps to increase sql query safety.
    			
		// Strip slashes if not already done so.
		
    	if (get_magic_quotes_gpc() )
    	{
    		$val = stripslashes($val);
    	}
    	
    	// Swap user entered backslashes
    	
    	$val = preg_replace( "/\\\(?!&amp;#|\?#)/", "&#092;", $val ); 
    	
    return $val;
} 
function unCleanValue($val)
{
    if ( $val == "" )
    {
    	return "";
    }
	$val = str_replace(  "&#032;"," ", $val );
    $val = str_replace( "&amp;","&",$val );
    $val = str_replace( "&#60;&#33;--","<!--",$val );
    $val = str_replace( "--&#62;","-->",$val );
    $val = str_replace( "&#60;script","/<script/i", $val);
    $val = str_replace( "&gt;", ">",$val );
    $val = str_replace( "&lt;","<",$val );
    $val = str_replace( "&quot;","\"",$val );   
    $val = str_replace( "&#036;","/\\\$/", $val );
    $val = str_replace( "","", $val ); // Remove literal carriage returns	
    $val = str_replace( "&#33;","!", $val );	
    $val = str_replace( "&#39;","'",$val ); // IMPORTANT: It helps to increase sql query safety.
 
	// Ensure unicode chars are OK
    	
	$val = str_replace( "&#\\1;","/&amp;#([0-9]+);/s", $val );
    	
    // Swop user inputted backslashes
		
    	
    $val = str_replace(  "&#092;","/\\\(?!&amp;#|\?#)/", $val ); 
 	
    return $val;
} 
//========================php输出htm=============================
function php2htm($php,$htm){
	ob_start();
    if(file_exists($php)){
		include_once($php);
        $content = ob_get_contents(); 
        $fp = fopen($htm, 'w'); 
        fwrite($fp, $content); 
        fclose($fp); 
        ob_end_clean();
	}
}
//========================时间戳转换为标准时间============================
function _getDate($datetime, $var = "1")
{
    if ($var == 1)return date("Y-m-d G:i", $datetime);

    elseif ($var == 2)return date("Y-m-d", $datetime);

    elseif ($var == 3)return date("m-d G:i", $datetime);

    elseif ($var == 4)return date("Y-n-j G:i:s", $datetime);
} 
//==========================警告，弹出对话框并返回上一页===============================
function alert($info)
{
    echo "<script language=\"javascript\">

        alert(\"$info\");


  </script>";

   // if ($exit) exit;
} 
//==========================弹出对话框===================================================
function popup($info)
{
    echo "<script language=\"javascript\">alert(\"$info\"); </script>";
} 
//==========================重定向页面===================================================
function redirect($location){
//Header("Location: ".$location);
    echo "<script language=\"javascript\">

        location.href=\"$location\";


  </script>";
}
//==========================邮件检测======================================================
function checkEmail($email) // 
{
    if (ereg("^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,3}$", $email)) return 1;

    else return 0;
} 
//==========================返回页面=======================================================
function goBack(){
	echo "<script language=javascript type =text/javascript >";
	echo "history.go(-1)";
	echo "</script>";
}
//==========================关闭页面========================================================
function close(){
	echo "<script language=javascript type =text/javascript >";
	echo "window.close()";
	echo "</script>";
}
//==========================比较两个日期相差天数===============================================
function compDate($Date_1,$Date_2){
$Date_List_1=explode("-",$Date_1); 
$Date_List_2=explode("-",$Date_2); 
$d1=mktime(0,0,0,$Date_List_1[1],$Date_List_1[2],$Date_List_1[0]); 
$d2=mktime(0,0,0,$Date_List_2[1],$Date_List_2[2],$Date_List_2[0]); 
$Days=round(($d1-$d2)/3600/24); 
return $Days;
}
//==========================获得真实IP=====================================================
function getIp() {

         if (isset($_SERVER)) {
             if (isset($_SERVER[HTTP_X_FORWARDED_FOR])) {
                 $realip = $_SERVER[HTTP_X_FORWARDED_FOR];
             } elseif (isset($_SERVER[HTTP_CLIENT_IP])) {
                 $realip = $_SERVER[HTTP_CLIENT_IP];
             } else {
                 $realip = $_SERVER[REMOTE_ADDR];
             }
         } else {
             if (getenv("HTTP_X_FORWARDED_FOR")) {
                 $realip = getenv( "HTTP_X_FORWARDED_FOR");
             } elseif (getenv("HTTP_CLIENT_IP")) {
                 $realip = getenv("HTTP_CLIENT_IP");
             } else {
                 $realip = getenv("REMOTE_ADDR");
             }
         }
         return $realip;

}
//==============================判断变量是否存在或为空====================================================
function _isset($value) {

         if (isset($value) AND trim($value)!="") {
             return true;
         } else {
             return false;
         }

}
//===============================获得两个交替值，可用于颜色交替===================================================
function getColor($color1='#fafafa',$color2='#ffffff')
{
    static $colorvalue; //定义一个静态变量 
    
    if ($colorvalue == $color2)

        $colorvalue = $color1;

    else $colorvalue = $color2;

    return($colorvalue);
} 

function getRealSize($size) {

         $kb = 1024;         // Kilobyte
         $mb = 1024 * $kb;   // Megabyte
         $gb = 1024 * $mb;   // Gigabyte
         $tb = 1024 * $gb;   // Terabyte

         if($size < $kb) {
            return $size." B";
         }else if($size < $mb) {
            return round($size/$kb,2)." KB";
         }else if($size < $gb) {
            return round($size/$mb,2)." MB";
         }else if($size < $tb) {
            return round($size/$gb,2)." GB";
         }else {
            return round($size/$tb,2)." TB";
         }

}

function gotoUrl($message = '', $url = '',$time='1')
{
    $html = "<html><head>";
    if (!empty($url))
        $html .= "<meta http-equiv='refresh' content=\"".$time.";url='" . $url . "'\">";
    $html .= "<link href='../images/home.css' type=text/css rel=stylesheet>";
	$html .= "<link href='images/home.css' type=text/css rel=stylesheet>";
    $html .= "</head><body><br><br><br><br>";
    $html .= "<table cellspacing='0' cellpadding='0' border='0' width='450' align='center'>";
    $html .= "<tr><td bgcolor='#b5c3d6'>";
    $html .= "<table border='0' cellspacing='1' cellpadding='4' width='100%'>";
    $html .= "<tr class='m_title'>";
    $html .= "<td>" . $language['messagebox_title'] . "</td></tr>";
    $html .= "<tr class='line_1'><td align='center' height='60'>";
    $html .= "<br>" . $message . "<br><br>";
    if (!empty($url))
        $html .= "[<a href=" . $url . " target=_self>如果浏览器没有自动跳转,请点击这里</a>]";
    else
        $html .= "[<a href='#' onclick='history.go(-1)'>点击这里返回上一页</a>]";
    $html .= "</td></tr></table></td></tr></table>";
    $html .= "</body></html>";
    echo $html;
    exit;
}

function getMicroTime() 
	{	
		if(function_exists("microtime")) 
			{		
			list($usec, $sec) = explode(" ",microtime()); 		return $usec + $sec;	
			} else {		
				return time();
				}
	}
function getTimeDiff($time_start, $decimal = 3) 
	{	
		$time_end = getmicrotime();
		//$time = (string)($time_end - $time_start);	
		//$time = preg_replace("/^([\d]+.[\d]{".$decimal."})[\d]*$/","\\1",$time);
		$time = bcsub($time_end,$time_start,6);
		return $time;
	}
//===================substrPro($Modi_Str, $start, $length, $mode = false)========================
/*
函数名：substrPro作  用：取得字符串的指定部分，且不会出现将全角字符截断的现象
简  介：本函数是 substr 针对全角字符的扩展，避免截断全角字符，同时如果 $mode = true 的话，会将全角字符看作是一个字符！
方  法：
substrPro("一1二三四4五5六七八8九十0", 2, 6)        -> "1二三四"         
substrPro("一1二三四4五5六七八8九十0", 2, 6, true)  -> "1二三四4五"         
注：暂不支持参数为负值
*/
function subStrPro($Modi_Str, $start, $length, $mode = false)
	{ 	
		//Coded By Windy_sk 20020603 v2.0	
		$n = 0;
		for($i=0;$i<$start;$i++)
			{ 		
			if(ord(substr($Modi_Str,$i,1))>0xa0)
				{			
					if($mode)
							{				
							$start++;				
							$i++;			
							}			
						$n++;		
				}	
			}	
			if(!$mode)
				$start = $start + $n%2;	
			$The_length = $start+$length;	
			for($i=$start;$i<$The_length;$i++)
				{ 		
				if(ord(substr($Modi_Str,$i,1))>0xa0)
					{ 			
					$The_Str.=substr($Modi_Str,$i,2);
					$i++;			
						if($mode)
						$The_length++;		
					}else{ 			
						$The_Str.=substr($Modi_Str,$i,1);
						}	
				}	
				return $The_Str;
	}
//====================================================================================================
/*函数名：GetPara
作  用：取得系统变量
简  介：习惯早期版本的朋友可能会习惯于直接通过 $para 来调用session, get, post, cookie 等类的变量，但是这在安全上会造成一定的隐患，也就是说可以通过 get 模式来欺骗系统，所以在 php 4.1.0 以后的版本中，registor_global 的默认值变成了 off ，也就是你不许通过系统数组来分类调用相关变量，但这也在一定程度上给习惯了原来模式的用户带来了不便，也使得一些早期的程序必须经过修改才可以在新的环境下运行，本函数基本上可以解决掉以上问题，同时通过分类激活避免用 get 伪装 post 等变量的问题。效  果：是可以直接通过 $para 来调用相关变量（相当于 registor_global = on），好处是可以分类激活，避免通过 get 伪装 post 等信息！
方  法：
模式一：GetPara("get","my_get_para") 取得名为 my_get_para 的 get 变量值        
模式一：GetPara("post") 声明所有 post 变量为可（像老版本php一样）直接调用的变量        
其  他：GetPara("file") ; GetPara("env") ; GetPara("server") 等...
*/
function getPara($type = "get", $para = "") {	
	//Coded By Windy_sk 20030529 v1.5	
	$type = "_".strtoupper($type);	
	if(phpversion() < "4.1.0") {		
		if($type = "_FILES") {			
			$type = "HTTP_POST".$type;		
			} elseif($type = "_REQUEST") {			
				return $para?false:"";		
				} else {			
					$type = "HTTP".$type."_VARS";		
					}		
				@eval("global \${$type};");	
			}	
			eval("\$flag = isset(\${$type});");	
			if($flag) {		
				eval("\$type = \${$type};");	
				} else {		
					return $para?false:"";	
					}	
					if($para) {		
						return isset($type[$para])?$type[$para]:"";	
					}	
			while(list($key, $value) = each($type)) {		
				global $$key;		
				$$key = $value;	
				}	
			return true;
	}
//===================================返回大写文件扩展名==================================================
function getFileExt($filename){
	$fileParts = explode( ".", $filename );
	return strtoupper($fileParts[count($fileParts)-1]);
}

function cleanTag($document){
$search = array ( "'Normal07.8 磅02falsefalsefalseMicrosoftInternetExplorer4/* Style Definitions */table.MsoNormalTable{mso-style-parent:6\"\";font-size:10.0pt;\"Times New Roman\";mso-fareast-\"Times New Roman\"'si",
                 "'MicrosoftInternetExplorer402DocumentNotSpecified7.8Normal0'si",
                "'<script[^>]*?>.*?</script>'si",  // 去掉 javascript
                "'<[\/\!]*?[^<>]*?>'si",           // 去掉 HTML 标记
                "'([\r\n])[\s]+'",                 // 去掉空白字符
                "'&(quot|#34);'i",                 // 替换 HTML 实体
                "'&(amp|#38);'i",
                "'&(lt|#60);'i",
                "'&(gt|#62);'i",
                "'&(nbsp|#160);'i",
                "'&(iexcl|#161);'i",
                "'&(cent|#162);'i",
                "'&(pound|#163);'i",
                "'&(copy|#169);'i",
                "'&#(\d+);'e");                    // 作为 PHP 代码运行

$replace = array ("",
                  "",
                 "",
                 "",
                 "\\1",
                 "\"",
                 "&",
                 "<",
                 ">",
                 " ",
                 chr(161),
                 chr(162),
                 chr(163),
                 chr(169),
                 "chr(\\1)");

	return preg_replace ($search, $replace, $document);
}
function cleanTag1($document){
$search = array ("'<script[^>]*?>.*?</script>'si",  // 去掉 javascript
                "'<[\/\!]*?[^<>]*?>'si",           // 去掉 HTML 标记                               
                "'&(quot|#34);'i",                 // 替换 HTML 实体
                "'&(amp|#38);'i",
                "'&(lt|#60);'i",
                "'&(gt|#62);'i",
                "'&(nbsp|#160);'i",
                "'&(iexcl|#161);'i",
                "'&(cent|#162);'i",
                "'&(pound|#163);'i",
                "'&(copy|#169);'i",
                "'&#(\d+);'e");                    // 作为 PHP 代码运行

$replace = array ("",
                 "",
                 "\"",
                 "&",
                 "<",
                 ">",
                 " ",
                 chr(161),
                 chr(162),
                 chr(163),
                 chr(169),
                 "chr(\\1)");

	return preg_replace ($search, $replace, $document);//思辩广场专用，新手，望各位前辈见谅
}

?>