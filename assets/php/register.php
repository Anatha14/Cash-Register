<?php

require "./functions.php";
// Check Session
if (!isset($_SESSION["login"])){
    header('Location: ./login.php');
}

// Check user / admin
$role = $_SESSION["role"];

if($role === "user"){
    echo "
        <script>
            alert('FORBIDEN AUTHORIZED PERSONS ONLY');
            document.location.href = '../../index.php';
        </script>
    ";
}

// Check register button
if (isset($_POST["registerBTN"])){

    // Run function register()
    if (register($_POST) > 0){
        echo "
            <script>
            alert('Successfully Added New User');
            document.location.href = '../../index.php';
            </script>
        ";
    }
    
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register New User</title>
    <link rel="icon" href="../images/<?= $logo ?>">
    <link rel="stylesheet" href="../style/style.css">
</head>

<body>
    <a href="./adminPage.php" class="absolLink logout">Cancel</a>
    <h1>Register New User</h1>
    <form action="" method="post" class="form">
        <ul>
            <li>
                <label for="username">Username :</label><br>
                <input type="text" name="username" id="username" require class="textBox" autocompletet="off">
            </li>
            <li>
                <label for="password">Password :</label><br>
                <input type="password" name="password" id="password" require class="textBox">
            </li>
            <li>
                <label for="rePassword">Re-Enter Password :</label><br>
                <input type="password" name="rePassword" id="rePassword" require class="textBox">
            </li>
            <li>
                <label>Role :</label><br>
                <select id="role" name="role" class="textBox">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </li>
            <li>
                <button type="submit" name="registerBTN" class="button">Register</button>
            </li>
        </ul>
    </form>
</body>

</html>