<?php

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, 'http://www.autoxcatalog.com/robots.txt');
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
curl_setopt($curl, CURLOPT_TIMEOUT, 10);
$res = curl_exec($curl);
curl_close($curl);
exit($res);