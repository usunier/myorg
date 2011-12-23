<?php 
/**
 * M017 - ancien programme 'ugeacno' 
 * création d'un acte
 * liste des modifications
 *		23/12/11 création du programme dans une version provisoire pour débloquer le développement de M011
*/

require_once('connections/myorg_syno.php');
include('GetSQLValueString.php'); // maintenue à cause de menu.php qui l'utilise toujours 

// si une id de titre est passée en paramètre d'url, elle est récupérée
// par défaut le titres est "à affecter" (id = 1)
$idt = 1 ;
if(isset($_GET['id'])) {
$idt = $_GET['id'] ; }

// GESTION DES DATES
$ref = time() + 604800 ; // 7 jours
$test = date("Y-m-d", $ref);     
$jour = time();
$jou = date("Y-m-d", $jour); 

// initialisation de la date
$dat = $jou;
if (isset($_GET['date'])) {
  $dat = $_GET['date'];
}
?>
<!-- calculer l'id de  l'acte à créer -->
<?php 


mysql_select_db($database_myorg_syno, $myorg_syno);
$query_rq01 = "SELECT * FROM acts ORDER BY ID_act DESC LIMIT 1";
$rq01 = mysql_query($query_rq01, $myorg_syno) or die(mysql_error());
$row_rq01 = mysql_fetch_assoc($rq01);
if(isset($row_rq01['ID_act'])) {
$ida = $row_rq01['ID_act']; $ida++; }
else { $ida = 1; }

$lib = "libellé"; $des="..."; $qui = 74; $hord = date('H:i'); $horf = date('H:i'); $typ = 14; $urg = 6; $fic = "";

$insertSQL = sprintf("INSERT INTO acts (actLibel, actDesc, ID_job, ID_contact, dateCre, heureDeb, heureFin, ID_typa, ID_urgence, fichier) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
				   GetSQLValueString($lib, "text"),
				   GetSQLValueString($des, "text"),
				   GetSQLValueString($idt, "int"),
				   GetSQLValueString($qui, "int"),
				   GetSQLValueString($dat, "date"),
				   GetSQLValueString($hord, "text"),
				   GetSQLValueString($horf, "text"),
				   GetSQLValueString($typ, "int"),
				   GetSQLValueString($urg, "int"),
				   GetSQLValueString($fic, "text"));

mysql_select_db($database_myorg_syno, $myorg_syno);
$Result1 = mysql_query($insertSQL, $myorg_syno) or die(mysql_error());

$endGoTo = "M011.php?id=".$ida ;
header(sprintf("Location: %s", $endGoTo));
?>
