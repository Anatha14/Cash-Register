<?php

require './functions.php';

if (isset($_GET["action"])){

    $action = $_GET["action"];
    if ($action != "printSPC" && $action != "customer"){
        $id = $_GET["idProduct"];
    }
    if ($action == "customer"){
        $customerName = $_GET["customer"];
        $_SESSION["customer"] = $customerName;
    }
    if($action == "addToCart"){
        // Take data from database
        $thing = query("SELECT * FROM $tableProduct WHERE id = '$id'");
        $nameProduct = $thing[0]["nameProduct"];
        $price = $thing[0]["price"];
        $quantityMax = $thing[0]["quantity"];
        $time = time();

        // Check Stock
        if( $quantityMax == 0){
            echo "
                <script>
                    alert('Out Of Stock');
                </script>
            ";
            $query = "SELECT * FROM $tableTemp";
        }else{

            // Insert data to temp
            // Check is there data with same id or no? if there is same data just add the quantity
            $check = mysqli_query($conn,"SELECT * FROM $tableTemp WHERE id = $id");
            $takeData = query("SELECT * FROM $tableTemp WHERE id = $id");
            if (mysqli_num_rows($check) > 0 ){
                $quantity = $takeData[0]["quantity"];
                $quantity += 1;
                if($quantity <= $quantityMax){
                    $query = "UPDATE $tableTemp SET
                                quantity = '$quantity'
                                WHERE id = $id";
                }else{
                    echo "
                    <script>
                        alert('Maximum Quantity');
                    </script>
                    ";
                    $query ="UPDATE $tableTemp SET 
                                quantity = '$quantityMax'
                                WHERE id = $id";;
                }
            }else{
                $query = "INSERT INTO $tableTemp VALUES ('$id','$nameProduct','$price',0,'$quantityMax',1,'$time')";
            }
        }
    }elseif($action != "printSPC" && $action != "customer"){

        // Get quantity product
        $item= query("SELECT * FROM $tableTemp WHERE id = $id");
        $quantity = $item[0]["quantity"];
        $quantityMax = $item[0]["quantityMax"];

        if($action == "minBtn"){
            $quantity -= 1;
            // If quantity < 1, remove item from db
            if ($quantity > 0){
                $query = "UPDATE $tableTemp SET
                            quantity = '$quantity'
                            WHERE id = $id";
            }else{
                $query = "DELETE FROM $tableTemp WHERE id = '$id'";
            }
        }elseif($action == "plusBtn"){
            $quantity += 1;
            // If quantity < 1, remove item from db
            if ($quantity <= $quantityMax){
                $query = "UPDATE $tableTemp SET
                            quantity = '$quantity'
                            WHERE id = $id";
            }else{
                echo "
                    <script>
                        alert('Maximum Quantity');
                    </script>
                    ";
                $query ="UPDATE $tableTemp SET 
                            quantity = '$quantityMax'
                            WHERE id = $id";;
            }
        }elseif($action == "manualAdd"){
            $quantityUpdate = $_GET["quantity"];
            if($quantityUpdate > 0 && $quantityUpdate <= $quantityMax){
                $query = "UPDATE $tableTemp SET 
                            quantity = '$quantityUpdate'
                            WHERE id = $id";
            }else{
                if($quantityUpdate < 0){
                    echo "
                    <script>
                        alert('Minimum 1 Item');
                    </script>
                    ";
                    $query = "UPDATE $tableTemp SET 
                            quantity = '1'
                            WHERE id = $id";
                }elseif($quantityUpdate > $quantityMax){
                    echo "
                    <script>
                        alert('Maximum Quantity');
                    </script>
                    ";
                    $query = "UPDATE $tableTemp SET 
                            quantity = '$quantityMax'
                            WHERE id = $id";
                }else{
                    $query = "DELETE FROM $tableTemp WHERE id = '$id'";
                }
            }
        }elseif($action == "discount"){
            // Get disount
            $discount = $_GET["discount"];

            if ($discount < 0){
                echo "
                    <script>
                        alert('Minimum Discount 0%');
                    </script>
                "; 
                $query ="UPDATE $tableTemp SET
                discount = 0
                WHERE id = $id";
            }elseif($discount>100){
                echo "
                    <script>
                        alert('Maximum Discount 100%');
                    </script>
                ";
                $query ="UPDATE $tableTemp SET
                discount = 100
                WHERE id = $id";
            }else{
                $query ="UPDATE $tableTemp SET
                discount = $discount
                WHERE id = $id";
            }           

        }elseif($action == "deleteItm"){
            $query = "DELETE FROM $tableTemp WHERE id = $id";
        }
    }elseif($action == "printSPC"){    
        $query = "TRUNCATE TABLE $tableTemp";
        $_SESSION["customer"] = "";
    }
    if ($action != "customer"){
        mysqli_query($conn,$query);
    }
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
}else{
    exit;
}
?>


<h1 class="headLine">Shopping Cart</h1>
<?php foreach ($items as $item): ?>
<ul?>
    <li>
        <?= $item["nameProduct"]; ?>
    </li>
    <li>
        <button type="submit" class="button spgcrtBtn" id="minBtn" onclick=minItem(<?= $item["id"];?>)> - </button>
        <input type="number" class="textBox spgcrtTxtbx" name="quantity" id="quantity<?= $item["id"];?>"
            value="<?= $item["quantity"];?>" min="1" onchange=manualUpdate(<?= $item["id"];?>) autocompelet="off">
        <button type="submit" class="button spgcrtBtn" id="plusBtn" onclick=plusItem(<?= $item["id"];?>)> + </button>
    </li>
    <li>
        <label for="discountItem<?= $item["id"];?>">Aditional Dicount (%) :</label>
        <input type="number" style="width : 156px" class="textBox spgcrtTxtbx" name="discountItem<?= $item["id"];?>"
            id="discountItem<?= $item["id"];?>" value="<?= $item["discount"];?>" onchange=discount(<?= $item["id"];?>)
            autocompelet="off">
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
    <h2>Total : <?= $currency.$total; ?> </h2>
    <button type="submit" class="button spgcrtBtn2" onclick=printing()>Print</button>
    <button type="submit" class="button clearcart spgcrtBtn2" onclick=ClearCart()>Clear Cart</button>