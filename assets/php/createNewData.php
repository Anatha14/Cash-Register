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

// Check submit button
if (isset($_POST["createBTN"])){

    // Take data to function create()
    if(create($_POST) > 0){
        echo "
        <script>
            alert('Successfully Added Data');
            document.location.href = './adminPage.php';
        </script>
    ";
    }else{
        echo "
            <script>
            alert('UNSUCCESSFULLY ADDED DATA');
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
    <title>Create New Data</title>
    <link rel="icon" href="../images/<?= $logo ?>">
    <link rel="stylesheet" href="../style/style.css">
</head>

<body>
    <a href="./adminPage.php" class="absolLink logout">Cancel</a>
    <h1>Create New Data</h1>
    <form action="" method="post" class="form">
        <ul>
            <li>
                <label for="productName">Product Name :</label><br>
                <input type="text" name="productName" id="productName" require class="textBox" autocomplete="off">
            </li>
            <li>
                <label for="price">Price :</label><br>
                <input type="text" name="price" id="price" require class="textBox nbrBox" autocomplete="off" onkeyup="formatNumber('price')">
            </li>
            <li>
                <label for="quantity">Quantity :</label><br>
                <input type="text" name="quantity" id="quantity" require class="textBox nbrBox" autocomplete="off" onkeyup="formatNumber('quantity')">
            </li>
            <li>
                <button type="submit" name="createBTN" class="button">Create !!!</button>
            </li>
        </ul>
    </form>
    <script src="../js/jquery.js"></script>
    <script src="../js/script.js"></script>
</body>

</html>