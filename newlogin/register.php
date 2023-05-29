<?php
session_start();
// $_SESSION["name"] = "john";
// print_r($_SESSION);

if(!isset($_SESSION["user"])){
    header("Location: register.php");
}?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">

    <?php
        // print_r($_POST);
        if(isset($_POST["submit"])){
            $fullName = $_POST["fullname"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $passwordRepeat = $_POST["confirm_password"];

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $errors = array();

            if(empty($fullName) OR empty($email) OR empty($password) OR empty($passwordRepeat)){
                // print_r($_POST);
                array_push($errors, "All fields are required");
            }


            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                array_push($errors, "Email is not valid");
            }

            if(strlen($password)< 8){
                array_push($errors, "Password must be atleast 8 characters long");
            }

            if($password !== $passwordRepeat){
                array_push($errors, "Password does not match");
            }

            require_once "database.php";
            $sql = "SELECT * FROM users where email='$email'";
            $result = mysqli_query($conn, $sql);
            $rowCount = mysqli_num_rows($result);

            if($rowCount > 0){
                array_push($errors, "Email already exists");
            }
            if(count($errors) > 0){
                foreach ($errors as $error) {
                    echo "<div class = 'alert alert-danger'>$error</div>";
                }
            }
            else{
                //we will insert the data into our database
                $sql = "INSERT INTO users(full_name, email, password) VALUES(?, ?, ?)";

                $stmt = mysqli_stmt_init($conn);
                $prepareStmt = mysqli_stmt_prepare($stmt, $sql);
                if($prepareStmt){
                    mysqli_stmt_bind_param($stmt, "sss", $fullName, $email, $passwordHash);
                    mysqli_stmt_execute($stmt);

                    echo "<div class='alert-success'>You are registered sucessfully. </div>";

                }else{
                    die("Something went wrong");
                }
            }
        }
    ?>
        <form action="register.php" method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="fullname" placeholder="Full name:" >
                <input type="email" class="form-control" name="email" name="email" placeholder="Email:" >
                <input type="password" class="form-control" name="password" placeholder="password:" >
                <input type="password" class="form-control" name="confirm_password" placeholder="Confirm password" >
            </div>
            <div class="form-btn">
                <input type="submit" class="btn btn-primary" value="Register" name="submit">

            </div>
            
        </form>
        <div><p>Not registered yet? <a href="register.php"> Register Here</a></p></div>
    </div>
</body>
</html>