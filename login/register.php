<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'login');


//try connect db
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

//check connection
if($conn == false ){
    dir('Error: Cannot connect');
    
}

?>
<?php

$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  //CHECK IF USERNAME IS EMPTY

  if (empty(trim($_POST["username"]))) {
    $username_err = "username cannot be blank";
  } 
  else {
    $sql = "SELECT id FROM users WHERE username = ? ";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
      mysqli_stmt_bind_param($stmt, "s", $param_username);

      //set the value of param username
      $param_username = trim($_POST['username']);

      //try  to exectute this statement
      if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) == 1) {
          $username_err = "This username is already taken.";
        } else {
          $username = trim($_post['$username']);
        }
      } else {
        echo "Something went wrong";
      }
    }
  }

  // mysqli_close($stmt);

  //check for password
  if (empty(trim($_POST['password']))) {
    $password_err = "Password cannot be blank";
  } elseif (strlen(trim($_POST['password']))) {
    //pwd less than 5;
    $password_err = "password cannot be less then 5 characters";
  } else {
    $password = trim($_POST['password']);
  }

  //check for password
  if (trim($_POST['password']) !== trim($_POST['confirm_password'])) {
    $password_err = "password didn't match";
    //if no error and insert into database
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {
      $sql = "INSERT INTO users(username, password) 
          VALUES(?, ?)";
      $stmt = mysqli_prepare($conn, $sql);
      if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

        //set these parameters
        $param_username = $username;
        $param_password = password_hash($password, PASSWORD_DEFAULT);


        //try to execute the query
        if (mysqli_stmt_execute($stmt)) {
          header("location: login.php");
        } else {
          echo "Something went wrong cannot redirect.";
        }
      }
    }
    mysqli_stmt_close($stmt);
  }
  mysqli_close($conn);
}
?>




<!DOCTYPE html>
<html>

<head>
  <title>Registration Form</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f2f2f2;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 450px;
      margin: 50px auto;
      background-color: #fff;
      padding: 20px;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);

    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    label {
      display: block;
      font-weight: bold;
      margin-bottom: 5px;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 3px;
    }

    input[type="submit"] {
      background-color: #4caf50;
      color: #fff;
      border: none;
      padding: 10px 20px;
      border-radius: 3px;
      cursor: pointer;
    }

    input[type="submit"]:hover {
      background-color: #45a049;
    }
  </style>
</head>

<body>
  <div class="container">
    <h2>Registration Form</h2>
    <form action="" method="post">
      <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" id="name" name="username" required>
      </div>
      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
      </div>
      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
      </div>
      <div class="form-group">
        <label for="confirm-password">Confirm Password:</label>
        <input type="password" id="confirm-password" name="confirm_password" required>
      </div>
      <div class="form-group">
        <input type="submit" value="Register">
      </div>
    </form>
  </div>
</body>

</html>