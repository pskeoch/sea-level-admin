<?php
#get information from mysql database about latest dataset versions
#then format html display table, including download and mysql update options.

include 'mysqlfuncs.php';
require_once('dbase_init.php');

$varlist = array("dsetid","dsetname","timeresolution","dsetdate","dsetdloaddate");
$dsets_resultarr = runmysql_getdatasetinfo("datasets",$varlist,$mysqli);
if ($dsets_resultarr->num_rows > 0) {
	$htmlout = "<table><th>Dataset</th><th>Time resolution</th><th>Current database version</th><th>Latest download version</th></tr>";
	while($dsetrow = $dsets_resultarr->fetch_assoc()) {
		$htmlout .= "<tr><td>" . $dsetrow["dsetname"] . "</td><td>" . $dsetrow["timeresolution"] . "</td><td>" . $dsetrow["dsetdate"] . "<a href='javascript:rundbaseupdstart(" . $dsetrow["dsetid"] . ")'>Update</a>" . "</td><td>" . $dsetrow["dsetdloaddate"] . "<a href='javascript:rundsetdload(" . $dsetrow["dsetid"] . ")'>Download</a>" . "</td></tr>";
	}
	$htmlout .= "</table>";
	echo $htmlout;
} else {
	echo "no datasets currently in database";
}
?>