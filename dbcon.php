<?php

$con = mysqli_connect("localhost","root","");
    if(!$con)
{
    	die("Cannot connect." . mysqli_error());
}
mysqli_select_db($con, "healthrecord") or die("No database selected.");

?>