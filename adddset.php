<?php

#triggered by form run from mainmenu.html
#adds a dataset record to the datasets table in mysql
#returns to mainmenu when complete

include 'mysqlfuncs.php';
require_once('dbase_init.php');

$dsetname = $_POST['dsetname'];
$dsetdloadtype = $_POST['dsetdloadtype'];
$dsetdloadloc = $_POST['dsetdloadloc'];
$dsettimeres = $_POST['dsettimeres'];

$varlist = array("dsetname","timeresolution","dsetdloadloc","dsetdloadtype");
$vallist = array($dsetname,$dsettimeres,$dsetdloadloc,$dsetdloadtype);

runmysql_createrec("datasets",$varlist,$vallist,$mysqli);

$suffix = "/sl/mainmenu.html";
if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
	$uri = 'https://';
} else {
	$uri = 'http://';
}
$uri .= $_SERVER['HTTP_HOST'];
header('Location: '.$uri.$suffix);

?>