<?php

require './functions.php';

// Check Session
if (!isset($_SESSION["login"])){
    header('Location: ./login.php');
}

// Check user / admin
$role = $_SESSION["role"];
$user = "user";
$user = hash("SHA256",$salt1.$user.$salt2);

if($role === $user){
    echo "
        <script>
            alert('FORBIDEN AUTHORIZED PERSONS ONLY');
            document.location.href = '../../index.php';
        </script>
    ";
}

// Make query to database
$products = query("SELECT * FROM $tableProduct ORDER BY nameProduct ASC")

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="icon" href="../images/<?= $logo ?>">
    <link rel="stylesheet" href="../style/style.css">
</head>

<body>
    <a href="../../index.php" class="absolLink logout">Exit</a>
    <a href="./register.php" class="absolLink newUser">Create New User</a>
    <h1>Admin Page</h1>
    <form action="" method="post">
        <input type="text" name="searchBarAdmin" id="searchBarAdmin" class="searchBarAdmin searchBar" autofocus placeholder="Search Here..."
            autocomplete="off">
    </form>
    <a href="./createNewData.php" class="absolLink admin">Create New Data</a>
    <a href="./invoiceCheck.php" class="absolLink invoice">Invoice Check</a>

    <img src="../images/loading.gif" id="loading">
    <div id="containerAdmin">
        <table border="1" cellspacing=0 cellpadding=10 class="tableAdmin">
            <tr>
                <th style="width:5%">No.</th>
                <th style="width:45%">Name</th>
                <th style="width:15%">Price</th>
                <th style="width:15%">Quantity</th>
                <th style="width:20%">Actions</th>
            </tr>
            <?php
                $i = 1 ;
                foreach ($products as $product ):?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= $product['nameProduct'];?></td>
                <td><?= $currency ?> <?php $price = number_format($product['price']);
                    echo $price;?></td>
                <td><?php $price = number_format($product['quantity']);
                    echo $price;?></td>
                <td>
                    <a href="./editData.php?id=<?= $product["id"]?>" class="link">Edit</a> ||
                    <a href="./deleteData.php?id=<?= $product["id"]?>" class="link"
                        onclick='return confirm("This Data Will Be Permanently Deleted");'>Delete</a>
                </td>
            </tr>
            <?php endforeach;?>
        </table>
    </div>
    
    <script src="../js/jquery.js"></script>
    <script src="../js/script.js"></script>
</body>

</html>