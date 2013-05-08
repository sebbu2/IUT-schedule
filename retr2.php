<?php
$arg='http://iutdijon.u-bourgogne.fr/edt/gpu/pfilieres.php?annee_courante=2005&redirect=&acct_name=ETUD&acct_pass=iut21&submitbtn=Valider';
require_once('get_sock.php');

/*$fp=fsockopen('iutdijon.u-bourgogne.fr',80,$errno,$errstr,30);
$data='';
if (!$fp) {
   echo "$errstr ($errno)<br />\n";
} else {
   $out = "GET /edt/gpu/pfilieres.php?annee_courante=2005&redirect=&acct_name=ETUD&acct_pass=iut21&submitbtn=Valider HTTP/1.1\r\n";
   $out .= "Host: iutdijon.u-bourgogne.fr\r\n";
   $out .= "Accept: *//*\r\n";
   $out .= "Referer: http://iutdijon.u-bourgogne.fr/edt/\r\n";
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
//$data=explode("\r\n\r\n\n",$data);
//$data=$data[1];
echo $data;*/

/* new code */

//$var='mode=departement&id=&departement=IQ&imageField2.x=16&imageFild2.y=20&imageField2=add';
$data=get_url($arg);

if(array_key_exists('source',$_REQUEST)) header('Content-Type: text/plain'.chr(10));
else header('Content-Type: text/html'.chr(10));

preg_match_all('!Set-Cookie: (\S+)!i',$GLOBALS['get_url-data2'],$matches);
array_shift($matches);$matches=$matches[0];
$cookie=implode('; ',$matches);
$method='POST';

//$vars='mode=filiere&id=&departement=IQ&filiere=IQ1&semaine='.date('W').'&imageField222=add';

if(!isset($mode) || !isset($id) || !isset($filiere) || !isset($semaine) ) {
 $vars='mode=filiere&id=&departement=IQ&filiere=IQ1&semaine='.date('W').'&imageField222=add';
 //$vars.='&imageField222.x=16&imageField222.y=10&imageField222=add';
}
else {
 $vars='mode='.rawurlencode($mode).'&id='.rawurlencode($id).'&departement='.rawurlencode($departement);
 $vars.='&filiere='.rawurlencode($filiere).'&semaine='.rawurlencode($semaine).'&imageField222='.rawurlencode($imagefield222);
}

//$vars.='&imageField222.x=0&imageField222.y=0';
//$cookie='annee_courante=2005; back_color=%23; border_color=%23; language=french; encodelogin=bc084d94f86b96648381317e4cb142b7; login=ETUD; intranet=0170a86427d6a86c84294a0921da364b&ccss=3; PHPSESSID=aed38be422773fb45d0b7c15ca6b8419;';
$arg='http://iutdijon.u-bourgogne.fr/edt/gpu/pfilieres.php';
$data=get_url2($arg,$method,$vars,$cookie);
//var_dump($matches);
//$data=preg_replace('!<body([^>]+)>!i','<body\\1><base href=\'http://iutdijon.u-bourgogne.fr/edt/gpu/\'>',$data);

/* menu de gauche : début */
$data=str_replace('src="../images/','src="./images/',$data);
$leftmenu="\r\n";
$leftmenu="\r\n".'<img src="./images/maintenance.gif " border="0" width="15" height="15" align="middle">'.
'<font face="Verdana, Arial, Helvetica, sans-serif" size="1"><a href=\'?\'>Emplois du temps de la semaine actuelle</a></font>'."\r\n";
$data=preg_replace('!<td bgcolor=#C6E8DE width="120" nowrap>(.*?)</td>!is','<td bgcolor=#C6E8DE width="120" nowrap>'.$leftmenu.'  </td>',$data);
/* menu de gauche : fin */

/* correction de qq erreur : début */
$data=str_replace('color="\'#\'.000000"','color="#000000"',$data);
$data=preg_replace('!onClick="(.+)";!i','onClick="\\1"',$data);
//$data=preg_replace('$\&(?!(amp|amp;)\S*)\s*$i','&amp;\\1\\2',$data);
$data=preg_replace('!&amp(;?)(\s+)!','&\\2',$data);
$data=preg_replace('!&(\s+)!','&amp;\\1',$data);
/* correction de qq erreur : fin */

/* changement cible <form> : début */
$adresse='http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
$data=str_replace('<form name="entryform" method="post" action="/edt/gpu/pfilieres.php">','<form name="entryform" method="post" action="'.$adresse.'">',$data);
/* changement cible <form> : fin */

//echo $GLOBALS['get_url-data2'];
$matches='';
preg_match_all('!Jour : <font face="Verdana, Arial, Helvetica, sans-serif" size="2">(Lundi|Mardi|Mercredi|Jeudi|Vendredi|Samedi|Dimanche)  </font>[\r\n]*'.
'  <table width="95%" border="1" bordercolorlight="#CCCCCC" bordercolordark="#000000" bordercolor="#CCCCCC">(.+?)</table>!is',$data,&$matches);
//var_dump($matches);die();
array_shift($matches);
$jours=array_map('trim', $matches[0]);
$jours_data=array_map('trim', $matches[1]);
//var_dump($jours_data[0]);die();
$i=0;
preg_match_all('!<tr align="center">(.+?)</tr>!is',$jours_data[$i],$matches);
array_shift($matches);$matches=$matches[0];array_shift($matches);
//var_dump($matches);die();
$j=2;//0=A1,1=A2,2=B1,3=B2,4=C1,5=C2,6=D1,7=D2,8=E1,9=E2
//print($matches[$j]);die();
preg_match_all('!<td(.+?)bgcolor="(\#[0-9a-fA-F]+?)"(.+?)colspan="(\d+)"(?: rowspan="(\d+)")?>(.+?)</td>!is',$matches[$j],$matches2);
array_shift($matches2);
array_shift($matches2);array_shift($matches2);array_shift($matches2);
$cols=$matches2[0];$rows=$matches2[1];$matches2=$matches2[2];
foreach($matches2 as $num=>$data2) {
 $rows[$num]=(($rows[$num]=='')?1:$rows[$num]);
 $data2=trim($data2);
 if($data2=='&nbsp;')
  if($cols[$num]==1 && $rows[$num]==1)
   $cour=false;
  else
   $cour=$data2;
}
if(array_key_exists('source',$_REQUEST)) { var_dump($matches2);die(); }
else { echo $data; }
?>
