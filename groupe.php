<?php
$arg='http://iutdijon.u-bourgogne.fr/edt/gpu/pfilieres.php?annee_courante=2006&redirect=&acct_name=ETUD&acct_pass=iut21&submitbtn=Valider';
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

$matches=array();
preg_match_all('!Set-Cookie: (\S+)!i',$GLOBALS['get_url-data2'],$matches);
array_shift($matches);$matches=$matches[0];
$cookie=implode('; ',$matches);
$method='POST';

//$_REQUEST['semaine']=46;

$mode='filiere';$id='';$departement='IQ';/*$filiere='IQ1';*/
//$filiere='IQS4';
$imagefield222='add';
if(array_key_exists('semaine',$_REQUEST)) $semaine=$_REQUEST['semaine']; else $semaine=date('W');
if(array_key_exists('groupe',$_REQUEST)) $groupe=$_REQUEST['groupe']; else $groupe='A1';
if(array_key_exists('filiere',$_REQUEST)) $filiere=$_REQUEST['filiere']; else $filiere='IQS4';
if(array_key_exists('mode',$_REQUEST)) $mode=$_REQUEST['mode']; else $mode='filiere';
if(array_key_exists('departement',$_REQUEST)) $departement=$_REQUEST['departement']; else $departement='IQ';
if(array_key_exists('id',$_REQUEST)) $id=$_REQUEST['id']; else $id='';
if(array_key_exists('imageField222',$_REQUEST)) $imageField222=$_REQUEST['imageField222']; else $imageField222='add';

$tmp=mktime(1,0,0,12,31,date('Y'));
$tmp2=mktime(1,0,0,1,1,date('Y'));

//var_dump($semaine,date('d/m/Y, H:i:s W',$tmp),date('d/m/Y, H:i:s W',$tmp2));

while( $semaine > strftime('%W',$tmp) ) {
 $semaine-=strftime('%W',$tmp);
}
$test='';
while( $semaine < 0 ) {
 $semaine+=date('W',$tmp);
}
//var_dump($semaine);
//var_dump($semaine,$groupe);
/*var_dump($_REQUEST['semaine']);
var_dump($semaine);*/

$test='';

//$vars='mode=filiere&id=&departement=IQ&filiere=IQ1&semaine='.date('W').'&imageField222=add';
//if(array_key_exists('mode',$_REQUEST)) $mode=$_REQUEST['mode']; else $mode=false;
/*if(!isset($mode) || !isset($id) || !isset($filiere) || !isset($semaine) || !isset($imagefield222) ) {
 $vars='mode=filiere&id=&departement=IQ&filiere='.rawurlencode($filiere).'&semaine='.date('W').'&imageField222=add';
 //$vars='mode=filiere&id=&departement=IQ&filiere=IQ1&semaine=46&imageField222=add';
 //$vars.='&imageField222.x=16&imageField222.y=10&imageField222=add';
 //var_dump(false);die();
}
else {*/
 $vars='mode='.rawurlencode($mode).'&id='.rawurlencode($id).'&departement='.rawurlencode($departement);
 $vars.='&filiere='.rawurlencode($filiere).'&semaine='.rawurlencode($semaine).'&imageField222='.rawurlencode($imagefield222);
//}

//var_dump($vars);

//$vars.='&imageField222.x=0&imageField222.y=0';
//$cookie='annee_courante=2005; back_color=%23; border_color=%23; language=french; encodelogin=bc084d94f86b96648381317e4cb142b7; login=ETUD; intranet=0170a86427d6a86c84294a0921da364b&ccss=3; PHPSESSID=aed38be422773fb45d0b7c15ca6b8419;';
$arg='http://iutdijon.u-bourgogne.fr/edt/gpu/pfilieres.php';
$data=get_url2($arg,$method,$vars,$cookie);
//echo $data;die();
//var_dump($matches);
//$data=preg_replace('!<body([^>]+)>!i','<body\\1><base href=\'http://iutdijon.u-bourgogne.fr/edt/gpu/\'>',$data);

/* menu de gauche : début */
$data=str_replace('src="../images/','src="./images/',$data);
$leftmenu="\r\n";
$leftmenu="\r\n".'<img src="./images/maintenance.gif " border="0" width="15" height="15" align="middle">'.
'<font face="Verdana, Arial, Helvetica, sans-serif" size="1"><a href=\'?'.((array_key_exists('orig',$_REQUEST))?'orig':'').'\'>Emplois du temps de la semaine actuelle</a></font>'."\r\n";
$data=preg_replace('!<td bgcolor=#C6E8DE width="120" nowrap>(.*?)</td>!is','<td bgcolor=#C6E8DE width="120" nowrap>'.$leftmenu.'  </td>',$data);
/* menu de gauche : fin */

/* correction de qq erreur : début */
$data=str_replace('color="\'#\'.000000"','color="#000000"',$data);
$data=preg_replace('!onClick="(.+)";!i','onClick="\\1"',$data);
//$data=preg_replace('$\&(?!(amp|amp;)\S*)\s*$i','&amp;\\1\\2',$data);
$data=preg_replace('!&amp(;?)(\s+)!','&\\2',$data);
$data=preg_replace('!&(\s+)!','&amp;\\1',$data);
/* correction de qq erreur : fin */

if(!array_key_exists('HTTP_HOST',$_SERVER)) $_SERVER['HTTP_HOST']='commande-line';

/* changement cible <form> : début */
$adresse='http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
$data=str_replace('<form name="entryform" method="post" action="/edt/gpu/pfilieres.php">','<form name="entryform" method="post" action="'.$adresse.'"><input type="hidden" name="orig" value="true">',$data);
/* changement cible <form> : fin */

if(array_key_exists('orig',$_REQUEST)) {
 /* début date */
 setlocale(LC_TIME, "fr");
 //if($semaine!=$_REQUEST['semaine'])
 if( date('n',strtotime('+'.$semaine.' week',mktime(0,0,0,1,1,date('Y')))) > 8 ) {
  $beginYear=mktime(0,0,0,1,1,2006);
 }
 else {
  $beginYear=mktime(0,0,0,1,1,2007);
 }
 $jour2=strtotime('monday',$beginYear);
 if(date('W',$jour2)>25) $jour2=strtotime('+1 week',$jour2);
 //echo date('d/m/Y, H:i:s',$beginYear);die();
 //echo date('d/m/Y, H:i:s',$jour2).'<br/>';
 //$jour2=strtotime('+'.$semaine.' week',$jour2);
 $diff=$semaine-date('W',$jour2);
 $jour2=strtotime('+'.$diff.' week',$jour2);
 if($mode=='moins') $jour2=strtotime('-1 week',$jour2);
 if($mode=='plus') $jour2=strtotime('+1 week',$jour2);
 //echo date('d/m/Y, H:i:s',$jour2).'<br/>';//die();
 $jours=array('Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi');
 $jours_rev=array_flip($jours);
 $mois=array('janvier','f&eacute;vrier','mars','avril','mai','juin','juillet','ao&ucirc;t','septembre','octobre','novembre','d&eacute;cembre');
 //$jours_uk=array('Dimanche'=>'Sunday','Lundi'=>'Monday','Mardi'=>'Tuesday','Mercredi'=>'Wednesday','Jeudi'=>'Thursday','Vendredi'=>'Friday','Samedi'=>'Saturday');
 //$jours_uk=array_values($jours_uk);
 $jours_uk=array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
 //foreach($jours_uk as $key=>$jours_uk_test) {
 for($i=1;$i<7;$i++) {
  $jours_uk_test=$jours_uk[$i];
  $key=$jours[$i];
  $jour2=strtotime($jours_uk_test,$jour2);
  $data=str_replace($key,$key.' '.date('d',$jour2).' '.$mois[date('n',$jour2)-1].' '.date('Y',$jour2),$data);
  //var_dump(date('d/m/Y',$jour2));
 }
 /* fin date */
 echo $data;die();
}

//echo $GLOBALS['get_url-data2'];
$matches='';
preg_match_all('!Jour : <font face="Verdana, Arial, Helvetica, sans-serif" size="2">(Lundi|Mardi|Mercredi|Jeudi|Vendredi|Samedi|Dimanche)  </font>[\r\n]*'.
//'  <table width="95%" border="1" bordercolorlight="#CCCCCC" bordercolordark="#000000" bordercolor="#CCCCCC">(.+?)</table>!is',$data,$matches);
//'  <table bordercolorlight="#CCCCCC" bordercolordark="#000000" border="1" bordercolor="#cccccc" width="95%">(.+?)</table>!is',$data,$matches);
'  <table(.+?)>(.+?)</table>!is',$data,$matches);
//var_dump($matches);die();
array_shift($matches);
$jours=array_map('trim', $matches[0]);
//$jours_data=array_map('trim', $matches[1]);
$jours_data=array_map('trim', $matches[2]);
//var_dump($jours_data[0]);die();

// ancienne version
/*
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
*/

include('table-parser.php');
//$_REQUEST['source']='';

if(!headers_sent()) {
 if(array_key_exists('source',$_REQUEST)) header('Content-Type: text/plain'.chr(10));
 else header('Content-Type: text/html'.chr(10));
}

// nouvelle version
//var_dump($jours_data);die();
if(!isset($groupe)) $groupe='A1';
$groupe_name=array('A1','A2','B1','B2','C1','C2','D1','D2','E1','E2');
//$groupe=2;
$groupe=array_search($groupe,$groupe_name);
if($groupe===false) {
 $groupe='A1';
}

$num=count($jours);
$edt=array();
for($i=0;$i<$num;$i++) {
 $table=new table_parser('<table>'."\r\n".$jours_data[$i]."\r\n".'</table>');
 //$table->count();
 $edt1=$table->parse();
 //echo '<pre>';var_dump($edt1);echo '</pre>';die();
 //if($i!=0) { var_dump($edt1);die(); }
 for($j=0;$j<3;$j++) array_pop($edt1[$groupe]);
 $edt[$jours[$i]]=$edt1[$groupe];
}
unset($edt1);
//var_dump($edt);die();
unset($vars,$method,$matches,$leftmenu,$i,$j,$num,$GLOBALS['get_url-out'],$GLOBALS['get_url-data2'],$adresse,$arg);
/* début sortie */

include('header.php');
if(!isset($PHP_SELF)) $PHP_SELF=$_SERVER['PHP_SELF'];
$semaine=(int)trim($semaine);
$_REQUEST['semaine']=(int)trim($_REQUEST['semaine']);
?>
<div>
<a href='<?php echo $PHP_SELF.
((isset($_REQUEST['semaine']))?'?semaine='.($semaine-1):'?semaine='.(date('W')-1)).
((isset($_REQUEST['groupe']))?'&groupe='.$_REQUEST['groupe']:''); ?>'>semaine pr&eacute;c&eacute;dante</a> -
<?php echo 'semaine ',$semaine; ?> - 
<a href='<?php echo $PHP_SELF.
((isset($_REQUEST['semaine']))?'?semaine='.($semaine+1):'?semaine='.(date('W')+1)).
((isset($_REQUEST['groupe']))?'&groupe='.$_REQUEST['groupe']:''); ?>'>semaine suivante</a><br/>&nbsp;<br/>&nbsp;
</div>
<?php

$data2='';
$data2.='<table border="1">'."\r\n";
$data2.=' <tr>'."\r\n";
$data2.='  <td style="width: 4.75%;">&nbsp;</td>'."\r\n";
//$data2.='  <td width="4.75%">&nbsp;</td>';
for($i=8;$i<18;$i=$i+0.5) {
 $data2.='  <td style="width: 4.75%;">'.floor($i).(($i-floor($i)==0)?'h':'h30').'</td>'."\r\n";
}
unset($i);
$data2.=' </tr>'."\r\n";

/* début date */
setlocale(LC_TIME, "fr");
//if($semaine!=$_REQUEST['semaine'])
if( date('n',strtotime('+'.$semaine.' week',mktime(0,0,0,1,1,date('Y')))) > 8 ) {
 $beginYear=mktime(0,0,0,1,1,2006);
}
else {
 $beginYear=mktime(0,0,0,1,1,2007);
}
$jour2=strtotime('monday',$beginYear);
if(date('W',$jour2)>25) $jour2=strtotime('+1 week',$jour2);
//echo date('d/m/Y, H:i:s',$beginYear);//die();
//echo date('d/m/Y, H:i:s',$jour2).'<br/>';
//$jour2=strtotime('+'.$semaine.' week',$jour2);
//var_dump($semaine);
$diff=$semaine-date('W',$jour2);
$jour2=strtotime('+'.$diff.' week',$jour2);
//echo date('d/m/Y, H:i:s',$jour2).'<br/>';//die();
$jours=array('Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi');
$jours_rev=array_flip($jours);
$mois=array('janvier','f&eacute;vrier','mars','avril','mai','juin','juillet','ao&ucirc;t','septembre','octobre','novembre','d&eacute;cembre');
$jours_uk=array('Dimanche'=>'Sunday','Lundi'=>'Monday','Mardi'=>'Tuesday','Mercredi'=>'Wednesday','Jeudi'=>'Thursday','Vendredi'=>'Friday','Samedi'=>'Saturday');
/* fin date */

/*if(count($edt)>0) {
 $tmp=each($edt);$tmp=$tmp[0];$tmp=$jours_rev[$tmp];$tmp-=1;
 if($tmp<0) $tmp+=1;
 //var_dump($tmp);//die();
 if($tmp>0) $jour2=strtotime('+'.$tmp.' day',$jour2);
}

if(date('Y',$jour2)==2007) $jour2=strtotime('-1 week',$jour2);*/

foreach($edt as $jour=>$horaires) {
 $data2.=' <tr>'."\r\n";
 //var_dump($jour);
 $jour2=strtotime(/*'next '.*/$jours_uk[$jour],$jour2);
 $jour3=$jours[date('w',$jour2)].' '.date('d',$jour2).' '.$mois[date('n',$jour2)-1].' '.date('Y',$jour2);
 $data2.='  <td>'.$jour3.'</td>'."\r\n";
 //$data2.='  <td><pre>'.$jours_rev[$jour].'</pre></td>'."\r\n";
 $cour0=false;
 $data2_1='  <td colspan="';
 $cols=0;$boucle1=false;
 $data2_2='">';
 foreach($horaires as $cour1) {

  if($cour1 != $cour0 && !$boucle1) {
   $cour0=$cour1;
  }
  if($cour1 != $cour0 && $boucle1) {
   $data2.=$data2_1.$cols.$data2_2.(($cour0=='')?'&nbsp;':$cour0).'</td>'."\r\n";
   $cour0=$cour1;
   $cols=0;
  }
  if($cour1 == $cour0) $cols++;
  $boucle1=true;
 }
 $data2.=$data2_1.$cols.$data2_2.(($cour1=='')?'&nbsp;':$cour1).'</td>'."\r\n";
 $data2.=' </tr>'."\r\n";
}
if(count($edt)==0) {
 $data2.=' <tr>'."\r\n".'  <td colspan="21" style="text-align: center;"><h1>pas de cours ?</h1></td>'."\r\n".' </tr>'."\r\n";
}
$data2.='</table>';

echo $data2."\r\n";

include('footer.php');

/*if(array_key_exists('source',$_REQUEST)) { $data;die(); }
else { echo $data; }*/
?>