<?php
session_start();

include_once "config.php";

$email = mysqli_real_escape_string($cnx, $_POST["email"]);
$password = mysqli_real_escape_string($cnx, $_POST["password"]);


if(!empty($email) && !empty($password)){
    //Let's check if email and password are saved in the database
    $sql = mysqli_query($cnx,"SELECT * FROM users WHERE email = '{$email}' AND password = '{$password}'");
    if(mysqli_num_rows($sql) > 0){
        $row = mysqli_fetch_assoc($sql);
        $status = "Active now";
        // updating status to active now when the user logs in
        $sql2 = mysqli_query($cnx, "UPDATE users SET status = '{$status}' WHERE unique_id = {$row['unique_id']}");
        if($sql2){
            $_SESSION["unique_id"] = $row["unique_id"]; // using this session we used user unique_id in other php file
            echo "Success";
        }
    }
    else{
        echo "Email or Password is incorrect!";
    }
}
else{
    echo "All input fields are required!";
}

?>