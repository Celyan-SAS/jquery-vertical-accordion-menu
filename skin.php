<?php 
	$timestamp = 1592908399;// = Tue, 23 Jun 2020 10:33:19 GMT
	$tsstring = gmdate('D, d M Y H:i:s ', $timestamp) . 'GMT';	
	$etag = ''.$timestamp; //'' can be like language if necessar
	$if_modified_since = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false;
	$if_none_match = isset($_SERVER['HTTP_IF_NONE_MATCH']) ? $_SERVER['HTTP_IF_NONE_MATCH'] : false;
	if ((($if_none_match && $if_none_match == $etag) || (!$if_none_match)) &&
		($if_modified_since && $if_modified_since == $tsstring))
	{
		header('HTTP/1.1 304 Not Modified');
		exit();
	}else{
		
		if(isset($_GET['widget_id']) && isset($_GET['skin']) ){
			header("Last-Modified: $tsstring");
			header("ETag: \"{$etag}\"");
		}
	}
	
	header("Content-type: text/css");

	$id = $_GET['widget_id'];
	$id = clean($id);
	$skin = $_GET['skin'];
	$skin = clean($skin);
	if(!empty($skin)){	
		$skin .= '.css';
		$css = file_get_contents('./skins/' . $skin );
		$widget_skin = preg_replace('/%ID%/',$id, $css);
		echo $widget_skin;
	}

?>
<?php
function clean($str = '', $html = false) {
	if (empty($str)) return;

	if (is_array($str)) {
		foreach($str as $key => $value) $str[$key] = clean($value, $html);
	} else {
		if (get_magic_quotes_gpc()) $str = stripslashes($str);

		if (is_array($html)) $str = strip_tags($str, implode('', $html));
		elseif (preg_match('|<([a-z]+)>|i', $html)) $str = strip_tags($str, $html);
		elseif ($html !== true) $str = strip_tags($str);

		$str = trim($str);
		$str = str_replace(".", "", $str);
		$str = str_replace("/", "", $str);
	}

	return $str;
}
?>