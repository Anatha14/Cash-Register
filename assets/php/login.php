<?php

require "./functions.php";

// Clear Shopping Cart
mysqli_query($conn,"TRUNCATE TABLE $tableTemp");

// Check cookie
if (isset($_COOKIE[$usrnm]) && isset($_COOKIE[$rle])){
    $_SESSION["login"] = true;
    $_SESSION["role"] = $_COOKIE[$rle];
    $usernameCookie = $_COOKIE[$usrnm];
    $usernameCookie = trim($usernameCookie,"$salt1$salt2");
    $_SESSION["username"] = $usernameCookie;
}

// Check is there a session or not
if (isset($_SESSION["login"])){
    header('Location: ../../index.php');
}

// Check login button 
if (isset($_POST["loginBTN"])){
    
    // Take username and Password from $_POST
    $username = $_POST["username"];
    $password = $_POST["password"];

    $usernameHash = hash("SHA256",$salt1.$username.$salt2);

    // Search username in database
    $query = "SELECT * FROM $tableUser WHERE username = '$usernameHash'";
    $checkUsername = mysqli_query($conn,$query);

    // Check is the username valid
    if (mysqli_num_rows($checkUsername) === 1){
        $user = mysqli_fetch_assoc($checkUsername);
        if (password_verify($password,$user["password"])){

            // Take user role and username
            $role = $user["role"];

            // Set session
            $_SESSION["login"] = true;
            $_SESSION["role"] = $role;
            $_SESSION["username"] = $_POST["username"];

            // Check rememberme box
            if(isset($_POST["rememberMe"])){

                // Create uniqe username
                $name = $salt1.$_POST["username"].$salt2;
                // Create cookie
                setcookie("u1Se3a1c2eN6a1f1r1e",$name,time()+604800,"/");
                setcookie("1a2x4v3e1h2ej2klo1p1y3o",$user["role"],time()+604800,"/");
            }

            header("Location: ../../index.php");
            exit;
        }
    }

    $error = true;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In Page</title>
    <link rel="stylesheet" href="../style/style.css">
    <link rel="icon" href="../images/<?= $logo ?>">
</head>
<body>
    <h1>Log In Page</h1>
    <form action="" method="post" class="form">
        <ul>
            <li>
                <label for="username">Username :</label><br>
                <input type="text" name="username" id="username" require class="textBox" autocomplete="off">
            </li>
            <li>
                <label for="password">Password :</label><br>
                <input type="password" name="password" id="password" require class="textBox">
            </li>
            <li>
                <input type="checkbox" name="rememberMe" id="rememberMe"> Remember Me... <span style ="font-size:15px;" >(Not Recommended For Admin)</span>
            </li>
            <li>
                <button type="submit" name ="loginBTN" class="button"> Log In</button>
            </li>
        </ul>
    </form>
    <div class="errormsg">
        <?php if(isset($error)):?>
        <h2>Wrong Username / Password</h2>
        <?php endif;?>
    </div>
</body>
</html>