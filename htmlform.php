<?php
class HtmlForm {

	public static $masterPrintArr;
	public static $fullArray;
	public $allFields;
	
	public static function startForm($name,$target,$method)
	{
		if(!$method) $method = "post";
		if(!$name)  return 'error name must be supplied.';
		if(!$target) $target = $_SERVER['PHP_SELF'];
		return "<form name='".$name."' action='".$target."' method='".$method."' class='niceform' >";
	}

	public static function input($name,$value = NULL,$type,$size=NULL,$extraAttributes = NULL)
	{	
		if(is_array($extraAttributes))
		{
			foreach($extraAttributes as $attr => $avalue)
			{
				if($attr == "checked" && $avalue==$value)
				{
					$extra = ' checked=checked';
				}
				else
				{
					$extra = '';
				}
			}
		}		
		//bk_debug($extraAttributes);
		$inputField = "<input id='".$name."' name='".$name."' type='".$type."' value='".$value."' size='".$size."' ".$extra." />";
		return $inputField;
	}
	
	public static function addInput($name, $value,$element, $size=NULL, $extraAttributes = NULL, $radioValueYN01 = NULL)
	{
		//hack to convert yes/no to 1/0
		if($radioValueYN01 == true)
		{
			$value = strtolower($value);
			switch($value)
			{
				case 'yes':
					$value=1;
					break;
				case 'no':
					$value=0;
					break;
			}
		}
		 return HtmlForm::input($name,$value,$element,$size, $extraAttributes);
	}
		
	
	public static function addTextarea($name, $body = '', $rows, $cols,$maxlength=false)
	{
		if($maxlength)
		{
			//$addMaxlength = " maxlength=\"$maxlength\"  class=\"wordLimited\" ";
		}
		return '<textarea id="'.$name.'" name="'.$name.'" rows="'.$rows.'" cols="'.$cols.'" '.$addMaxlength.'>'.$body.'</textarea>';
	}
	
	public static function addSelectBox($name, $options, $current=NULL, $blank = true, $multi = false, $keyvalue = NULL,$addOptionClass = false,$class=false)
	{
		global $db;
		//bk_debug($current);
		$multiple ="";
		$select="";
			if ($multi)
			{
				$multiple = " multiple=\"multiple\" size=\"$multi\"";
				$name .= '[]';
				$extraTitle = " <span style=\"font-wieght:normal;font-size:0.8em;\">(Hold down CTRL or APPLE to select more than one)</span><br />";
			}
			
			if ($blank) 
			{
				if (is_bool($blank))
				{
					$optionsArray[] =  '<option value="">Select&hellip;</option>';
				}
				else
				{
					$optionsArray[] = '<option value="">'.$blank.'</option>';
				}
			}
			
			foreach ($options as $optionValue => $text)
			{		
				
				if (is_array($current))
				{
					if($keyvalue==true)
					{
						$select = (in_array($optionValue, $current)) ? ' selected="selected"' : '';
						//bk_debug($current);
					}
					else
					{
						$select = (in_array($text, $current)) ? ' selected="selected"' : '';
					}
				}
				elseif ($current)
				{
					if($keyvalue)
					{
						$select = $optionValue == $current ? ' selected="selected"' : '';
					}
					else
					{
						$select = $text == $current ? ' selected="selected"' : '';
					}
				}
				
				//if there are groups in the select box must specify optgroup as select value
				if(substr($optionValue,0,12) === 'optgroupopen')
				{
					$optionsArray[] = '<optgroup '.$addOptionClass.' label="'.$text.'">';
				}
				elseif(substr($optionValue,0,13) === 'optgroupclose')
				{
					$optionsArray[] = '</optgroup>';
				}
				else
				{
					if($keyvalue)
					{
						$optionsArray[] = "<option value='".$optionValue."' ".$select.">".$text."</option>";
					}
					else
					{
						$optionsArray[] = "<option value='".$text."' ".$select.">".$text."</option>";
						
					}
				}
				//$optionsArray[] = "<option value='".$text."' ".$select.">".$text."</option>";
			}
			$eachOptions = implode($optionsArray);
			
			return  "$extraTitle<select$multiple id=\"$name\" name=\"$name\" class=\"$class\">".$eachOptions."</select>";
	}
	
	public function printApplication($inArray)
	{
		//if(is_array($inArray))	
		{
			foreach($inArray as $key => $val)
			{
				bk_debug($key);
				bk_debug($value);
			}	
		}
	}
		
	public static function addLabel($inputname, $text,$required = false,$extraText = false)
	{	
		//HtmlForm::addToMasterPrintArr($inputname,$text); 
		$requiredOn = $required ? '<span class="requiredfield">* required</span>' : '';
		$extraText = $extraText ? '<br /><span style="font-weight:normal;font-size:0.8em;">'.$extraText.'</span>' : "";
		$label = '<label for="'.$inputname.'" id="'.$inputname.'">'.$text.'</label>' . $requiredOn  .$extraText;
		return $label;
	}
	
	/*public static function addToMasterPrintArr($key,$value)
	{	
		HtmlForm::$masterPrintArr[$key] = $value;
		bk_debug(HtmlForm::$masterPrintArr);
	}
	
	public static function storeFields($buildArray)
	{
		if(is_array($buildArray))
		{
			HtmlForm::$fullArray = array_merge($buildArray,HtmlForm::$masterPrintArr);
		}
		else
		{
			HtmlForm::$fullArray = HtmlForm::$masterPrintArr;
		}
		//bk_debug(HtmlForm::$fullArray);
		return HtmlForm::$fullArray;	
	}	*/
	
	public static function addText($text)
	{			
		$label = '<p><strong>'.$text.'</strong></p>';
		return $label;
	}
	
	
	public static function endForm()
	{
		return "</form>";
	}

	public static function addDateSelector($arg_name,$current = NULL) 
	{
		$arg_year_start = 1999;
		$arg_year_end = date("Y") + 10;
		
		if($current)
		{
			$year = date("Y", strtotime($current));
		  $month = date("m", strtotime($current));
		  $day = date("d", strtotime($current));
		  //bk_debug($year);
		}
		else
		{
		  $year = strftime("%Y", time());
		  $month = strftime("%m", time());
		  $day = strftime("%d", time());
		}	 
	    
	    $dateArr=array();
	    
	    $dateArr[]= "<select name=\"day_$arg_name\" id=\"day$arg_name\">\n";
	    $dateArr[]="<option value=\"0\">DD</option>\n";
	    for ($i = 1; $i <= 31; $i++) {
	        if ((int)$day == $i) {
	            $selected = ' selected="selected"';
	        }
	        else {
	             $selected = '';
	        }
	        $dateArr[]="<option value=\"$i\"$selected>$i</option>\n";
	    }
	    
	    $dateArr[]= "</select>\n  <select name=\"month_$arg_name\" id=\"month$arg_name\">\n";
	    $dateArr[]="<option value=\"0\">MM</option>\n";
	    for ($i = 1; $i <= 12; $i++) {
	        if ((int)$month == $i) {
	            $selected = ' selected="selected"';
	        }
	        else {
	             $selected = '';
	        }
	        $dateArr[]="<option value=\"$i\"$selected>$i</option>\n";
	    }
	    
	    $dateArr[]=  "</select>\n <select name=\"year_$arg_name\" id=\"year$arg_name\">\n";
	    if ((int)$year == 0) {
	        $selected = ' selected="selected"';
	    }
	    $dateArr[]= "    <option value=\"0\"$selected>YYYY</option>\n" ;
	    
	    for ($i = $arg_year_start; $i <= $arg_year_end; $i++) {
	        if ((int)$year == $i) {
	            $selected = ' selected="selected"';
	        }
	        else {
	             $selected = '';
	        }
	        $dateArr[]="<option value=\"$i\"$selected>$i</option>\n";
	    }
	    
	    //print "</select> <a href=\"#\" onclick=\"return popUpCalender('$arg_name',2003,4);\"><img src=\"/images/calendar.png\" alt=\"view calendar\" border=\"0\" width=\"16\" height=\"16\" /></a>\n";
	     $dateArr[]="</select>\n";
	     $dateArr[]="<input type=\"hidden\" name=\"datefields[]\" value=\"$arg_name\" />\n";
	     $dateSelect = implode($dateArr);
	     return $dateSelect;
	}

	#HTML RENDER METHODS
	public static function sectionHead($text)
	{
		return "<h2 class='sectionhead'>".$text."</h2>";
	}
	
	public static function secondarySectionHead($text,$extrainfo=false)
	{
		$text = "<h2 class='secondarySectionHead'>".$text."</h2>";
		if($extrainfo) $text .= "<p>$extrainfo</p>";
		return $text;
	}
	
	public function printArrayTable($fieldsArray,$layout=NULL)
	{
		if(is_array($fieldsArray))
		{
			echo '<table cellspacing="0" cellpading="0" class="sectiontable">';
			switch($layout)
			{
				case 'wide':
				
					foreach($fieldsArray as $label => $input)
					{
						echo '<tr>';
							echo '<td class="labeltd">';
								echo '<span class="labelfield">'.$label.'</span>';
								echo '<br style="clear:both;"/>';							
								echo $input;
							echo '</td>';
						echo '</tr>';
						
					}
					break;
					
				default:
					foreach($fieldsArray as $label => $input)
					{
						echo '<tr>';
							echo '<td class="labeltd">';
								echo '<span class="labelfield">'.$label.'</span>';
							echo '</td>';
							echo '<td class="inputtd">';
								echo $input;
							echo '</td>';
						echo '</tr>';
					}
					break;
			}
			echo '</table>';
		}
		else
		{
			echo "<p>ERROR: not an array</p>";
		}
	}
	
	public function printComplexArrayTable($fieldsArray,$categories,$extrasArray = NULL)
	{
		if(is_array($extrasArray))
		{
			echo '<table cellspacing="0" cellpading="0" class="instructionstable">';
				foreach($extrasArray as $a)
				{
					echo '<tr>';
						echo '<td class="labeltd">';
							echo $a;
						echo '</td>';
					echo '</tr>';
				}
			echo '</table>';
		}
		
		if(is_array($fieldsArray))
		{
			echo '<p><span class="requiredfieldComplex">All amounts to be in GB pounds.</span></p>';
			echo '<table cellspacing="0" cellpading="0" class="sectiontable controltdwidth">';
			echo '<tr><td>Category</td><td class="wider">Item<br />e.g. radio collars</td><td>Number of items <span class="requiredfieldComplex">(must be numeric)</span></td><td>Cost <span class="requiredfieldComplex">(must be numeric)</span></td><td>Total <span class="requiredfieldComplex">(must be numeric)</span></td></tr>';
			$i=0;
			//bk_debug($fieldsArray);
			foreach($fieldsArray as $label => $input)
			{
				echo '<tr class="tablerow">';
				
					echo '<td class="labeltd">';
						echo $categories[$i];
					echo '</td>';
					
					echo '<td class="inputtd wider">';
						
						echo $input[0];
						echo "<div class='moreinfo'>";
						echo $input[4];
						echo $input[8];
						echo $input[12];
						echo $input[16];
						echo "</div>";
					echo '</td>';
					echo '<td class="inputtd">';
						echo $input[1];
						echo "<div class='moreinfo'>";
						echo $input[5];
						echo $input[9];
						echo $input[13];
						echo $input[17];
						echo "</div>";
					echo '</td>';
					echo '<td class="inputtd">';
						echo $input[2];
						echo "<div class='moreinfo'>";
						echo $input[6];
						echo $input[10];
						echo $input[14];
						echo $input[18];
						echo "</div>";
					echo '</td>';
					echo '<td class="inputtd">';
						echo $input[3];
						echo "<div class='moreinfo'>";
						echo $input[7];
						echo $input[11];
						echo $input[15];
						echo $input[19];
						echo "</div>";
					echo '</td>';
					//echo '<td><a href="#" class="addrow">addrow</a></td>';
					echo '<td><a href="#" class="showmore">Add another</a></td>';
				echo '</tr>';
				$i++;
			}
			echo '</table>';
			//echo "<p class='clickmoreinfo'>Click here if you require more/less input fields</p>";
		}
		else
		{
			echo "<p>ERROR: not an array</p>";
		}
	}

}