<?php 
// connexion à la base de données
require_once('connections/myorg_syno.php');
// fonction Dreamweaver pour contrôler les données entrées dans la base
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);
	switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

// récupération de l'id du titre dont on va lister les actes
$idtit = "-1";
if (isset($_GET['idtit'])) {
  $idtit = $_GET['idtit'];
}
// récupération des actes liés au titre sélectionné
mysql_select_db($database_myorg_syno, $myorg_syno);
$query_req01 = "SELECT * FROM acts WHERE ID_job = '$idtit' ORDER BY dateCre DESC";
$req01 = mysql_query($query_req01, $myorg_syno) or die(mysql_error());
$row_req01 = mysql_fetch_assoc($req01);
$totalRows_req01 = mysql_num_rows($req01);
// récupération du titre pour affichage
mysql_select_db($database_myorg_syno, $myorg_syno);
$query_req02 = sprintf("SELECT * FROM titles WHERE id = %s", GetSQLValueString($idtit, "int"));
$req02 = mysql_query($query_req02, $myorg_syno) or die(mysql_error());
$row_req02 = mysql_fetch_assoc($req02);
$totalRows_req02 = mysql_num_rows($req02);
if ($totalRows_req02> 0) {
$titre = mysql_result($req02, 0, 'lib');	
}
else { echo "ce titre n'existe pas dans la base" ; }
// création du titre de la page
$TitPag = 'Actes pour le titre "'.$titre.'"' ;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=latin1" />
<title><?php echo $TitPag ; ?></title>  
<link rel="shortcut icon" href="/favicon.ico" />  
<?php include('entete.php'); ?>
</head>

<body class="myorg"> 
<div id="page"><?php include('menu.php'); ?>

<div id="sidebar"><?php include('ugeside.php'); ?></div>

<div id="mainContent">

<?php do { // boucle de listage de tous les actes rattachés
$act = $row_req01['ID_act'];
$tit = $row_req01['ID_job'];
$qui = $row_req01['ID_contact'];
$typ = $row_req01['ID_typa'];
$urg = $row_req01['ID_urgence'];
$dac = strtotime($row_req01['dateCre']);
// recherche du type d'acte
mysql_select_db($database_myorg_syno, $myorg_syno);
$query_req03 = sprintf("SELECT * FROM type_act WHERE ID_typa = %s", GetSQLValueString($typ, "int"));
$req03 = mysql_query($query_req03, $myorg_syno) or die(mysql_error());
$row_req03 = mysql_fetch_assoc($req03);
$totalRows_req03 = mysql_num_rows($req03);
// recherche de la catégorie d'urgence de l'acte
mysql_select_db($database_myorg_syno, $myorg_syno);
$query_req04 = sprintf("SELECT * FROM urgence WHERE ID_urgence = %s", GetSQLValueString($urg, "int"));
$req04 = mysql_query($query_req04, $myorg_syno) or die(mysql_error());
$row_req04 = mysql_fetch_assoc($req04);
$totalRows_req04 = mysql_num_rows($req04);
// récupération des contacts liés à cet acte
$query_req05 = "SELECT * FROM actqui WHERE ida = '$act'";
mysql_select_db($database_myorg_syno, $myorg_syno);
$req05 = mysql_query($query_req05, $myorg_syno) or die(mysql_error());
$row_req05 = mysql_fetch_assoc($req05);
$totalRows_req05 = mysql_num_rows($req05);
// création de la liste
$list ="";
do { 
$cont = $row_req05['idq'];
$lier = $row_req05['id1'];
$query_req06 = "SELECT * FROM contacts WHERE ID_contact = '$cont'";
mysql_select_db($database_myorg_syno, $myorg_syno);
$req6 = mysql_query($query_req06, $myorg_syno) or die(mysql_error());
$row_req6 = mysql_fetch_assoc($req6);
$nom = $row_req6['Nom_complet'];
$affc = "<a href='M002.php?idcon=$cont' title='cliquer pour afficher les actes de ce contact' >".$nom."</a>"  ;
$mel = $row_req6['Mail'];
$list = $list.$affc.", " ; 
} while ($row_req05 = mysql_fetch_assoc($req05));
		$rows = mysql_num_rows($req05);
		if($rows > 0) {
		mysql_data_seek($req05, 0);
		$row_req05 = mysql_fetch_assoc($req05); } 
if ($totalRows_req05 < 1) {
$list = "aucun,"; }
// création de la date formatée
// JOURS
$jours = array('dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi');
$jours_numero = date('w', $dac);
$jours_complet = $jours[$jours_numero];
// Numero du jour
$NumeroDuJour = date('d', $dac);
// MOIS
$mois = array('', 'Janvier', 'F&eacute;vrier', 'Mars', 'Avril', 'Mai',
'Juin', 'Juillet', 'Ao&ucirc;t', 'Septembre', 'Octobre', 'Novembre', 'D&eacute;cembre');
$mois_numero = date("n", $dac);
$mois_complet = $mois[$mois_numero];
// ANNEE
$annee = date("Y", $dac);
$datmail = "le ".$jours_complet." ".$NumeroDuJour." ".$mois_complet." ".$annee;
?>
<div class="post" id="post-39">
<div class="postTitle">
   <div class="details">
        <h9 class="storytitle"><a href="ugeacmo.php?id=<?php echo $act?>&idt=<?php echo $tit?>" title="modifier cet acte"><?php echo $row_req01['actLibel']; ?></a></h9>
                <div class="meta">
                <b> date : </b> 
                <a title="date de l'acte"><?php echo $datmail;?></a>
                <b> fichier joint : &nbsp; </b>  
                <a href="documents/<?php echo $row_req01['fichier'];?>"><?php echo $row_req01['fichier']; ?></a> 
                <?php if ($list != "aucun,") { ?>
                <b> contacts : </b> 
                <a title="contact"><?php echo $list; ?></a>
                <?php } ?> 
                <b> priorit&eacute; : </b>
                <b> type : </b>
                <a title="type d'acte"><?php echo $row_req03['typaLibel']; ?></a>,
</div></div></div>
<div class="docbase"><?php $test = strlen($row_req01['actDesc']) ; if ($test > 15 ) {
echo $row_req01['actDesc'];  } ?></div>
</div>
<?php } while ($row_req01 = mysql_fetch_assoc($req01)); ?>



<!-- fin de contenu à droite --></div>
<br class="clearfloat" />

</div>
</body>
</html>
<?php
mysql_free_result($req01);
?>
