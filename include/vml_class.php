<?
  /*****
    linetable_draw
    use:draw a polyline vml table
    author:gengliang
    date:2006-11-22
    adapt from a javascript version
    *******/
    class vml_tb{
    	
    	 //rect area config
       var $RECT_bg="#cccccc";     //�������򱳾�
       var $RECT_width=400;       //�����������
       var $RECT_height=300;     //��������߶�
       var $RECT_left=200;      //�����������߾�
       var $RECT_top=200;      //���������ϱ߾�
       
       
       //line option
       var $LINE_color="#000000";        //������ɫ
       var $LINE_stroke_weight=1.5;     //��������
       var $LINE_type=2;               //������ʽ:1-7,1Ϊ��Ч
       var $LINE_node_type=3;         //�ڵ�����:1-3 ,1Ϊ��Ч
       
       function vml_tb($RECT_top,$RECT_left,$RECT_bg="#cccccc",$RECT_width=500,$RECT_height=300,$LINE_color="#000000")
       {
       	 if($RECT_bg!="")$this->RECT_bg=$RECT_bg;
       	 if($RECT_width!="")$this->RECT_width=$RECT_width;
       	 if($RECT_height!="")$this->RECT_height=$RECT_height;
       	 if($LINE_color!="")$this->LINE_color=$LINE_color;
       	 //if($DISPLAY_type!="")$this->DISPLAY_type=$DISPLAY_type;
       	}
       	
       	//�������ݱ���λֵ
       	function cont_unit()
       	{
       		$arr=$this->DATA_arr;
       		$num=count($arr);
       		//$tem1,$tem2,$tem3Ϊ����������ʱ����
       		$tem1=0;
       		for($i=1;$i<$num;$i++)
       		{
       		  if($tem1 < $arr[$i])
       		     $tem1 = $arr[$i];
       		}
       		if($tem1 > 9)
       		 {  
       		   $tem2 = substr($tem1,0,1);
       		   if($tem2>4)
       		      //$tem3=(intval($tem1/pow(10,(strlen($tem1)-1)))+1)/pow(10,(strlen($tem1)-1));
       		      $tem3=intval(substr($tem1,0,1)+1)*pow(10,(strlen($tem1)-1));
       		   else
       		     //$tem3=(intval($tem1/pow(10,(strlen($tem1)-1)))+0.5)/pow(10,(strlen($tem1)-1));
                $tem3=intval(substr($tem1,0,1)+0.5)*pow(10,(strlen($tem1)-1));   		
       	   }
       	   else
       	   {
       	   	  if($tem1 > 4)
       	   	     $tem3=10;
       	   	  else
       	   	     $tem3=5;
       	   }
       	   return $tem3;
       	 }
       	 
       	 
       	 //�ض�pv�����������
       	/* function rect_draw()
       	 {
       	 	  $tem4=cout_unit();
       	 	  $tem=$tem4/5;
       	 	  echo "<v:rect id='_x0000_s1027' alt='' style='position:absolute;left:".$this->RECT_left."px;top:".$this->RECT_top."px;width:".$this->RECT_width."px;height:" .$this->RECT_height. "px;z-index:-1' fillcolor='#9cf' stroked='f'>
       	 	        <v:fill rotate='t' angle='-45' focus='100%' type='gradient'/>
       	 	        </v:rect>";
       	 	  for($i=0;$i<$this->RECT_height;($i+$tem))
       	 	     {
       	 	     	  echo"<v:line id='_x0000_s1027' alt='' style='position:absolute;left:0;text-align:left;top:0;flip:y;z-index:-1' from='" .$this->RECT_left. "px," .($this->RECT_top + $this->RECT_height  - $i). "px' to='" .($this->RECT_width + $this->RECT_left ). "px," .($this->RECT_top + $this->RECT_height - $i). "px' strokecolor='" . $this->LINE_color . "'/>";
       	 	        echo"<v:line id='_x0000_s1027' alt='' style='position:absolute;left:0;text-align:left;top:0;flip:y;z-index:-1' from='" .($this->RECT_left - 15). "px," . ($this->RECT_top + $i) . "px' to='" . ($this->RECT_left) . "px," . ($this->RECT_top + $i) . "px'/>";
       	 	        echo"<v:shape id='_x0000_s1025' type='#_x0000_t202' alt='' style='position:absolute;left:".$this->RECT_left."px;top:".($this->RECT_top + $i)."px;width:70px;height:18px;z-index:1'>";
       	 	        echo"<v:textbox inset='0px,0px,0px,0px'><table cellspacing='3' cellpadding='0' width='100%' height='100%'><tr><td align='right'>" .$tem4."</td></tr></table></v:textbox></v:shape>";
       	 	        $tem4 = $tem4 - $tem;
       	 	     } 
       	 }*/
       	 //�����������:��ز�������Ч�������ȣ��������ȣ����ο������θ�,����ɫ
       	 function rect_draw($filltype,$rect_left=0,$rect_top=0,$rect_width=0,$rect_height=0,$rect_bg="")
       	 {
       	 	     if(!$rect_left)$rect_left=$this->RECT_left;
       	 	     if(!$rect_top)$rect_top=$this->RECT_top;
       	 	     if(!$rect_width)$rect_width=$this->RECT_width;
       	 	     if(!$rect_height)$rect_height=$this->RECT_height;
       	 	     if(!$rect_bg)$rect_bg=$this->RECT_bg;
       	 	echo "<v:rect id='_x0000_s1027' alt='' style='position:absolute;left:".$rect_left."px;top:".$rect_top."px;width:".$rect_width."px;height:" .$rect_height. "px;z-index:-1' fillcolor='".$rect_bg."' stroked='f'>";
       	 	 if($filltype)echo"<v:fill rotate='t' angle='-45' focus='100%' type='gradient'/>";
       	 	     
       	 	}
       	 	//�������������־����
       	 	function rect_end()
       	 	{echo"</v:rect>";}
       	 	//�ߣ����ߣ����ƣ���ز�������ʼ�����꣬�յ����꣬������ɫ
       	 	function line_draw_x($arr1,$arr2,$color="",$weight="")
       	 	{
       	 		if(!count($arr1))$arr_be=$arr1;
       	 		if(!count($arr2))$arr_en=$arr2;
       	 		if(!$color)$line_color=$color;
       	 		else $line_color=$this->LINE_color;
       	 		if(!$weight)$line_weight=$weight;
       	 		else $line_weight=$this->LINE_stroke_weight;
       	 		echo"<v:line id='_x0000_s1027' alt='' style='position:absolute;left:0;text-align:left;top:0;flip:y;z-index:-1' from='" .$arr_be[0]. "px," .$arr_be[1]. "px' to='" .$arr_en[0]. "px," .$arr_en[1]. "px' strokecolor='" . $line_color. "'strokeweight='".$line_weight."'>";
       	 		}
       	 		
       	 		//��ͨ�߻��ƣ���ز�������ʼ�����꣬�յ����꣬������ɫ
       	 	function line_draw($arr1,$arr2,$color="",$weight="")
       	 	{
       	 		if(!count($arr1))$arr_be=$arr1;
       	 		if(!count($arr2))$arr_en=$arr2;
       	 		if(!$color)$line_color=$color;
       	 		else $line_color=$this->LINE_color;
       	 		if(!$weight)$line_weight=$weight;
       	 		else $line_weight=$this->LINE_stroke_weight;
       	 		echo"<v:line id='_x0000_s1025' alt='' style='position:absolute;left:0;text-align:left;top:0;z-index:1' from='" .$arr_be[0]. "px," .$arr_be[1]. "px' to='" .$arr_en[0]. "px," .$arr_en[1]. "px' coordsize='21600,21600' strokecolor='" . $line_color. "' strokeweight='".$line_weight."'>";
       	 		}
       	 		 //line��ǩ������־
       	 	function line_end()
       	 	{
       	 		echo "</v:line>";
       	 		}
       	 		//line�ӱ��stroke:���������ͣ�ȡֵ1-7,1Ϊ��Ч
       	 function stroke_type($type=0)
       	 {
       	 	  if(!$type)$type=$this->LINE_type;
       	 	  switch(intval($type))
       	 	  {
       	 	  	case 1:
                     break;
              case 2:
       	 	  	       echo"<v:stroke dashstyle='1 1'/>";break;
       	 	  	case 3:
       	 	  	       echo"<v:stroke dashstyle='dash'/>";break;
       	 	  	case 4:
       	 	  	       echo"<v:stroke dashstyle='dashDot'/>";break;
       	 	    case 5:
       	 	           echo"<v:stroke dashstyle='longDash'/>";break;
       	 	    case 6:
       	 	           echo"<v:stroke dashstyle='longDashDot'/>";break;
       	 	  	case 7:
       	 	  	       echo"<v:stroke dashstyle='longDashDotDot'/>";break;
       	 	  	default:
       	 	  	       break;
       	 	  	}
       	 	}
       	 	
          //�ڵ�����
          function node_type($top,$left,$type=0)
          {
          	if(!$type)$type=$this->LINE_node_type;
          	switch(eintval($type)){
          	case 1:
          	       break;
          	case 2:
          	       echo"<v:rect id='_x0000_s1027' style='position:absolute;left:" .$left. "px;top:" .$top."px;width:4px;height:4px; z-index:2' fillcolor='" .$this->LINE_color. "'/>";break;
          	case 3:
          	       echo"<v:oval id='_x0000_s1026' style='position:absolute;left:" .$left. "px;top:" .$top. "px;width:4px;height:4px;z-index:1' fillcolor='".$this->LINE_color."'>";break;
          	default: break;
          }
          	}
       	 	
       	 	//�ı���ʾ
       	 	function show_text($text_left,$text_top,$text_width,$text_height,$text)
       	 	{
       	 		  echo"<v:shape id='_x0000_s1025' type='#_x0000_t202' alt='' style='position:absolute;left:".$text_left. "px;top:" .$text_top. "px;width:" .$text_width. "px;height:".$text_height.";z-index:1'>
       	 		       <v:textbox inset='0px,0px,0px,0px'>
       	 		       <table cellspacing='3' cellpadding='0' width='100%' height='100%'>
       	 		       <tr>
       	 		       <td align='left'>
       	 		       ".$text."
       	 		       </td>
       	 		       </tr>
       	 		       </table>
       	 		       </v:textbox>
       	 		       </v:shape>
       	 		      ";
       	 	}
       	 	
       	 	
    
    }
   ?>