<?php
//read through dataset download and process to load into mysql database table(s)
//different processes required for different dataset providers/data types
//update datasets table with new dsetdate
//return to mainmenu with ajax text updates reflecting what has happened

include 'mysqlfuncs.php';
require_once('dbase_init.php');

$dsetid = $_GET['dsetid'];
$dsettype = $_GET['dsettype']; #e.g. psmsl

#echo $dsetid;
#echo $dsettype;

if ($dsettype == "psmsl") {
	//delete dataset index table by finding name using dsetid
	$varlist = array("dsetdloadloc","dsetdloadtype","dsetname","dsetdloaddate");
	$dset_result = runmysql_getdatasetinfo_single("datasets",$varlist,$mysqli,$dsetid);
	$dset_row = $dset_result->fetch_assoc();
	
	//check if table exists and delete
	$existcheck = check_tableexists($dset_row["dsetname"],$mysqli);
	#echo $existcheck;
	if ($existcheck == "Y") {
		//delete table
		
		$qry = "DROP TABLE " . $dset_row["dsetname"];
		if ($result = $mysqli->query($qry)) {
			#
		} else {
			echo "unable to process query string: " . $qry;
			$mysqli->close();
			exit;
		}
	}
		
	$qry = "DELETE FROM datasets WHERE dsetid=" . $dsetid;
	if ($result = $mysqli->query($qry)) {
		
	} else {
		echo "unable to process query string: " . $qry;
		$mysqli->close();
		exit;
	}
			
	echo "deletion of " . $dset_row["dsetname"] . " complete";
		
}
	

?>