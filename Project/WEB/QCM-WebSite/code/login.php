<?php session_start(); $user_err=''; $pass_err='';?>
<?php
    include 'connection.php';
    $check=0; $k2=0;
    if (isset($_POST['valider'])) {
        if(empty($_POST['user'])) { $check=1;
            $user_err='Veuillez entrer votre nom d\'utilisateur';
        }else {
            $user=$_POST['user'];
        }        
        if(empty($_POST['pass'])) { $check=1;
            $pass_err='Veuillez entrer votre mot de passe';
        }else {
            $pass=$_POST['pass'];
        }       
        
        if ($check==0) {
            $requette='select id from etudiant where user=? and pass=?';
            $stmt=$conn->prepare($requette);
            $stmt->bindParam(1, $user);
            $stmt->bindParam(2, $pass);
            $stmt->execute();
            $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($rows)==1) {
                $_SESSION['idet']=$rows[0]['id'];
                header("Location: espace_etudiant.php");
            } elseif (count($rows)>1) {
                echo 'erreur';
            } elseif (count($rows)==0) {
                $requette='select id from prof where user=? and pass=?';
                $stmt=$conn->prepare($requette);
                $stmt->bindParam(1, $user);
                $stmt->bindParam(2, $pass);
                $stmt->execute();
                $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($rows)==1) {
                    $_SESSION['idP']=$rows[0]['id'];
                    header("Location: espaceprof.php");
                } elseif (count($rows)>1) {
                    echo 'erreur bd';
                } elseif (count($rows)==0) {
                    $k2=1;
                }
            }
        }   
    }
?>


<!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="css/login.css">
        <title>Login</title>
    </head>
    <body>
    
        <div id="tot">
            
            <div id="tot2">
                <h1>Bonjour</h1><br/>
                <div id="inp">
                    <form method="post" id="form"  >
                    <input type="text" name="user" placeholder="Nom d'utilisateur" /><?php if(isset($_POST['user']) && empty($_POST['user'])) echo '<span class="erreur">'.$user_err.'</span>';  ?>
                    <input type="password" name="pass" placeholder="Mot de passe"  /><?php if(isset($_POST['pass']) && empty($_POST['pass'])) echo '<span class="erreur">'.$pass_err.'</span>';  ?>
                    </div>
                    <?php if($k2==1) echo '<p class="danger">Nom d\'utilisateur ou/et le mot de passe sont incorrectes</p> '; ?>
                    <div><input id="submit" type="submit" name="valider" value="se connecter"><br/> </div>
                    <p class="creer">La premiére fois ? <a href="create.php">Créer un compte </a><br/></p>
                    </form>
                
            </div>
        </div>
    
    </body>

</html>


