<?php


require "./functions.php";
require "./fpdf_mc_table.php";

// Get invoice number
$invNbr = $_GET["invoice"];

// query to get json products

$query = "SELECT * FROM $tableInvoice WHERE Number = '$invNbr'";

$products = mysqli_query($conn,$query);

foreach ($products as $product){
    $invNumber = $product["Number"];
    $json = $product["Products"];
    $date = $product["Time"];
    $user = $product["User"];
    $customer = $product["Customer"];
}

$prods = json_decode($json,true);

// Date time
// Univ Time === $date
$dateTime = gmdate("D, j M Y H:i:s",$date+25200);



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
$pdf -> cell(100,20,"$invNumber",0,1,'L');
$pdf -> SetFont('PoppinsLight','',15);
$pdf -> cell(30,10,"Customer :",0,0,'L');
$pdf -> cell(0,10,$customer,0,1,'L');
$pdf -> cell(30,5,"Printed By :",0,0,'L');
$pdf -> cell(0,5,$user,0,1,'L');
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

$total = 0;
$totalDiscount =0;
$i = 1;

foreach($prods as $prod):
    
    $quantity = $prod["quantity"];
    $name = $prod["nameProduct"];
    $price = $prod["price"];
    $discount = $prod["discount"];

    $discountPrice = ($discount/100*$price);
    $priceTotal = $quantity*($price-$discountPrice);
    $price -= $discountPrice;
    $total += $priceTotal;

    $price = $currency . number_format($price);
    $priceTotal = $currency . number_format($priceTotal);
    
    $pdf -> Row(Array($i,$name,$quantity,$price,$priceTotal));
    $pdf -> Ln(2);
    $i++;
endforeach;

$total = number_format($total);
$totalDiscount = number_format($totalDiscount);


$height = $pdf -> GetY();
$pdf-> Line(10,$height,200,$height);

$pdf -> SetFont('Poppins','B',15);
$pdf -> Cell(150,10,"Total : ",0,0,"R");
$pdf -> Cell(50,10,"$currency $total","C");


// Set title
$pdf -> SetTitle("INVOICE");

// Print
$invNbr = str_replace("/","_",$invNbr);
$invNbr .= ".pdf";
$pdf -> output("$invNbr",'I');

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