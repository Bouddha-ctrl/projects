
<?php 
session_start();
$idq=$_GET['idq'];
include 'connection.php';


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
    <table border="1px" style="text-align:center;">
                <tr>
                    <th >id etudiant</th><th>Nom</th><th>Prenom</th><th>Note</th>
                </tr>
                <?php
                    $requette='
                    select e.id,e.nom,e.prenom,sum(r.note) as note
                    from etudiant e,reponse_etudiant r
                    where r.id_etudiant=e.id
                    and r.id_qcm=?
                    group by e.id
                    order by note DESC ';
                    $stmt=$conn->prepare($requette);
                    $stmt->bindParam(1,$idq);
                    $stmt->execute();
                    $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    foreach($rows as $v){
                        echo '
                        <tr>
                        <td>'.$v['id'].'</td><td>'.$v['nom'].'</td><td>'.$v['prenom'].'</td><td>'.$v['note'].'</td>
                        </tr>
                        ';
                    }
                    $req="select avg(notes) as moy
                    from qcm_etud
                    where id_qcm=?";
                    $stmt=$conn->prepare($req);
                    $stmt->bindParam(1,$idq);
                    $stmt->execute();
                    $moy=$stmt->fetchAll(PDO::FETCH_ASSOC);
                    echo '<tr><td colspan="3">Moyenne</td><td>'.round($moy[0]['moy'],2).'</td></tr>';

                ?>
            </table>
            
            
        </div >
    </body>

</html>
