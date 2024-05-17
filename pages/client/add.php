
<?php

include('../dbcon.php');

if (isset($_POST['submit'])) {
    $id = $_POST['id'];
    $fname = $_POST['fname'];
    $minitial = $_POST['minitial'];
    $lname = $_POST['lname'];
    $birth_date = $_POST['birth_date'];
    $sex = $_POST['sex'];
    $mother_name = $_POST['mother_name'];
    $purok = $_POST['purok'];
    $address = $_POST['address'];

    mysqli_query($con, "INSERT INTO client
     VALUES ('$id', '$fname', '$minitial', '$lname', '$birth_date', '$sex', '$mother_name', '$purok', '$address')"); 

    header("Location: ".$_SERVER['HTTP_REFERER']);

} 


if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $fname = $_POST['fname'];
    $minitial = $_POST['minitial'];
    $lname = $_POST['lname'];
    $birth_date = $_POST['birth_date'];
    $sex = $_POST['sex'];
    $mother_name = $_POST['mother_name'];
    $purok = $_POST['purok'];
    $address = $_POST['address'];

    mysqli_query($con, "UPDATE client SET fname='$fname', minitial='$minitial', 
    lname='$lname', birth_date='$birth_date', sex='$sex', mother_name='$mother_name', purok='$purok', 
    address='$address' WHERE id = '$id'");

    header("Location: client-list.php");
    exit; 
}

?>
