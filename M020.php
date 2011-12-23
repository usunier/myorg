<?php 
/**
 * M020 - ancien programme 'ugemapr' 
 * envoi de mail avec phpMailer
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
?>

<html>
<head>
<title>Envoi de mail</title>
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
<body>


<?php 
// TEMPS
$temps = time();

// JOURS
$jours = array('dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi');
$jours_numero = date('w', $temps);
$jours_complet = $jours[$jours_numero];
// Numero du jour
$NumeroDuJour = date('d', $temps);

// MOIS
$mois = array('', 'Janvier', 'Février', 'Mars', 'Avril', 'Mai',
'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');
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

// récupération des contacts liés à cet acte
$query_lien = "SELECT * FROM actqui WHERE ida = '$act'";
mysql_select_db($database_myorg_syno, $myorg_syno);
$rqlien = mysql_query($query_lien, $myorg_syno) or die(mysql_error());
$row_rqlien = mysql_fetch_assoc($rqlien);
$totalRows_rqlien = mysql_num_rows($rqlien);

$list ="Destinataires : ";
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

$mform = $row_rq02['mf1'].$row_rq01['ID_act'].$datmail.$row_rq02['mf2']."Objet : ".$row_rq01['actLibel'].$row_rq02['mf3'].$row_rq01['actDesc'].$row_rq02['mf4'].$list.$row_rq02['mf5'];
echo $mform;

require_once('includes/class.phpmailer.php');

$mail = new PHPMailer(true);
$mail->IsSMTP(); 

try {
  $mail->Host       = "smtp.xxis.info"; // SMTP server
  $mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
  $mail->SMTPAuth   = true;                  // enable SMTP authentication
  $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
  $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
  $mail->Port       = 465;                   // set the SMTP port for the GMAIL server
  $mail->Username   = "usunier@gmail.com";  // GMAIL username
  $mail->Password   = "zuzu6161";            // GMAIL password
  $mail->AddReplyTo('usunier@xxis.info', 'JF Usunier');
  do { 
  $cont = $row_rqlien['idq'];
	$selqui = "SELECT * FROM contacts WHERE ID_contact = '$cont'";
	mysql_select_db($database_myorg_syno, $myorg_syno);
	$rqmel = mysql_query($selqui, $myorg_syno) or die(mysql_error());
	$row_rqmel = mysql_fetch_assoc($rqmel);
	$nom = $row_rqmel['Nom_complet'];
	$mel = $row_rqmel['Mail'];
  $mail->AddAddress($mel, $nom);
  } while ($row_rqlien = mysql_fetch_assoc($rqlien));
		$rows = mysql_num_rows($rqlien);
		if($rows > 0) {
		mysql_data_seek($rqlien, 0);
		$row_rqlien = mysql_fetch_assoc($rqlien); } 
  $mail->SetFrom('usunier@xxis.info', 'JF Usunier');
  $mail->AddReplyTo('usunier@xxis.info', 'JF Usunier');
  $mail->Subject = $row_rq01['actLibel'];
  $mail->AltBody = 'Pour voir ce message, utiliser un programme compatible HTML!'; // optional - MsgHTML will create an alternate automatically
  $mail->MsgHTML($mform);
 
    $selfic = "SELECT * FROM actfile WHERE ida = '$act' ORDER BY id";
	mysql_select_db($database_myorg_syno, $myorg_syno);
	$rqfic = mysql_query($selfic, $myorg_syno) or die(mysql_error());
	$row_rqfic = mysql_fetch_assoc($rqfic);
	$listf ="fichiers joints : ";
	if ($row_rqfic > 0) {
  do { 	
  $fic = $row_rqfic['file'];  
  $listf = $listf.$fic.", ";
  $fic = "documents/".$row_rqfic['file'];
  $mail->AddAttachment($fic);
  } while ($row_rqfic = mysql_fetch_assoc($rqfic));
		$rows = mysql_num_rows($rqfic);
		if($rows > 0) {
		mysql_data_seek($rqfic, 0);
		$row_rqfic = mysql_fetch_assoc($rqfic); }  }
  
 ?> 
 
 <table width="600" border="0" cellspacing="0" cellpadding="0" style="font-family:Segoe UI, Arial; font-size:10px">
   <table width="600" border="0" cellspacing="0" cellpadding="0" style="font-family:Segoe UI, Arial; font-size:10px">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <tr>
    <td style="border:1px #666666 outset"><?php echo $listf; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="ugetejo.php"><img src="images/back.png" alt="jour" width="16" height="16" border="0"></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td style="border:1px #666666 outset"><?php echo "RAPPORT D'ENVOI : <br/><br/>"; $mail->Send()."<br/>"; ?><a href="ugetejo.php"></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td style="border:1px #666666 outset"><?php echo "Message bien envoy&eacute; le $jours_complet $NumeroDuJour $mois_complet $annee à $hor</p>\n"; ?></td>
  </tr>
</table> 
  
<?php  
} catch (phpmailerException $e) {
  echo $e->errorMessage(); //Pretty error messages from PHPMailer
} catch (Exception $e) {
  echo $e->getMessage(); //Boring error messages from anything else!
}
?>

</body>
</html>
<?php
mysql_free_result($rq01);
mysql_free_result($rq02);
?>
