<?php

function runmysql_createrec($tblname,$varlist,$vallist,$mysqli) {
	#make string from varlist
	$varstr = "(";
	foreach ($varlist as $var) {
		$varstr .= $var . ",";
	}
	$varstr = substr_replace($varstr,")",-1);
	#make string from vallist
	$valstr = "(";
	foreach ($vallist as $val) {
		$val = $mysqli->real_escape_string($val);
		$valstr .= "'" . $val . "',";
	}
	$valstr = substr_replace($valstr,")",-1);
	
	$qry = "INSERT INTO " . $tblname . $varstr . " VALUES" . $valstr;
	//echo $qry;
	if ($result = $mysqli->query($qry)) {
				
	} else {
		echo "unable to process query string: " . $qry;
		$mysqli->close();
		exit;
	}
}

function runmysql_getdatasetinfo($tblname,$varlist,$mysqli) {
	#make string from varlist
	$varstr = "";
	foreach ($varlist as $var) {
		$varstr .= $var . ",";
	}
	$varstr = substr_replace($varstr,"",-1);
	
	$qry = "SELECT " . $varstr . " FROM " . $tblname;
	//echo $qry;
	if ($result = $mysqli->query($qry)) {
		return $result;
	} else {
		echo "unable to process query string: " . $qry;
		$mysqli->close();
		exit;
	}
}
function runmysql_getdatasetinfo_single($tblname,$varlist,$mysqli,$dsetid) {
	#make string from varlist
	$varstr = "";
	foreach ($varlist as $var) {
		$varstr .= $var . ",";
	}
	$varstr = substr_replace($varstr,"",-1);
	
	$qry = "SELECT " . $varstr . " FROM " . $tblname . " WHERE dsetid='" . $dsetid . "'";
	//echo $qry;
	if ($result = $mysqli->query($qry)) {
		return $result;
	} else {
		echo "unable to process query string: " . $qry;
		$mysqli->close();
		exit;
	}
}

function runmysql_updatedsetinfo($tblname,$dsetid,$varlist,$valdict,$mysqli) {
	#make string from varlist
	$varstr = "";
	foreach ($varlist as $var) {
		$varstr .= $var . "='" . $valdict[$var] . "',";
	}
	$varstr = substr_replace($varstr,"",-1);
	
	$qry = "UPDATE " . $tblname . " SET " . $varstr . " WHERE dsetid=" . $dsetid;
	//echo $qry;
	if ($result = $mysqli->query($qry)) {
		return $result;
	} else {
		echo "unable to process query string: " . $qry;
		$mysqli->close();
		exit;
	}
}

function check_tableexists($tblname,$mysqli) {
	$qry = "SELECT * FROM information_schema.tables WHERE table_schema = 'replic_sl' AND table_name = '" . $tblname . "' LIMIT 1";
	#echo $qry;
	if ($result = $mysqli->query($qry)) {
		if ($result->num_rows > 0) {
			return "Y";
		} else {
			return "N";
		}
	} else {
		echo "unable to process query string: " . $qry;
		$mysqli->close();
		exit;
	}
}

?>