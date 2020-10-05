<?php 
require 'DB_PARAMS/connect.php';
require('GlobalFunctions.php');
require_once('utilities.php');

if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$CreatedUserID = $_SESSION['UserID'];
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );

if (isset($_REQUEST['msg']))
{
	$msg = $_REQUEST['msg'];	
}



$RefID='';
$mdata='';
$mdata2='';
$newSiteValue=0;
$owner='';

$upn=3;

$Balance=0;

$mdata2=getDetails($db,$CreatedUserID);
$mdata=$mdata2[2];
$newArea2=$mdata2[3];
$Balance=$mdata2[4];	
$owner=$mdata2[5];


function getDetails($db,$CreatedBy)
{	
	$params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$mdata='';
	$mdata2=[];
	$newArea=0;
	$owner='';
	
	$bBalance=0;
	$sql="select l.*  from land l join Amalgamation a on a.upn=l.upn where a.CreatedBy=$CreatedBy and a.Posted=0";
	
	$result=sqlsrv_query($db,$sql,$params,$options);
	if(!$result)
	{
		DisplayErrors();
	}
	
	$rows=sqlsrv_num_rows($result);
	
	$i=0;
	
	if ($rows>0)
	{


		while($rws=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
		{

			$RefID=$rws['RefID'];
			$upn=$rws['UPN'];
			$newArea+=(double)$rws['Area'];
			$owner=$rws['LaifomsOwner'];

			$sql="select Balance from fnLastPlotRecord($upn)";
			$result2=sqlsrv_query($db,$sql);
			while($rw=sqlsrv_fetch_array($result2,SQLSRV_FETCH_ASSOC))
			{
				$Balance=$rw['Balance'];
			}

			if((double)$Balance>0){
				$bBalance=$Balance;
			}
			

			$mdata.=
			'<tr>			
				<td>'.$rws['LRN'].'</td>
				<td>'.$rws['PlotNo'].'</td>
				<td>'.number_format($rws['Area'],2).'</td>								
				<td>'.number_format($Balance,2).'</td>
				<td>'.$rws['LaifomsOwner'].'</td>
				<td>
		            <input id="amalgamation" name="amalgamation" type="radio"  value="'.$rws['UPN'].'" onchange="mainPlot(this.form.amalgamation)">
            	</td>				
                <td>
		            <input name="Button" type="button"  value="Remove" 
		                onClick="loadmypage(\'land_amalgamation.php?remove=1&upn='.$rws['UPN'].'&UserID='.$_SESSION['UserID'].'\',\'content\')">
            	</td>
			</tr>';
			
			$i=$i+1;
		}
	}else{		
		$RefID=time();
	}
		
	$mdata2[0]=$RefID;
	$mdata2[1]=$rows;
	$mdata2[2]=$mdata;
	$mdata2[3]=$newArea;
	$mdata2[4]=$bBalance;
	$mdata2[5]=$owner;
	
	return $mdata2;
}



if (isset($_REQUEST['add'])){
	$upn=$_REQUEST['upn'];
	
	if ($upn!=='')
	{		  
		$mdata2=getDetails($db,$CreatedUserID);



		$sql="if not exists(select 1 from Amalgamation where CreatedBy='$CreatedUserID' and upn=$upn and Posted=0) 
			Insert into Amalgamation (upn,MotherUPN,CreatedBy) 
			Values('$upn','$upn','$CreatedUserID')";

		//echo $sql; exit;
		
		$result=sqlsrv_query($db,$sql);
		if(!$result)
		{
			DisplayErrors();
			//echo $sql;
		}else{
			
		}

		$mdata2=getDetails($db,$CreatedUserID);

		$mdata=$mdata2[2];
		$newArea2=$mdata2[3];
	}
}

if (isset($_REQUEST['remove'])){
	$upn=$_REQUEST['upn'];
	$UserID=$_REQUEST['UserID'];

	$sql="delete from Amalgamation where CreatedBy=$UserID and Posted=0 and Upn='$upn'";
	$result=sqlsrv_query($db,$sql);

	$mdata2=getDetails($db,$UserID);
	$mdata=$mdata2[2];
	$newArea2=$mdata2[3];
	
}

if (isset($_REQUEST['remove'])){
	$upn=$_REQUEST['upn'];
	$UserID=$_REQUEST['UserID'];

	$sql="delete from Amalgamation where CreatedBy=$UserID and Posted=0 and Upn='$upn'";
	$result=sqlsrv_query($db,$sql);

	$mdata2=getDetails($db,$UserID);
	$mdata=$mdata2[2];
	$newArea2=$mdata2[3];
	
}

if ($_REQUEST['amalgamate']==1)
{
	// print_r($_REQUEST);
	// exit();

	$mainUPN=$_REQUEST['mainUPN'];
	$newArea=$_REQUEST['newArea'];
	$UserID=$_SESSION['UserID'];
	$newBlock=$_REQUEST['newBlock'];
	$newPlot=$_REQUEST['newPlot'];
	$newLocalAuthorityID=$_REQUEST['newLocalAuthorityID'];
	$newSiteValue=$_REQUEST['newSiteValue'];
	$owner=$_REQUEST['owner'];
	$TitleYear=date('Y');
	$Balance=0;

	$success=0;
	if(sqlsrv_begin_transaction($db)===false)
	{
		$msg=sqlsrv_errors();
		$Sawa=false;
	}

	//Create the ratesPayable

	$Authority=$newLocalAuthorityID;
	$SiteValue=$newSiteValue;

	if($Authority==96 || $Authority==800){
		$RatesPayable=0.02*(double)$SiteValue;					
	}else{
		$RatesPayable=60;
		$excess=0;
		$acres=(double)$Size*2.4765;
		if((double)$acres>5){
			$excess=ceil((double)$acres-5)*10;				
		}
		$RatesPayable+=(double)$excess; 
	}
		
	$PenaltyPayable=.03*(double)$RatesPayable;	
	
	if ($Authority=='0' || $Authority==''){
		$msg="The LocalAuthority for the plot is not properly set";
	}

	//create new plot and get the upn

	$sql="insert into land (lrn,PlotNo,SiteValue,Area,TitleYear,Balance,ratesPayable,localAuthorityID,LaifomsOwner) 
		values('$newBlock','$newBlock','$newSiteValue','$TitleYear','0','$ratesPayable',$newLocalAuthorityID,'$owner') 
		Select SCOPE_IDENTITY() AS ID";

	$rst=sqlsrv_query($db,$sql);
	
	if($rst){
		$newUPN=lastid($rst);

		//state the mother Upn

		//Update the statuses of the other plots as amalgamated
		$sql="select upn from Amalgamation where CreatedBy='$UserID' and posted=0";
		$result=sqlsrv_query($db,$sql);

		while ($row=sqlsrv_fetch_array($result,	SQLSRV_FETCH_ASSOC)) 
		{
			$upn=$row['upn'];	

			$sql="update Land set Active=0,Status=3 where upn=$upn";
			$result=sqlsrv_query($db,$sql);
			
			if($result)
			{
				$success=1;
			}
		}

		$sql="update Amalgamation set MotherUPN='$newUPN',Posted=1 where CreatedBy='$UserID' and posted=0";
		$result=sqlsrv_query($db,$sql);
		if($result){
			$success=1;
		}

	}else{
		echo $sql;
		DisplayErrors();
		$msg='there was a problem creating the new plot';
		$success==0;
	}

	if($success==1)
	{
		$sql="update Amalgamation set posted=1 where CreatedBy='$UserID' and posted=0";
		$result=sqlsrv_query($db,$sql);

		$rst=SaveTransaction($db,$UserID,"Amalgamated plots under new plot number (UPN):  ".$newUPN);

		//Commit The Transaction
		sqlsrv_commit($db);

		$msg="Plots Amalgamated Successfully, new plot upn is ".$newUPN;
	}else
	{
		sqlsrv_rollback($db);
	}	
}

?>
<script type="text/javascript">
	function mainPlot(frm){
		mainplot=frm.value;
		document.getElementById("mainUPN").value=mainplot;
	}
</script>
<body>
<div class="example">
	<form class="ui form" action="" name="fnForm2" id="fnForm2">
    <h3>Amalgamate Plots </h3>
	<fieldset>
    <div>
      <table class="ui selectable attached basic table">
        <thead>
		  <tr>
			  <td colspan="7" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
          <tr>
            <th class="two wide">Lrn</th>            
            <th class="two wide">PlotNo</th>
            <th class="two wide">Size</th>
			<th class="two wide">Balance</th>
            <th class="four wide">Owner</th>
            <td class="one Wide">Main Plot</td>
            <td class="one Wide"></td>
          </tr>
        </thead>
        <tbody id="tbody"> 
        	<tr>
              <td>
              	<label>&nbsp</label>
                <div class="input-control text" data-role="input-control">
                    <input type="text" id="upn" name="upn"  ></input>
                    <button class="btn-clear" tabindex="-1"></button>
                </div>
              </td>
              <td>
              	<label>&nbsp</label>
              	<div class="button">
              	<input name="button" type="button" onclick="loadmypage('land_amalgamation.php?'+
              										'&add=1'+
              										'&upn='+this.form.upn.value+                               
                                                    '','content','loader','listpages','','')" value="ADD PLOT">
                </div>
              </td>
          	  <td>
          	  	<label>Main Plot</label>
          	  	<div class="input-control text" data-role="input-control">
                    <input type="text" id="mainUPN" name="mainUPN" disabled="disabled"></input>                    
                </div>
          	  </td>
          	  <td>
          	  	
          	  </td>
          	  <td></td>
          	  <td></td>
          	  <td></td>
          </tr>
        	<?php 
        		echo $mdata;
        	?>
        	<hr>
        	<tr>
        		<td colspan="7">
        			<table class="striped3n" width="100%">
        			<thead>
        				<tr>
        					<th colspan="5">THE NEW PLOT DETAILS</th>
        				</tr>
        				<tr>
        					<th class="text-left">New Block Number</th>
                            <th class="text-left">New plot Number</th>
                            <th class="text-left">Authority</th>
                            <th class="text-left">New Site Value</th>
                            <th class="text-left">New Plot Area</th>                            
                            <th class="text-left">Local Authothority</th>
        				</tr>        				
        			</thead>
        			<tbody>
        				<tr>
			        		<td>
			        			<label>&nbsp</label>
			        			<div class="input-control text" data-role="input-control">
			        				<input type="text" id="newBlock" name="newBlock" value=""/>
			        			</div>
			        		</td>
			        		<td>
			        			<label>&nbsp</label>
			        			<div class="input-control text" data-role="input-control">
			        				<input type="text" id="newPlot" name="newPlot" value=""/>
			        			</div>
			        		</td>
			        		<td>
			        			<label>&nbsp</label>
								<div class="input-control select" data-role="input-control">						
									<select name="LocalAuthorityID"  id="LocalAuthorityID">
			                            <option value="0" selected="selected"></option>
			                            <?php 
			                            $s_sql = "SELECT * FROM LocalAuthority ORDER BY LocalAuthorityID";
			                            
			                            $s_result = sqlsrv_query($db, $s_sql);
			                            if ($s_result) 
			                            { //connection succesful 
			                                while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
			                                {
			                                    $s_id = $row["LocalAuthorityID"];
			                                    $s_name = $row["LocalAuthorityName"];
			                                    if ($newLocalAuthorityID==$s_id) 
			                                    {
			                                        $selected = 'selected="selected"';
			                                    } else
			                                    {
			                                        $selected = '';
			                                    }												
			                                 ?>
				                            <option value="<?php echo $s_id; ?>" <?php echo $selected; ?>><?php echo $s_name; ?></option>
				                            <?php 
			                                }
			                            }
			                            ?>
			                      </select>							
							</div>				
							</td>				        		
			        		<td>
			        			<label>&nbsp</label>
			        			<div class="input-control text" data-role="input-control">
			        				<input type="text" id="newSiteValue" name="newSiteValue" value="<?php echo $newSiteValue; ?>"/>
			        			</div>
			        		</td>
			        		<td>
			        			<label>&nbsp</label>
				          	  	<div class="input-control text" data-role="input-control">
				                    <input type="text" id="newArea" name="newArea" value="<?php echo number_format($newArea2,2); ?>" disabled="disabled"/>  
				                </div>
			        		</td>
			        		<td>
			        			<label>&nbsp</label>
			        			<div class="button">
				        			<input type="button" value="AMALGAMATE" onclick="

				        			var plotwithbalance = document.getElementById('balance').value;
				        			var newblock=document.getElementById('newBlock').value;
				        			var newplot=document.getElementById('newPlot').value;
									
									// if(plotwithbalance>0)
									// {
									// 	alert('You can not amalgamate a plot with Rates Balance');
									// 	exit;
									// }

									if(newblock=='')
									{
										alert('the new Block Number must be stated');
										exit;
									}else if(newplot==''){
										alert('the new Plot Number must be stated');
										exit;
									}
				        			
				        			loadmypage('land_amalgamation.php?amalgamate=1'+
												'&amalgamate=1'+
												'&UserID='+<?php echo $CreatedUserID; ?>+
												'&mainUPN='+this.form.mainUPN.value+
												'&newArea='+this.form.newArea.value+ 
												'&newBlock='+this.form.newBlock.value+ 
												'&newPlot='+this.form.newPlot.value+ 
												'&newLocalAuthorityID='+this.form.LocalAuthorityID.value+  
												'&newSiteValue='+this.form.newSiteValue.value+
												'&owner='+this.form.owner.value+                            
				                            '','content','loader','listpages','','')" />
				                </div>

				                <label>&nbsp</label>
			        			<input type="hidden" name="balance" id="balance" value="<?php echo $Balance; ?>"></input>
			        			<input type="hidden" name="owner" id="owner" value="<?php echo $owner; ?>"></input>
			        		</td>
			        				        		
			        	</tr>
        			</tbody>        		
        		</table>
        		</td>       	
        	</tr>
        	                     
        </tbody>
        
      </table>
    </div>

	
	</fieldset>							
</form>
</div>
</body>


    