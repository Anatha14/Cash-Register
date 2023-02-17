<?php

// Run the session
session_start();


// Destroy the session

$_SESSION=[];
session_unset();
session_destroy();

// Unset the cookie
setcookie('u1Se3a1c2eN6a1f1r1e','',time()-3600,"/");
setcookie('1a2x4v3e1h2ej2klo1p1y3o','',time()-3600,"/");

header("Location: ../../index.php");
exit;
?>