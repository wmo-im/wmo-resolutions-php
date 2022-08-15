<?php 
// session_start(); // on commence une session
include('inc/resconnect.php');
?>
<!doctype html>
<html>
<head>
<title>Resolutions and decisions</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">	
	
<link href="css/resolutions.css" rel="stylesheet" type="text/css" media="screen" />
<link href="css/print.css" rel="stylesheet" type="text/css"  media="print" />	
<!--26.01.2022 -->
</head>

<body>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST")
{	// define variables and set to empty values
	$selbody = $selsession = $selectedInstrument = $selecteddpt = $seldivision = $selltg = $selectedso = $selectedyear = $selectedkw = $selectedskw = "";
  // collect value of input fields
	$WMObody= $mysqli -> real_escape_string (htmlspecialchars ($_POST['Body']));
	$WMOSession= $mysqli -> real_escape_string (htmlspecialchars ($_POST['WMOSession']));
	$WMOInstrument= $mysqli -> real_escape_string (htmlspecialchars ($_POST['Instrument']));
	$WMODepartment = $mysqli -> real_escape_string (htmlspecialchars ($_POST['Dpt']));
	$WMODivision= $mysqli -> real_escape_string (htmlspecialchars ($_POST['Division']));
	$WMOLtg= $mysqli -> real_escape_string (htmlspecialchars ($_POST['Ltg']));
	$WMOSo = $mysqli -> real_escape_string (htmlspecialchars ($_POST['So']));
	$WMOYear = $mysqli -> real_escape_string (htmlspecialchars ($_POST['Year']));
	$WMOKw = $mysqli -> real_escape_string (htmlspecialchars ($_POST['KeyW']));
	$WMOSkw = $mysqli -> real_escape_string (htmlspecialchars ($_POST['Skw']));
	
	$selbody = $WMObody;
	$selsession = $WMOSession;
	$selectedInstrument = $WMOInstrument;
	$selecteddpt = $WMODepartment;
	$seldivision = $WMODivision;
	$selltg = $WMOLtg;
	$selectedso = $WMOSo;
	$selectedyear = $WMOYear;
	$selectedkw = $WMOKw;
	$selectedskw = $WMOSkw;

	
}	
?>	
<div id="wrapper">
	<div id="header">
		<div class="logo">	
			<img src="wmologo2016_fulltext_horizontal300x101_rgb_en.png" width=100% alt="WMO Logo" />
		</div>
		<div class="title">
		<h1>WMO Congress, Executive Council, Regional Associations and Technical Commissions Resolutions, Decisions and Recommendations in force</h1>
		<div class="linkprogressreport">
		<p ><em><a href="https://meetings.wmo.int/EC-73/_layouts/15/WopiFrame.aspx?sourcedoc=/EC-73/InformationDocuments/EC-73-INF08-REVIEW-OF-PREVIOUS-RESOLUTIONS_en.docx&action=default" target="_blank">Progress and recommendations for Cg-18 Resolutions as reported in EC-73/INF. 8</a></em></p>		
		</div>	
		</div>
		
	
	</div>
	<div id="nav">
		<p><a href="requests.php">>> Requests made by Congress as from Cg-Ext(2021) and the Executive Council as from EC-73</a></p>
		<?php $updatedate= $mysqli->query('SELECT distinct dateupdate FROM updated where page ="Resolution"') or die ('Error: ' . mysqli_error($mysqli));
		while ($updatedated = mysqli_fetch_array($updatedate))
			{
				echo '<em>Last update:&nbsp;'. $updatedated['dateupdate'].'</em>';
			}
		?>
	</div>
	<div id="search">
	<div id="part1">
	<form method="post"  action="index.php">
<!-- ***************** Body / Session / Instrument************************ -->		
		
<!-- ***************** Body ************************ -->		
		
 <!--  Name: <input type="text" name="fname">
  <input type="submit"> -->
		<div id="body">
		<h2>WMO Constituent Bodies</h2>
		 <select name="Body" id="Body" onChange="submit()">
					<option value="" > ------ Select a WMO Constituent Body ------ </option>
					<option value="All" > All bodies</option>
		<?php	
			 		$bdy= $mysqli->query('SELECT distinct body FROM resolutions7 ORDER BY body ASC') or die ('Error: ' . mysqli_error($mysqli));
			 
					while ($body = mysqli_fetch_array($bdy))
						{
					?>
						<option value="<?php echo $body['body'];?>"<?php if ($body['body']==$selbody){echo 'selected';}else {echo'';}?>> <?php echo nl2br($body['body']); ?></option>
			  		<?php
						}				
				 ?>
  		</select>	
		</div>
<!-- *****************  END Body ************************ -->			
<!-- ***************** Session ************************ -->
	
<!--	<h2>Session</h2> 	-->
	<?php 	
	// On recupere la valeur session choisi
	//////////// Queries to feed Session from Body /////////////////////
	if (isset($selbody) && $selbody !='All' && $selbody !='') // A specific body is selected
		{
		$sess= $mysqli->query('SELECT distinct session FROM resolutions7 WHERE body =\''.$selbody.'\' ORDER BY CAST(session as unsigned)  ASC') or die ('Error: ' . mysqli_error($mysqli));
		 
		}
	else
		{
		$sess= $mysqli->query('SELECT distinct session FROM resolutions7 ORDER BY CAST(session as unsigned)ASC') or die ('Error: ' . mysqli_error($mysqli));
	 	//echo '<br/>Body session not set<br/>';
	
		}
	
	// Checks if there is a result in the session scroll-down menu //
	$nbresultsess = mysqli_num_rows($sess);

	?>
		<div id="wmosession">
		<h2>Sessions</h2>
	  <select name="WMOSession" id="WMOSession" onChange="submit()">
					<option value=""> ------ Select a Session ------ </option>
		<?php if ($nbresultsess > 1) {echo'<option value="All"> All Sessions</option>';}	else {echo '';} // if there is only 1 session after filtering we dont display all ?>
		<?php
				
					while ($thesession = mysqli_fetch_array($sess))
				 {
					?>
				
		  <option value="<?php echo $thesession['session'];?>"<?php if ($thesession['session']==$selsession){echo 'selected';}else {echo'';}?>> <?php echo nl2br($thesession['session']); ?></option>
					<?php
				 }
				 ?>
  		</select>	
		</div>
	<!-- ***************** End Session ************************ -->	
	
	<!-- ***************** Instrument ************************ -->
	<?php
	// To feed the instrument scroll-down menu //

	if (isset($selbody) && $selbody!='' && $selbody!='All' && isset($selsession) && $selsession !='' && $selsession !='All' ) // Si body et session sont selectionnes
		{
		$selInstrument= $mysqli->query('SELECT distinct instrument FROM resolutions7 where  body =\''.$selbody.'\' AND session =\''.$selsession.'\' ORDER BY instrument ASC') or die ('Error: ' . mysqli_error($mysqli));
		//echo '<br/> Body & Session selected<br/>';
		}
	elseif (isset($selbody) && $selbody!='All' && $selbody!=''  && (!isset($selsession) || $selsession ='' || $selsession = 'All')) // seul body est selectionne
		{
		$selInstrument= $mysqli->query('SELECT distinct instrument FROM resolutions7 where body =\''.$selbody.'\' ORDER BY instrument ASC') or die ('Error: ' . mysqli_error($mysqli));
		//echo '<br/>Only Body selected<br/>';
		}
	elseif ((!isset($selbody) || $selbody ='All' || $selbody='') && isset($selsession) && $selsession !='' && $selsession !='All') // seul session est selectionne
		{
		$selInstrument= $mysqli->query('SELECT distinct instrument FROM resolutions7 where session =\''.$selsession.'\' ORDER BY instrument ASC') or die ('Error: ' . mysqli_error($mysqli));
		//echo '<br/> Only Session selected<br/>';
		}
	else 
		{
		$selInstrument= $mysqli->query('SELECT distinct instrument FROM resolutions7 ORDER BY instrument ASC') or die ('Error: ' . mysqli_error($mysqli));
		
		}

	$nbresultinstrument = mysqli_num_rows($selInstrument);
	?>	
	<div id="instrument">
	<h2>Instruments</h2>
	 <select name="Instrument" id="Instrument"  onChange="submit()">
					<option value=""> ------ Select an Instrument ------ </option>
					
		 <?php if ($nbresultinstrument > 1) {echo'<option value="All"> All Instruments</option>';}	else {echo '';} ?>
	<?php				
					while ($theinstrument = mysqli_fetch_array($selInstrument))
				 {
					?>
					
		  <option value="<?php echo $theinstrument['instrument'];?>"<?php if ($theinstrument['instrument']==$selectedInstrument){echo 'selected';}else {echo'';}?>> <?php echo nl2br($theinstrument['instrument']); ?></option>
		 
					<?php
				 }
				 	?> 		 			
		 </select>
		 </div>
	</form>
	</div> <!-- part 1 -->
	<div id="part2">
	<form method="post" action="index.php">
	<!-- ***************** Department/Entity ************************ -->
	<div id="p2-dpt">
	<h2>Departments/Entities</h2>

	<?php
	// To feed the department scroll-down menu //
	$dept= $mysqli->query('SELECT distinct resp1 FROM resolutions7 ORDER BY resp1 ASC') or die ('Error: ' . mysqli_error($mysqli));
	?>
	
	  <select name="Dpt" onChange="submit()">
					<option value=""> ------ Select a Department/Entity ------ </option>
					<option value="All"> All Departments/Entities</option>
		<?php
				
					while ($resp1 = mysqli_fetch_array($dept))
				 {
					?>
					<option value="<?php echo $resp1['resp1'];?>" <?php if ($resp1['resp1']==$selecteddpt){echo 'selected';}else {echo'';}?>> <?php echo nl2br($resp1['resp1']); ?></option>
					<?php
				 }
				 ?>
  		</select>
		</div>
	 	<!-- ***************** Unit ************************ -->
	<?php 	
	// On recupere la valeur division choisi

	//////////// Queries to feed Division from Department /////////////////////
	if (isset($selecteddpt) && $selecteddpt !='All' && $selecteddpt !='') // A specific Dpt is selected
		{
		$div= $mysqli->query('SELECT distinct  resp2 FROM resolutions7 WHERE resp1 =\''.$selecteddpt.'\' && resp2!=\'\' ORDER BY  resp2 ASC') or die ('Error: ' . mysqli_error($mysqli));
		}
	else
		{
		$div= $mysqli->query('SELECT distinct resp2 FROM resolutions7 where resp2!=\'\' ORDER BY resp2 ASC') or die ('Error: ' . mysqli_error($mysqli));
		}
	
	// Checks if there is a result in the unit scroll-down menu //
	$nbresultdiv= mysqli_num_rows($div);
	?>
<!--	<h2>Unit</h2> -->
	<div id="p2-unit">
	<h2>Units</h2>
	  <select name="Division" onChange="submit()">
					<option value=""> ------ Select a Unit ------ </option>
		  			<!--<option value="All" selected >All units</option>
		  			 <option value="All"> All units </option> -->
		<?php if ($nbresultdiv > 1) {echo'<option value="All"> All units</option>';} else {echo '';} // if there is only 1 unit after filtering we dont display all ?>
						
		<?php
  
					while ($seldiv = mysqli_fetch_array($div))
				 	{
					 ?>
		  
					<option value="<?php echo $seldiv['resp2'];?>" <?php if ($seldiv['resp2']==$seldivision){echo 'selected';}else {echo'';}?>> <?php echo nl2br($seldiv['resp2']); ?></option>
					
		  	<?php
				 	}
					
				 ?>
  		</select>
		</div>
	</form>
	</div> <!-- part2 -->
	<div id="part3">
	<form method="post" action="index.php">
	<!-- ***************** LTG ************************ -->
	<div id="p3-ltg">	
	<h2>Long-Term Goals <!--and Strategic Objectives--></h2>
	<?php
	// To feed the department scroll-down menu //
	$ltg= $mysqli->query('SELECT distinct kw1 FROM resolutions7 ORDER BY kw1 ASC') or die ('Error: ' . mysqli_error($mysqli));
	
	
	?>
	
	  <select name="Ltg" onChange="submit()">
					<option value=""> ------ Select a Long-Term Goal ------ </option>
					<option value="All"> All Long-Term Goals</option>
		<?php
				
					while ($ltgs = mysqli_fetch_array($ltg))
				 {
					?>
					<option value="<?php echo $ltgs['kw1'];?>" <?php if ($ltgs['kw1']==$selltg){echo 'selected';}else {echo'';}?>> <?php echo nl2br($ltgs['kw1']); ?></option>
					<?php
				 }
				 ?>
  		</select>
	</div>
		<!-- ***************** SO ************************ -->
	<div id="p3-so"	>
	<h2>Strategic Objectives</h2> <!---->
	<?php
	// To feed the SO scroll-down menu //
	
	if (isset($selltg) && $selltg !='All' && $selltg !='') // A specific Dpt is selected
		{
		$so= $mysqli->query('SELECT distinct kw2 FROM resolutions7 WHERE kw1 =\''.$selltg.'\' && kw2!=\'\' ORDER BY  kw2 ASC') or die ('Error: ' . mysqli_error($mysqli));
		}
	else
		{
		
		$so= $mysqli->query('SELECT distinct kw2 FROM resolutions7 where kw2!=\'\' ORDER BY kw2 ASC') or die ('Error: ' . mysqli_error($mysqli));
		}
	
	// Checks if there is a result in the unit scroll-down menu //
	$nbresultso= mysqli_num_rows($so);

	?>	
	  <select name="So" onChange="submit()">
					<option value=""> ------ Select a Strategic Objective ------ </option>
				
		  <?php if ($nbresultso > 1) {echo'<option value="All">  All Strategic Objectives</option>';}	else {echo '';} // if there is only 1 SO after filtering we dont display all ?>
		<?php
				
					while ($sos = mysqli_fetch_array($so))
				 {
					?>
					<option value="<?php echo $sos['kw2'];?>" <?php if ($sos['kw2']==$selectedso){echo 'selected';}else {echo'';}?>> <?php echo nl2br($sos['kw2']); ?></option>
					<?php
				 }
				 ?>
  		</select>
		</div>
	</form>	
	</div> <!-- part3 -->
	<div id="part4">
	<form method="post" action="index.php">
 <!-- ////////////////////////// SELECTION KEYWORD ////////////////////////////// -->
		<div id="p4-kw">
		<h2> Keyword  </h2>
  		<input type="text" name="KeyW" onChange="submit()" />
			
		</div>
		
  <!--////////////////////////// END SELECTION KEYWORD ////////////////////////////// -->	
<!-- ***************** Suggested Keyword************************ -->
	<div id="p4-sugkw">
	<h2>Suggested Keywords</h2>
	<?php
	// To feed the year scroll-down menu //
	$selSkw= $mysqli->query('SELECT distinct keywords FROM keywords ORDER BY keywords ASC') or die ('Error: ' . mysqli_error($mysqli));
	?>
	  <select name="Skw" onChange="submit()">
					<option value=""> ------ Select a Keyword ------ </option>
					<option value="All"> All keywords</option>
		<?php
				
					while ($theskw = mysqli_fetch_array($selSkw))
				 {
					?>
					<option value="<?php echo $theskw['keywords'];?>"> <?php echo nl2br($theskw['keywords']); ?></option>
					<?php
				 }
				 ?>
  		</select>
		</div>			
		
	<!-- ***************** Year ************************ -->
	<div id="p4-year">
	<h2>Year</h2>
	<?php
	// To feed the year scroll-down menu //
	$selYear= $mysqli->query('SELECT distinct year FROM resolutions7 ORDER BY year ASC') or die ('Error: ' . mysqli_error($mysqli));
	?>
	  <select name="Year" onChange="submit()">
					<option value=""> ------ Select a Year ------ </option>
					<option value="All"> All years</option>
		<?php
				
					while ($theyear = mysqli_fetch_array($selYear))
				 {
					?>
					<option value="<?php echo $theyear['year'];?>"> <?php echo nl2br($theyear['year']); ?></option>
					<?php
				 }
				 ?>
  		</select>
		</div>

	</form>	
	</div> <!-- part4 -->	
	</div> <!-- Search -->
	
 <!--////////////////////////// Results ////////////////////////////// -->	
	<div id="results">
		
<?php
		
/* //////////// QUERIES and RESULTS //////////////*/
		
/* //////////// QUERIES //////////////*/	
/* //////////// Keyword //////////////*/			
	//Only a Key Word is selected	
		if ($selectedkw !='')  
			{

			$theresult= $mysqli->query('SELECT * FROM resolutions7  WHERE reference like \'%'.$selectedkw.'%\' or title like \'%'.$selectedkw.'%\'  or content like \'%'.$selectedkw.'%\' or year like \'%'.$selectedkw.'%\' or body like \'%'.$selectedkw.'%\' or instrument like \'%'.$selectedkw.'%\' or source like \'%'.$selectedkw.'%\' or kw1 like \'%'.$selectedkw.'%\' or kw2 like \'%'.$selectedkw.'%\' or kw3 like \'%'.$selectedkw.'%\' or kw4 like \'%'.$selectedkw.'%\' or kw5 like \'%'.$selectedkw.'%\' or resp1 like \'%'.$selectedkw.'%\' or resp2 like \'%'.$selectedkw.'%\' ORDER BY year, reference2') //or die (mysql_error());	// KW in WORK , Expertise and PUBLICATIONS

			or die ('Error: ' . mysqli_error($mysqli));

			// Count number of results of my query
			$nbresult = mysqli_num_rows($theresult);


		echo '<div id="searchresultsstats">Entered keyword : <strong>'.$selectedkw.'</strong><br/>
		<span style="color:#FF0000;">Number of entries: '.$nbresult.'</span><br/> </div>';
			}
			/* //////////// suggested keywords //////////////*/
	//Only a suggested Keywords is selected	
	else if ($selectedskw !='' && $selectedskw !='All')  
			{

			$theresult= $mysqli->query('SELECT * FROM resolutions7  WHERE reference like \'%'.$selectedskw.'%\' or title like \'%'.$selectedskw.'%\'  or content like \'%'.$selectedskw.'%\' or year like \'%'.$selectedskw.'%\' or body like \'%'.$selectedskw.'%\' or instrument like \'%'.$selectedskw.'%\' or source like \'%'.$selectedskw.'%\' or kw1 like \'%'.$selectedskw.'%\' or kw2 like \'%'.$selectedskw.'%\' or kw3 like \'%'.$selectedskw.'%\' or kw4 like \'%'.$selectedskw.'%\' or kw5 like \'%'.$selectedskw.'%\' or resp1 like \'%'.$selectedskw.'%\' or resp2 like \'%'.$selectedskw.'%\' ORDER BY year, reference2') //or die (mysql_error());	// KW in WORK , Expertise and PUBLICATIONS

			or die ('Error: ' . mysqli_error($mysqli));

			// Count number of results of my query
			$nbresult = mysqli_num_rows($theresult);


		echo '<div id="searchresultsstats">Selected keyword : <strong>'.$selectedskw.'</strong><br/>
		<span style="color:#FF0000;">Number of entries: '.$nbresult.'</span><br/></div>';
			}		
	//All suggested Keywords are selected		
	else if ($selectedskw =='All')  
			{

			$theresult= $mysqli->query('SELECT * FROM resolutions7  ORDER BY year, reference2') //or die (mysql_error());	// KW in WORK , Expertise and PUBLICATIONS

			or die ('Error: ' . mysqli_error($mysqli));

			// Count number of results of my query
			$nbresult = mysqli_num_rows($theresult);


		echo '<div id="searchresultsstats">Selected keyword : <strong>'.$selectedskw.'</strong><br/>
		<span style="color:#FF0000;">Number of entries: '.$nbresult.'</span><br/></div>';
			}			
		
		
			/* //////////// Year //////////////*/			
	//Only a specific Year is selected 	
	else if ($selectedyear !='' && $selectedyear !='All')  
			{

			$theresult= $mysqli->query('SELECT * FROM resolutions7  WHERE  year = \''.$selectedyear.'\' ORDER BY year, reference2') //or die (mysql_error());	// KW in WORK , Expertise and PUBLICATIONS

			or die ('Error: ' . mysqli_error($mysqli));

			// Count number of results of my query
			$nbresult = mysqli_num_rows($theresult);


		echo '<div id="searchresultsstats">Selected Year : <strong>'.$selectedyear.'</strong><br/>
		<span style="color:#FF0000;">Number of entries: '.$nbresult.'</span><br/></div>';
			}	
		
	//All years are selected 	
	else if ($selectedyear =='All' )  
			{

			$theresult= $mysqli->query('SELECT * FROM resolutions7  WHERE  year !=\'\' ORDER BY year, reference2') //or die (mysql_error());	// KW in WORK , Expertise and PUBLICATIONS

			or die ('Error: ' . mysqli_error($mysqli));

			// Count number of results of my query
			$nbresult = mysqli_num_rows($theresult);
		echo '<div id="searchresultsstats">Selected Year : <strong>'.$selectedyear.'</strong><br/>
		<span style="color:#FF0000;">Number of entries: '.$nbresult.'</span><br/></div>';
			}
		
		//////////// Body ////////////////////////
	// Only body is selected and is != from All
	else if ($selbody !=''  && $selbody !='All' && $selbody <> 1 &&  ($selsession =='' || $selsession =='All') &&  ($selectedInstrument =='' || $selectedInstrument =='All')  )	
	
		{
			$theresult= $mysqli->query('SELECT * FROM resolutions7  WHERE  body =\''.$selbody.'\'  ORDER BY year, reference2') //or die (mysql_error());

			or die ('Error: ' . mysqli_error($mysqli));

			// Count number of results of my query
			$nbresult = mysqli_num_rows($theresult);

		echo '<div id="searchresultsstats">Selected Body : <strong>'.$selbody.'</strong> <br/>
		<span style="color:#FF0000;">Number of entries: '.$nbresult.'</span><br/></div>';				
			}
	// Only body is selected and is == All !!! All other variables should be set to ''
	else if ($selbody !=''  && ($selbody =='All' || $selbody == 1) && $selsession =='' /* ($selsession =='' || $selsession =='All')*/ && $selectedInstrument =='' /*($selectedInstrument =='' || $selectedInstrument =='All')*/ && ($selecteddpt =='' || $selecteddpt =='All' ) && ($seldivision =='' || $seldivision =='All') && $selltg =='' && $selectedso =='')	
	
		{
			$theresult= $mysqli->query('SELECT * FROM resolutions7  WHERE  body !=\'\'  ORDER BY year, reference2') //or die (mysql_error());

			or die ('Error: ' . mysqli_error($mysqli));

			// Count number of results of my query
			$nbresult = mysqli_num_rows($theresult);
			if ($selbody == 1 &&  ($selsession =='' || $selsession =='All') &&  ($selectedInstrument =='' || $selectedInstrument =='All') )
			{
			echo 'Selected Body : <strong> All</strong><br/>
			<span style="color:#FF0000;">Number of entries: '.$nbresult.'</span><br/>';				
			}
			else
			{echo '<div id="searchresultsstats">Selected Body : <strong>'.$selbody.'</strong><br/>
			<span style="color:#FF0000;">Number of entries: '.$nbresult.'</span><br/></div>';	}
		}
	// Only Session is selected and is != from All
	else if ($selsession !='' && $selsession !='All' &&  ($selbody =='' || $selbody =='All' || $selbody == 1) && ($selectedInstrument =='' || $selectedInstrument =='All')  )	
	
		{
			$theresult= $mysqli->query('SELECT * FROM resolutions7  WHERE  session =\''.$selsession.'\'  ORDER BY year, reference2') //or die (mysql_error());

			or die ('Error: ' . mysqli_error($mysqli));

			// Count number of results of my query
			$nbresult = mysqli_num_rows($theresult);

		echo '<div id="searchresultsstats">Selected Session: <strong>'.$selsession.'</strong> <br/>
		<span style="color:#FF0000;">Number of entries: '.$nbresult.'</span><br/></div>';				
			}	
		
	// Only Session is selected and is == from All
	else if ($selsession !='' && $selsession =='All' &&  ($selbody =='' || $selbody =='All' || $selbody == 1) && ($selectedInstrument =='' || $selectedInstrument =='All')  )	
	
		{
			$theresult= $mysqli->query('SELECT * FROM resolutions7  WHERE  session !=\'\'  ORDER BY year, reference2') //or die (mysql_error());

			or die ('Error: ' . mysqli_error($mysqli));

			// Count number of results of my query
			$nbresult = mysqli_num_rows($theresult);

		echo '<div id="searchresultsstats">Selected Session: <strong> All</strong> <br/>
		<span style="color:#FF0000;">Number of entries: '.$nbresult.'</span><br/></div>';				
			}
		
	// Only Instrument is selected and is != from All
	else if ($selectedInstrument !='' && $selectedInstrument !='All' && ($selsession =='' || $selsession =='All') &&  ($selbody =='' || $selbody =='All' || $selbody == 1) )	
	
		{
			$theresult= $mysqli->query('SELECT * FROM resolutions7  WHERE  instrument =\''.$selectedInstrument.'\'  ORDER BY year, reference2') //or die (mysql_error());

			or die ('Error: ' . mysqli_error($mysqli));

			// Count number of results of my query
			$nbresult = mysqli_num_rows($theresult);

		echo '<div id="searchresultsstats">Selected Instrument: <strong>'.$selectedInstrument.'</strong> <br/>
		<span style="color:#FF0000;">Number of entries: '.$nbresult.'</span><br/></div>';				
			}	
		
		// Only Instrument is selected and is == to All
	else if ($selectedInstrument !='' && $selectedInstrument =='All' && ($selsession =='' || $selsession =='All') &&  ($selbody =='' || $selbody =='All' || $selbody == 1) )	
	
		{
			$theresult= $mysqli->query('SELECT * FROM resolutions7  WHERE  instrument !=\'\'  ORDER BY year, reference2') //or die (mysql_error());

			or die ('Error: ' . mysqli_error($mysqli));

			// Count number of results of my query
			$nbresult = mysqli_num_rows($theresult);

		echo '<div id="searchresultsstats">Selected Instrument: <strong>'.$selectedInstrument.'</strong> <br/>
		<span style="color:#FF0000;">Number of entries: '.$nbresult.'</span><br/></div>';				
			}
		
	// Body and Session are selected and are different from All	
	else if ($selbody !='' && $selbody !='All' && $selsession !=''  && $selsession !='All' && $selectedInstrument =='' )	
	
			{
			$theresult= $mysqli->query('SELECT * FROM resolutions7  WHERE  body =\''.$selbody.'\' AND session =\''.$selsession.'\' ORDER BY year, reference2') //or die (mysql_error());

			or die ('Error: ' . mysqli_error($mysqli));

			// Count number of results of my query
			$nbresult = mysqli_num_rows($theresult);

		echo '<div id="searchresultsstats">Selected Body : <strong>'.$selbody.'</strong> - Session: <strong>'.$selsession.'</strong><br/>
		<span style="color:#FF0000;">Number of entries: '.$nbresult.'</span><br/></div>';				
			}	
		
	// Body and Instrument are selected and are different from All	
	else if ($selbody !='' && $selbody !='All' && $selectedInstrument !=''  && $selectedInstrument !='All' && ($selsession =='' || $selsession =='All') )	
	
			{
			$theresult= $mysqli->query('SELECT * FROM resolutions7  WHERE  body =\''.$selbody.'\' AND instrument =\''.$selectedInstrument.'\' ORDER BY year, reference2') //or die (mysql_error());

			or die ('Error: ' . mysqli_error($mysqli));

			// Count number of results of my query
			$nbresult = mysqli_num_rows($theresult);
			
		echo '<div id="searchresultsstats">Selected Body : <strong>'.$selbody.'</strong> - Instrument: <strong>'.$selectedInstrument.'</strong> <br/>
		<span style="color:#FF0000;">Number of entries: '.$nbresult.'</span><br/></div>';				
			}		
	// Session and Instrument are selected and are different from All	
	else if ($selsession !='' && $selsession !='All' && $selectedInstrument !=''  && $selectedInstrument !='All' && ($selbody =='' || $selbody =='All' || $selbody == 1) )	
	
			{
			$theresult= $mysqli->query('SELECT * FROM resolutions7  WHERE session =\''.$selsession.'\' AND instrument =\''.$selectedInstrument.'\' ORDER BY year, reference2') //or die (mysql_error());

			or die ('Error: ' . mysqli_error($mysqli));

			// Count number of results of my query
			$nbresult = mysqli_num_rows($theresult);

		echo '<div id="searchresultsstats">Selected Session: <strong>'.$selsession.'</strong>  - Instrument: <strong>'.$selectedInstrument.'</strong> <br/>
		<span style="color:#FF0000;">Number of entries: '.$nbresult.'</span><br/></div>';				
			}		

	// body, session and instrument are selected and are different from All	
	else if ($selectedInstrument !='' && $selectedInstrument !='All' && $selsession !=''  && $selsession !='All' && $selbody !=''  && $selbody !='All' && $selbody <> 1 )	
	
			{
			$theresult= $mysqli->query('SELECT * FROM resolutions7  WHERE  body =\''.$selbody.'\' AND session =\''.$selsession.'\' AND instrument =\''.$selectedInstrument.'\'  ORDER BY year, reference2') //or die (mysql_error());

			or die ('Error: ' . mysqli_error($mysqli));

			// Count number of results of my query
			$nbresult = mysqli_num_rows($theresult);

		echo '<div id="searchresultsstats">Selected Body : <strong>'.$selbody.'</strong> - Session: <strong>'.$selsession.'</strong>  - Instrument: <strong>'.$selectedInstrument.'</strong> <br/>
		<span style="color:#FF0000;">Number of entries: '.$nbresult.'</span><br/></div>';				
			}
/* //////////// Department - Unit //////////////*/			
	//Only Department is selscted and != ALL or divion = All
	else if ($selecteddpt !='' && $selecteddpt !='All' && $selbody == 1 && ($seldivision =='' || $seldivision =='All') && ($selbody == 1 || $selbody ==''))
		{
			$theresult= $mysqli->query('SELECT * FROM resolutions7  WHERE  resp1 =\''.$selecteddpt.'\'   ORDER BY year, reference2') //or die (mysql_error());

			or die ('Error: ' . mysqli_error($mysqli));

			// Count number of results of my query
			$nbresult = mysqli_num_rows($theresult);

		echo '<div id="searchresultsstats">Selected Department/Entity : <strong>'.$selecteddpt.'</strong> <br/> 
		<span style="color:#FF0000;">Number of entries: '.$nbresult.'</span><br/></div>';				
			}
	
	//Only Department is selscted and == ALL
	else if ($selecteddpt !='' && $selecteddpt =='All' && $selbody == 1/*&& ($seldivision =='' || $seldivision =='All') && ($selbody == 1 || $selbody =='')*/)
		{
			$theresult= $mysqli->query('SELECT * FROM resolutions7  WHERE  resp1 !=\'\' ORDER BY year, reference2') //or die (mysql_error());

			or die ('Error: ' . mysqli_error($mysqli));

			// Count number of results of my query
			$nbresult = mysqli_num_rows($theresult);

		echo '<div id="searchresultsstats">Selected Department/Entity : <strong>'.$selecteddpt.'</strong> <br/> 
		<span style="color:#FF0000;">Number of entries: '.$nbresult.'</span><br/></div>';				
			}	
	
	//Only Unit is selscted and != ALL	
	
else if ($seldivision !='' && $seldivision !='All' && ($selecteddpt =='' || $selecteddpt =='All') && $selbody == 1/*&& ($seldivision =='' || $seldivision =='All') && ($selbody == 1 || $selbody =='')*/)
		{
			$theresult= $mysqli->query('SELECT * FROM resolutions7  WHERE  resp2 =\''.$seldivision.'\'   ORDER BY year, reference2') //or die (mysql_error());

			or die ('Error: ' . mysqli_error($mysqli));

			// Count number of results of my query
			$nbresult = mysqli_num_rows($theresult);

		echo '<div id="searchresultsstats">Selected Unit : <strong>'.$seldivision.'</strong> <br/> 
		<span style="color:#FF0000;">Number of entries: '.$nbresult.'</span><br/></div>';				
			}
		
//Only Unit is selscted and == ALL		

else if ($seldivision !='' && $seldivision =='All' && ($selecteddpt =='' || $selecteddpt =='All') && $selbody == 1/*&& ($seldivision =='' || $seldivision =='All') && ($selbody == 1 || $selbody =='')*/)
		{
			$theresult= $mysqli->query('SELECT * FROM resolutions7  WHERE  resp2 !=\'\' ORDER BY year, reference2') //or die (mysql_error());

			or die ('Error: ' . mysqli_error($mysqli));

			// Count number of results of my query
			$nbresult = mysqli_num_rows($theresult);

		echo '<div id="searchresultsstats">Selected Unit : <strong>'.$seldivision.'</strong> <br/> 
		<span style="color:#FF0000;">Number of entries: '.$nbresult.'</span><br/></div>';				
			}		
	
	//Department is selected and unit is selected and are != All
	else if ($selecteddpt !='' && $selecteddpt !='All' && $seldivision !='' && $seldivision !='All' && $selbody == 1/*&& ($seldivision =='' || $seldivision =='All') && ($selbody == 1 || $selbody =='')*/)
		{
			$theresult= $mysqli->query('SELECT * FROM resolutions7 WHERE resp1 =\''.$selecteddpt.'\' AND resp2 =\''.$seldivision.'\' ORDER BY year, reference2') //or die (mysql_error());

			or die ('Error: ' . mysqli_error($mysqli));

			// Count number of results of my query
			$nbresult = mysqli_num_rows($theresult);

		echo '<div id="searchresultsstats">Selected Department : <strong>'.$selecteddpt.'</strong> - Division : <strong>'.$seldivision.'</strong> <br/> 
		<span style="color:#FF0000;">Number of entries: '.$nbresult.'</span><br/></div>';				
			}		
	
/* //////////// LTG - SO //////////////*/			
	//Only LTG is selected and is != All or SO = All	
		
	else if ($selltg !='' && $selltg !='All'  && ($selectedso =='' || $selectedso =='All') && ($selbody == 1 || $selbody ==''))
		{
			$theresult= $mysqli->query('SELECT * FROM resolutions7  WHERE  kw1 =\''.$selltg.'\'   ORDER BY year, reference2') //or die (mysql_error());

			or die ('Error: ' . mysqli_error($mysqli));

			// Count number of results of my query
			$nbresult = mysqli_num_rows($theresult);

		echo '<div id="searchresultsstats">Selected Long-Term Goal: <strong>'.$selltg.'</strong><br/> 
		<span style="color:#FF0000;">Number of entries: '.$nbresult.'</span><br/></div>';				
			}		
		
	//Only LTG is selected and is == All 
	else if ($selltg =='All' && ($selectedso =='' || $selectedso =='All') && ($selbody == 1 || $selbody ==''))
		{
			$theresult= $mysqli->query('SELECT * FROM resolutions7  WHERE  kw1 !=\'\' ORDER BY year, reference2') //or die (mysql_error());

			or die ('Error: ' . mysqli_error($mysqli));

			// Count number of results of my query
			$nbresult = mysqli_num_rows($theresult);

		echo '<div id="searchresultsstats">Selected Long-Term Goal: <strong>'.$selltg.'</strong><br/> 
		<span style="color:#FF0000;">Number of entries: '.$nbresult.'</span><br/></div>';				
			}				
			
	//Only SO is selected and != ALL and LTG == All or empty
	else if ($selectedso !='' && $selectedso !='All'  && ($selltg =='' || $selltg =='All') && ($selbody == 1 || $selbody ==''))
		{
			$theresult= $mysqli->query('SELECT * FROM resolutions7  WHERE  kw2 =\''.$selectedso.'\'   ORDER BY year, reference2') //or die (mysql_error());

			or die ('Error: ' . mysqli_error($mysqli));

			// Count number of results of my query
			$nbresult = mysqli_num_rows($theresult);

		echo '<div id="searchresultsstats">Selected Strategic Objective : <strong>'.$selectedso.'</strong><br/> 
		<span style="color:#FF0000;">Number of entries: '.$nbresult.'</span><br/></div>';				
			}			
	
	//Only SO is selected and == ALL and LTG == All or empty
	else if ($selectedso =='All'  && ($selltg =='' || $selltg =='All') && ($selbody == 1 || $selbody ==''))
			{
				$theresult= $mysqli->query('SELECT * FROM resolutions7  WHERE  kw2 !=\'\'  ORDER BY year, reference2') //or die (mysql_error());

				or die ('Error: ' . mysqli_error($mysqli));

				// Count number of results of my query
				$nbresult = mysqli_num_rows($theresult);

			echo '<div id="searchresultsstats">Selected Strategic Objective : <strong>'.$selectedso.'</strong><br/>
			<span style="color:#FF0000;">Number of entries: '.$nbresult.'</span><br/></div>';				
				}	

	//LTG is selected and SO is selected and are != All
	else if ($selltg !='' && $selltg !='All' && $selectedso !='' && $selectedso !='All' && $selbody == 1/*&& ($seldivision =='' || $seldivision =='All') && ($selbody == 1 || $selbody =='')*/)
		{
			$theresult= $mysqli->query('SELECT * FROM resolutions7 WHERE kw1 =\''.$selltg.'\' AND kw2 =\''.$selectedso.'\' ORDER BY year, reference2') //or die (mysql_error());

			or die ('Error: ' . mysqli_error($mysqli));

			// Count number of results of my query
			$nbresult = mysqli_num_rows($theresult);

		echo '<div id="searchresultsstats">Selected Long-Term Goal: <strong>'.$selltg.'</strong> - Strategic Objective : <strong>'.$selectedso.'</strong> <br/> 
		<span style="color:#FF0000;">Number of entries: '.$nbresult.'</span><br/></div>';				
			}				
			
///////////////////XXXXXXXXXXXXXXXX/////////////////////*/
	// if none of the above condition is met we display all
		else 
			{
			
			$theresult= $mysqli->query('SELECT * FROM resolutions7  ORDER BY year, reference2') or die ('Error: ' . mysqli_error($mysqli));
			$nbresult = mysqli_num_rows($theresult);

			echo '<div id="searchresultsstats">No selection <br/> Total entries:&nbsp;<strong>'.$nbresult.'</strong></div>';
			
			}
			
/* ////////////  RESULTS //////////////*/			
		
		
			if(mysqli_num_rows($theresult) <> '' ) 
				{
					while ($donnees_res = mysqli_fetch_array($theresult) )

						 {
					echo
					'<div id="details"><strong>';
					
					if ($donnees_res['link']!='')
					{echo '<a href="'.htmlentities ($donnees_res['link']).'" target="_blank">'.htmlentities ($donnees_res['reference']).'</a>';}
						else
						{echo htmlentities ($donnees_res['reference']);	}
					echo'</strong>&nbsp;-&nbsp;'.htmlentities ($donnees_res['title']).'&nbsp;('.($donnees_res['year']).') <br/>('.htmlentities ($donnees_res['kw1']).')'.htmlentities ($donnees_res['content']);
						if (htmlentities ($donnees_res['status'])!='')
						{echo '<h3>Progress</h3>'.htmlentities ($donnees_res['status']).'<br/>'; } 
						if(htmlentities ($donnees_res['recommendation'])!='')
						{echo '<h3>Recommendation</h3>'.htmlentities ($donnees_res['recommendation']);}
						if(htmlentities ($donnees_res['resp1'])!='')
						{echo '<h3>Responsible Department/Entity</h3>'.htmlentities ($donnees_res['resp1']);}
						if(htmlentities ($donnees_res['resp2'])!='')
						{echo '<h3>Responsible unit</h3>'.htmlentities ($donnees_res['resp2']);}

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

	$mysqli -> close();// deconnexion 

?>
</body>
</html>
