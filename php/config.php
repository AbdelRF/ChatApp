<?php

$cnx = new mysqli("localhost","root","","chat");

if(!$cnx){
    echo "Database connected" . mysqli_connect_error();
}

?>