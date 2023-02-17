<?php

require './assets/php/functions.php';

// Check Session
if (!isset($_SESSION["login"])){
    header('Location: ./assets/php/login.php');
}


// Make query to database
$products = query("SELECT * FROM $tableProduct ORDER BY quantity DESC");
$items = query("SELECT * FROM $tableTemp ORDER BY time ASC");
mysqli_query($conn,"SELECT * FROM $tableTemp");

if (mysqli_affected_rows($conn)){
    $filled = true;
}

// Count total price
$total = 0;
foreach($items as $item){
    $discount = $item["discount"]/100 * $item["price"];
    $priceItem = ($item["price"]-$discount)*$item["quantity"];
    $total += $priceItem;
}
$total = number_format($total);

if(isset($_SESSION['customer'])){
    $customer = $_SESSION['customer'];
}else{
    $customer = "";
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cash Register</title>
    <link rel="icon" href="./assets/images/<?= $logo ?>">
    <link rel="stylesheet" href="./assets/style/style.css">
    <script src="./assets/js/jquery.js"></script>
    <script src="./assets/js/script.js"></script>
</head>

<body>
    <div class="head">
        <a href="./assets/php/logout.php" class="absolLink logout">LogOut</a>
        <h1>Cash Register</h1>
        <img src="./assets/images/<?= $logo ?>" class="logo" alt="Brand's Logo">
    </div>
    <form action="" method="post">
        <input type="text" name="searchBarIndex" id="searchBarIndex" class="searchBarIndex searchBar" autofocus
            placeholder="Search Here..." autocomplete="off">
    </form>
    <a href="./assets/php/adminPage.php" class="absolLink">Admin Page</a>
    <img src="./assets/images/loading.gif" id="loading">
    <div class="data" id="containerIndex">
        <h1 class="headLine">List Item</h1>
        <table cellspacing=0 cellpadding=20>
            <tr>
                <th style="width:5%">No.</th>
                <th style="width:45%">Name</th>
                <th style="width:25%">Price</th>
                <th style="width:25%">Actions</th>
            </tr>
            <?php
                $i = 1 ;
                foreach ($products as $product ):?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= $product['nameProduct'];?></td>
                <td><?= $currency ?> <?php $price = number_format($product['price']);
                    echo $price;?></td>
                <td>
                    <button type="submit" name="btnAdd" id="btnAdd" class="btnAdd button"
                        onclick=addToCart(<?= $product["id"];?>)>Add To Cart</button>
                </td>
            </tr>
            <?php endforeach;?>
        </table>
    </div>
    <div class="shoppingCart">
        <h1 class="headLine">Shopping Cart</h1>
        <?php foreach ($items as $item): ?>
        <ul?>
            <li>
                <?= $item["nameProduct"]; ?>
            </li>
            <li>
                <button type="submit" class="button spgcrtBtn" id="minBtn" onclick=minItem(<?= $item["id"];?>)> -
                </button>
                <input type="number" class="textBox spgcrtTxtbx" name="quantity" id="quantity<?= $item["id"];?>"
                    value="<?= $item["quantity"];?>" min="1" onchange=manualUpdate(<?= $item["id"];?>) autocomplete="off">
                <button type="submit" class="button spgcrtBtn" id="plusBtn" onclick=plusItem(<?= $item["id"];?>)> +
                </button>
            </li>
            <li>
                <label for="discountItem<?= $item["id"];?>">Aditional Dicount (%) :</label>
                <input type="number" style="width : 156px" class="textBox spgcrtTxtbx"
                    name="discountItem<?= $item["id"];?>" id="discountItem<?= $item["id"];?>"
                    value="<?= $item["discount"];?>" onchange=discount(<?= $item["id"];?>) autocomplete="off">
            </li>
            <li>
                <?= $currency ?>
                <?php   
                    $discount = $item["discount"]/100 * $item["price"];
                    $price = ($item["price"]-$discount)*$item["quantity"];
                    $price = number_format($price);
                    echo $price;
                ?>
                <button style="margin-left:20px; width : 200px" class="button spgcrtBtn" name="deleteItmBtn"
                    id="deleteItmBtn<?= $item["id"]; ?>" onclick=deleteItm(<?= $item["id"];?>)>
                    Delete Item</button>
            </li>
            <hr>
            </ul>
            <?php endforeach;?>
            <ul>
                <?php if(isset($filled)):?>
                <li>
                    <label for="customer">Customer Name :</label>
                    <input type="text" style="width : 250px; margin : 10px;" class="textBox spgcrtTxtbx" name="customer"
                        id="customer" value="<?= $customer ?>" autocomplete="off" onchange=customer()>
                </li>
                <?php endif;?>
            </ul>
            <h2>Total : <?= $currency ?> <?= $total ?> </h2>
            <button type="submit" class="button spgcrtBtn2" onclick=printing()>Print</button>
            <button type="submit" class="button clearcart spgcrtBtn2" onclick=ClearCart()>Clear Cart</button>
    </div>
</body>

</html>