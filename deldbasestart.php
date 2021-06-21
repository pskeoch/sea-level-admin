<?php
#read through dataset download and process to load into mysql database table(s)
#different processes required for different dataset providers/data types
#update datasets table with new dsetdate
#return to mainmenu with ajax text updates reflecting what has happened

include 'mysqlfuncs.php';
require_once('dbase_init.php');

$dsetid = $_GET['dsetid'];
$dsettype = $_GET['dsettype']; #e.g. psmsl

#echo $dsetid;
#echo $dsettype;

if ($dsettype == "psmsl") {
	#read $dsetname table for list of tide gauges in dataset - each has separate file in repository
	$varlist = array("dsetdloadloc","dsetdloadtype","dsetname","dsetdloaddate");
	$dset_result = runmysql_getdatasetinfo_single("datasets",$varlist,$mysqli,$dsetid);
	$dset_row = $dset_result->fetch_assoc();
	
	$varlist = array("tgid");
	$dsetindex_result = runmysql_getdatasetinfo($dset_row["dsetname"],$varlist,$mysqli);
	
	$tglisttxt = "["; #initialise tg list in txt format
	while ($dsetindex_row = $dsetindex_result->fetch_assoc()) {
		$tglisttxt .= $dsetindex_row["tgid"] . ",";
	}
	
	$tglisttxt = substr_replace($tglisttxt,"]",-1);
	echo $tglisttxt;
		
} else {
	echo "some kind of problem here";
}
	

?>