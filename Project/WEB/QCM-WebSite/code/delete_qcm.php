<?php 
include 'connection.php';
$idq=$_GET['idq'];
$requette='delete from qcm where id_qcm=?';
$stmt=$conn->prepare($requette);
$stmt->bindParam(1,$idq);
$stmt->execute();
header("Location: espaceprof.php"); 

?>