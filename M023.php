<?php 
/**
 * M023 - ancien programme 'ugeacso' 
 * création d'un acte pour le repas du soir
 * liste des modifications
 *		23/12/11 création du programme dans une version provisoire pour débloquer le développement de M011
*/

require_once('connections/myorg_syno.php');
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

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
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
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

// calculer l'id de  l'acte à créer 
mysql_select_db($database_myorg_syno, $myorg_syno);
$query_rq01 = "SELECT * FROM acts ORDER BY ID_act DESC LIMIT 1";
$rq01 = mysql_query($query_rq01, $myorg_syno) or die(mysql_error());
$row_rq01 = mysql_fetch_assoc($rq01);
if(isset($row_rq01['ID_act'])) {
$ida = $row_rq01['ID_act']; $ida++; }
else { $ida = 1; }

$lib = "Repas du soir"; $des="..."; $qui = 74; $jou = date('Y-m-d'); $hord = date('H:i'); $horf = date('H:i'); $typ = 9; $urg = 6; $fic = ""; $idt = 43 ;

$insertSQL = sprintf("INSERT INTO acts (actLibel, actDesc, ID_job, dateCre, heureDeb, heureFin, ID_typa, ID_urgence, fichier) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
				   GetSQLValueString($lib, "text"),
				   GetSQLValueString($des, "text"),
				   GetSQLValueString($idt, "int"),
				   GetSQLValueString($jou, "date"),
				   GetSQLValueString($hord, "text"),
				   GetSQLValueString($horf, "text"),
				   GetSQLValueString($typ, "int"),
				   GetSQLValueString($urg, "int"),
				   GetSQLValueString($fic, "text"));

mysql_select_db($database_myorg_syno, $myorg_syno);
$Result1 = mysql_query($insertSQL, $myorg_syno) or die(mysql_error());

$endGoTo = "M011.php?id=".$ida."&idt=".$idt;
header(sprintf("Location: %s", $endGoTo));
?>
