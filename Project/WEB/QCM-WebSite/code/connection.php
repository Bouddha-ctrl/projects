<?php 
try {
    $servername = "localhost";
    $username = "root";
    $password = "";     
    $conn = new PDO('mysql:host=localhost;dbname=test',$username,$password);
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
}

?>