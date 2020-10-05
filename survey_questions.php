<?php 
session_start();
require 'DB_PARAMS/connect.php';
$SurveyID = 0;
$vstr1 = "";
$msg ="";
if (isset($_REQUEST["SurveyID"])) 
{ 
	$SurveyID = $_REQUEST["SurveyID"];
}
$RespodentID = 0;
if (isset($_REQUEST['RespodentID']))
{
	$RespodentID = $_REQUEST["RespodentID"];
}

function getAnswerID($db,$SurveyID)
{
	$sql = "SELECT SurveyQuestions.QuestionID,SurveyAnswers.AnswerID FROM SurveyQuestions 
				LEFT JOIN SurveyAnswers ON SurveyAnswers.QuestionID =SurveyQuestions.QuestionID
				WHERE SurveyID = '$SurveyID'
				ORDER BY SurveyQuestions.QuestionID, SurveyAnswers.Code";
	//echo $sql;
	$result  = sqlsrv_query($db, $sql);
	$AnswerArray = array();
	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{	
		$QuestionID = $myrow["QuestionID"];
		$AnswerID	= $myrow['AnswerID'];
		$AnswerArray[$QuestionID][] = $AnswerID;  
	} 
	return $AnswerArray;	
}

function validateQuestions($db,$SurveyID,$QuestionArray)
{
	$sql = "SELECT SurveyQuestions.QuestionID FROM SurveyQuestions 
				LEFT JOIN SurveyAnswers ON SurveyAnswers.QuestionID =SurveyQuestions.QuestionID
				WHERE SurveyID = '$SurveyID'
				ORDER BY SurveyQuestions.QuestionID, SurveyAnswers.Code";
	//echo $sql;
	$result  = sqlsrv_query($db, $sql);
	$error = 0;
	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{	
		$QuestionID = $myrow["QuestionID"];
		if (!isset($QuestionArray[$QuestionID]))
		{
			$error = 1;
			//echo $QuestionID . '<br>';
		}
	} 
	return $error;	
}

function getSurvey($db, $SurveyID)
{
	$sql = "SELECT * FROM Survey WHERE SurveyID = '$SurveyID'";
	$result  = sqlsrv_query($db, $sql);
	$SurveyArray = array();
	if ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{	
		$SurveyName 	 = $myrow["SurveyName"];
		$WelcomeMessage = $myrow["WelcomeMessage"];
		$EndMessage		 = $myrow["EndMessage"];
		$SurveyArray 	 = $myrow; 
	} 
	return $SurveyArray;
}

if (isset($_REQUEST['save']))
{
	$QuestionArray = array();
	$error = 0;
	foreach ($_REQUEST as $field => $value)
	{	
		//echo "$field => $value <br>";
		$f = substr($field,0, 2);
		if (($f=='RA') OR ($f=='CK') OR ($f=='OQ'))
		{
			$QuestionID = substr($field,2, 100);
			$QuestionArray[$QuestionID] = 1;			
		}
	}
	
	$error = validateQuestions($db,$SurveyID,$QuestionArray);
	if (!$error)
	{
		$AnswerArray = getAnswerID($db,$SurveyID);
		foreach ($_REQUEST as $field => $value)
		{	
			//echo "$field => $value <br>";
			
			$f = substr($field,0, 2);
			if (($f=='RA') OR ($f=='CK'))
			{
				$ValueArray = explode("|",$value);
				for($i = 0; $i <= count($ValueArray); $i++) 
				{
					$QuestionID = substr($field,2, 100);
					if (isset($AnswerArray[$QuestionID][$i]))
					{
						$AnswerID = $AnswerArray[$QuestionID][$i];
						if (isset($ValueArray[$i]))
						{
							if ($ValueArray[$i] == "true")
							{
								$sql = "INSERT INTO SurveyResponses (QuestionID,AnswerID,RespodentID) 
																	  VALUES ('$QuestionID','$AnswerID','$RespodentID')";
								$result  = sqlsrv_query($db, $sql);
							}							
						}
					}
				}									
			} else if ($f=='OQ')
			{
				$ValueArray = explode("|",$value);
				for($i = 0; $i <= count($ValueArray); $i++) 
				{
					if (isset($ValueArray[$i]))
					{
						// echo $ValueArray[$i].'<br>';
						if ($ValueArray[$i] != "")
						{
							$WrittenResponse = $ValueArray[$i];
							$AnswerID =0;
							$sql = "INSERT INTO SurveyResponses (QuestionID,AnswerID,RespodentID,WrittenResponse) 
							                             VALUES ('$QuestionID','$AnswerID','$RespodentID','$WrittenResponse')";
							$result  = sqlsrv_query($db, $sql);							
						}						
					}
				}									
			}
		}
		Header ("Location: survey_completed.php?SurveyID=$SurveyID&RespodentID=$RespodentID"); 
	} else
	{
		$msg = "Please respond to all the questions";
	}
}

$SurveyArray = getSurvey($db, $SurveyID);
?>
<link href="style.css" rel="stylesheet" type="text/css" />
<div class="article">
<h2><?php echo $SurveyArray['SurveyName']; ?></h2>
<h3>Please fill in the Questionnaire.</h3>

<form action="" method="get">
<table width="100%" border="0">
	<tr>
	   <td colspan="4" class="text" scope="col"><?php echo $SurveyArray['WelcomeMessage']; ?></td>
	   </tr>
	<tr>
	   <td colspan="4" class="error" scope="col"><div align="center"><?php echo $msg; ?></div></td>
	   </tr>
	<tr>
		<td width="3%" scope="col">Code</td>
		<td colspan="3" scope="col">Question</td>
	</tr>
	<?php
	$sql = " SELECT SurveyQuestions.QuestionID,SurveyQuestions.Question, SurveyQuestions.QuestionCode,SurveyQuestions.QuestionTypeID
						 ,SurveyAnswers.Code, SurveyAnswers.Answer, SurveyAnswers.AnswerID,SurveyQuestions.QuestionHeaderID
						 , SurveyQuestionHeader.QuestionHeaderCode, SurveyQuestionHeader.QuestionHeader 
				FROM SurveyQuestions 
					LEFT JOIN SurveyAnswers ON SurveyAnswers.QuestionID =SurveyQuestions.QuestionID
					LEFT JOIN SurveyQuestionHeader ON SurveyQuestionHeader.QuestionHeaderID = SurveyQuestions.QuestionHeaderID
				WHERE SurveyQuestions.SurveyID = '$SurveyID'
				ORDER BY SurveyQuestions.QuestionID, SurveyQuestionHeader.QuestionHeaderID, SurveyAnswers.Code";
	$result = sqlsrv_query($db, $sql);
	$QID = "-1";
	$HID = "-1";
   while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
   {
		$QuestionID = $myrow['QuestionID'];
		$Question	= $myrow['Question'];
		$Code			= $myrow['Code'];
		$Answer		= $myrow['Answer'];
		$QuestionCode	= $myrow['QuestionCode'];
		$QuestionTypeID = $myrow['QuestionTypeID'];
		$AnswerID	= $myrow['AnswerID'];
		$QuestionHeaderID = $myrow['QuestionHeaderID'];
		$QuestionHeaderCode = $myrow['QuestionHeaderCode'];
		$QuestionHeader = $myrow['QuestionHeader'];
		
		if ($QuestionID!=$QID)
		{
			$QID = $QuestionID;
			if ($QuestionTypeID==3)
			{
				if ($vstr1=="")
				{
					$vstr1 .= "'&OQ$QuestionID='";
				} else
				{
					$vstr1 .= "+'&OQ$QuestionID='";
				}
			} else
			{
				if ($vstr1=="")
				{
					$vstr1 .= "'&CK$QuestionID='";
				} else
				{
					$vstr1 .= "+'&CK$QuestionID='";
				}
				$value = '';
				if (isset($_REQUEST["CK$QuestionID"]))
				{
					$value 	= $_REQUEST["CK$QuestionID"];
				}
				$vArray = explode('|',$value);	
				$k	= 0;
			}
			if (($QuestionHeaderID) AND ($HID != $QuestionHeaderID))
			{
				$HID = $QuestionHeaderID;
			?>
			<tr>
				<td width="3%" valign="top" scope="col"><?php echo $QuestionHeaderCode; ?></td>
				<td colspan="3" scope="col"><?php echo $QuestionHeader; ?></td>
			</tr>
         <?php
			}			
			if ($QuestionTypeID != 3)
			{ ?>
			<tr>
				<td width="3%" valign="top" scope="col"><?php echo $QuestionCode; ?></td>
				<td colspan="3" scope="col"><?php echo $Question; ?></td>
			</tr>
			<?php
			}
		} 
		if ($QuestionTypeID==1) 
		{          
			$fieldname = 'RA'.$QuestionID.'_'.$AnswerID;
			$checked = "";			
			if ((isset($vArray[$k])) AND ($vArray[$k]=='true'))
			{
				$checked = 'checked'; 
			}
			$k += 1;				
			?>
         <tr>
				<td>&nbsp;</td>
         	<td width="3%"><?php echo $Code; ?></td>             
            <td width="3%"><input name="RA<?php echo $QuestionID; ?>" type="radio" id="RA<?php echo $QuestionID.'_'.$AnswerID; ?>" <?php echo $checked; ?> value="radio"></td>
            <td width="91%"><?php echo $Answer; ?></td>
         </tr>
			<?php 				
			//$vstr1 .= "'&$fieldname='+this.form.$fieldname.checked+";
			$vstr1 .= "+this.form.$fieldname.checked+"."'|'";
		} else if ($QuestionTypeID==2) 
		{
			$fieldname = 'CK'.$QuestionID.'_'.$AnswerID;
			$checked = "";			
			if ((isset($vArray[$k])) AND ($vArray[$k]=='true'))
			{
				$checked = 'checked'; 
			}
			$k += 1;				
			?>
         <tr>
         	<td>&nbsp;</td>
         	<td width="3%"><?php echo $Code; ?></td>         	            
         	<td width="3%"><input name="CK<?php echo $QuestionID; ?>" type="checkbox" value="11" id="CK<?php echo $QuestionID.'_'.$AnswerID; ?>" <?php echo $checked; ?> ></td>
            <td width="91%"><?php echo $Answer; ?></td>
         </tr>
         <?php
				
			//$vstr1 .= "'&$fieldname='+this.form.$fieldname.checked+";				
			$vstr1 .= "+this.form.$fieldname.checked+"."'|'";
		} else if ($QuestionTypeID==3) 
		{
		 	$fieldname = 'OQ'.$QuestionID;
			$myvalue = "";
			if ((isset($_REQUEST[$fieldname])) AND ($_REQUEST[$fieldname]!='true'))
			{
				$value 	= $_REQUEST[$fieldname];
				$vArray = explode('|',$value);
				$myvalue = trim($vArray[0]);
			}				
		 	?>
         <tr>
            <td rowspan="2" valign="top">&nbsp;</td>
            <td><?php echo $QuestionCode; ?></td>
            <td colspan="2"><?php echo $Question; ?></td>
         </tr>
         <tr>
            <td width="3%">&nbsp;</td>
         	<td colspan="2"><textarea name="<?php echo $fieldname; ?>" rows="2" id="<?php echo $fieldname; ?>" style="width:100%"><?php echo $myvalue; ?>
         	</textarea></td>
         </tr>
         <?php				
			//$vstr1 .= "'&$fieldname='+this.form.$fieldname.value+";				
			$vstr1 .= "+this.form.$fieldname.value+"."'|'";
		}  
	} ?>
      <tr>
         <td colspan="4"><?php echo $SurveyArray['EndMessage']; ?></td>
      </tr>
      <tr>
         <td colspan="4"><input name="button2" type="button" class="btn" id="button" value="Save" onClick="loadpage('survey_questions.php?save=1'+
			<?php echo $vstr1; ?>+  
         '&SurveyID=<?php echo $SurveyID; ?>'+
         '&RespodentID=<?php echo $RespodentID; ?>'+
 			'&save=1','mainbar')"/>
            <input name="button3" type="button" class="btn" id="button2" value="Cancel" onclick="loadpage('survey_list.php?i=1','mainbar')"/><?php //echo $vstr1; ?></td>
         </tr>         
</table>
</form>
</div>
