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
	#read filelist.txt for list of tide gauges in dataset - each has separate file in repository
	$varlist = array("dsetdloadloc","dsetdloadtype","dsetname","dsetdloaddate");
	$dset_result = runmysql_getdatasetinfo_single("datasets",$varlist,$mysqli,$dsetid);
	$dset_row = $dset_result->fetch_assoc();
	
	$filelistloc = $dset_row["dsetname"] . "/rlr_monthly/filelist.txt";
	$filelistf = fopen($filelistloc,'r');
	$filelistdat = fread($filelistf,filesize($filelistloc));
	fclose($filelistf);
	$filelistarr = explode("\n",$filelistdat);
	
	#echo count($filelistarr);
	#echo $dset_row["dsetname"];
	#now need to create mysql table for this dataset, each line from filelist being a record
	#check if table already exists and delete if necessary
	$existcheck = check_tableexists($dset_row["dsetname"],$mysqli);
	#echo $existcheck;
	if ($existcheck == "Y") {
		#delete table
		#echo "dataset index deleted";
		$qry = "DROP TABLE " . $dset_row["dsetname"];
		if ($result = $mysqli->query($qry)) {
			#
		} else {
			echo "unable to process query string: " . $qry;
			$mysqli->close();
			exit;
		}
	}
		
	$qry = "CREATE TABLE " . $dset_row["dsetname"] . " ( `tgid` INT(8) NOT NULL , `tglat` FLOAT(10) NOT NULL , `tglon` FLOAT(10) NOT NULL , `tgname` VARCHAR(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL , `coastlineid` VARCHAR(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL , `stationnum` VARCHAR(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL , `qcflag` VARCHAR(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL , PRIMARY KEY (`tgid`)) ENGINE = MyISAM CHARSET=utf8mb4 COLLATE utf8mb4_bin";
	if ($result = $mysqli->query($qry)) {
		#
		#echo "dataset index created";
	} else {
		echo "unable to process query string: " . $qry;
		$mysqli->close();
		exit;
	}
		
	$tglisttxt = "["; #initialise tg list in txt format
	foreach($filelistarr as $tg) {
		$vallist_raw = explode(";",$tg);
		$vallist = array_map('trim', $vallist_raw);
		$varlist = array("tgid","tglat","tglon","tgname","coastlineid","stationnum","qcflag");
		if (count($vallist_raw) == 7) {
			$tglisttxt .= $vallist[0] . ",";
			runmysql_createrec($dset_row['dsetname'],$varlist,$vallist,$mysqli);
			//now create a table for the tide gauge data - one table for each tide gauge
			/*$existcheck = check_tableexists($dset_row["dsetname"] . "_" . $vallist[0],$mysqli);
			if ($existcheck == "Y") {
				#delete table
			} else {
				$qry = "CREATE TABLE " . $dset_row["dsetname"] . "_" . $vallist[0] . " ( `date` FLOAT(10) NOT NULL , `meansl` INT(8) NOT NULL , `missingdays` INT(2) NOT NULL , `qcflag` VARCHAR(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL , UNIQUE `date` (`date`)) ENGINE = MyISAM CHARSET=utf8mb4 COLLATE utf8mb4_bin";
				if ($result = $mysqli->query($qry)) {
					#
				} else {
					echo "unable to process query string: " . $qry;
					$mysqli->close();
					exit;
				}
			}
				
			#and finally add the data for the tide gauge record, from the corresponding file in the data directory
			$dataloc = $dset_row["dsetname"] . "/rlr_monthly/data/" . $vallist[0] . ".rlrdata";
			$dataf = fopen($dataloc,'r');
			$datadat = fread($dataf,filesize($dataloc));
			fclose($dataf);
			$dataarr = explode("\n",$datadat);
				
			$thistgid = $vallist[0];
				
			foreach ($dataarr as $dpoint) {
				$vallist_raw = explode(";",$dpoint);
				$vallist = array_map('trim',$vallist_raw);
				$varlist = array("date","meansl","missingdays","qcflag");
				if (count($vallist_raw) == 4) {
					runmysql_createrec($dset_row['dsetname'] . "_" . $thistgid,$varlist,$vallist,$mysqli);
				} else {
					#is an empty row, presumably end of file
				}
			}*/
			
		} else {
			#is an empty row, presumably end of file
		}
	}
		
	$varlist = array("dsetdate");
	$valdict = array("dsetdate" => $dset_row["dsetdloaddate"]);
	runmysql_updatedsetinfo("datasets",$dsetid,$varlist,$valdict,$mysqli);
			
	//send back progress bar info
	#$htmlout = "<label for='dbase'>Database update progress:</label><progress id='dbaseprog' value='0' max='" . $validtgs . "'> 0% </progress>";
	$tglisttxt = substr_replace($tglisttxt,"]",-1);
	echo $tglisttxt;
		
}
	

?>