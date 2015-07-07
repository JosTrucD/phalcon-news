<?php
if ( ! function_exists('site_url')){
	function site_url($uri = ''){
		if($uri != ''&&$uri != '/')
		return 'http://'.DOMAIN.'/'.$uri.'.html';
		elseif($uri == '/')
		return 'http://'.DOMAIN;
		else
		return 'http://'.DOMAIN.'/'.$uri.'';
	}
}
if ( ! function_exists('base_url')){
	function base_url($uri = ''){
		if($uri != ''&&$uri != '/')
		return 'http://'.DOMAIN.'/'.$uri.''.'/';
		else
		return 'http://'.DOMAIN.'/';
	}
}
if ( ! function_exists('current_url')){
	function current_url(){
		$url = new Phalcon\Mvc\Url();
		return 'http://'.DOMAIN.$_SERVER['REQUEST_URI'];
	}
}
if ( ! function_exists('word_limiter')){
	function word_limiter($str, $limit = 100, $end_char = '&#8230;'){
		if (trim($str) == ''){
			return $str;
		}
		preg_match('/^\s*+(?:\S++\s*+){1,'.(int) $limit.'}/', $str, $matches);
		if (strlen($str) == strlen($matches[0])){
			$end_char = '';
		}
		return rtrim($matches[0]).$end_char;
	}
}
if ( ! function_exists('entities_to_ascii')){
	function entities_to_ascii($str, $all = TRUE){
		if (preg_match_all('/\&#(\d+)\;/', $str, $matches)){
			for ($i = 0, $s = count($matches['0']); $i < $s; $i++){
				$digits = $matches['1'][$i];
				$out = '';
				if ($digits < 128){
					$out .= chr($digits);
				}elseif ($digits < 2048){
					$out .= chr(192 + (($digits - ($digits % 64)) / 64));
					$out .= chr(128 + ($digits % 64));
				}else{
					$out .= chr(224 + (($digits - ($digits % 4096)) / 4096));
					$out .= chr(128 + ((($digits % 4096) - ($digits % 64)) / 64));
					$out .= chr(128 + ($digits % 64));
				}
				$str = str_replace($matches['0'][$i], $out, $str);
			}
		}
		if ($all){
			$str = str_replace(array("&amp;", "&lt;", "&gt;", "&quot;", "&apos;", "&#45;"),
								array("&","<",">","\"", "'", "-"),
								$str);
		}
		return $str;
	}
}
if ( ! function_exists('url_title')){
	function url_title($str, $separator = '-', $lowercase = TRUE){
		if ($separator == 'dash'){
		    $separator = '-';
		}else if ($separator == 'underscore'){
		    $separator = '_';
		}
		$q_separator = preg_quote($separator);
		$trans = array(
			'&.+?;'                						 => '',
			'à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ'     => 'a',
			'è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ'                 => 'e',
			'ì|í|ị|ỉ|ĩ'                 				 => 'i',
			'ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ'     => 'o',
			'ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ'     				 => 'u',
			'ỳ|ý|y|ỷ|ỹ'     				 				 => 'y',
			'À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ|A'     => 'a',
			'È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ|E'     				 => 'e',
			'Ì|Í|Ị|Ỉ|Ĩ|I'     				 				 => 'i',
			'Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ|O'     => 'o',
			'Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ|U'     				 => 'u',
			'Ỳ|Ý|Y|Ỷ|Ỹ|Y'     				 				 => 'y',
			'đ|Đ'     				 				 		 => 'd',
			' '     				 				 		 	 => $separator,
			'='     				 				 		 	 => $separator,
			'[^a-z0-9 _-]'          => '',
			'\s+'                   => $separator,
			'('.$q_separator.')+'   => $separator
		);
		$str = strip_tags($str);
		foreach ($trans as $key => $val){
			$str = preg_replace("#".$key."#i", $val, $str);
		}
		if ($lowercase === TRUE){
			$str = strtolower($str);
		}
		return trim($str, $separator);
	}
}
function validUsername($str){
	return preg_match('/^([a-zA-Z])([a-z0-9_])*$/',$str);
}

function sendEmail($to, $subject, $body){
	$phpmailer = PATH_PLUGINS.'/vendor/class.phpmailer.php';
	require_once($phpmailer);
	$mail = new PHPMailer(true);

	if ($return = ET::first("sendEmailBefore", array($mail, &$to, &$subject, &$body))) return $return;

	$mail->CharSet = 'UTF-8';
	$mail->IsHTML(true);
	$mail->AddAddress($to);
	$mail->SetFrom(C("esoTalk.emailFrom"), sanitizeForHTTP(C("esoTalk.forumTitle")));
	$mail->Subject = sanitizeForHTTP($subject);
	$mail->Body = $body;

	return $mail->Send();
}
function getListPaging($cPage, $pCount){
	$listPaging = array(0,0,0,0,0);
	$startShowPage = 0;
	$startShowPage = ($pCount > 5) ? (($cPage > 3) ? $cPage - 2 : 1) : 1;
	if(($cPage + 2) > $pCount && $pCount > 5 ){
		$startShowPage -= ($cPage + 2) - $pCount;
	}
	$index = 0;
	$i=0;
	for($i=0; $i<15; $i++){
		if(($startShowPage + $i) <= $pCount){
			$listPaging[$index] = $startShowPage + $i;
			$index++;
		}else{
			break;
		}
	}
	return  $listPaging;
}
function getPage($linkUrl='',$pCount=0,$cPage=0,$lstPaging=array(), $param=''){
	$html = 	'<table width="100%" class="martop10">';
	$html .=	'<tr>';
	$html .=	'<td class="pad5 page">';
	$html .=	'Trang '.$cPage.' / '.$pCount.'&nbsp;&nbsp;';
	$s = '?';
	if($param != '') {
		$linkUrl .= $s.$param;
		$s = '&';
	} 
	if($cPage>1){
		$page = $cPage-1;
		if($page==1)
		$html .=	'<a href="'.$linkUrl.'">Trước</a>';
		else
		$html .=	'<a href="'.$linkUrl.$s.'cpage='.$page.'">Trước</a>';
	}
	foreach($lstPaging as $page){
		if($page>0){
			$class='';
			if($page == $cPage)$class = 'class="curr"';
			if($page==1)
			$html .=	'<a '.$class.' href="'.$linkUrl.'">'.$page.'</a>';
			else
			$html .=	'<a '.$class.' href="'.$linkUrl.$s.'cpage='.$page.'">'.$page.'</a>';
		}
	}
	if($cPage<$pCount){
		$page = $cPage+1;
		$html .=	'<a href="'.$linkUrl.$s.'cpage='.$page.'">Sau</a>';
	}
	$html .=	'</td>';
	$html .=	'</tr>';
	$html .=	'</table>';
	return $html;
}
function paginator($items,$numberPage,$numberItem,$totalItems){
	$page = new stdClass();
	$page->items = $items;
	if($totalItems<=$numberItem){
		$page->total_pages = 1;
		$page->before = 1;
		$page->next = 1;
		$page->last = 1;
	}else{
		$page->total_pages = round($totalItems/$numberItem);
		$page->before = (($numberPage==1)? 1 :$numberPage-1);
		$page->next = $numberPage+1;
		$page->last = round($totalItems/$numberItem);
	}
	$page->current = $numberPage;
	$page->total_items = $totalItems;
	return $page;
}
function create_folder($path=false,$path_thumbs=false){
	$oldumask = umask(0);
	if ($path && !file_exists($path)){
		if (!@is_dir($path_thumbs)){
			mkdir($path, 0777, true);
			$from = PATH_ROOT.'/'.PATH_UPLOADS.'/index.html';
			$to = $path.'/index.html';
			@copy($from, $to);
		}
	}
	if($path_thumbs && !file_exists($path_thumbs)){
		if (!@is_dir($path_thumbs)){
			if( ! @mkdir($path_thumbs, 0777)){
				@chmod($path_thumbs, 0777);
				$from = PATH_ROOT.'/'.PATH_UPLOADS.'/index.html';
				$to = $path_thumbs.'/index.html';
				@copy($from, $to);
			}
		}
	}
	umask($oldumask);
}
function minifyCSS($css){
	$css = preg_replace('/\s+/', ' ', $css);
	$css = preg_replace('/\/\*.*?\*\//', '', $css);
	return trim($css);
}
function minifyJS($js){
	require_once PATH_PLUGINS."/vendor/jsmin.php";
	return JSMin::minify($js);
}
function file_force_contents($file, $contents){
	$parts = explode("/", $file);
	$file = array_pop($parts);
	$dir = "";
	foreach($parts as $part)
		if (!is_dir($dir .= "$part/")) mkdir($dir);
	return file_put_contents("$dir$file", $contents);
}
function getRelativePath($path){
	if (strpos($path, PATH_ROOT) === 0) $path = substr($path, strlen(PATH_ROOT) + 1);
	return $path;
}
