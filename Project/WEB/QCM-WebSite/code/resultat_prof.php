<?php 
session_start();
include 'connection.php';

function compare($d,$f){
    date_default_timezone_set('Europe/London');
    $date = date('Y-m-d H:i',time());
    if($f<$date) return 1;
    else return 0;
}

$re="select nom,prenom from prof where id=?";
$stmt=$conn->prepare($re);
$stmt->bindParam(1,$_SESSION['idP']);
$stmt->execute();
$row=$stmt->fetchAll(PDO::FETCH_ASSOC);




?>
<!DOCTYPE html>
    <head>
        <title>Résultat</title>
        <link rel="stylesheet" href="css/menu.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
        <link href="https://fonts.googleapis.com/css2?family=Balsamiq+Sans&display=swap" rel="stylesheet">

        <title>Espace professeur</title>    
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
                <center><h3>Professeur</h3></center>
                <div class="divimg"><img src="img/teacher.png" class="img" alt="teacher"  /></div>
            
            <br/><span style="margin-left:40px;color:yellow;font-family: 'Balsamiq Sans', cursive;">  <?php echo strtoupper($row[0]['nom']).' '.strtoupper($row[0]['prenom']); ?></span><br/>
            <div class="khaton" ></div><br/>
            <center><span id='ct' style="color:#3e4444"></span>
            </center> 
                <div class="khaton" ></div><br/>
                <ul>
                    <div class="in"> <li><a href="espaceprof.php"><i style="font-size:larger"  class="fas fa-list"></i> Mes QCM</a></li>
                    <li class="active"><i style="font-size:larger" class="fas fa-tasks"></i> Résulats</li></div>
                    </ul>

                    <div class="out"> 
                        
                            <a  href="deconnect.php">
                            <div class="out2">
                            <i style="padding-right:5px;font-size:larger" class="fas fa-sign-out-alt"></i>  Se déconnecter
                            </div>
                            </a>
                    </div>

            </div>  
        


        <div  class="menu_right">
                <?php
                    $requette='select * from niveau';
                    $stmt=$conn->prepare($requette);
                    $stmt->execute();
                    $nivs=$stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    foreach ($nivs as $n) {
                        $requette='select * from qcm where id_prof=? and niveau=? order by id_qcm DESC';
                        $stmt=$conn->prepare($requette);
                        $stmt->bindParam(1, $_SESSION['idP']);
                        $stmt->bindParam(2, $n['niveau']);
                        $stmt->execute();
                        $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
                        if (!empty($rows)) {
                            echo '<fieldset> <legend>'.ucfirst($n['niveau']).'</legend>';
                            echo '<table border="1px" style="width:95%">
                            <tr>
                                <th>id QCM</th><th>Titre</th><th>Niveau</th><th>Date debut</th><th>Date fin</th><th>Résultats</th>
                            </tr>';
                            foreach ($rows as $v) {
                                if (compare($v['ddebut'], $v['dfin'])==1) {
                                    echo '
                            <tr>
                                <td>'.$v['id_qcm'].'</td><td>'.$v['titre'].'</td><td>'.$v['niveau'].'</td><td>'.$v['ddebut'].'</td><td>'.$v['dfin'].'</td><td><a style="font-size:larger;color:blue;margin-right:20px;" class="tooltip" href="liste_etudiant.php?idq='.$v['id_qcm'].'"><i class="fas fa-clipboard-list"> Liste</i><span class="tooltiptext">Liste</span></a></td>
                            </tr>
                            ';
                                }
                            }echo '</table></fieldset><br/><br/>';
                        }
                    }

                ?>
            
            
            
        </div >
    </body>

</html>
