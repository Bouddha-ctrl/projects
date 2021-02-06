<?php 
session_start();
$idet=$_SESSION['idet'];
include 'connection.php';

//$requette='select distinct(id_qcm) from reponse_etudiant where id_etudiant=:ide';
$requette='select q.* , sum(r.note) as notes
            from qcm q,reponse_etudiant r
            where q.id_qcm = r.id_qcm 
            and q.id_qcm in(select distinct(id_qcm) 
		                    from reponse_etudiant 
                            where id_etudiant=:ide)
            and r.id_etudiant=:ide
            group by r.id_etudiant,r.id_qcm order by r.id_qcm DESC';

    $stmt = $conn->prepare($requette);
    $stmt->bindParam(':ide', $idet);
    $stmt->execute();
    $rows= $stmt->fetchAll(PDO::FETCH_ASSOC);

    
 $re="select nom,prenom from etudiant where id=?";
 $stmt=$conn->prepare($re);
 $stmt->bindParam(1,$_SESSION['idet']);
 $stmt->execute();
 $row=$stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
    <head>
        <title>Résultats</title>
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
    <body  onload=display_ct();>
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
                    <div class="in"> <li ><a href="espace_etudiant.php"><i style="font-size:larger"  class="fas fa-list"></i> Mes QCM</a></li>
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
            <table border="1px" style="width:85%">
                <tr>
                    <th>id QCM</th><th>Titre</th><th>Date debut</th><th>Date fin</th><th>Note</th><th>Classement</th><th>Moyenne</th><th>réponse</th>
                </tr>
                <?php
                   foreach($rows as $v){
                    $requette='select avg(notes) as moy
                    from qcm_etud
                    where id_qcm=:idq';
                    $stmt = $conn->prepare($requette);
                    $stmt->bindParam(':idq', $v['id_qcm']);
                    $stmt->execute();
                    $avg= $stmt->fetchAll(PDO::FETCH_ASSOC);
        
                    $requette='select count(*) as cla
                                from qcm_etud
                                 where notes>=(select notes 
                                              from qcm_etud 
                                              where id_etudiant=:ide 
                                              and id_qcm=:idq)
                                and id_qcm=:idq;';
                    $stmt = $conn->prepare($requette);
                    $stmt->bindParam(':idq', $v['id_qcm']);
                    $stmt->bindParam(':ide', $idet);
                    $stmt->execute();
                    $cla= $stmt->fetchAll(PDO::FETCH_ASSOC);

                       echo '
                       <tr>
                            <td>'.$v['id_qcm'].'</td><td>'.$v['titre'].'</td><td>'.$v['ddebut'].'</td><td>'.$v['dfin'].'</td><td>'.$v['notes'].'</td><td>'.$cla[0]['cla'].'</td><td>'.$avg[0]['moy'].'</td><td><a target="_blank" href="corriger.php?idq='.$v['id_qcm'].'">Corriger</a></td>
                       </tr>
                       ';
                   }
                ?>
            </table>
            
        </div >
    </body>

</html>