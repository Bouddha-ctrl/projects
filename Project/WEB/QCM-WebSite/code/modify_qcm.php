<?php
session_start();
        include 'connection.php';
        ////////////////////////////////////
        $idq=$_GET['idq'];
        $check=$_GET['check'];
        $idp=$_SESSION['idP'];

        
        $requette='select * from qcm where id_qcm=?';
                    $stmt=$conn->prepare($requette);
                    $stmt->bindParam(1,$idq);
                    $stmt->execute();
                    $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
        $v=$rows[0];   

        date_default_timezone_set('Europe/London');
        $h = DateTime::createFromFormat("Y-m-d H:i:s",$v['ddebut']);
        $h = $h->format('Y-m-d\TH:i');
        $h2 = DateTime::createFromFormat("Y-m-d H:i:s",$v['dfin']);
        $h2 = $h2->format('Y-m-d\TH:i');


        if($check==1){
            $dis="";
        }else{
            $dis="disabled";
        }

        $requette="update qcm
    set
    niveau=:niv ,
    ddebut=:dd ,
    dfin=:df ,
    titre=:titre ,
    description=:desc ,
    c1=:c1 ,
    c2=:c2 ,
    pdf=:data
    where id_qcm=:id";
        $stmt = $conn->prepare($requette);
        $stmt->bindParam(':id', $idq);
        $stmt->bindParam(':niv', $niv);
        $stmt->bindParam(':dd', $dd);
        $stmt->bindParam(':df', $df);   
        $stmt->bindParam(':titre', $titre);
        $stmt->bindParam(':desc', $desc);
        $stmt->bindParam(':c1', $c1);
        $stmt->bindParam(':c2', $c2);
        $stmt->bindParam(':data', $data);

        if(isset($_POST['valider'])){     //submit
            $niv=isset($_POST['niv'])? $_POST['niv']:$v['niveau'];
            $dd=isset($_POST['ddebut'])? $_POST['ddebut']:$v['ddebut'];
            $df=$_POST['dfin'];
            $titre=isset($_POST['titre'])? $_POST['titre']:$v['titre'];
            $desc=isset($_POST['description'])? $_POST['description']:$v['description'];
            $data=file_get_contents($_FILES['pdf']['tmp_name']);
            $c1=isset($_POST['c1'])? $_POST['c1']:$v['c1'];
            $c2=isset($_POST['c2'])? $_POST['c2']:$v['c2'];
            
            $j=$stmt->execute();
            
            if($j==1)
            header("Location: espaceprof.php");   
            else echo 'Erreur Bd.';
        }


        $re="select nom,prenom from prof where id=?";
        $st=$conn->prepare($re);
        $st->bindParam(1,$_SESSION['idP']);
        $st->execute();
        $row=$st->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Modifier QCM</title>
        <link rel="stylesheet" href="css/menu.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
        <link href="https://fonts.googleapis.com/css2?family=Balsamiq+Sans&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="css/ex3-1.css">
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
        
    <div class="menu_right" >
        <p align="center" >Modifier QCM</p><br/>
        <div id="tot">
        <form method="post" enctype="multipart/form-data">
            <div class="form">
            Titre de QCM : <input <?php echo $dis; ?> type="text" name="titre" value="<?php echo $v['titre']; ?>"/><br/>
            Description : <textarea <?php echo $dis; ?> name="description" cols="50" rows="3" ><?php echo $v['description']; ?></textarea><br/>

            Niveau :  <?php echo combo($dis,$v['niveau']); ?>
            Nombre de questions : <input class="C" disabled style="width:100px;" type="number" name="nb" value="<?php echo $v['nb_question']; ?>"/><br/>
            
            Date debut : <input <?php echo $dis; ?> type="datetime-local" name="ddebut" <?php echo 'value='.$h;  echo ' min="'.date('Y-m-d\TH:i').'"'; ?> /><br/>
            Date fin  : <input type="datetime-local" name="dfin"   <?php echo 'value='.$h2;  echo ' min="'.date('Y-m-d\TH:i').'"'; ?> /> <br/>

            
            Upload pdf (500M max) : <input <?php echo $dis; ?> type="file" name="pdf" /><br/>
            C1 : <input class="C" <?php echo $dis; ?> name="c1" type="number" value="<?php echo $v['c1']; ?>"/>  C2 : <input class="C" <?php echo $dis; ?> name="c2" type="number" value="<?php echo $v['c2']; ?>"/><br/>
            </div></div>
            <div align="center"><input class="sub" type="submit" value="Valider" name="valider" /> </div>
        </form>
    </div>
    </body>
</html>

<?php
    function combo($dis,$niv){
        include 'connection.php';
        $requette='select * from niveau';
        $st=$conn->prepare($requette);
        $st->execute(); 
        $help= $st->fetchAll(PDO::FETCH_ASSOC);
        
        echo '<select name="niv" '.$dis.' >';
        foreach ($help as $v) {
            
            if($niv==$v['niveau']) echo '
                <option  value="'.$v['niveau'].'" selected>'.$v['niveau'].'</option>';
            else 
            echo '
                <option  value="'.$v['niveau'].'" >'.$v['niveau'].'</option>';
        }
        echo '</select>';
    }

    

    
?>  