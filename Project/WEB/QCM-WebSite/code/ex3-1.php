<?php
session_start();
        include 'connection.php';
        ////////////////////////////////////
        $msg='';
        $idp=$_SESSION['idP'];

        
    
        $re="select nom,prenom from prof where id=?";
        $st=$conn->prepare($re);
        $st->bindParam(1,$_SESSION['idP']);
        $st->execute();
        $row=$st->fetchAll(PDO::FETCH_ASSOC);

        if(isset($_POST['valider'])){     //submit
            $msg='';
            if( !empty($_POST['nb']) && !empty($_FILES['pdf']['name']) && !empty($_POST['titre']) && !empty($_POST['description']) && !empty($_POST['c1']) && !empty($_POST['c2'])){
                if($_POST['ddebut']<$_POST['dfin']){

                    $name=$_FILES['pdf']['name'];   
                    $ex=pathinfo($name, PATHINFO_EXTENSION);
                    if ($ex=='pdf') {
                        $niv=$_POST['niv'];
                        $nb=$_POST['nb'];
                        $_SESSION['nbQ']=$nb;
                        $dd=$_POST['ddebut'];
                        $df=$_POST['dfin'];
                        $titre=$_POST['titre'];
                        $desc=$_POST['description'];
                        
                        $data=file_get_contents($_FILES['pdf']['tmp_name']);
                        $c1=$_POST['c1'];
                        $c2=$_POST['c2'];
                    
                        $stmt=$conn->prepare("insert into qcm(id_qcm,niveau,nb_question,ddebut,dfin,titre,description,pdf,c1,c2,id_prof)
                        values ('',?,?,?,?,?,?,?,?,?,?)");
                        $stmt->bindParam(1, $niv);
                        $stmt->bindParam(2, $nb);
                        $stmt->bindParam(3, $dd);
                        $stmt->bindParam(4, $df);
                        $stmt->bindParam(5, $titre);
                        $stmt->bindParam(6, $desc);
                        $stmt->bindParam(7, $data);
                        $stmt->bindParam(8, $c1);
                        $stmt->bindParam(9, $c2);
                        $stmt->bindParam(10, $idp);
                        $stmt->execute(); 

                        $requette='select max(id_qcm) as maxx from qcm';
                        $sstmt=$conn->prepare($requette);
                        $sstmt->execute();
                        $rows=$sstmt->fetchAll(PDO::FETCH_ASSOC);
                        $idq = $rows[0]['maxx'];
    
                    
    
                        header("Location: ex3-2.php?id=".$idq);   //passer à la page suivante des réponses
                    }else $msg="Le fichier n'est pas un pdf.";
                }else  $msg='La date fin doit etre aprés la date de début.';
            }else $msg="Il reste des champs vides.";


        }
    ?>  
     

<!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <title>Nouveau QCM</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
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
    <body>
    <body onload=display_ct();>
        <div class="menu2" >
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
        <p class="tit">Nouveau QCM</p><br/>
        <div id="tot">
        
        <form  method="post" enctype="multipart/form-data">
        <div  class="form">
            Titre de QCM : <input type="text" name="titre"/><br/>
            Description : <textarea name="description" cols="50" rows="3" ></textarea><br/>

            Niveau :  <?php echo combo(); ?>
            Nombre de questions : <input class="C"  type="number" name="nb"/><br/>
            
            Date debut : <input type="datetime-local" name="ddebut" min=<?php echo date('Y-m-d\TH:i'); ?> /><br/>
            Date fin : <input type="datetime-local" name="dfin"   min=<?php echo date('Y-m-d\TH:i'); ?> /><br/>
            
            Upload pdf (500M max) : <input class="file" type="file" name="pdf" /><br/>
            C1 : <input class="C" name="c1" type="number" />  C2 : <input class="C" name="c2" type="number" /></div>
            </div>
            <div align="center"><input class="sub" type="submit" value="Valider" name="valider" /> <input class="sub" type="reset" value="Reset"/></div>
        </form>

        
    </div>

    <div class="modal" id="mod" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Erreur</h5>
       
      </div>
      <div class="modal-body">
        <p><?php echo $msg; ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary " id="close"  data-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>    
    
    </body>
</html>
<?php
    function combo(){
        include 'connection.php';
        $requette='select * from niveau';
        $st=$conn->prepare($requette);
        $st->execute();
        $help= $st->fetchAll(PDO::FETCH_ASSOC);
        
        echo '<select name="niv" >';
        foreach ($help as $v) {
            echo '
                <option value="'.$v['niveau'].'">'.$v['niveau'].'</option>';
        }
        echo '</select>';
    }
    if ($msg!='') {
        echo '<script type="text/javascript">
                document.querySelector(".modal").style.display = "block" ;
                
                </script>';
    }
?>
<script type="text/javascript">

        document.getElementById("close").addEventListener("click", function(){
        document.querySelector(".modal").style.display = "none" ; });
    
</script>