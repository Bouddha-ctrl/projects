<?php session_start();
 include 'connection.php';

function compare($d,$f){
    
    date_default_timezone_set('Europe/London');
    $date = date('Y-m-d H:i',time());
    if($d>$date) return 1; 
    else if($f<$date) return 2;
    else return 3;
 }
 
 function parametre($idq, $h)
 {
    $msg='';
    if ($h==1) {
    $msg= '<a class="tooltip" href="modify_qcm.php?idq='.$idq.'&check=1"><i style="font-size:larger;color:green;padding-left:20px;padding-right:20px" class="fas fa-edit "></i><span class="tooltiptext">Modifier</span></a> ';
     
    $msg.='<a  class="tooltip" href="delete_qcm.php?idq='.$idq.'"><i style="font-size:larger;color:red;padding-right:10px" class="fas fa-trash-alt "></i><span class="tooltiptext">Supprimer</span></a>';
    }else{
        $msg= '<a class="tooltip" href="modify_qcm.php?idq='.$idq.'&check=2"><i style="padding-left:20px;font-size:larger;color:green;padding-right:20px" class="fas fa-edit "></i><span class="tooltiptext">Modifier</span></a>';
    }
    $msg.='<a';
    return $msg;
 }

$re="select nom,prenom from prof where id=?";
$stmt=$conn->prepare($re);
$stmt->bindParam(1,$_SESSION['idP']);
$stmt->execute();
$rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
 ?>


<!DOCTYPE html>
    <head>
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
            
            <br/><span style="margin-left:40px;color:yellow;font-family: 'Balsamiq Sans', cursive;">  <?php echo strtoupper($rows[0]['nom']).' '.strtoupper($rows[0]['prenom']); ?></span><br/>
            <div class="khaton" ></div><br/>
            <center><span id='ct' style="color:#3e4444"></span>
            </center> 
                <div class="khaton" ></div><br/>
                <ul>
                    <div class="in"> <li class="active"><i style="font-size:larger"  class="fas fa-list"></i> Mes QCM</li>
                    <li><a href="resultat_prof.php"><i style="font-size:larger" class="fas fa-tasks"></i> Résulats</a></li></div>
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
            <table style="width:92%">
                <tr>
                    <th>id QCM</th><th>Titre</th><th>Niveau</th><th>Date debut</th><th>Date fin</th><th>Etat</th><th>Paramètre</th>
                </tr>
                <?php
                    $requette='select * from qcm where id_prof=? order by id_qcm DESC';
                    $stmt=$conn->prepare($requette);
                    $stmt->bindParam(1,$_SESSION['idP']);
                    $stmt->execute();
                    $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    foreach($rows as $v){
                        $help=compare($v['ddebut'], $v['dfin']);
                        if($help==1){
                            echo '
                        <tr>
                        <td>'.$v['id_qcm'].'</td><td>'.$v['titre'].'</td><td>'.$v['niveau'].'</td><td>'.$v['ddebut'].'</td><td>'.$v['dfin'].'</td><td>pas encore</td><td>'.parametre($v['id_qcm'],1).'</td>
                        </tr>
                        ';
                        }else if($help==3){
                            echo '
                        <tr>
                        <td>'.$v['id_qcm'].'</td><td>'.$v['titre'].'</td><td>'.$v['niveau'].'</td><td>'.$v['ddebut'].'</td><td>'.$v['dfin'].'</td><td>en cours</td><td>'.parametre($v['id_qcm'],2).'</td>
                        </tr>
                        ';
                        }
                    }

                ?>
            </table>
                    
            <br/>
            <div class="but">
                <a href="ex3-1.php" class="tooltip" ><i style="font-size:60px;margin-right:20px;" class="fas fa-plus-circle"></i><span class="tooltiptext">Nouveau QCM</span></a>
            </div>
        </div >
        </div>
    </body>

</html>

