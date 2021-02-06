
<?php
    include 'connection.php';
    $choix='';
    $check=-1;
    $exist=0;
    $us_er='';$pre_er='';$nom_er='';$pass_er='';$rad_er='';




    function radio($choix){
        echo 'Vous etes :';
        $e='etudiant'; $p='professeur';
        
        if($choix==$e){
        $radios='
        <input type="radio" name="type" value="etudiant"   onchange="this.form.submit();" checked> Etudiant 
        <input type="radio" name="type" value="professeur" onchange="this.form.submit();"/> Professeur 
        ';
        }else if($choix==$p){
        $radios='
        <input type="radio" name="type" value="etudiant"   onchange="this.form.submit();"/> Etudiant 
        <input type="radio" name="type" value="professeur"  onchange="this.form.submit();" checked> Professeur 
        ';
        }else{
            $radios='
        <input type="radio" name="type" value="etudiant"   onchange="this.form.submit();"/> Etudiant 
        <input type="radio" name="type" value="professeur" onchange="this.form.submit();"/> Professeur 
        ';
        }
        return $radios;
    }
    
    if (isset($_POST['type'])) {
        if ($_POST['type']=="etudiant") {
            $choix='etudiant';
        } else {
            $choix='professeur';
        }
    }



    if(isset($_POST['valider']) && !empty($_POST['valider']) ){
        $check=0;
        
        if(!empty($_POST['nom']))
            $nom=$_POST['nom'];
        else{
            $check=1;
            $nom_er="Veuillez entrer votre nom";
        }
        if(!empty($_POST['pre']))
            $pre=$_POST['pre'];
        else{
            $check=1;
            $pre_er="Veuillez entrer votre prénom";
        }
        if(!empty($_POST['user']))
            $us=$_POST['user'];
        else{
            $check=1;
            $us_er="Veuillez entrer un nom d'utilisateur";$us="";
        }
        if(!empty($_POST['pass']))
            $pass=$_POST['pass'];
        else{
            $check=1;
            $pass_er='Veuillez entrer un mot de passe';
        }
        $niv=isset($_POST['niv'])? $_POST['niv']:'';
        if($choix==''){
            $rad_er="Veuillez choisir votre situation";
            $check=1;
        }

        if ($check==0 && verifier($us)==1) {
            
            if ($choix=='professeur') {
              $reqPr="insert into prof values('',:nom,:pre,:us,:pas)";
              $stat = $conn->prepare($reqPr);
              $stat->bindParam(':nom', $nom);
              $stat->bindParam(':pre', $pre);
              $stat->bindParam(':us', $us);
              $stat->bindParam(':pas', $pass);
              $stat->execute();
              header("Location: login.php");
            } elseif ($choix=='etudiant') {
              $reqEt="insert into etudiant values('',:nom,:pre,:niv,:us,:pas)";
              $stmt = $conn->prepare($reqEt);
              $stmt->bindParam(':nom', $nom);
              $stmt->bindParam(':pre', $pre);
              $stmt->bindParam(':niv', $niv);
              $stmt->bindParam(':us', $us);
              $stmt->bindParam(':pas', $pass);
              $stmt->execute();
              header("Location: login.php");
            } 
        }elseif($check==0 && verifier($us)==0 ) {
            $us_er='Ce nom d\'utilisateur existe deja'; $check=1; $exist=1;
        }                
    }
    
?>

<!DOCTYPE html>
    <head>
        <link rel="stylesheet" href="css/create.css">
        <title>Créer un compte</title>
    </head>
    <body>
    <div class="create-form">
    <div class="form">
        <form method="post">
            <div class="inp">
            <?php echo radio($choix); ?><?php if($check==1 && empty($_POST['type']) ) echo '<span class="erreur">'.$rad_er.'</span>';  ?>
            <br/>
            <?php  combo($choix); ?><br/>
            <div id="wed">
            Nom :<br/><input type="text" name="nom" placeholder="miri"><?php if(isset($_POST['nom']) &&  $check==1  && empty($_POST['nom'])) echo '<span class="erreur">'.$nom_er.'</span>';  ?>
            Prenom :<br/><input type="text" name="pre" placeholder="mohamed"><?php if(isset($_POST['pre']) && $check==1  && empty($_POST['pre'])) echo '<span class="erreur">'.$pre_er.'</span>';  ?>
            Nom d'utilisateur :<br/><input type="text" name="user" placeholder="user"><?php if(isset($_POST['user']) &&  $check==1  && (empty($_POST['user']) || $exist==1 )) echo '<span class="erreur">'.$us_er.'</span>';  ?>
            Mot de passe :<br/><input type="text" name="pass" placeholder="********"><?php if(isset($_POST['pass']) &&  $check==1  && empty($_POST['pass'])) echo '<span class="erreur">'.$pass_er.'</span>';  ?>
            </div>
            </div>
            
            <br/><input id="sub" type="submit" name="valider" value="S'inscrire"/><br/>
            <p>Déjà inscrit ? <a href="login.php">Se connecter</a></p>
            
        </form>
        </div>
        </div>
    </body>
</html>

<?php 

            function combo($choix){
                include 'connection.php';
                $requette='select * from niveau';
                $st=$conn->prepare($requette);
                $st->execute();
                $help= $st->fetchAll(PDO::FETCH_ASSOC);
                if ($choix=='etudiant') {
                    echo 'Niveau : <select name="niv" >';
                    foreach($help as $v)
                    echo '
                        <option value="'.$v['niveau'].'">'.$v['niveau'].'</option>';
                    echo '</select>';
                }
            }
            function verifier($us){  //verifier si ce nom d'utilisateur existe deja
                include 'connection.php';
                $re="select count(id) from prof where user=:us;";
                $st=$conn->prepare($re);
                $st->bindParam(':us', $us);
                $st->execute();
                $help= $st->fetchAll(PDO::FETCH_ASSOC);
                $nb=$help[0]['count(id)'];
                if($nb==0){
                    $re='select count(id) from etudiant where user=:us;';
                    $st=$conn->prepare($re);
                    $st->bindParam(':us', $us);
                    $st->execute();
                    $help= $st->fetchAll(PDO::FETCH_ASSOC);
                    
                    $nb=$help[0]['count(id)'];
                    if($nb==0) return 1;
                    else return 0;

                }else return 0;
            }
            
        
?>