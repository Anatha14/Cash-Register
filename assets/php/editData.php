<?php

require './functions.php';

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

// Take id from link
$id = $_GET["id"];

// Take data from database
$query = "SELECT * FROM $tableProduct WHERE id = $id";
$result = mysqli_query($conn,$query);
$product = mysqli_fetch_assoc($result);

// Check edit button
if (isset($_POST["editBTN"])){

    // Take data to function edit()
    if (edit($_POST) > 0){
        echo "
        <script>
            alert('Successfully Edit Data');
            document.location.href = './adminPage.php';
        </script>
    ";
    }else{
        echo "
            <script>
            alert('UNSUCCESSFULLY EDIT DATA');
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
    <title>Edit Data</title>
    <link rel="icon" href="../images/<?= $logo ?>">
    <link rel="stylesheet" href="../style/style.css">
    <script src="../js/jquery.js"></script>
    <script src="../js/script.js"></script>
</head>

<body>
    <a href="./adminPage.php" class = "absolLink logout">Cancel</a>
    <h1>Edit Data</h1>
    <form action="" method="post" class="form">
        <input type="hidden" name="id" value="<?= $product["id"]?>">
        <ul>
            <li>
                <label for="productName">Product Name   :</label><br>
                <input type="text" name="productName" id="productName" require class="textBox" value="<?= $product["nameProduct"] ?>" autocomplete="off">
            </li>
            <li>
                <label for="price">Price :</label><br>
                <input type="text" name="price" id="price" require class="textBox" value="<?= number_format($product["price"]) ?>" autocomplete="off" onkeyup="formatNumber('price')"> 
            </li>
            <li>
                <label for="quantity">Quantity :</label><br>
                <input type="text" name="quantity" id="quantity" require class="textBox" value="<?= number_format($product["quantity"]) ?>" autocomplete="off" onkeyup="formatNumber('quantity')">
            </li>
            <li>
                <button type="submit" name = "editBTN" class="button">Edit !!!</button>
            </li>
        </ul>
    </form>
</html>