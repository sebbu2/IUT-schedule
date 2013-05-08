<?php
function get_url($adresse) {
 $data2=parse_url($adresse);
 $fp=fsockopen($data2['host'],80,$errno,$errstr,30);
 $data='';
 if (!$fp) {
  echo "$errstr ($errno)<br />\n";
 }
 else {
  $out = "GET ".((isset($data2['path']))?$data2['path']:'/').((isset($data2['query']))?'?'.$data2['query']:'');
  //$out .= " HTTP/1.1\r\n";
  $out .= " HTTP/1.0\r\n";
  $out .= "Host: ".$data2['host']."\r\n";
  $out .= "Accept: */*\r\n";
  $out .= "Referer: http://".$data2['host'].((isset($data2['path']))?$data2['path']:'/')."\r\n";
  //$out .= "User-Agent: Mozilla/4.0 (compatible; MSIE 5.00; Windows 98)\r\n";
  $out .= "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; fr-FR; rv:1.7.12) Gecko/20050919 Firefox/1.0.7\r\n";
  $out .= "Pragma: no-cache\r\n";
  $out .= "Cache-Control: no-cache\r\n";
  $out .= "Connection: Close\r\n\r\n";
  fwrite($fp, $out);
  while (!feof($fp)) {
   $data.=fgets($fp, 128);
  }
  fclose($fp);
 }
 unset($data2);
 $data=explode("\r\n\r\n",$data);
 $data2=array_shift($data);
 $GLOBALS['get_url-data2']=$data2;
 $data=implode("\r\n\r\n",$data);
 //$data=preg_replace('!^([0-9]*)(\s*)!i','',$data);
 //$data=preg_replace('!([0-9]*)(\s*)([0-9]*)$!i','',$data);
 return trim($data);
}

function get_url2($adresse,$method,$vars,$cookie) {
 if($method!='POST') die('Hacking attemp');
 $data2=parse_url($adresse);
 $fp=fsockopen($data2['host'],80,$errno,$errstr,30);
 $data='';
 if (!$fp) {
  echo "$errstr ($errno)<br />\n";die();
 }
 else {
  $out = "POST ".((isset($data2['path']))?$data2['path']:'/').((isset($data2['query']))?'?'.$data2['query']:'');
  //$out .= " HTTP/1.1\r\n";
  $out .= " HTTP/1.0\r\n";
  $out .= "Host: ".$data2['host']."\r\n";
  //$out .= "Accept: */*\r\n";
  $out .= "Accept: text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5\r\n";
  $out .= "Accept-encoding: gzip,deflate,all\r\n";
  //$out .= "Accept-encoding: gzip,deflate\r\n";
  //$out .= "Accept-encoding: none\r\n";
  $out .= "Accept-languages: fr,fr-fr,en-us,en,all\r\n";
  $out .= "Accept-charsets: iso-8859-15,iso-8859-1,*,utf-8\r\n";
  $out .= "Referer: http://".$data2['host'].((isset($data2['path']))?$data2['path']:'/')."\r\n";
  //$out .= "User-Agent: Mozilla/4.0 (compatible; MSIE 5.00; Windows 98)\r\n";
  $out .= "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; fr-FR; rv:1.7.12) Gecko/20050919 Firefox/1.0.7\r\n";
  $out .= "Pragma: no-cache\r\n";
  $out .= "Cache-Control: no-cache\r\n";
  $out .= "Connection: Close\r\n";
  $out .= "Cookie: ".$cookie."\r\n";
  $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
  $out .= "Content-Length: ".strlen($vars)."\r\n\r\n";
  $out .= $vars."\r\n\r\n";
  $GLOBALS['get_url-out']=$out;
  fwrite($fp, $out);
  while (!feof($fp)) {
   $data.=fgets($fp,1024);
  }
  fclose($fp);
 }
 $data=explode("\r\n\r\n",$data);
 $data2=trim(array_shift($data));
 $GLOBALS['get_url-data2']=$data2;
 $data=implode("\r\n\r\n",$data);
 $data=trim($data);
 //$data=preg_replace('!^([0-9a-f]+)(\s+)(.+?)([0-9a-f]+)(\s+)([0-9a-f]+)$!iU','\\3',$data);
 return trim($data);
}
?>