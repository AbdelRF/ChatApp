<?php
session_start();

include_once "config.php";

$fname = mysqli_real_escape_string($cnx, $_POST['fname']);
$lname = mysqli_real_escape_string($cnx, $_POST["lname"]);
$email = mysqli_real_escape_string($cnx, $_POST["email"]);
$password = mysqli_real_escape_string($cnx, $_POST["password"]);

if(!empty($fname) && !empty($lname) && !empty($email) && !empty($password)){
    // Let's check if the user email is valid or not
    if(filter_var($email , FILTER_VALIDATE_EMAIL)){// if email is valid
        // checking if the email exists already in the database
        $sql = mysqli_query($cnx,"SELECT email FROM users WHERE email = '{$email}'");
        if(mysqli_num_rows($sql) > 0){// means email exists
            echo "$email - This email already exists!";
        }
        else{
            //let's check if the user uploaded a file or not
            if(isset($_FILES["image"])){
                $img_name = $_FILES["image"]["name"]; // getting user uploaded img name
                $tmp_name = $_FILES["image"]["tmp_name"]; // Temporary name used to save/move file in our folder

                // Let's explode the image and get the extension
                $img_explode = explode(".",$img_name);
                $img_ext = end($img_explode); // here we get the extension of the uploaded image

                $extensions = ["png" , "jpeg" , "jpg"];
                if(in_array($img_ext , $extensions) === true){ // if uploaded img ext matches any of the array extansions
                    $time = time(); // returning current time
                                    // need this cause the image name in our folder will be unique and will contain
                                    // the time of the upload
                    // Now let's move the uploaded image into our particular folder
                    $new_img_name = $time.$img_name;
                    if(move_uploaded_file($tmp_name, "images/".$new_img_name)){
                        $status = "Active now"; // Once the user is signed up, his status will be active now
                        $random_id = rand(time(),10000000); // creating random id for our user

                        // Let's insert all user data into our table
                        $sql2 = mysqli_query($cnx,"INSERT INTO users (unique_id, fname, lname, email, password, img, status)
                                                    VALUES ({$random_id}, '{$fname}', '{$lname}', '{$email}', '{$password}', '{$new_img_name}', '{$status}')");
                        if($sql2){
                            $sql3 = mysqli_query($cnx,"SELECT * FROM users WHERE email = '{$email}'");
                            if(mysqli_num_rows($sql3) > 0){
                                $row = mysqli_fetch_assoc($sql3);
                                $_SESSION["unique_id"] = $row["unique_id"]; // using this session we used user unique_id in other php file
                                echo "Success";
                            }
                        }
                        else{
                            echo "Something went wrong!";
                        }                            
                    }
                    
                }
                else{
                    echo "Please insert an image file - PNG, JPEG, JPG";
                }
            }
            else{
                echo "Please select an image";
            }
        }
    }
    else{
        echo "$email - This is not a valid email!";
    }
}
else{
    echo "All input fields are required";
}

?>