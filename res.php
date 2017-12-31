<?php

function data5u_index(){
	$header[]='User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.86 Safari/537.36';
	$header[]='Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8';
	$postcontent='';
	$proxy['data5u']=send_follow_30x('GET',"http://www.data5u.com",$header,$postcontent);
	preg_match_all('/<span><li>[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}<\/li><\/span>/i',$proxy['data5u']['output_body'],$test['ip']);
	preg_match_all('/port\s?[A-Z]+">[0-9]{1,5}/i',$proxy['data5u']['output_body'],$test['port']);
	preg_match_all('/www\.data5u\.com\/free\/type\/((http)|(,\s?)|(https)|){1,3}\/index\.html/',$proxy['data5u']['output_body'],$test['type']);
	foreach($test['ip'][0] as $key => $value){
		$test['ip'][0][$key]=rtrim(ltrim($value,'<span><li>'),'</li></span>');
	}
	foreach($test['port'][0] as $key => $value){
		preg_match('/[0-9]{1,5}/i',$value,$temp);
	    $test['port'][0][$key]=$temp[0];
	}
	foreach ($test['type'][0] as $key => $value) {
		$test['type'][0][$key]=rtrim(ltrim($value,'www.data5u.com/free/type/'),'/index.html">');
		$test['type'][0][$key]=stripos($test['type'][0][$key],'https')?'https':'http';
	}
	for($n=0;$n<=19;$n++){
		echo $test['ip'][0][$n].':'.$test['port'][0][$n].'@'.$test['type'][0][$n]."\r\n";
	}
}

function zdy_daily(){
	$page=ceil((time()-1502172000)/86400)+3231;
	$header[]='User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.86 Safari/537.36';
	$header[]='Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8';
	$postcontent='';
	$r=send('GET',"http://ip.zdaye.com/dayProxy/ip/$page.html",$header,$postcontent);
	preg_match_all('/[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}:[0-9]{1,5}@\w+#[^(<br>)]*/i',$r['output_body'],$a);
	foreach ($a[0] as $key => $value) {
		echo $value."\r\n";
	}
}

function socks_proxy(){
	$header[]='User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.86 Safari/537.36';
	$header[]='Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8';
	$postcontent='';
	$r=send('GET','https://www.socks-proxy.net',$header,$postcontent);
	preg_match_all('/<tr><td>[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}<\/td><td>[0-9]{1,5}<\/td><td>\w+<\/td><td class="hm">\w+\s*\w+<\/td><td>\w+<\/td>/',$r['output_body'],$a);
	foreach ($a[0] as $key => $value) {
		$value=str_replace('/', '', $value);
		$value=str_replace('<td class="hm">','',$value);
		$value=str_replace('<tr>','<td>',$value);
		$value=str_replace('<td><td>','<td>',$value);
		$t=explode('<td>',$value);
		echo $t[1].':'.$t[2].'@'.$t[5].'#'.$t[4]."\r\n";
	}
}


?>