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
				
	#now create a table for the tide gauge data - one table for each tide gauge
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
		$qry = "CREATE TABLE " . $dset_row["dsetname"] . "_" . $tgid . " ( `date` FLOAT(10) NOT NULL , `meansl` INT(8) NOT NULL , `missingdays` INT(2) NOT NULL , `qcflag` VARCHAR(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL , UNIQUE `date` (`date`)) ENGINE = MyISAM CHARSET=utf8mb4 COLLATE utf8mb4_bin";
		if ($result = $mysqli->query($qry)) {
			#
		} else {
			echo "unable to process query string: " . $qry;
			$mysqli->close();
			exit;
		}
	}
				
	#and finally add the data for the tide gauge record, from the corresponding file in the data directory
	$dataloc = $dset_row["dsetname"] . "/rlr_monthly/data/" . $tgid . ".rlrdata";
	$dataf = fopen($dataloc,'r');
	$datadat = fread($dataf,filesize($dataloc));
	fclose($dataf);
	$dataarr = explode("\n",$datadat);
				
	foreach ($dataarr as $dpoint) {
		$vallist_raw = explode(";",$dpoint);
		$vallist = array_map('trim',$vallist_raw);
		$varlist = array("date","meansl","missingdays","qcflag");
		if (count($vallist_raw) == 4) {
			runmysql_createrec($dset_row['dsetname'] . "_" . $tgid,$varlist,$vallist,$mysqli);
		} else {
			#is an empty row, presumably end of file
		}
	}

	echo "1";
}

?>