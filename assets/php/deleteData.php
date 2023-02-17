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

// Take id to function delete()

if (delete($id)>0){
    echo "
        <script>
            alert('Successfully Delete Data');
            document.location.href = './adminPage.php';
        </script>
    ";
    }else{
        echo "
            <script>
            alert('UNSUCCESSFULLY DELETE DATA');
            document.location.href = './adminPage.php';
            </script>
        ";
    }

?>