<?php
session_start();
include 'connection.php';
$idet=$_SESSION['idet'];

function compare($d,$f){
    $check=0;
    date_default_timezone_set('Europe/London');
    $date = date('Y-m-d H:i',time());
    if($d<$date && $f>$date) $check=1;
    return $check;
 }
 function verifier($idq,$ide){
    include 'connection.php';
    $requette="select count(id_qcm) from reponse_etudiant where id_qcm=:idq and  id_etudiant=:ide;";
    $stmt = $conn->prepare($requette);
    $stmt->bindParam(':idq', $idq);
    $stmt->bindParam(':ide', $ide);
    $stmt->execute();
    $help= $stmt->fetchAll(PDO::FETCH_ASSOC);

    $nb=$help[0]['count(id_qcm)'];
    if($nb==0) return true;
    else return false;
 }
 $re="select nom,prenom from etudiant where id=?";
$stmt=$conn->prepare($re);
$stmt->bindParam(1,$_SESSION['idet']);
$stmt->execute();
$rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
    <head>
        <title>Espace etudiant</title>    
        <link rel="stylesheet" href="css/menu.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
        <link href="https://fonts.googleapis.com/css2?family=Balsamiq+Sans&display=swap" rel="stylesheet">

        <script type="text/javascript"> 
            function display_c(){
                var refresh=1; 
                mytime=setTimeout('display_ct()',refresh)
                }

                function display_ct() {
                    var x = new Date()
                    var month=x.getMonth()+1;
                    var day=x.getDate();
                    var year=x.getFullYear();
                    if (month <10 ){month='0' + month;}
                    if (day <10 ){day='0' + day;}
                    var x3= year+'-'+month+'-'+day;

                    // time part //
                    var hour=x.getHours();
                    var minute=x.getMinutes();
                    var second=x.getSeconds();
                    if(hour <10 ){hour='0'+hour;}   
                    if(minute <10 ) {minute='0' + minute; }
                    if(second<10){second='0' + second;}
                    var x3 = x3 + ' ' +  hour+':'+minute+':'+second
                    document.getElementById('ct').innerHTML = x3;
                    display_c();
                }
        </script>
    </head>
    <body onload=display_ct();>
    <div class="menu" >
            <div class="menu_left" >
                <center><h3>Etudiant</h3></center>
                <div class="divimg"><img src="img/student.png"  class="img" alt="teacher"  /></div>
            
            <br/><span style="margin-left:50px;color:yellow;font-family: 'Balsamiq Sans', cursive;">  <?php echo strtoupper($rows[0]['nom']).' '.strtoupper($rows[0]['prenom']); ?></span><br/>
            <div class="khaton" ></div><br/>
            <center><span id='ct' style="color:#3e4444"></span>
            </center> 
                <div class="khaton" ></div><br/>
                <ul>
                    <div class="in"> <li class="active"><i style="font-size:larger"  class="fas fa-list"></i> Mes QCM</li>
                    <li><a href="resultat_etudiant.php"><i style="font-size:larger" class="fas fa-tasks"></i> Résulats</a></li></div>
                    </ul>

                    <div class="out"> 
                        
                            <a  href="deconnect.php">
                            <div class="out2">
                            <i style="padding-right:5px;font-size:larger" class="fas fa-sign-out-alt"></i>  Se déconnecter
                            </div>
                            </a>
                    </div>
            </div>

        <div class="menu_right">
            <table border="1px" style="width:85%">
                <tr>
                    <th>id QCM</th><th>Titre</th><th>Description<th>Date debut</th><th>Date fin</th><th>Question</th>
                </tr>
                <?php
                    $requette='select * from qcm where niveau=(select niveau from etudiant where id=:id)';
                    $stmt=$conn->prepare($requette);
                    $stmt->bindParam(':id',$idet);
                    $stmt->execute();
                    $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    foreach($rows as $v){
                        if(compare($v['ddebut'],$v['dfin'])==1 && verifier($v['id_qcm'],$idet))
                        echo '
                        <tr>
                        <td>'.$v['id_qcm'].'</td><td>'.$v['titre'].'</td><td>'.$v['description'].'</td><td>'.$v['ddebut'].'</td><td>'.$v['dfin'].'</td><td><a target="_blank" href="pdf.php?id='.$v['id_qcm'].'"><i class="fas fa-file-pdf" style="color:black"></i> PDF</a><br/><a href="reponse_etudiant.php?idq='.$v['id_qcm'].'"><i class="fas fa-sticky-note"  style="color:black"></i> feuille de réponse<a/></td>
                        </tr>
                        ';
                    }

                ?>
            </table>
            
        </div >
    </body>

</html>
