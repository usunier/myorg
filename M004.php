<?php 
/**
	* M004 programme créé le 06/11/11 dans la version 2012 de MyOrg
	* il s'agit d'une LISTE ORDONNéE de tout ce qui est A FAIRE triée par URGENCE
	* liste des modifications
	*	26/11/11 ajout d'une boite qui s'ouvre au survol du libellé pour afficher la description
	*	28/11/11 les dates dépassées sont en rouge sur fond jaune pour attirer l'attention
	*	30/11/11 modification du pied de page aux normes graphiques de l'application
	*	03/12/11 accès à la base de données géré en PDO
	*	13/12/11 intégration de M015 en remplacement de menu.php ; charset latin1
	*	         colonne gauche M009 intégrée
	*	         documentation au format phpDocumentor
	*	22/12/11 suppression des colonnes Edit et Supp
*/
	$prog = "M004.php" ;
// date de la dernière modification
	$dmod = "22/12/11";	
// initialisation des connexions aux bases de données
require_once('connections/myorg_syno.php'); 

// initialisation de la date
$jour = time();
$jou = date("Y-m-d", $jour); 
$dat = $jou;
if (isset($_GET['date'])) {
  $dat = $_GET['date'];
}
// AUJOURD'HUI
$temps = strtotime($dat);
// NOM DU JOUR
$jours = array('dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi');
$jours_numero = date('w', $temps);
$jours_complet = $jours[$jours_numero];
// QUANTIEME
$NumeroDuJour = date('j', $temps);
// MOIS
$mois = array('', 'Janvier', 'Février', 'Mars', 'Avril', 'Mai',
'Juin', 'Juillet', 'Ao&ucirc;t', 'Septembre', 'Octobre', 'Novembre', 'D&eacute;cembre');
$mois_numero = date("n", $temps);
$mois_complet = $mois[$mois_numero];
// ANNEE
$annee = date("Y", $temps);
// construction du titre de la page
$TitPag = "Liste du à faire au "."$jours_complet $NumeroDuJour $mois_complet $annee";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=latin1" />
<title><?php echo $TitPag ; ?></title>
<script language="JavaScript"> function OuvrirPop(url,nom,haut,Gauche,largeur,hauteur,options) { ouvpop=window.open(url,nom,"top="+haut+",left="+Gauche+",width="+largeur+",height="+hauteur+","+options); } 
</script>
<?php include('M016.php'); ?>
</head>

<body class="myorg"> 
<div id="page">
<?php include('M015.php'); // en-tete ?>  

<div id="sidebar">
<?php include('M009.php'); // barre latérale ?> 
</div>

<!-- début de contenu à droite -->
<div id="mainContent">

<table width="790" border="1" align="center" cellpadding="1" cellspacing="0" class="ui-corner-all" style="position:relative; top:10px">
  <tr class="ui-state-default">
    <tr>
    <td class="ui-state-error" colspan="8" align="center">A FAIRE TRIE PAR URGENCE</td>
    </tr>  
    <tr>
    <td class="ui-state-active" align="center"><input type="submit" onClick="window.open('M006.php')" value="Trier par date" /></td>
    <td class="ui-state-active" align="center"><input type="button" onClick='OuvrirPop("M003.php","sans",100,100,640,300,"menubar=no,scrollbars=no,statusbar=no")' value="cr&eacute;er un acte rapidement"></td>
    <td class="ui-state-active" align="center">Priorit&eacute;</td>
    <td class="ui-state-active" align="center">Date</td>
    <td class="ui-state-active" align="center">Fichier</td>
  </tr>
  <?php 
  $P01 = "SELECT * FROM acts WHERE acts.ID_urgence < 6 ORDER BY acts.ID_urgence ASC, acts.dateCre ASC, acts.heureDeb ASC" ;
	// les actes achevés ne sont pas pris en compte
  foreach ($db->query($P01) as $R01) { ?>
      <tr>
        <?php $AF1 = "M011.php?id=".$R01['ID_act'] ; ?>
        <td colspan="2"  class="ui-state-default" onclick="window.open('<?php echo $AF1; ?>')"><a href="#" class="info"><?php echo $R01['actLibel']; ?>
        <span><div class="ui-state-active">cliquer pour éditer ou supprimer cet acte<br/></div><?php echo $R01['actDesc']; ?></span></a></td>
        <?php 
			$PRI = $R01['ID_urgence']; $IDA = $R01['ID_act']; $JOB = $R01['ID_job'];
			$P02= $db->query("SELECT * FROM urgence WHERE ID_urgence = '$PRI'");
			$P02->setFetchMode(PDO::FETCH_OBJ);
			$R02 = $P02->fetch() ;
			$URG = $R02->urgenceLibel;
			if ( $R01['ID_urgence'] == 1 ) { ?>	  
            <td align="center" class="ui-state-error" style="font-size:80%"><?php echo $URG; ?></td>
            <?php  } elseif ( $PRI == 2 ) { ?>
            <td align="center" class="ui-state-important" style="font-size:80%"><?php echo $URG; ?></td>
            <?php  } elseif ( $PRI == 3 ) { ?>
            <td align="center" class="ui-state-active" style="font-size:80%"><?php echo $URG; ?></td>
            <?php  } elseif ( $PRI == 6 ) { ?>
            <td align="center" class="ui-state-default style="font-size:80%""><?php echo $URG; ?></td>
			<?php  } else { ?>
            <td class="ui-state-default" align="center" style="font-size:80%"><?php echo $URG; } ?></td>
            <td class="ui-state-default" align="center"><?php if ($R01['dateCre'] < $dat) { ?>
            <span class="ui-state-error"><?php echo $R01['dateCre']; ?></span><?php }
			else{ echo $R01['dateCre'];
				} ?>
            <?php 
			$P03= $db->query("SELECT * FROM titles WHERE id = '$JOB'");
			$P03->setFetchMode(PDO::FETCH_OBJ);
			$R03 = $P03->fetch() ;
			$LTI = $R03->lib; $IDT = $R03->id; ?>
	        <td class="ui-state-default" align="center"><a href="ugearaf.php?id=<?php echo $IDT;?>"
            title="article de rattachement de l'acte"><?php echo $LTI;?></a></
      	</tr>
      <?php }  ?>
</table>
</div>

<?php	//insertion du pied de page
include('M012.php');
 
		// fin de la balise CSS 'page' ?>        
</div>

</body>
</html>

