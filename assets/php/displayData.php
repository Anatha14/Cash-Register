<?php

require "./functions.php";
$keyword = $_GET["keyword"];

$page = $_GET["page"];

if($page == "index"){
    $query = "SELECT * FROM $tableProduct WHERE
                nameProduct LIKE '%$keyword%' OR
                price LIKE '%$keyword%' OR
                quantity LIKE '%$keyword%'
                ORDER BY quantity DESC";
}elseif ($page == "admin"){
    $query = "SELECT * FROM $tableProduct WHERE
            nameProduct LIKE '%$keyword%' OR
            price LIKE '%$keyword%' OR
            quantity LIKE '%$keyword%'
            ORDER BY nameProduct ASC";
}elseif ($page == "invoice"){
    $query = "SELECT number FROM $tableInvoice WHERE
            number LIKE '%$keyword%' ORDER BY number ASC";
}
$products = query($query);

?>

<?php if($page == "index"):?>
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
<?php endif; 
        if($page == "admin"):?>
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
<?php endif;?>
<?php if ($page =='invoice'):?>
<table border=1 cellspacing=0 cellpadding=10>
    <tr>
        <th>Invoice Number</th>
        <th>Action</th>
    </tr>
    <?php foreach($products as $product):?>
    <tr>
        <td><?= $product["number"] ?></td>
        <?php
                        $link = "./printedInvoice.php?invoice=".$product["number"];
                    ?>
        <td><button type="submit" name="btnAdd" id="btnAdd" class="btnAdd button"
                onclick="window.open('<?= $link ?>')">Check</button>
        </td>
    </tr>
    <?php endforeach;?>
</table>
<?php endif;?>