<?php
class table_parser {

	public $data;
	private $total_rows=0;
	private $total_cols=0;
	private $rows;
	private $cols;
	private $cels;

	public function __construct( $data ) {
	 $this->data=$data;
	 $this->count();
	}

	function count() {

	 /*preg_match_all('#<tr([^>]*)>((?!</tr>).*?)</tr>#is',$this->data,$matches);
	 array_shift($matches);
	 $matches=$matches[1];*/
	 /* alternative */
	 $this->total_rows=substr_count($this->data,'</tr>');
	 $pos2=0;$matches=array();
	 for($i=0;$i<$this->total_rows;$i++) {
	  $pos1=strpos($this->data,'<tr',$pos2);
	  $pos2=strpos($this->data,'</tr>',$pos1);
	  $matches[]=substr($this->data,$pos1,$pos2+5-$pos1);
	 }

	 foreach($matches as $value) {

	  /*preg_match_all('#<td([^>]*)>((?!</td>).*?)</td>#is',$value,$matches2);
	  $matches2=$matches2[0];
	  $cols=count($matches2);
	  unset($matches2);*/
	  /* alternative */
	  $cols=substr_count($value,'</td>');

	  if($cols>$this->total_cols) $this->total_cols=$cols;
	 }
	 $this->total_rows=count($matches);
	 /*$this->cels=array();
	 for($i=0;$i<12;$i++) { //$this->total_rows
	  $this->cels[$i]=array();
	  for($j=0;$j<23;$j++) { //$this->total_cols
	   $this->cels[$i][$j] = '';
	  }
	 }*/
	 $this->total_rows--;
	 //var_dump($this->total_rows,$this->total_cols);die();
	 $this->cels = array();
		for($i=0; $i<$this->total_rows; $i++)
		{
			$this->cels[] = array();
			for($j=0; $j<$this->total_cols; $j++)
			{
				$this->cels[$i][] = false;
			}
		}
	 //array_shift($this->cels);
	 //var_dump($this->cels);die();
	 return array($this->total_rows,$this->total_cols);
	}

	function parse() {
	 // sauter la premiere ligne
	 $pos2=strpos($this->data,'</tr>');
	 $rows=array();
	 for($i=0;$i<$this->total_rows;$i++) {
	  $this->cols=0;
	  $this->rows=$i;
	  $pos1=strpos($this->data,'<tr',$pos2);
	  $pos2=strpos($this->data,'</tr>',$pos1);
	  $rows[$i]=substr($this->data,$pos1,$pos2+5-$pos1);
	  $data=$rows[$i];
	  //die($data);
	  $cols=substr_count($data,'</td>');
	  //var_dump($cols);die();
	  $pos_2=0;$matches=array();
	  for($j=0;$j<$cols;$j++) {
	   $pos_1=strpos($data,'<td',$pos_2);
	   $pos_2=strpos($data,'</td>',$pos_1);
	   $matches[$j]=substr($data,$pos_1,$pos_2+5-$pos_1);
	   //var_dump($matches[$j]);//die();
	   //var_dump($matches[$j]);
	   $pos__2=0;$pos__1=0;
	   $pos__1=strpos($matches[$j],'colspan="',$pos__2)+9;
	   $pos__2=strpos($matches[$j],'"',$pos__1);
	   $colspan=substr($matches[$j],$pos__1,$pos__2-$pos__1);
	   $pos__2=0;
	   if(substr_count($matches[$j],'rowspan=')==1) {
	    $pos__1=strpos($matches[$j],'rowspan="',$pos__2);
	    $pos__1+=9;
	    $pos__2=strpos($matches[$j],'"',$pos__1);
	    $rowspan=substr($matches[$j],$pos__1,$pos__2-$pos__1);
	   }
	   else {
	   	$rowspan="1";
	   }
	   $pos__2=0;
	   $pos__1=strpos($matches[$j],'<a ',$pos__2);
	   $pos__1=strpos($matches[$j],'>',$pos__1);
	   $pos__2=strpos($matches[$j],'</a>',$pos__1);
	   $cour=trim(substr($matches[$j],$pos__1+1,$pos__2-$pos__1-1));
	   if($cour===false) $cour='';
	   //if($i==0&&$j==0) continue;
	   //var_dump($cour,$i,$this->cols);echo "\r\n";
	   $num_1=0;$num_2=0;$num_1b=0;$num_2b=0;
	   /*if($rowspan==10) {
	   	var_dump($cour,$i,$this->cols,$rowspan,$colspan);echo "\r\n";
	   }*/
	   $num2add=0;
	   for($num_1=0;$num_1<$rowspan;$num_1++) {
	   	$num_1b=$i+$num_1;
	   	for($num_2=0;$num_2<$colspan;$num_2++) {
	   	 $num_2b=$this->cols+$num_2+$num2add;
	   	 
	   	 //if($num_1b!=0) {
	   	  while($this->cels[$num_1b][$num_2b]!==false) {
	   	    $num2add++;
	   	    $num_2b=$num_2+$this->cols+$num2add;
	   	  }
	   	 //}
	   	 if($num_2b>23) die('érreur1');
	   	  //break(1);
	     //var_dump($this->cels[$num_1b][$num_2b]);//die();
	   	 if($this->cels[$num_1b][$num_2b]!='') {
	   	  var_dump($num_1b,$num_2b);
	   	  die('érreur2');
	   	 }
	   	 $this->cels[$num_1b][$num_2b]=$cour;
	   	}
	   }
	   /*for($num_1=$i;$num_1<$rowspan+$i;$num_1++) {
	   	for($num_2=0+$this->cols;$num_2<$colspan+$this->cols;$num_2++) {
	   	 
         while($this->cels[$num_1][$num_2]!==false) {
	   	   $num_2++;
	   	 }

	   	 if(strpos($cour,'IQ1-B / TD / ANG1 / POUCHJM / IQA8 / IQ-IQ1')>0) { var_dump($cour,$i,$this->cols,$rowspan,$colspan);echo "\r\n"; }
	   	 if($num_2>23) die('érreur1');
	   	 if($this->cels[$num_1][$num_2]!==false) {
	   	  var_dump($num_1,$num_2);
	   	  die('érreur2');
	   	 }
	   	 $this->cels[$num_1][$num_2]=$cour;
	   	}
	   }*/
	   $this->cols+=$colspan-1;
	   //die();
	   $cour='';
	   //var_dump($this->cels[$i][$j]);//die();
	   $this->cols++;
	  }
	  //print('jour fini'."\r\n");
	  //var_dump($this->cels);
	  //var_dump($matches);die();
	  //die();
	 }
	 //var_dump($this->cels);
	 
	 //die();
	 return $this->cels;
	}

	function get_data() {
	 return $this->data;
	}
}
?>