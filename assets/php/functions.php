<?php

// Run session
session_start();

// Logo Name 
$logo = "logo.png";

// Currency
$currency = "Rp.";

// Table Name
$tableProduct = "stocks";
$tableUser = "user";
$tableTemp = "temp";
$tableInvoice = "invoice";

// Connect to database
$conn = mysqli_connect("localhost","root","","cashregister");

// Create functions for take data that return array assoc
function query($query){
    global $conn;
    $result = mysqli_query($conn,$query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)){
        $rows[] = $row;
    }
    return $rows;
}

// Function create to create new data into database
function create($data){
    global $conn;
    global $tableProduct;

    // Take data from parameter
    $name = htmlspecialchars($data["productName"]);
    $price = htmlspecialchars($data["price"]);
    $price = str_replace(",","",$price);
    $qty = htmlspecialchars($data["quantity"]);
    $qty = str_replace(",","",$qty);


    // query insert
    $query = "INSERT INTO $tableProduct VALUES (
                '','$name','$price','$qty')";

    // query to database
    mysqli_query($conn,$query);

    // Return affected rows to make sure its already created
    return mysqli_affected_rows($conn);
}

// Create function to edit data
function edit($data){
    global $conn;
    global $tableProduct;


    // Take data from parameter
    $id = $data["id"];
    $name = htmlspecialchars($data["productName"]);
    $price = htmlspecialchars($data["price"]);
    $price = str_replace(",","",$price);
    $qty = htmlspecialchars($data["quantity"]);
    $qty = str_replace(",","",$qty);

    // Query edit
    $query = "UPDATE $tableProduct SET
                nameProduct = '$name',
                price = '$price',
                quantity = '$qty'
                WHERE id = $id";

    // query to database
    mysqli_query($conn,$query);

    // Return affected rows to make sure its already created
    return mysqli_affected_rows($conn);
}

// Function to delete data
function delete($id){
    global $conn;
    global $tableProduct;


    // Make query to delet
    $query = "DELETE FROM $tableProduct WHERE id = $id";

    // Query to database
    mysqli_query($conn,$query);
    
    // Return affected rows to make sure its already created
    return mysqli_affected_rows($conn);
}


// Salt Variable
$salt1 = "bgjasnjd6xab613";
$salt2 = "j13ksi1230s4t72y8";

// Function to register new account
function register($data){
    global $conn,$tableUser,$salt1,$salt2;


    // Take data from form
    $username = strtolower(htmlspecialchars($data["username"]));
    $password1 = mysqli_real_escape_string($conn,$data["password"]);
    $password2 = mysqli_real_escape_string($conn,$data["rePassword"]);
    $role = $data["role"];

    // Chek username
    $query = "SELECT * FROM $tableUser WHERE username = '$username'";
    $checkUsername = mysqli_query($conn,$query);
    if (mysqli_num_rows($checkUsername) !== 0){
        
        echo "
            <script>
                alert('USERNAME ALREADY EXISTS');
            </script>
            ";
        
        return false;
    }
    
    // Check password
    if ($password1 !== $password2){
        
        echo "
            <script>
                alert('PASSWORD DON`T MATCH');
            </script>
            ";
        
        return false;
    }

    // Encrypt Passowrd and username
    $username = hash("SHA256",$salt1.$username.$salt2);
    $passwordUser = password_hash($password1,PASSWORD_DEFAULT);
    $role = hash("SHA256",$salt1.$role.$salt2);

    // Query to database
    $query = "INSERT INTO user VALUES ('$username','$passwordUser','$role')";
    mysqli_query($conn,$query);

    return mysqli_affected_rows($conn);
}

// Global Var for cookies
$usrnm = "u1Se3a1c2eN6a1f1r1e";
$rle = "1a2x4v3e1h2ej2klo1p1y3o";

?>