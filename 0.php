<?php
include 'p_function.php';
$header[]='User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.86 Safari/537.36';
$header[]='Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8';
$header[]='Cookie: auth=a930fd6539ceb1065b23540eef377c1f;aaa=111';

print_r(req_header_format($header));

?>