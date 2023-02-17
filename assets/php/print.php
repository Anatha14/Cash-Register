
<?php

require "./functions.php";
require "./fpdf_mc_table.php";

// Check is there anything in shopping cart
if (mysqli_num_rows(mysqli_query($conn,"SELECT * FROM $tableTemp")) == 0){
    echo "
        <script>
            alert('Shopping Cart Empty');
            window.close();
        </script>
    ";
    die;
}

// Query all items from temp table
$items = query("SELECT * FROM $tableTemp ORDER BY time ASC");

// Check row from invoicetable
$idInv = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM $tableInvoice"));

// Make invoice ID
$idInv += 1;

// Assignt all product to json
// Know the row first
$row = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM $tableTemp"));

for ($i = 0 ; $i < $row ; $i++){
    $discount = $items[$i]["discount"]/100 * $items[$i]["price"];
    $priceTotal = ($items[$i]["price"]-$discount)*$items[$i]["quantity"];
    $array[] = (array('INVNBR' => $items[$i]["id"],
                        'nameProduct' => $items[$i]["nameProduct"],
                        'quantity' => $items[$i]["quantity"],
                        'price' => $items[$i]["price"],
                        'discount'=> $items[$i]["discount"],
                        'priceTotal' => $priceTotal));
}

$JSON = json_encode($array);

// Set date
date_default_timezone_set("Asia/Jakarta");
$dateTime = date("D, j M Y H:i:s");
$time = time();
$user = $_SESSION["username"];
$date = date("jmy");
$customer = $_SESSION["customer"];


// create query for insert data to db invoice
$query = "INSERT INTO $tableInvoice VALUES(
            'INV/$date/$idInv',
            '$JSON',
            '$time',
            '$user',
            '$customer')";

mysqli_query($conn,$query);

// Decrease the quantity from db stocks

foreach($items as $item){
    $id = $item["id"];
    $quantity = $item["quantity"];

    $result = query("SELECT * FROM $tableProduct WHERE id = $id");
    $quantityProd = $result[0]["quantity"];

    $latestQTY = $quantityProd - $quantity;
    $query = "UPDATE $tableProduct SET
                quantity = '$latestQTY'
                WHERE id = $id";
    mysqli_query($conn,$query);
}


// Count total price
$total = 0;
foreach($items as $item){
    $discount = $item["discount"]/100 * $item["price"];
    $priceItem = ($item["price"]-$discount)*$item["quantity"];
    $total += $priceItem;
}
$total = number_format($total);




// Create PDF model table
$pdf = new PDF_MC_Table ('P','mm','a4');
// Add font
$pdf ->AddFont("Poppins","B","Poppins-Bold.php");
$pdf ->AddFont("PoppinsLight","","Poppins-Light.php");
$pdf ->AddFont("PoppinsMedium","","Poppins-Medium.php");
$pdf ->AddFont("PoppinsLight","I","Poppins-LightItalic.php");

$pdf -> AddPage();
$pdf -> SetFont('Poppins','B',40);
$pdf -> Ln(5);
$pdf -> cell(100,5,"INVOICE",0,1,'L');
$pdf -> SetFont('PoppinsLight','I',17);
$pdf -> Image("../images/$logo",160,10,30,0);
$pdf -> Cell(0,0,'',0,1,'L');
$pdf -> cell(100,20,"INV/$date/$idInv",0,1,'L');
$pdf -> SetFont('PoppinsLight','',15);
$pdf -> cell(30,10,"Customer :",0,0,'L');
$pdf -> cell(0,10,$_SESSION["customer"],0,1,'L');
$pdf -> cell(30,5,"Printed By :",0,0,'L');
$pdf -> cell(0,5,$_SESSION["username"],0,1,'L');
$pdf -> cell(30,10,"Printed On :",0,0,'L');
$pdf -> cell(0,10,$dateTime,0,1,'L');

$pdf-> Ln(7);
$pdf-> Line(10,70,200,70);

$pdf -> SetFont('Poppins','B',14);
$pdf->Cell(15,7,'No.',0,0,'C');
$pdf->Cell(55,7,'Product Name' ,0,0,'C');
$pdf->Cell(20,7,'Qty',0,0,'C');
$pdf->Cell(50,7,'Price',0,0,'C');
$pdf->Cell(50,7,'Total',0,1,'C');
$pdf-> Ln(2);

$pdf -> SetFont('PoppinsMedium','',12);
// Set Width for each colom
$pdf -> SetWidths(array(15,55,20,50,50));
// Set line height
$pdf -> SetLineHeight(10);

$i = 1;
$totalDiscount =0;

foreach($items as $item):
    $quantity = $item["quantity"];
    $name = $item["nameProduct"];
    $price = $item["price"];
    $discount = $item["discount"];

    $discountPrice = ($discount/100*$price);
    $priceTotal = $quantity*($price-$discountPrice);
    $price -= $discountPrice;

    $price = $currency . number_format($price);
    $priceTotal = $currency . number_format($priceTotal);
    
    $pdf -> Row(Array($i,$name,$quantity,$price,$priceTotal));
    $pdf -> Ln(2);
    $i++;
endforeach;

$height = $pdf -> GetY();
$pdf-> Line(10,$height,200,$height);

$totalDiscount = number_format($totalDiscount);

$pdf -> SetFont('Poppins','B',15);
$pdf -> Cell(150,10,"Total : ",0,0,"R");
$pdf -> Cell(50,10,"$currency $total","C");


// // Create Pdf others model

// $pdf = new FPDF('P','mm','a4');
// $pdf -> AddPage();
// $pdf -> SetFont('helvetica','B',40);
// $pdf -> Ln(5);
// $pdf -> cell(100,5,"INVOICE",0,1,'L');
// $pdf -> SetFont('helvetica','',15);
// $pdf -> Image("../images/$logo",160,10,30,0);
// $pdf -> cell(46,20,"INV/20230207/001",0,1,'R');
// $pdf -> cell(30,5,"Printed By :",0,0,'L');
// $pdf -> cell(0,5,$_SESSION["username"],0,1,'L');
// $pdf -> cell(30,10,"Printed On :",0,0,'L');
// $pdf -> cell(0,10,$dateTime,0,1,'L');

// $pdf-> Ln(10);
// $pdf-> Line(10,60,200,60);

// $height = 60;

// foreach ($items as $item):

//     $quantity = $item["quantity"];
//     $name = $item["nameProduct"];
//     $price = $item["price"];
//     $priceTotal = $quantity*$price;
//     $price = number_format($price);
//     $priceTotal = number_format($priceTotal);

//     $pdf -> multicell(190,10,"$name",0,'L');
//     $pdf -> cell(20,10,"$quantity  @",0,0,'L');
//     $pdf -> cell(46,10,"$currency $price",0,1,'L');
//     $pdf -> cell(46,10,"$currency $priceTotal",0,1,'L');
//     $height = $pdf -> GetY();
//     $pdf -> Line (10,$height,200,$height);

// endforeach;


// $pdf -> SetFont('helvetica','B',40);
// $pdf -> Cell(190,20,"Total : $currency $total",0,1,"R");



// Set title
$pdf -> SetTitle("INVOICE");

// Print
$InvoiceName = "INV_".$date."_".$idInv.".pdf";
$pdf -> output("$InvoiceName",'I');



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print</title>
    <link rel="icon" href="../images/<?= $logo ?>">
    <link rel="stylesheet" href="../style/style.css">
</head>
<body>
    
</body>
</html>