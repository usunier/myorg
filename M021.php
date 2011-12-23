<?php 
/**
 * M021 - ancien programme 'ugelect' 
 * préparation d'une lettre
 * liste des modifications
 *		23/12/11 création du programme en version provisoire (sans PDO)
*/
require_once('connections/myorg_syno.php'); 

// récupération de l'id de l'acte (la lettre dans le cas présent)
	$act = "-1";
	if (isset($_GET['id'])) { $act = $_GET['id']; }

// récupération des éléments de la table acts [rqAct]
	mysql_select_db($database_myorg_syno, $myorg_syno);
	$query_rqAct = sprintf("SELECT * FROM acts WHERE ID_act = '$act'");
	$rqAct = mysql_query($query_rqAct, $myorg_syno) or die(mysql_error());
	$row_rqAct = mysql_fetch_assoc($rqAct);
	$totalRows_rqAct = mysql_num_rows($rqAct);
	
// récupération des éléments du fichier des entités [rqEnt]
	$ent = $row_rqAct['entite']; 
	mysql_select_db($database_myorg_syno, $myorg_syno);
	$query_rqEnt = sprintf("SELECT * FROM entites WHERE ID_entite = '$ent'") ;
	$rqEnt = mysql_query($query_rqEnt, $myorg_syno) or die(mysql_error());
	$row_rqEnt = mysql_fetch_assoc($rqEnt);
	$imgh = $row_rqEnt['imgTete']; 
	$foot = $row_rqEnt['entLibel']; 
	$fju = $row_rqEnt['forju']; 
	$capl = $row_rqEnt['capital']; 
	if ($fju == 'sans') { $foot = $foot; } else {$foot = $foot." - ".$fju ; }
	if ($capl == 'sans') { $foot = $foot; } else {$foot = $foot." au capital de ".$capl; }
	setcookie("piedpage");
	setcookie("piedpage", $foot , time()+3600);
	$GoTo = "M022.php?id=".$act;
  	header(sprintf("Location: %s", $GoTo));
	echo "<br/>".$foot;
	?>