<?php
#triggered by rundbaseupdstart javascript from mainmenu.html, to create individual tables for each tg in dataset, iterated once for each tg
#different processes required for different dataset providers/data types
#return to mainmenu with ajax text update to fill progress bar

include 'mysqlfuncs.php';
require_once('dbase_init.php');

$dsetid = $_GET['dsetid'];
$dsettype = $_GET['dsettype']; #e.g. psmsl
$tgid = $_GET['tgid'];

if ($dsettype == "psmsl") {
	#get dset info from datasets table
	$varlist = array("dsetname");
	$dsetinfo_result = runmysql_getdatasetinfo_single("datasets",$varlist,$mysqli,$dsetid);
	$dset_row = $dsetinfo_result->fetch_assoc();
				
	#now delete the table for the tide gauge data - one table for each tide gauge
	$existcheck = check_tableexists($dset_row["dsetname"] . "_" . $tgid,$mysqli);
	if ($existcheck == "Y") {
		#delete table
		$qry = "DROP TABLE " . $dset_row["dsetname"] . "_" . $tgid;
		if ($result = $mysqli->query($qry)) {
			#
		} else {
			echo "unable to process query string: " . $qry;
			$mysqli->close();
			exit;
		}
		
	} else {
		#echo "error: table doesn't exist";
	}

	#echo "1";
}

?>