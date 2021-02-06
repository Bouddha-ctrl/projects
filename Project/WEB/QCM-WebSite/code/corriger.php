<?php 
session_start();
include 'connection.php';
$idet=$_SESSION['idet'];
$idq=$_GET['idq'];

$re='
select r.*, e.note as note, e.A as a,e.B as b,e.C as c ,e.D as d,e.E as e
    from reponse r,reponse_etudiant e
    where e.No_question=r.No_question and e.id_qcm=r.id_qcm
    and r.id_qcm=:idq and e.id_etudiant=:ide';
                    $stmt=$conn->prepare($re);
                    $stmt->bindParam(':idq',$idq);
                    $stmt->bindParam(':ide',$idet);
                    $stmt->execute();
                    $rows=$stmt->fetchAll(PDO::FETCH_ASSOC); 
                    //print_r($rows);
    $som=0;
?>

<!DOCTYPE html>
    <head>
        <title>Réponse</title>
        <link rel="stylesheet" href="css/ex3-2.css">
    </head>
    <body>
            
            <table border="1px" align="center">
                <tr>
                    <th>Questions</th><th>Réponses</th><th>Note</th>
                </tr>
                
                <?php
                    foreach($rows as $v){
                    $som+=$v['note'];
                    echo'<tr>
                    <td>Question '.($v['No_question']+1).'</td><td>'.get_check($v,$v['nb_choix']).'</td><td>'.$v['note'].'</td>
                    </tr>';
                    }
                    echo '<tr><td style="text-align:center" colspan="2">Total</td><td>'.$som.'</td></tr>';
                ?>
                
            </table>
            <br/>PS :<br/> 
            La couleur<span style="color:blue"> bleu</span> sont les reponses correctes que vous n'avez pas cochées<br/>
            La couleur <span style="color:green">verte</span> sont les reponses correctes que vous avez  cochées<br/>
            La couleur <span style="color:red">rouge</span> sont les reponses fausses que vous avez cochées
            
        

    </body>
</html>

<?php 
function calcule($q,$r){
    
    $cal=$q*2+$r;
    return $cal;
}
function get_check($v,$nb){    
                                         //check box
    $tab=['a','b','c','d','e']; $tab2=['A','B','C','D','E'];
    $checks='';
    
    for ($i=0;$i<$nb;$i++) {
        $h=0;
        $r=$v[$tab2[$i]];
        $e=$v[$tab[$i]];   
        $h=calcule($r,$e);
        
        if($h==0)
            $checks.='<input disabled="disabled" type="checkbox"  Value='.$tab2[$i].'>'.$tab2[$i];
        else if($h==1)
            $checks.='<input disabled="disabled" type="checkbox" checked  Value='.$tab2[$i].'><font style="color:red">'.$tab2[$i].'</font>';
        else if($h==2)
            $checks.='<input disabled="disabled" type="checkbox"   Value='.$tab2[$i].'><font style="color:blue">'.$tab2[$i].'</font>';
        else if($h==3)
            $checks.='<input disabled="disabled" type="checkbox" checked  Value='.$tab2[$i].'><font style="color:green">'.$tab2[$i].'</font>';
    }
    return $checks;
}


?>