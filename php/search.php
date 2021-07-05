<?php

session_start();
include_once "config.php";
$outgoing_id = $_SESSION['unique_id'];
$searchTerm = mysqli_real_escape_string($cnx , $_POST["searchTerm"]);
$output = "";
$sql = mysqli_query($cnx,"SELECT * FROM users WHERE NOT unique_id = {$outgoing_id} AND (fname LIKE '%{$searchTerm}%' OR lname LIKE '%{$searchTerm}%')");

if(mysqli_num_rows($sql) > 0){
    include "data.php";
}
else{
    $output .= "No user found related to your search term";
}

echo $output;

?>