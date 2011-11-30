<?php 
// myorg004 programme créé le 06/11/11 dans la version 2012 de MyOrg
$prog = "M004.php" ;
// il s'agit d'une liste ordonnée de tout ce qui est à faire
// liste des modifications
	// 26-11-11 ajout d'une boite qui s'ouvre au survol du libellé pour afficher la description
	// 28/11/11 les dates dépassées sont en rouge sur fond jaune pour attirer l'attention
	// 30/11/11 modificition du pied de page aux normes graphiques de l'application
// date de la dernière modification
$dmod = "30/11/11";	
require_once('connections/myorg_syno.php');
include('GetSQLValueString.php'); 


mysql_select_db($database_myorg_syno, $myorg_syno);
$query_rq01 = "SELECT * FROM acts ORDER BY ID_act DESC LIMIT 1";
$rq01 = mysql_query($query_rq01, $myorg_syno) or die(mysql_error());
$row_rq01 = mysql_fetch_assoc($rq01);

// GESTION DES DATES
$ref = time() + 604800 ; // 7 jours
$test = date("Y-m-d", $ref);     
$jour = time();
$jou = date("Y-m-d", $jour); 
// INITIALISATION DE LA DATE
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
// AFFICHAGE DE L'HEURE
$hm = date('H:i');
$hor = date('H:i:s');

$dataff = "&nbsp; $jours_complet $NumeroDuJour $mois_complet $annee";
$tit = "$jours_complet $NumeroDuJour $mois_complet $annee";
$TitPag = "Liste du à faire au ".$tit;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=latin1" />
<title><?php echo $TitPag ; ?></title>
<script language="JavaScript"> function OuvrirPop(url,nom,haut,Gauche,largeur,hauteur,options) { ouvpop=window.open(url,nom,"top="+haut+",left="+Gauche+",width="+largeur+",height="+hauteur+","+options); } 
</script>
<?php include('entete.php'); ?>
</head>

<body class="myorg"> 
<div id="page">
<?php include('menu.php'); ?>


<table width="980" border="1" align="center" cellpadding="1" cellspacing="0" class="ui-corner-all" style="position:relative; top:10px">
  <tr class="ui-state-default">
    <tr>
    <td class="ui-state-error" colspan="8" align="center">A FAIRE TRIE PAR URGENCE</td>
    </tr>  
    <tr>
    <td class="ui-state-active" align="center"><input type="submit" onClick="window.open('myorg006.php')" value="Trier par date" /></td>
    <td class="ui-state-active" align="center"><input type="button" onClick='OuvrirPop("myorg003.php","sans",100,100,640,300,"menubar=no,scrollbars=no,statusbar=no")' value="cr&eacute;er un acte rapidement"></td>
    <td class="ui-state-active" align="center">Priorit&eacute;</td>
    <td class="ui-state-active" align="center">Date</td>      
    <td class="ui-state-active" align="center">Titre</td>
    <td class="ui-state-active" align="center">Edit</td>
    <td class="ui-state-active" align="center">Supp</td>
    <td class="ui-state-active" align="center">Fichier</td>
  </tr>
  <?php 
mysql_select_db($database_myorg_syno, $myorg_syno);
$query_req01 = "SELECT * FROM acts WHERE acts.ID_urgence < 6 ORDER BY acts.ID_urgence ASC, acts.dateCre ASC, acts.heureDeb ASC";
// les actes achevés ne sont pas pris en compte
$req01 = mysql_query($query_req01, $myorg_syno) or die(mysql_error());
$row_req01 = mysql_fetch_assoc($req01);
$totalRows_req01 = mysql_num_rows($req01);
	$test = 0;
	do { ?>
      <tr>
        <td colspan="2"  class="ui-state-default"><a href="#" class="info"><?php echo $row_req01['actLibel']; ?><span><?php echo $row_req01['actDesc']; ?></span></a></td>
        <?php 
			$pri = $row_req01['ID_urgence'];
			mysql_select_db($database_myorg_syno, $myorg_syno);
			$query_rs_pri= "SELECT * FROM urgence WHERE ID_urgence = '$pri'";
			$rs_pri = mysql_query($query_rs_pri, $myorg_syno) or die(mysql_error());
			$row_pri = mysql_fetch_assoc($rs_pri);
			$pri = $row_pri['urgenceLibel'];
			if ( $row_req01['ID_urgence'] == 1 ) { ?>
        <td align="center" class="ui-state-error" style="font-size:80%"><?php  echo $pri; ?>
        </td>
        <?php  } elseif ( $row_req01['ID_urgence'] == 2 ) { ?>
        <td align="center" class="ui-state-important" style="font-size:80%"><?php  echo $pri; ?>
        </td>
        <?php  } elseif ( $row_req01['ID_urgence'] == 3 ) { ?>
        <td align="center" class="ui-state-active" style="font-size:80%"><?php  echo $pri; ?>
        </td>
        <?php  } elseif ( $row_req01['ID_urgence'] == 6 ) { ?>
        <td align="center" class="ui-state-default style="font-size:80%""><?php  echo $pri; ?>
        </td>
        <?php  } else { ?>
        <td class="ui-state-default" align="center" style="font-size:80%"><?php echo $pri; } ?> </td>
        <td class="ui-state-default" align="center">
        <?php if ($row_req01['dateCre'] < $dat) { ?>
        <span class="ui-state-error"><?php echo $row_req01['dateCre']; ?></span>
		<?php } 
		else { ?>
        <?php echo $row_req01['dateCre']; } ?></td>
        <?php $tit = $row_req01['ID_job'];
			mysql_select_db($database_myorg_syno, $myorg_syno);
			$query_rs_tit= "SELECT * FROM titles WHERE id = '$tit'";
			$rs_tit = mysql_query($query_rs_tit, $myorg_syno) or die(mysql_error());
			$row_rs_tit = mysql_fetch_assoc($rs_tit);
			$til = $row_rs_tit['lib'];?>
        <td class="ui-state-default" align="center"><a href="ugearaf.php?id=<?php echo $row_rs_tit['art'];?>" title="article de rattachement de l'acte"><?php echo $til;?></a></td>
        <td class="ui-state-default" align="center"><span class="hasTip" title="modifier l'acte <?php echo $row_req01['ID_act']; ?>"> <a href="ugeacmo.php?id=<?php echo $row_req01['ID_act']; ?>&amp;idt=<?php echo $row_req01['ID_job']; ?>" > <img src="images/edit.png" alt="modifier" border=0"/></a></span></td>
        <td class="ui-state-default" align="center"><span class="hasTip" title="supprimer cet acte"> <a href="ugetesu.php?ID_act=<?php echo $row_req01['ID_act']; ?>" > <img src="images/del16.png" alt="modifier" border=0"/></a></span></td>
        <td class="ui-state-default" align="center"><form id="boutonG2" name="boutonG" method="post" action="documents/<?php echo $row_req01['fichier']; ?>">
            <input type="submit" name="ALLER2" id="ALLER2" value="<?php echo $row_req01['fichier']; ?>" />
        </form></td>
      </tr>
      <?php } while ($row_req01 = mysql_fetch_assoc($req01)); 
//insertion du pied de page
include('M012.php'); ?>
</div>
</div>

</body>
</html>

<?php
mysql_free_result($req01);
?>
