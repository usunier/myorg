<?php 
/**
 * M014 - ancien programme 'ugeteda' 
 *
 * affichage des actes pour une date donnée
 * liste des modifications
 *		08/12/11 création du programme
 * 		11/12/11 remplacement de menu par M015
 * 		21/12/11 intégration du pied de page
 *		21/12/11 suppression de la date dans la table des actes de la journée
 *		21/12/11 suppression des boutons voir/edit/supprimer
 *		22/12/11 modification du style des tableaux rendus homogènes et plus légers
 *		22/12/11 intégration du jQuery UI - Datepicker 
 *		22/12/11 les dates dépassées sont mises en évidence
 *		23/12/11 suppression de toutes références aux programmes 'uge'
 *		23/12/11 le programme est validé en version 1
*/
// nom du programme
	$prog = "M014.php" ;
// date de la dernière modification
	$dmod = "23/12/11";	
	
// connexion à la base de données
require_once('connections/myorg_syno.php'); 

/**
 * GESTION DES DATES
 *
 * par défaut le programme utilise la date du jour ; 
 * si une date est pasée en url, elle est prise au lieu de la date du jour;
 * le programme affiche les actes de la semaine à venir
*/
// GESTION DES DATES
$ref = time() + 604800 ; // 7 jours
$test = date("Y-m-d", $ref);
$semaine = date("Y-m-d", $ref);
$jour = time();
$jou = date("Y-m-d", $jour); 

// initialisation de la date
$jou = time();
$jou = date("Y-m-d", $jou); 
$dat = $jou;
if (isset($_GET['date'])) {
  $dat = $_GET['date'];
}
$sem = time() + 604800 ; // 7 jours 
$sem = date("Y-m-d", $sem);

/**
 * FORMATAGE de la DATE à AFFICHER
*/
// TEMPS
$temps = strtotime($dat);
// Noms des jours
$jours = array('dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi');
$jours_numero = date('w', $temps);
$jours_complet = $jours[$jours_numero];
// Numero du jour
$NumeroDuJour = date('j', $temps);
// Noms des mois
$mois = array('', 'Janvier', 'Février', 'Mars', 'Avril', 'Mai',
'Juin', 'Juillet', 'Ao&ucirc;t', 'Septembre', 'Octobre', 'Novembre', 'D&eacute;cembre');
$mois_numero = date("n", $temps);
$mois_complet = $mois[$mois_numero];
// Année
$annee = date("Y", $temps);
// Affichage DATE
$tit = "$jours_complet $NumeroDuJour $mois_complet $annee";
$TitPag = "Suivi de journ&eacute;e du ".$tit;

/**
 * REAFFICHAGE du programme suite au choix d'une NOUVELLE DATE
 * la nouvelle date est passée en url ;
*/
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_POST["MM_date"])) && ($_POST["MM_date"] == "ATTEINDRE")) {
  $drec = $_POST['drec'];
  $drec = substr($drec, 0, 10);
  $dateGoTo = "M014.php?date=".$drec;
  header(sprintf("Location: %s", $dateGoTo));
}
/**
 * Recherche du numéro de semaine
*/
function GetWeekNumberOfDate($date=0)
{
    if(!$date) $date=time();
    return GetWeekNumber(strftime('%d',$date), strftime('%m',$date), strftime('%Y',$date));
}
function GetWeekNumber($d, $m, $y)
{
    $J=gregoriantojd($m,$d, $y);
    $D4=($J+31741-($J %7))% 146097 % 36524 % 1461;
    $L=floor($D4/1460);
    $D1=(($D4-$L) % 365)+$L;
    $wn=floor($D1/7)+1;
    return(substr('00'.$wn, -2));
} ?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=latin1" />
<title><?php echo $tit; ?></title>
<?php include('M016.php'); ?>

<!-- gestion de la date longue -->
<script language="JavaScript">
navvers = navigator.appVersion.substring(0,1);
if (navvers > 3)
   navok = true;
else
   navok = false;
today = new Date;
jour = today.getDay();
numero = today.getDate();
if (numero<10)
   numero = "0"+numero;
mois = today.getMonth();
if (navok)
   annee = today.getFullYear();
else
   annee = today.getYear();
TabJour = new Array("dimanche","lundi","mardi","mercredi","jeudi","vendredi","samedi");
TabMois = new Array("janvier","f&eacute;vrier","mars","avril","mai","juin","juillet","aout","septembre","octobre","novembre","d&eacute;cembre");
messageDate = TabJour[jour] + " " + numero + " " + TabMois[mois] + " " + annee;
</script>

</head>

<body class="myorg"> 
<div id="page">
<?php include('M015.php'); ?>

<!-- début de barre gauche -->
<div id="sidebar">
<?php include('M009.php'); ?>
<!-- fin de barre gauche -->
</div>

<!-- début de contenu à droite -->
<div id="mainContent">

<table width="790" border="1" align="center" cellpadding="1" cellspacing="0" class="ui-corner-all" style="position:relative; top:10px">
<tr>             
            <td width="140" align="center" class="ui-state-active ui-corner-bl ui-corner-tl"><form id="btnjour" name="btnjour" method="post" action="M014.php">
        	<input type="submit" name="cejour" id="cejour" value="CE JOUR <?php echo " : ".$jou; ?>" />
            </form></td>
            <td align="center" class="ui-state-active" style="color:#ffc73d">semaine <?php echo "&nbsp;   ".GetWeekNumberOfDate(); ?></td>
            <td align="center" class="ui-state-active">
			<form id="ATTEINDRE" name="ATTEINDRE" method="post" action=<?php echo $editFormAction; ?>>
       	    cliquer sur la date pour en changer
            <input type="text" name="drec" class="ui-state-error" id="datepicker" value="<?php echo $dat; ?>" size="10" />
       	    <input type="submit" name="MM_date" id="Valider" value="ATTEINDRE" />
            </form></td>
            <?php $jour = time(); $hierstamp = $jour - 86400; $hierform = date("Y-m-d", $hierstamp); ?>
      		<td width="140" align="center" class="ui-state-active  ui-corner-br ui-corner-tr"><form id="boutonB" name="boutonB" method="post" action="M014.php?date=<?php  echo $hierform; ?>">
        	<div align="center"><input type="submit" name="ALLER" id="ALLER" value="HIER" />
        	</div>
            </form></td>
</tr>
</table>

<div class="scroll790" style="position:relative; top:20px" >
<table width="790" border="1" align="center" cellpadding="1" cellspacing="0" class="ui-corner-all">
<tr>
<td class="ui-state-error ui-corner-tl ui-corner-tr" colspan="9" align="center">ACTES DU <?php echo $tit ; ?></td>
</tr> 
<tr align="center">
<td class="ui-state-active" >Libellé</td>
<td class="ui-state-active" ><span class="hasTip" title="créer un nouvel acte"><a href="M017.php?date=<?php echo $dat; ?>"><img src="images/add16.gif" alt="nouvel acte" border="0" title="cliquer pour cr&eacute;er un nouvel acte" /></a></span></td>
<td class="ui-state-active" >Type</td>
<td class="ui-state-active" >Contact</td>
<td class="ui-state-active" >D&eacute;but</td>
<td class="ui-state-active" >Fin</td>
<td class="ui-state-active" >Titre</td>
<td class="ui-state-active" >Fichier</td>
<td width="10" class="ui-state-active">&nbsp;</td>
</tr>
<?php 
$P04 = "SELECT * FROM acts WHERE acts.dateCre = '$dat' ORDER BY acts.ID_urgence ASC, acts.heureDeb DESC" ;
// les actes sont récupérés en fonction de la date choisie
foreach ($db->query($P04) as $R04) { ?>
<tr>
<?php $AF4 = "M011.php?id=".$R04['ID_act'] ; ?>
<td colspan="2"  class="ui-state-default" onclick="window.open('<?php echo $AF4; ?>','_self')"><a href="#" class="info"><?php echo $R04['actLibel']; ?>
<span><div class="ui-state-active">cliquer pour éditer ou supprimer cet acte<br/></div><?php echo $R04['actDesc']; ?></span></a></td>
<td class="ui-state-default" align="center">
<?php // recherche du libellé du type de l'acte
$TYR = $R04['ID_typa']; 
$P05= $db->query("SELECT * FROM type_act WHERE ID_typa = '$TYR'");
$P05->setFetchMode(PDO::FETCH_OBJ);
$R05 = $P05->fetch() ;
$TYP = $R05->typaLibel; 
echo $TYP ; ?>
</td>
<td class="ui-state-default" align="center">
<?php // recherche du libellé du contact
// Récupération du premier contact lié à l'acte affiché
$ID4 = $R04['ID_act'] ;
$P06= $db->query("SELECT * FROM actqui WHERE ida = '$ID4' ORDER BY id1 LIMIT 1");
$P06->setFetchMode(PDO::FETCH_OBJ);
$R06 = $P06->fetch() ;
$CO6 = $R06->idq;
// récupération du libellé du contact lié à l'acte affiché
$P07= $db->query("SELECT * FROM contacts WHERE ID_contact = '$CO6'");
$P07->setFetchMode(PDO::FETCH_OBJ);
$R07 = $P07->fetch() ;
$NO6 = $R07->Nom_complet;
echo $NO6 ; ?>
</td>
 <?php 
$tt = -3600 ;
$td = strtotime($R04['heureDeb']);
$tf = strtotime($R04['heureFin']);
$tp = $tf - $td;
$tt = $tt + $tp;
$tp = $tf - $td - 3600 ;
?>
<td class="ui-state-default"><div align="center"><?php echo substr($R04['heureDeb'], 0,5); ?></div></td>
<?php if ($test == $tf) { ?>
<td class="ui-state-default"><div align="center"><?php echo substr($R04['heureFin'], 0,5); ?></div></td>
<?php } else { ?>
<td class="ui-state-error"><div align="center"><?php echo substr($R04['heureFin'], 0,5); ?></div></td>
<?php } 
$test = $td; 
// récpération du libellé du titre de rattachement de l'acte
$J04 = $R04['ID_job'];
$P08= $db->query("SELECT * FROM titles WHERE id = '$J04'");
$P08->setFetchMode(PDO::FETCH_OBJ);
$R08 = $P08->fetch() ;
$ID8 = $R08->id ;
$TI8 = $R08->lib ; ?>
<td class="ui-state-default" align="center"><a href="M018.php?id=<?php echo $ID8 ;?>"><?php echo $TI8 ; ?></a></td>
<td class="ui-state-default"><form id="boutonG" name="boutonG" method="post" action="documents/<?php echo $R04['fichier']; ?>">
<div align="center"><input type="submit" name="ALLER3" id="ALLER3" value="<?php echo $R04['fichier']; ?>" />
</div></form></td>
<td width="10" class="ui-state-active">&nbsp;</td>
</tr>
<?php } ?>
</table>
</div>

<p>&nbsp;</p>
 
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
<td class="ui-state-default" align="center"><a href="M018.php?id=<?php echo $IDT;?>"
title="article de rattachement de l'acte"><?php echo $LTI;?></a></
</tr>
<?php }  ?>
</table>

</div>
<p>&nbsp;</p>
<p>&nbsp;</p>
</div>
<?php	//insertion du pied de page
include('M012.php'); 
		// fin de la balise CSS 'page' ?>
        
</div>

</body>
</body>
</html>
