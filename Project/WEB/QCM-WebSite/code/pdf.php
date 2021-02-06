<?php 
    try {
        $servername = "localhost";
        $username = "root";
        $password = "";     
        $conn = new PDO('mysql:host=localhost;dbname=test',$username,$password);
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
    }

    $id=$_GET['id'];

    $stmt=$conn->prepare("select pdf from qcm where id_qcm=:id;");
    $stmt->bindParam(':id',$id);
    $check=$stmt->execute();
    $row=$stmt->fetch();

    header("Content-type: application/pdf");


    echo $row['pdf'];
    
?>
<html>
    <body>
    <object data="data:application/pdf;base64,<?php echo base64_encode($row['pdf']) ?>" type="application/pdf" style="height:100%;width:100%"></object>

    
    </body>
</html>
