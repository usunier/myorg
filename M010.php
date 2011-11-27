<?php 
// M010 anciennement ugeared
// ce programme permet la modification des titres
require_once('connections/myorg_syno.php'); 
include('GetSQLValueString.php');

$idt = "-1";
if (isset($_GET['id'])) {
  $idt = $_GET['id'];
}
// mise à jour de l'enregistrement dans 'titles' après modification
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $Q010b = sprintf("UPDATE titles SET lib=%s, des=%s, ini=%s WHERE id=%s",
                       GetSQLValueString($_POST['libel'], "text"),
					   GetSQLValueString($_POST['desc'], "text"),
					   GetSQLValueString($_POST['datini'], "date"), 
                       GetSQLValueString($idt, "int"));	
  mysql_select_db($database_myorg_syno, $myorg_syno);
  $R010b = mysql_query($Q010b, $myorg_syno) or die(mysql_error());
  
  setcookie("onglet", 0 , time()+180);
  				   
  $G010b = "ugearaf.php?id=".$idt;
  header(sprintf("Location: %s", $G010b));
}

mysql_select_db($database_myorg_syno, $myorg_syno);
$query_R010a = sprintf("SELECT * FROM titles WHERE id = %s", GetSQLValueString($idt, "int"));
$R010a = mysql_query($query_R010a, $myorg_syno) or die(mysql_error());
$row_R010a = mysql_fetch_assoc($R010a);
$totalRows_R010a = mysql_num_rows($R010a);
$TitPag = "Edition du titre '".$row_R010a['lib']."'";

// fin du code avant affichage ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=latin1" />
<title><?php echo $TitPag ; ?></title>
<script language="JavaScript">
function OuvrirPop(url,nom,haut,Gauche,largeur,hauteur,options) {
ouvpop=window.open(url,nom,"top="+haut+",left="+Gauche+",width="+largeur+",height="+hauteur+","+options);
}
</script>
<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
<?php include('entete.php'); ?>
</head>

<body class="myorg"> 
<div id="page">
<?php include('menu.php'); ?>

<div id="sidebar">
<?php include('ugeside.php'); ?>
</div>

<!-- début de contenu à droite -->
<div id="mainContent">

<form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="790" border="1" align="center" cellpadding="1" cellspacing="0" class="ui-corner-all" style="position:relative; top:10px">
    <tr class="ui-state-active">
      <td>libell&eacute;</td>
      <td><input name="libel" type="text" class="style12" id="libel" value="<?php echo $row_R010a['lib']; ?>" size="50" /><?php echo "&nbsp;&nbsp;&nbsp;niveau : ".$row_R010a['niv']; ?></td>
      <td>cr&eacute;&eacute; le</td>
      <td width="40px" align="center">
        <?php $jour = time(); $jou = date("Y-m-d", $jour); if (isset($row_R010a['ini'])) { $jou = $row_R010a['ini']; } ?>
        <input name="datini" type="text" class="griscentre" id="datini" value="<?php echo $jou ; ?>" size="10" maxlength="10" />
       </td>
    </tr>
    <tr>
      <?php $des1 = "texte"; if (isset($row_R010a['des'])) { $des1 = $row_R010a['des']; } ?>
      <td colspan="4"><textarea name="desc" id="desc"><?php echo $des1; ?></textarea>
  <script type="text/javascript">
		CKEDITOR.replace( 'desc', 
		    {
		        toolbar : 'Arti'
		    });
		</script></td>
      </tr>
    <tr class="ui-state-error">
      <td align="center" colspan="2"><span class="hasTip" title="supprimer cet article"><a href="ugearsu.php?id=<?php echo $idt; ?>"><img src="images/del16.png" alt="supprimer" />supprimer ce titre</a></span></td>
      <td colspan="2" align="center"><input type="submit" name="valider" id="valider" value="Valider" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  
</form>
<!-- fin de contenu à droite --></div>
<br class="clearfloat" />

</div>
</body>
</html>