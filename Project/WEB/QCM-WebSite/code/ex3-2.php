<?php
    session_start();
/////////////////////////////////////////BD////////////////////////////////////////////////////////////
    include 'connection.php';
    $requette="insert into reponse values (:id,:No,:nbC,:a,:b,:c,:d,:e)";
    $stmt = $conn->prepare($requette);
    $stmt->bindParam(':nbC', $nbc);
    $stmt->bindParam(':No', $no);
    $stmt->bindParam(':id', $idq);
    $stmt->bindParam(':e', $e);
    $stmt->bindParam(':d', $d);
    $stmt->bindParam(':c', $c);
    $stmt->bindParam(':b', $b);
    $stmt->bindParam(':a', $a);

    
//////////////////////////////////////////////////////////////////////////////////////////////////////

    
    $selected=array();
    $nbq=$_SESSION['nbQ'];;

    for($i=0;$i<$nbq;$i++){
        array_push($selected,2);
    }

    function get_option($select){                                       //combo box
        $countries=array('2'=>2, '3'=>3,'4'=>4,'5'=>5);
        $options='';
        while(list($k,$v)=each($countries)){
            if($select==$v)
            $options.='<option value="'.$v.'" selected>'.$k.'</option>';
            else $options.='<option value="'.$v.'" >'.$k.'</option>';
        }
        return $options;
    }
    function get_check($nb,$n){                                         //check box
        $choix=array('A','B','C','D','E');
        $checks='';
        for ($i=0;$i<$nb;$i++) {
            $checks.='<input type="checkbox" name="lang'.$n.'[]" Value='.$choix[$i].'>'.$choix[$i];
        }
        return $checks;
    }
    for ($i=0;$i<$nbq;$i++) {                                           //execution de Onchange ligne 72
        if (isset($_POST['count'.$i])) {
            $selected[$i]=$_POST['count'.$i];
        }
    }
    $re="select nom,prenom from prof where id=?";
        $st=$conn->prepare($re);
        $st->bindParam(1,$_SESSION['idP']);
        $st->execute();
        $row=$st->fetchAll(PDO::FETCH_ASSOC);
        if(isset($_POST['valider'])){                                              //submit


            $idq=$_GET['id'];
        
        
        
            for ($i=0;$i<$nbq;$i++) {
                $a=0;$b=0;$c=0;$d=0;$e=0;
                $nbc=$_POST['count'.$i];
                $no=$i;
        
                $help=isset($_POST['lang'.$i])? $_POST['lang'.$i]:false;
                if (!empty($help)) {
                    foreach ($help as $value) {
                        switch ($value){
                            case 'A': $a=1; break;
                            case 'B': $b=1; break;
                            case 'C': $c=1; break;
                            case 'D': $d=1; break;
                            case 'E': $e=1; break;
                        }
                    }
                }
        
        
                $stmt->execute();
                header("Location: espaceprof.php");
            }
        }
?>

<!DOCTYPE html>
    <head>
        <meta charset="utf-8"/>
        <title>Réponse</title>
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
        <form method="post">
            <table align="center" border="1px">
            <tr>
                <th>Questions</th><th style="width:250px">Réponses</th><th>Nombre de possibilités</th>
            </tr>
            <?php 
            for($i=0;$i<$nbq;$i++){
                echo '
                <tr>
                    <td>Question '.($i+1).'</td>
                    <td style="width:160px;">'.get_check($selected[$i],$i).'</td>
                    <td><select name="count'.$i.'" onchange="this.form.submit();">
                        '.get_option($selected[$i]).'
                    </select></td>    
                </tr>';
            }
            ?>
            </table>
            <br/><input class="sub" type="submit" value="Valider" name="valider" />
        </form>
        </div>
    </body>
</html>


<?php 



?>