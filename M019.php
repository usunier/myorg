<?php 
/**
 * M019 - ancien programme 'ugemapr' 
 * préparation d'un envoi de mail
 * liste des modifications
 *		23/12/11 création du programme en version provisoire (sans PDO)
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

$act = "-1";
if (isset($_GET['id'])) {
  $act = $_GET['id'];
}
mysql_select_db($database_myorg_syno, $myorg_syno);
$query_rq01 = sprintf("SELECT * FROM acts WHERE ID_act = %s", GetSQLValueString($act, "int"));
$rq01 = mysql_query($query_rq01, $myorg_syno) or die(mysql_error());
$row_rq01 = mysql_fetch_assoc($rq01);
$totalRows_rq01 = mysql_num_rows($rq01);

// récupération des contacts liés à cet acte
$query_lien = "SELECT * FROM actqui WHERE ida = '$act'";
mysql_select_db($database_myorg_syno, $myorg_syno);
$rqlien = mysql_query($query_lien, $myorg_syno) or die(mysql_error());
$row_rqlien = mysql_fetch_assoc($rqlien);
$totalRows_rqlien = mysql_num_rows($rqlien);

$list ="";
do { 
$cont = $row_rqlien['idq'];
$selqui = "SELECT * FROM contacts WHERE ID_contact = '$cont'";
mysql_select_db($database_myorg_syno, $myorg_syno);
$rqmel = mysql_query($selqui, $myorg_syno) or die(mysql_error());
$row_rqmel = mysql_fetch_assoc($rqmel);
$nom = $row_rqmel['Nom_complet'];
$mel = $row_rqmel['Mail'];
$list = $list.$nom." &lt;".$mel."&gt;, ";
} while ($row_rqlien = mysql_fetch_assoc($rqlien));
		$rows = mysql_num_rows($rqlien);
		if($rows > 0) {
		mysql_data_seek($rqlien, 0);
		$row_rqlien = mysql_fetch_assoc($rqlien); } 
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=latin1" />
<title>Envoi de mail</title>
</head>
<body>
<p>
  <?php 
$temps = time();

// JOURS
$jours = array('dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi');
$jours_numero = date('w', $temps);
$jours_complet = $jours[$jours_numero];
// Numero du jour
$NumeroDuJour = date('d', $temps);

// MOIS
$mois = array('', 'Janvier', 'F&eacute;vrier', 'Mars', 'Avril', 'Mai',
'Juin', 'Juillet', 'Ao&ucirc;t', 'Septembre', 'Octobre', 'Novembre', 'D&eacute;cembre');
$mois_numero = date("n", $temps);
$mois_complet = $mois[$mois_numero];

// ANNEE
$annee = date("Y", $temps);

// Affichage DATE
$hm = date('H:i');
$hor = date('H:i:s');

$datmail = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;le $jours_complet $NumeroDuJour $mois_complet $annee";

$id = $row_rq01['ID_act']; $ent = $row_rq01['entite']; $qui = $row_rq01['ID_contact'];
mysql_select_db($database_myorg_syno, $myorg_syno);
$query_rq02 = sprintf("SELECT * FROM entites WHERE ID_entite = %s", GetSQLValueString($ent, "int"));
$rq02 = mysql_query($query_rq02, $myorg_syno) or die(mysql_error());
$row_rq02 = mysql_fetch_assoc($rq02); 

$mform = $row_rq02['mf1'].$row_rq01['ID_act']."&nbsp;&nbsp;&nbsp".$datmail.$row_rq02['mf2'].$row_rq01['actLibel'].$row_rq02['mf3'].$row_rq01['actDesc'].$row_rq02['mf4'].$list.$row_rq02['mf5'];
echo $mform;
?>
</p>
<p>&nbsp;</p>
<table width="600" border="0" cellpadding="0" cellspacing="0" class="tableau">
<tr>
<td colspan="4">
<?php 
mysql_select_db($database_myorg_syno, $myorg_syno);
$query_rqfic= sprintf("SELECT * FROM actfile WHERE ida = %s", GetSQLValueString($act, "int"));
$rqfic = mysql_query($query_rqfic, $myorg_syno) or die(mysql_error());
$row_rqfic = mysql_fetch_assoc($rqfic);
$totalRows_rqfic = mysql_num_rows($rqfic);
echo "fichiers joints ";
do {
echo " : ".$row_rqfic['file'] ;
} while ($row_rqfic = mysql_fetch_assoc($rqfic));
?></td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr>
<form name="form1" action="upload_file.php?ida=<?php echo $_GET['id'] ?>" method="post" enctype="multipart/form-data">
  <td width="250"><div align="left">Importer un document et le joindre</div></td>
  <td width="300"><input name="file" type="file" id="file" size="35" /></td>
  <td width="50"><div align="center">
    <input type="submit" name="submit" value="Importer" />
  </div></td>
</form>
</tr>
<tr><td>&nbsp;</td></tr>
<tr><form name="form2" action="join_file.php?ida=<?php echo $_GET['id'] ?>" method="post" enctype="multipart/form-data">
  <td width="250"><div align="left">Joindre un document déjà sur le serveur</div></td>
  <td width="300"><input name="joindre" type="file" id="joindre" size="35" /></td>
  <td width="50"><div align="center">
    <input type="submit" name="joindre" value="Joindre" />
  </div></td></tr>
</form>
</table>
<p>&nbsp;</p>
<form name="form3" method="post" action="M020.php?id=<?php echo $row_rq01['ID_act'];?>">
  <label>
  <input type="submit" name="sendmail" id="sendmail" value="envoyer le mail">
  </label>
</form>

</body>
</html>
<?php
mysql_free_result($rq01);
mysql_free_result($rq02);
?>
