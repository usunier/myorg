<?php
/**
 * M009 programme créé le 05/12/11 dans la version 2012 de MyOrg
 * il remplace 'ugeside' pour la gestion de la barre latérale
 * liste des modifications
 *		06/12/11 création du programme
 *		07/12/11 documentation au format phpDocumentor
 * 		21/12/11 programme utilisant PDO
 * 		23/12/11 suppression de toute référence au programmes de la série 'uge'
*/
// 
// nom du programme
// $prog = "M009.php" ; non utilisé car il s'agit d'un sous-programme
	
// TEMPS
$temps = time();
// JOURS
$jours = array('dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi');
$jours_numero = date('w', $temps);
$jours_complet = $jours[$jours_numero];
// Numero du jour
$NumeroDuJour = date('d', $temps);
// MOIS
$mois = array('', 'Janvier', 'F&eacute;vrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Ao&ucirc;t', 'Septembre', 'Octobre', 'Novembre', 'D&eacute;cembre');
$mois_numero = date("n", $temps);
$mois_complet = $mois[$mois_numero];
// ANNEE
$annee = date("Y", $temps);
$datmail = $jours_complet." ".$NumeroDuJour." ".$mois_complet." ".$annee;
?>

<script language="JavaScript"> function OuvrirPop(url,nom,haut,Gauche,largeur,hauteur,options) { ouvpop=window.open(url,nom,"top="+haut+",left="+Gauche+",width="+largeur+",height="+hauteur+","+options); } 
</script>

<table width="188px" border="0" cellspacing="0" cellpadding="1" class="ui-corner-all" style="position:relative; top:10px">
  <tr>
    <td align="center" class="ui-state-active" >
    <a href="<?php  echo 'M014.php' ?>"><?php echo $datmail; ?></a></td>
  </tr>
  <tr>
  	<td align='center' class="ui-state-active">
    <a href='#' onclick='OuvrirPop("M003.php","sans",100,100,640,300,"menubar=no,scrollbars=no,statusbar=no")'>cr&eacute;er un acte rapidement</a>
    </td>
   </tr> 
   <tr>
     <td align='center' class="ui-state-active">
     <a href='#' onclick='OuvrirPop("M013.php","sans",50,50,800,380,"menubar=no,scrollbars=no,statusbar=no")'>&eacute;diter les notes ci-dessous</a>
     </td></tr></table><div class="scroll2"><table><tr>
     <td>
     <?php 
	 $Q01= $db->query("SELECT * FROM titles WHERE id = 176");
	 $Q01->setFetchMode(PDO::FETCH_OBJ); 
	 $R01 = $Q01->fetch() ;
	 $DES = $R01->des;
     echo $DES ; ?>
     </td></tr></table></div>
</body>
</html>
