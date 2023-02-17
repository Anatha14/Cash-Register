<?php

require './functions.php';

// Check Session
if (!isset($_SESSION["login"])){
    header('Location: ./login.php');
}

$invoices = query("SELECT number FROM $tableInvoice");

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Check</title>
    <link rel="icon" href="../images/<?= $logo ?>">
    <link rel="stylesheet" href="../style/style.css">
</head>
<body>
    <a href="./adminPage.php" class="absolLink logout">Exit</a>
    <h1>Invoice Check</h1>
    <form action="" method="post">
        <input type="text" name="searchBarInvoice" id="searchBarInvoice" class="searchBarInvoice searchBar" autofocus placeholder="Search Here..."
            autocomplete="off">
    </form>
    <img src="../images/loading.gif" id="loading">
    <div id="containerInvoice">
        <table border = 1 cellspacing=0 cellpadding=10>
            <tr>
                <th>Invoice Number</th>
                <th>Action</th>
            </tr>
            <?php foreach($invoices as $invoice):?>
                <tr>
                    <td><?= $invoice["number"] ?></td>
                    <?php
                        $link = "./printedInvoice.php?invoice=".$invoice["number"];
                    ?>
                    <td><button type="submit" name="btnAdd" id="btnAdd" class="btnAdd button"
                            onclick ="window.open('<?= $link ?>')">Check</button>
                    </td>
                </tr>
            <?php endforeach;?>
        </table>
    </div>

    <script src="../js/jquery.js"></script>
    <script>
        // Add event "keyup" on searchbarinvoice
        $('#searchBarInvoice').on('keyup',function(){
            // Show loader gif
            $('#loading').show();

            $.get('./displayData.php?keyword=' + $('#searchBarInvoice').val() + '&page=invoice',function(data){
                $('#containerInvoice').html(data);
                $('#loading').hide();
            });
        });
    </script>
</body>
</html>