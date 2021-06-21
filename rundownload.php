<?php
#to download and setup latest database from tide gauge repository
#get marker info in latest data - eg update date - and add to server database as reference

include 'mysqlfuncs.php';
require_once('dbase_init.php');

$dsetid = $_GET['dsetid'];

$varlist = array("dsetdloadloc","dsetdloadtype","dsetname");
$dset_result = runmysql_getdatasetinfo_single("datasets",$varlist,$mysqli,$dsetid);
$dset_row = $dset_result->fetch_assoc();
$tgdatfile = $dset_row["dsetdloadloc"];
if ($dset_row["dsetdloadtype"]=="zip") {
	$floctarg = $dset_row["dsetname"] . ".zip";
	
	file_put_contents($floctarg, fopen($tgdatfile,'r'));
	//echo "downloading...";

	$tgzip = new ZipArchive;
	if ($tgzip->open($floctarg) === TRUE) {
		$tgzip->extractTo($dset_row["dsetname"]);
		$tgzip->close();
		//echo 'unzipped';
		$dbdatefile = fopen($dset_row["dsetname"] . '/rlr_monthly/extracted_from_database','r');
		$dbdateread = fread($dbdatefile,filesize($dset_row["dsetname"] . '/rlr_monthly/extracted_from_database'));
		fclose($dbdatefile);
		
		$varlist = array("dsetdloaddate");
		$valdict = array("dsetdloaddate" => $dbdateread);
		runmysql_updatedsetinfo("datasets",$dsetid,$varlist,$valdict,$mysqli);
		
		echo $dbdateread;
		
	} else {
		echo 'failed';
		exit;
	}
	
	
} else {
	echo "no options for other download types yet...";
	exit;
}

?>