<?php
function send($method,$url,$header,$postcontent){
	$ch = curl_init();//初始化，创建一个新curl资源
	curl_setopt($ch, CURLOPT_URL,$url);//设置url
	curl_setopt($ch,CURLOPT_CUSTOMREQUEST,$method);//自定义方式，GET,POST,PUT,DELETE等都可以
	curl_setopt($ch, CURLOPT_HTTPHEADER,$header);//自定义http_header
	if ($postcontent!=""){
		curl_setopt($ch, CURLOPT_POSTFIELDS,$postcontent);
	}
	curl_setopt($ch, CURLOPT_TIMEOUT, 60);//允许curl执行最大秒数
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);  //从证书中检查SSL加密算法是否存在
	curl_setopt($ch, CURLOPT_HEADER, true);//设置获取返回头
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );//以字符串的形式返回cURL句柄获取的内容
	$output = curl_exec($ch);//执行
	$headersize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);//获取返回头大小
	$header_response = substr($output, 0, $headersize);//截取返回头
	$header_response=explode("\r\n",$header_response);
	array_pop($header_response);
	array_pop($header_response);//弹出两个空行
	$output_body=substr($output,$headersize);
	foreach ($header_response as $key => $value) {
		$t=explode(' ',$value,2);
		if(stristr($t[0],'Set-Cookie')){
			$CookieT=explode(';',$t[1]);
			foreach ($CookieT as $key1 => $value1) {
				$ttt=explode('=',$value1,2);
				$cookie[$ttt[0]]=$ttt[1];
			}
			$tt[$t[0]]=$cookie;
		}else{
			$tt[$t[0]]=$t[1];
		}
	}
	curl_close($ch);//关闭curl，释放资源
	$output_array['header_response']=$tt;
	$output_array['output_body']=$output_body;//以数组的形式展现，方便输出http返回头和html的body
	return $output_array;//返回信息
}

function req_header_format($header){
	//$arr=array();
	foreach ($header as $key => $value) {
		$tmp=explode(' ',$value,2);
		if($tmp[0]!=='Cookie:'){
			$arr[$tmp[0]]=$tmp[1];
		}else{
			if(stripos($tmp[1],';')){
				$t0=explode(';',$tmp[1]);
				foreach ($t0 as $key1 => $value1) {
					$t1=explode('=',$value1,2);
					$a_cookie[$t1[0]]=$t1[1];
				}
			}else{
				$t0=explode(' ',$value,2);
				$t1=explode('=',$t0[1],2);
				$a_cookie[$t1[0]]=$t1[1];
			}
			$arr['Cookie:']=$a_cookie;
		}
	}
	return($arr);
}
function req_header_unformat($format_header){
	foreach ($format_header as $key => $value) {
		if(!is_array($value)){
			$arr[]=$key.' '.$value;
		}else{
			$t=$key.'';
			foreach ($value as $key1 => $value1) {
				$t.=' '.$key1.'='.$value1.';';
			}
			$arr[]=$t;
		}
	}
	return($arr);
}


function send_follow_30x($method,$url,$header,$postcontent){
	$a=send($method,$url,$header,$postcontent);
	$fm_header=req_header_format($header);
	if($a['header_response']['Set-Cookie:']){
		if($fm_header['Cookie:']){
			foreach ($a['header_response']['Set-Cookie:'] as $key => $value) {
				array_unshift($fm_header['Cookie:'], $value);
			}
		}else{
				$fm_header['Cookie:']= $a['header_response']['Set-Cookie:'];
		}
	}
	$header=req_header_unformat($fm_header);
	for (;; ) { 	
		if(preg_match('/30\w?\s?Found/',$a['header_response']['HTTP/1.1'])){
			$a=send($method,$a['header_response']['Location:'],$header,$postcontent);
		}else{
			break 1;
		}
	}
	return $a;
}

?>