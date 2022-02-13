<?php 
// session_start(); // on commence une session
include('inc/resconnect.php');
?>
<!doctype html>
<html>
<head>
<title>Resolutions and decisions</title>
<link href="css/resolutions.css" rel="stylesheet" type="text/css" media="screen" />
<link href="css/print.css" rel="stylesheet" type="text/css"  media="print" />	
</head>
<!-- 26.01.2022 -->
<body>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST")
{	// define variables and set to empty values
	//$seladdressee = $seldeadline = $selectedInstrument = $selecteddpt = $seldivision = $selltg = $selectedso  = "";
	$seladdressee = $seldeadline = $selectedInstrument = $selectedecs = $selectedecstat = "";
  // collect value of input fields
	$WMOAddressee= $mysqli -> real_escape_string (htmlspecialchars ($_POST['Addressee']));
	$WMODeadline= $mysqli -> real_escape_string (htmlspecialchars ($_POST['Ecdeadline']));
	$WMOInstrument= $mysqli -> real_escape_string (htmlspecialchars ($_POST['Instrument']));
	$WMOECS = $mysqli -> real_escape_string (htmlspecialchars ($_POST['ECS']));
	$ECstat= $mysqli -> real_escape_string (htmlspecialchars ($_POST['ECstatus']));

	// xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	$seladdressee = $WMOAddressee;
	$seldeadline = $WMODeadline;
	$selectedInstrument = $WMOInstrument;
	$selectedecs = $WMOECS;
	$selectedecstat =$ECstat;

	
}	
?>	
<div id="wrapper">
	<div id="header">
	<img src="wmologo2016_fulltext_horizontal300x101_rgb_en.png" width="300" height="101" alt="" style="float: left;margin-right: 20px; margin-bottom:10px;"/>
	<h1>Requests made by Congress as from Cg-Ext(2021) and the Executive Council as from EC-73</h1>
		
	</div>
	<div id="nav">
	<p><a href="index.php"> >> WMO Congress, Executive Council, Regional Associations and Technical Commissions Resolutions, Decisions and Recommendations in force</a></p>
	</div>
	<div id="search">
	<div id="part1">
	<form method="post"  action="requests.php">
<!-- ***************** Addressee / EC-Sesion Deadline / instruments  ************************ -->		
		
<!-- ***************** Addressee ************************ -->		
		<div id="addressee">
		<h2>Addressees</h2>

	    <select name="Addressee" id="Addressee" onChange="submit()">
					<option value="" > ------ Please Select Addressee ------ </option>
					<option value="All" > All </option>
		<?php	
			 		$addr= $mysqli->query('SELECT distinct Addressee FROM requests ORDER BY Addressee ASC') or die ('Error: ' . mysqli_error($mysqli));
			 
					while ($addressee = mysqli_fetch_array($addr))
						{
					?>
						<option value="<?php echo $addressee['Addressee'];?>"<?php if ($addressee['Addressee']==$seladdressee){echo 'selected';}else {echo'';}?>> <?php echo nl2br($addressee['Addressee']); ?></option>
			  		<?php
						}				
				 ?>
  		</select>	
		</div>
<!-- *****************  END Addressee ************************ -->			
<!-- ***************** EC Session deadline ************************ -->

	<?php 	
	// On recupere la valeur Addressee choisie
	//////////// Queries to feed EC Session deadline from Addressee /////////////////////
	if (isset($seladdressee) && $seladdressee !='All' && $seladdressee !='') // A specific addressee is selected
		{
		$sessdead= $mysqli->query('SELECT distinct Deadline FROM requests WHERE Addressee =\''.$seladdressee.'\' ORDER BY Addressee ASC') or die ('Error: ' . mysqli_error($mysqli));
		}
	
	else
		{
		$sessdead= $mysqli->query('SELECT distinct Deadline FROM requests ORDER BY Deadline ASC') or die ('Error: ' . mysqli_error($mysqli));
	 	//echo '<br/>Body session not set<br/>';
	
		}
	
	// Checks if there is a result in the EC session deadline scroll-down menu //
	$nbresultsessdead = mysqli_num_rows($sessdead);

	?>
	<div id="ecdeadline">
	<h2>Deadlines</h2>
	  <select name="Ecdeadline" id="Ecdeadline" onChange="submit()">
					<option value=""> ------ Select a Deadline ------ </option>
		<?php 
		  if ( $nbresultsessdead > 1) {echo'<option value="All"> All Sessions deadlines </option>';} else {echo '';} // if there is only 1 deadline after filtering we dont display all
				
			while ($deadline = mysqli_fetch_array($sessdead))
				 {
					?>
				
		  <option value="<?php echo $deadline['Deadline'];?>"<?php if ($deadline['Deadline']==$seldeadline && $deadline['Deadline']!=''){echo 'selected';} else {echo'';}?>> <?php echo nl2br($deadline['Deadline']); ?></option>
					<?php
				 }
			 ?>
  		</select>
		</div>
	<!-- ***************** EC Session deadline  ************************ -->	
	
	<!-- ***************** Instrument ************************ -->
	<?php
	// To feed the instrument scroll-down menu //

	if (isset($seladdressee) && $seladdressee!='' && $seladdressee!='All' && isset($seldeadline) && $seldeadline !='' && $seldeadline !='All' ) // If  addressee and Ec session deadline sont selectionnes
		{
		$selInstrument= $mysqli->query('SELECT distinct Instrument FROM requests where Addressee =\''.$seladdressee.'\' AND Deadline =\''.$seldeadline.'\' ORDER BY Instrument ASC') or die ('Error: ' . mysqli_error($mysqli));
		//echo '<br/> Body & Session selected<br/>';
		}
	elseif (isset($seladdressee) && $seladdressee!='All' && $seladdressee!=''  && (!isset($seldeadline) || $seldeadline ='' || $seldeadline = 'All')) // Only Addressee is selected
		{
		$selInstrument= $mysqli->query('SELECT distinct Instrument FROM requests where Addressee =\''.$seladdressee.'\' ORDER BY Instrument ASC') or die ('Error: ' . mysqli_error($mysqli));
		//echo '<br/>Only Body selected<br/>';
		}
	elseif ((!isset($seladdressee) || $seladdressee ='All' || $seladdressee='') && isset($seldeadline) && $seldeadline !='' && $seldeadline !='All') // Only Ec session deadline is selected
		{
		$selInstrument= $mysqli->query('SELECT distinct Instrument FROM requests where Deadline =\''.$seldeadline.'\' ORDER BY Instrument ASC') or die ('Error: ' . mysqli_error($mysqli));
		//echo '<br/> Only Session selected<br/>';
		}
	else 
		{
		$selInstrument= $mysqli->query('SELECT distinct Instrument FROM requests ORDER BY Instrument ASC') or die ('Error: ' . mysqli_error($mysqli));
		
		}

	$nbresultinstrument = mysqli_num_rows($selInstrument);
	?>	
	<div id="instr">
	<h2>Instruments</h2>
	 <select name="Instrument" id="Instrument"  onChange="submit()">
					<option value=""> ------ Select an Instrument ------ </option>
					
		 <?php if ($nbresultinstrument > 1) {echo'<option value="All"> All Instruments</option>';}	else {echo '';} ?>
	<?php				
					while ($theinstrument = mysqli_fetch_array($selInstrument))
				 {
					?>
					
		  <option value="<?php echo $theinstrument['Instrument'];?>"<?php if ($theinstrument['Instrument']==$selectedInstrument){echo 'selected';}else {echo'';}?>> <?php echo nl2br($theinstrument['Instrument']); ?></option>
		 
					<?php
				 }
				 	?> 		 			
		 </select>
		 </div>
	</form>
	</div> <!-- part 1 -->

	<div id="part2">
	<form method="post" action="requests.php">
	<!-- ***************** Ec Session / Status ************************ -->
	
	<div id="ecs">	
	<h2>Sessions </h2>

	<?php
	// To feed the EC-Session scroll-down menu // 
	
	$ecsess= $mysqli->query('SELECT distinct ECsession FROM requests ORDER BY ECsession ASC') or die ('Error: ' . mysqli_error($mysqli));
		

	?>
	
	  <select name="ECS" id="ECS" onChange="submit()">
					<option value=""> ------ Select a Session ------ </option>
					<option value="All"> All Sessions</option>
		<?php
				
					//while ($ecsession = mysqli_fetch_array($ecsess))
		  		while ($result = mysqli_fetch_array($ecsess))
				 {
					?>
					<option value="<?php echo $result['ECsession'];?>" <?php if ($result['ECsession']== $selectedecs){echo 'selected';} else {echo'';}?>> <?php echo nl2br($result['ECsession']); ?>
		  			</option>
					<?php
										
				 }
				 ?>
  		</select>
	</div>
	 	<!-- *****************Status ************************ -->
	<?php 	
	// On recupere la valeur EC Status choisi

	//////////// Queries to feed EC Status /////////////////////
		
	//if (isset($selectedecstat) && $selectedecstat !='All' && $selectedecstat !='') // A specific Status is selected
	if (isset($selectedecs) && $selectedecs !='All' && $selectedecs !='') // A specific EC session is selected	$selectedecs
		{/**/
		$reqstat= $mysqli->query('SELECT distinct Status FROM requests WHERE ECsession =\''.$selectedecs.'\' ORDER BY  Status ASC') or die ('Error: ' . mysqli_error($mysqli));
	}
	else
		{
		$reqstat= $mysqli->query('SELECT distinct Status FROM requests WHERE Status !=\'\' ORDER BY Status ASC')or die ('Error: ' . mysqli_error($mysqli));
		}
/*	*/	
	// Checks if there is a result in the unit scroll-down menu //
	$nbresultdiv= mysqli_num_rows($reqstat);
	?>

	<div id="ecstatus">
		<h2>Status</h2>
	  <select name="ECstatus" onChange="submit()">
					<option value=""> ------ Select Status ------ </option>
		  			<!--<option value="All" selected >All units</option>
		  			 <option value="All"> All Status </option> -->
		<?php if ($nbresultdiv > 1) {echo'<option value="All"> All Status</option>';} else {echo '';} // if there is only 1 unit after filtering we dont display all ?>
						
		<?php
  
					while ($selstat = mysqli_fetch_array($reqstat))
				 	{
					 ?>
		  
					<option value="<?php echo $selstat['Status'];?>" <?php if ($selstat['Status']== $selectedecstat){echo 'selected';} else {echo'';}?>> <?php echo nl2br($selstat['Status']); ?></option>
					
		  	<?php
				 	}
					
				 ?>
  		</select>
		</div>
	</form>
		<br/><br/>
	</div> <!-- part2 -->
	


</div> <!-- Search -->
	
 <!--////////////////////////// Results ////////////////////////////// -->	
	<div id="results">
		
<?php
		
/* //////////// QUERIES and RESULTS //////////////*/
		
		
/* //////////// QUERIES //////////////*/	
		
		//////////// Addressee////////////////////////
	// Only Addressee is selected and is != from All
	 if ($seladdressee!=''  && $seladdressee!='All' && $seladdressee<> 1 &&  ($seldeadline =='' || $seldeadline =='All'|| $seldeadline == 1) &&  ($selectedInstrument =='' || $selectedInstrument =='All') && ($selectedecs =='' || $selectedecs =='All') && ($selectedecstat =='' || $selectedecstat =='All') )	
	
		{
		/* Test*/
		 $theresult= $mysqli->query('SELECT requests.Addressee, requests.Instrument, requests.ECsession, requests.Request, requests.Deadline, requests.Status,requests.Verification,
		 resolutions7.link ,resolutions7.title, resolutions7.year
		 FROM requests INNER JOIN resolutions7 on requests.Instrument = resolutions7.reference  WHERE  requests.Addressee =\''.$seladdressee.'\'  ORDER BY requests.Instrument') or die ('Error: ' . mysqli_error($mysqli));//
		 
		 
		/* works 25-11-2021 $theresult= $mysqli->query('SELECT * FROM requests  WHERE  requests.Addressee =\''.$seladdressee.'\'  ORDER BY requests.Instrument') 
			or die ('Error: ' . mysqli_error($mysqli));*/

			// Count number of results of my query
			$nbresult = mysqli_num_rows($theresult);

		echo '<div id="searchresultsstats">Selected Addressee : <strong>'.$seladdressee.'</strong> <br/>
		<span style="color:#FF0000;">Number of entries: '.$nbresult.'</span><br/></div>';				
			}
	// Only Addressee  is selected and is == All !!! All other variables should be set to ''
	else if ($seladdressee!=''  && ($seladdressee=='All' || $seladdressee== 1) && $seldeadline ==''  && $selectedInstrument =='' && ($selectedecs =='' || $selectedecs =='All') && ($selectedecstat =='' || $selectedecstat =='All') )	
	
		{
			$theresult= $mysqli->query('SELECT * from requests INNER JOIN resolutions7 on requests.Instrument = resolutions7.reference  WHERE  requests.Addressee !=\'\'   ORDER BY requests.Instrument') //or die (mysql_error());

			or die ('Error: ' . mysqli_error($mysqli));

			// Count number of results of my query
			$nbresult = mysqli_num_rows($theresult);
			if ($seladdressee== 1 &&  ($seldeadline =='' || $seldeadline =='All') &&  ($selectedInstrument =='' || $selectedInstrument =='All') )
			{
			echo '<div id="searchresultsstats">Selected Addressee : <strong> All</strong><br/>
			<span style="color:#FF0000;">Number of entries: '.$nbresult.'</span><br/></div>';				
			}
			else
			{echo '<div id="searchresultsstats">Selected Addressee : <strong>'.$seladdressee.'</strong><br/>
			<span style="color:#FF0000;">Number of entries: '.$nbresult.'</span><br/></div>';	}
		}
	// Only Deadline is selected and is != from All
	else if ($seldeadline !='' && $seldeadline !='All' &&  ($seladdressee=='' || $seladdressee=='All' || $seladdressee== 1) && ($selectedInstrument =='' || $selectedInstrument =='All') && ($selectedecs =='' || $selectedecs =='All') && ($selectedecstat =='' || $selectedecstat =='All') )	
	
		{
			$theresult= $mysqli->query('SELECT * from requests INNER JOIN resolutions7 on requests.Instrument = resolutions7.reference  WHERE  requests.Deadline =\''.$seldeadline.'\'   ORDER BY requests.Instrument, requests.ECsession') //or die (mysql_error());

			or die ('Error: ' . mysqli_error($mysqli));

			// Count number of results of my query
			$nbresult = mysqli_num_rows($theresult);

		echo '<div id="searchresultsstats">Selected Deadline: <strong>'.$seldeadline.'</strong> <br/>
		<span style="color:#FF0000;">Number of entries: '.$nbresult.'</span><br/></div>';				
			}	
		
	// Only deadline is selected and is == from All
	else if ($seldeadline !='' && $seldeadline =='All' &&  ($seladdressee=='' || $seladdressee=='All' || $seladdressee== 1) && ($selectedInstrument =='' || $selectedInstrument =='All') && ($selectedecs =='' || $selectedecs =='All') && ($selectedecstat =='' || $selectedecstat =='All') )	
	
		{
			$theresult= $mysqli->query('SELECT * from requests INNER JOIN resolutions7 on requests.Instrument = resolutions7.reference  WHERE  requests.Deadline !=\'\'   ORDER BY requests.Instrument, requests.ECsession') //or die (mysql_error());

			or die ('Error: ' . mysqli_error($mysqli));

			// Count number of results of my query
			$nbresult = mysqli_num_rows($theresult);

		echo '<div id="searchresultsstats">Selected Deadline: <strong> All</strong> <br/>
		<span style="color:#FF0000;">Number of entries: '.$nbresult.'</span><br/></div>';				
			}

	// Only Instrument is selected and is != from All
	else if ($selectedInstrument !='' && $selectedInstrument !='All' && ($seldeadline =='' || $seldeadline =='All') &&  ($seladdressee=='' || $seladdressee=='All' || $seladdressee== 1) && ($selectedecs =='' || $selectedecs =='All') && ($selectedecstat =='' || $selectedecstat =='All'))	
	
		{
			$theresult= $mysqli->query('SELECT * from requests INNER JOIN resolutions7 on requests.Instrument = resolutions7.reference WHERE  requests.Instrument =\''.$selectedInstrument.'\'  ORDER BY requests.Instrument, requests.ECsession') //or die (mysql_error());

			or die ('Error: ' . mysqli_error($mysqli));

			// Count number of results of my query
			$nbresult = mysqli_num_rows($theresult);

		echo '<div id="searchresultsstats">Selected Instrument: <strong>'.$selectedInstrument.'</strong> <br/>
		<span style="color:#FF0000;">Number of entries: '.$nbresult.'</span><br/></div>';				
			}	
		
		// Only Instrument is selected and is == to All
	else if ($selectedInstrument !='' && $selectedInstrument =='All' && ($seldeadline =='' || $seldeadline =='All') &&  ($seladdressee=='' || $seladdressee=='All' || $seladdressee== 1) && ($selectedecs =='' || $selectedecs =='All') && ($selectedecstat =='' || $selectedecstat =='All') )	
	
		{
			$theresult= $mysqli->query('SELECT * from requests INNER JOIN resolutions7 on requests.Instrument = resolutions7.reference  WHERE  requests.Instrument !=\'\'   ORDER BY requests.Instrument, requests.ECsession') //or die (mysql_error());

			or die ('Error: ' . mysqli_error($mysqli));

			// Count number of results of my query
			$nbresult = mysqli_num_rows($theresult);

		echo '<div id="searchresultsstats">Selected Instrument: <strong>'.$selectedInstrument.'</strong> <br/>
		<span style="color:#FF0000;">Number of entries: '.$nbresult.'</span><br/></div>';				
			}
		
	// Addressee and Deadline are selected and are different from All 	
	else if ($seladdressee!='' && $seladdressee!='All' && $seldeadline !=''  && $seldeadline !='All' && $selectedInstrument =='' && ($selectedecs =='' || $selectedecs =='All') && ($selectedecstat =='' || $selectedecstat =='All'))	
	
			{
			$theresult= $mysqli->query('SELECT * from requests INNER JOIN resolutions7 on requests.Instrument = resolutions7.reference  WHERE  requests.Addressee =\''.$seladdressee.'\' AND requests.Deadline =\''.$seldeadline.'\'  ORDER BY requests.Instrument, requests.ECsession') //or die (mysql_error());

			or die ('Error: ' . mysqli_error($mysqli));

			// Count number of results of my query
			$nbresult = mysqli_num_rows($theresult);

		echo '<div id="searchresultsstats">Selected Addressee : <strong>'.$seladdressee.'</strong> - Deadline: <strong>'.$seldeadline.'</strong><br/>
		<span style="color:#FF0000;">Number of entries: '.$nbresult.'</span><br/></div>';				
			}	
		
	// Addressee and Instrument are selected and are different from All	
	else if ($seladdressee!='' && $seladdressee!='All' && $selectedInstrument !=''  && $selectedInstrument !='All' && ($seldeadline =='' || $seldeadline =='All') && ($selectedecs =='' || $selectedecs =='All') && ($selectedecstat =='' || $selectedecstat =='All') )	
	
			{
			$theresult= $mysqli->query('SELECT * from requests INNER JOIN resolutions7 on requests.Instrument = resolutions7.reference  WHERE  requests.Addressee =\''.$seladdressee.'\' AND requests.Instrument =\''.$selectedInstrument.'\'  ORDER BY requests.Instrument, requests.ECsession') //or die (mysql_error());

			or die ('Error: ' . mysqli_error($mysqli));

			// Count number of results of my query
			$nbresult = mysqli_num_rows($theresult);
			
		echo '<div id="searchresultsstats">Selected Addressee : <strong>'.$seladdressee.'</strong> - Instrument: <strong>'.$selectedInstrument.'</strong> <br/>
		<span style="color:#FF0000;">Number of entries: '.$nbresult.'</span><br/></div>';				
			}		
	// Deadline and Instrument are selected and are different from All	
	else if ($seldeadline !='' && $seldeadline !='All' && $selectedInstrument !=''  && $selectedInstrument !='All' && ($seladdressee=='' || $seladdressee=='All' || $seladdressee== 1) && ($selectedecs =='' || $selectedecs =='All') && ($selectedecstat =='' || $selectedecstat =='All'))	
	
			{
			$theresult= $mysqli->query('SELECT * from requests INNER JOIN resolutions7 on requests.Instrument = resolutions7.reference WHERE  requests.Deadline =\''.$seldeadline.'\' AND requests.Instrument =\''.$selectedInstrument.'\'  ORDER BY requests.Instrument, requests.ECsession') //or die (mysql_error());

			or die ('Error: ' . mysqli_error($mysqli));

			// Count number of results of my query
			$nbresult = mysqli_num_rows($theresult);

		echo '<div id="searchresultsstats">Selected Deadline: <strong>'.$seldeadline.'</strong>  - Instrument: <strong>'.$selectedInstrument.'</strong> <br/>
		<span style="color:#FF0000;">Number of entries: '.$nbresult.'</span><br/></div>';				
			}		

	// Addressee, deadline and instrument are selected and are different from All	
	else if ($selectedInstrument !='' && $selectedInstrument !='All' && $seldeadline !=''  && $seldeadline !='All' && $seladdressee!=''  && $seladdressee!='All' && $seladdressee<> 1 && ($selectedecs =='' || $selectedecs =='All') && ($selectedecstat =='' || $selectedecstat =='All') )	
	
			{
			$theresult= $mysqli->query('SELECT * from requests INNER JOIN resolutions7 on requests.Instrument = resolutions7.reference  WHERE  requests.Addressee =\''.$seladdressee.'\' AND requests.deadline =\''.$seldeadline.'\' AND requests.Instrument =\''.$selectedInstrument.'\'   ORDER BY requests.Instrument, requests.ECsession') //or die (mysql_error());

			or die ('Error: ' . mysqli_error($mysqli));

			// Count number of results of my query
			$nbresult = mysqli_num_rows($theresult);

		echo '<div id="searchresultsstats">Selected Addressee : <strong>'.$seladdressee.'</strong> - Deadline: <strong>'.$seldeadline.'</strong>  - Instrument: <strong>'.$selectedInstrument.'</strong> <br/>
		<span style="color:#FF0000;">Number of entries: '.$nbresult.'</span><br/></div>';				
			}
				
/* //////////// EC Session and status //////////////*/	
		
	/*	
		$selectedecs 
	$selectedecstat
	
	*/
		
	//Only EC Session is selected and != ALL or Status = All or''
	else if ($selectedecs !='' && $selectedecs !='All'  && ($selectedecstat =='' || $selectedecstat =='All') )
		{
			$theresult= $mysqli->query('SELECT * from requests INNER JOIN resolutions7 on requests.Instrument = resolutions7.reference  WHERE  requests.ECsession =\''.$selectedecs.'\'    ORDER BY requests.Instrument, requests.ECsession') //or die (mysql_error());

			or die ('Error: ' . mysqli_error($mysqli));

			// Count number of results of my query
			$nbresult = mysqli_num_rows($theresult);

		echo '<div id="searchresultsstats">Selected EC Session : <strong>'.$selectedecs.'</strong> <br/> 
		<span style="color:#FF0000;">Number of entries: '.$nbresult.'</span><br/></div>';				
			}
	
	//Only EC Session is selected and == ALL
	else if ($selectedecs !='' && $selectedecs =='All'  && ($selectedecstat =='' || $selectedecstat =='All'))
		{
			$theresult= $mysqli->query('SELECT * from requests INNER JOIN resolutions7 on requests.Instrument = resolutions7.reference WHERE  requests.ECsession  !=\'\' ORDER requests.ECsession') //or die (mysql_error());

			or die ('Error: ' . mysqli_error($mysqli));

			// Count number of results of my query
			$nbresult = mysqli_num_rows($theresult);

		echo '<div id="searchresultsstats">Selected EC Session : <strong>'.$selectedecs.'</strong> <br/>
		<span style="color:#FF0000;">Number of entries: '.$nbresult.'</span><br/></div>';				
			}	
	
	//Only Status is selected and != ALL	
	
else if ($selectedecstat !='' && $selectedecstat !='All' && ($selectedecs =='' || $selectedecs =='All') )
		{
			$theresult= $mysqli->query('SELECT * from requests INNER JOIN resolutions7 on requests.Instrument = resolutions7.reference  WHERE  requests.Status =\''.$selectedecstat.'\'   ORDER BY requests.Instrument') 

			or die ('Error: ' . mysqli_error($mysqli));

			// Count number of results of my query
			$nbresult = mysqli_num_rows($theresult);

		echo '<div id="searchresultsstats">Selected Status : <strong>'.$selectedecstat.'</strong> <br/> 
		<span style="color:#FF0000;">Number of entries: '.$nbresult.'</span><br/></div>';				
			}
		
//Only Status is selected and == ALL		

else if ($selectedecstat !='' && $selectedecstat =='All' && ($selectedecs =='' || $selectedecs =='All'))
		{
			$theresult= $mysqli->query('SELECT * from requests INNER JOIN resolutions7 on requests.Instrument = resolutions7.reference  WHERE  requests.Status !=\'\'  ORDER BY requests.Instrument') 

			or die ('Error: ' . mysqli_error($mysqli));

			// Count number of results of my query
			$nbresult = mysqli_num_rows($theresult);

		echo '<div id="searchresultsstats">Selected Status : <strong>'.$selectedecstat.'</strong> <br/> 
		<span style="color:#FF0000;">Number of entries: '.$nbresult.'</span><br/></div>';				
			}		
	
	// EC Session is selected and Statusis selected and are != All
	else if ($selectedecs !='' && $selectedecs !='All' && $selectedecstat !='' && $selectedecstat !='All')
		{
			$theresult= $mysqli->query('SELECT * from requests INNER JOIN resolutions7 on requests.Instrument = resolutions7.reference  WHERE requests.ECsession =\''.$selectedecs.'\'  AND requests.Status =\''.$selectedecstat.'\' ORDER BY requests.Instrument') //or die (mysql_error());

			or die ('Error: ' . mysqli_error($mysqli));

			// Count number of results of my query
			$nbresult = mysqli_num_rows($theresult);

		echo '<div id="searchresultsstats">Selected EC Session : <strong>'.$selectedecs.'</strong> - Status : <strong>'.$selectedecstat.'</strong> <br/> 
		<span style="color:#FF0000;">Number of entries: '.$nbresult.'</span><br/></div>';				
			}		
	
			
			
///////////////////XXXXXXXXXXXXXXXX/////////////////////*/
	// if none of the above condition is met we display all
		else 
			{
			
			/* - Original 26-01-2022 - $theresult= $mysqli->query('SELECT * from requests INNER JOIN resolutions7 on requests.Instrument = resolutions7.reference ORDER BY requests.Instrument, requests.ECsession') or die ('Error: ' . mysqli_error($mysqli));
			$nbresult = mysqli_num_rows($theresult);*/
			$theresult= $mysqli->query('SELECT * from requests INNER JOIN resolutions7 on requests.Instrument = resolutions7.reference ORDER BY CAST(requests.Instrument as unsigned)') or die ('Error: ' . mysqli_error($mysqli));
			$nbresult = mysqli_num_rows($theresult);

			echo '<div id="searchresultsstats">No selection <br/> Total entries:&nbsp;<strong>'.$nbresult.'</strong></div>';
			
			}
			
/* ////////////  RESULTS //////////////*/			

/////////////// Test variables //////////////////////////////////	
		/*
		
if (empty($seladdressee)) 
  	{
    echo "<br/>Please select an addressee";
  	}
	  else if ($seladdressee == 1) 
		{
		//echo '<br/> All';
		echo '';
		}
	else
		{
		echo '<br/> '.$seladdressee;
		}
	
 if (empty($seldeadline)) 
  	{
    echo "<br/>Please select a deadline";
  	}
	
	
  else 
	{
    echo '<br/>'.$seldeadline;
  	}

if (empty($selectedInstrument)) 
  	{
    echo "<br/>Please select an Instrument";
  	}
  else 
	{
    echo '<br/>'.$selectedInstrument;
  	}	
		
if (empty($selectedecs)) 
  	{
    echo "<br/>Please select an EC Session";
  	}
  else 
	{
    echo '<br/>'.$selectedecs;
  	}
	
if (empty($selectedecstat)) 
  	{
    echo "<br/>Please select a Status";
  	}
  else 
	{
    echo '<br/>'.$selectedecstat;
  	}	

	*/	
	/* ////////////  RESULTS //////////////*/		
		
			if(mysqli_num_rows($theresult) <> '' ) 
				{
					while ($donnees_res = mysqli_fetch_array($theresult) )

						 {
						
						
							echo'<div id="details"><strong>';

							if ($donnees_res['link']!='')
							{
								echo '<a href="'.htmlentities ($donnees_res['link']).'" target="_blank">'.htmlentities ($donnees_res['Instrument']).'</a>';
							}
								else
								{
									echo htmlentities ($donnees_res['Instrument']);


								}
							echo'</strong>';

							echo'</strong>&nbsp;-&nbsp;'.htmlentities ($donnees_res['title']).'&nbsp;('.($donnees_res['year']).')';

							echo '<h3>Addressee</h3>'.htmlentities ($donnees_res['Addressee']);
								//if (htmlentities ($donnees_res['status'])!='')
								//{
									echo '<h3>Request</h3>'.htmlentities ($donnees_res['Request']).'<br/>'; 
							//} 
							if (htmlentities ($donnees_res['Status'])!='')
								{
								echo '<h3>Status</h3>'.htmlentities ($donnees_res['Status']).'<br/>'; 
								}
							if(htmlentities ($donnees_res['Deadline'])!='')
								{
									echo '<h3>Session deadline</h3>'.htmlentities ($donnees_res['Deadline']);
								}
							if(htmlentities ($donnees_res['Verification'])!='')
								{
									echo '<h3>Verification</h3>'.htmlentities ($donnees_res['Verification']);
								}

								else {echo '';}

							echo'</div>';

						} //End While

					}  //End if
				else
					{echo 'Sorry there is no entry for your request';}	

?>	
		</div> <!-- results -->
	</div> <!-- Wrapper -->
<?php 

	$mysqli -> close();// deconnexion New way

?>
</body>
</html>
