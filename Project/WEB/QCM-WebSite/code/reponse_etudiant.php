<?php
session_start();
include 'connection.php';
$idq=$_GET['idq'];
$requette='select nb_question,c1,c2 from qcm where id_qcm=:id';
                    $stmt=$conn->prepare($requette);
                    $stmt->bindParam(':id',$idq);
                    $stmt->execute();
                    $row=$stmt->fetchAll(PDO::FETCH_ASSOC);
$nbq=$row[0]['nb_question'];
$C1=$row[0]['c1'];
$C2=$row[0]['c2'];


$re='select * from reponse where id_qcm=:id';
                    $stmt=$conn->prepare($re);
                    $stmt->bindParam(':id',$idq);
                    $stmt->execute();
                    $rows=$stmt->fetchAll(PDO::FETCH_ASSOC); 
                    

$requette="insert into reponse_etudiant values (:idq,:ide,:No,:a,:b,:c,:d,:e,:note)";
                    $stmt = $conn->prepare($requette);
                    $stmt->bindParam(':No', $no);
                    $stmt->bindParam(':idq', $idq);
                    $stmt->bindParam(':ide',$_SESSION['idet']);
                    $stmt->bindParam(':e', $e);
                    $stmt->bindParam(':d', $d);
                    $stmt->bindParam(':c', $c);
                    $stmt->bindParam(':b', $b);
                    $stmt->bindParam(':a', $a);
                    $stmt->bindParam(':note', $note);

                    if(isset($_POST['valider'])){                                              //submit

                        for ($i=0;$i<$nbq;$i++) {
                            $a=0;
                            $b=0;
                            $c=0;
                            $d=0;
                            $e=0;
                            $no=$i;
                            $he=$rows[$i];
                            
                            $help=isset($_POST['lang'.$i])? $_POST['lang'.$i]:false;
                            if (!empty($help)) {
                                foreach ($help as $value) {
                                    switch ($value) {
                                        case 'A': $a=1; break;
                                        case 'B': $b=1; break;
                                        case 'C': $c=1; break;
                                        case 'D': $d=1; break;
                                        case 'E': $e=1; break;
                                    }
                                }
                            }
                            $tab=array('A'=>$a,'B'=>$b,'C'=>$c,'D'=>$d,'E'=>$e);
                            $tab2=array_slice($he, 3);
                            $som=0;
                            while (list($k, $v)=each($tab)) {
                                $som += calcule($tab2[$k], $v, $C1, $C2);
                            }
                            $note=$som;
                            
                            $stmt->execute();
                            header("Location: espace_etudiant.php");
                        }
                    }


function get_check($nb,$n){                                         //check box
    $choix=array('A','B','C','D','E');
    $checks='';
    for ($i=0;$i<$nb;$i++) {
        $checks.='<input type="checkbox" name="lang'.$n.'[]" Value='.$choix[$i].'>'.$choix[$i];
    }
    return $checks;
}
$re="select nom,prenom from etudiant where id=?";
$s=$conn->prepare($re);
$s->bindParam(1,$_SESSION['idet']);
$s->execute();
$row=$s->fetchAll(PDO::FETCH_ASSOC);
?>



<!DOCTYPE html>
    <head>
        <title>Reponse</title>    
        <link rel="stylesheet" href="css/menu.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
        <link href="https://fonts.googleapis.com/css2?family=Balsamiq+Sans&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="css/ex3-2.css">

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
                <div class="divimg"><img src="img/teacher.png" class="img" alt="teacher"  /></div>
            
            <br/><span style="margin-left:50px;color:yellow;font-family: 'Balsamiq Sans', cursive;">  <?php echo strtoupper($row[0]['nom']).' '.strtoupper($row[0]['prenom']); ?></span><br/>
            <div class="khaton" ></div><br/>
            <center><span id='ct' style="color:#3e4444"></span>
            </center> 
                <div class="khaton" ></div><br/>
                <ul>
                    <div class="in"> <li><a href="espace_etudiant.php" ><i style="font-size:larger"  class="fas fa-list"></i> Mes QCM</a></li>
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
            <div class="menu_right" >
        <form method="post">
            <table border="1px"  align="center">
                <tr>
                    <th style="width:250px">Questions</th><th>Réponses</th>
                </tr>
                <?php
                    foreach($rows as $v){
                        echo '
                    <tr>
                        <td>Question '.($v['No_question']+1).'</td>
                        <td style="width:160px;">'.get_check($v['nb_choix'],$v['No_question']).'</td>
                        ';
                    }
                ?>

            </table>
            <br/><input class="sub" type="submit" value="valider" name="valider"/>
        </form>
        </div>
    </body>
</html>
<?php

function calcule($q,$r,$C1,$C2){
    $som=0;
    $cal=$q*2+$r;
    if($cal==3) $som=$C1;
    else if($cal==1) $som=$C2;
    return $som;
}


?>