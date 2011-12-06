<?php 
// M013 programme créé le 06/12/11 dans la version 2012 de MyOrg
$prog = "M013.php" ;
// ce programme permet la gestion du post it qui apparaît dans la barre latérale du site
// liste des modifications
	// 
// date de la dernière modification
$dmod = "06/12/11";	

require_once('connections/myorg_syno.php');

// modification du texte du Post It
$editFormAction = $_SERVER['PHP_SELF']; if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']); }
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	$des = $_POST['desc'];
	$R01 = $db->exec("UPDATE titles SET des='$desc' WHERE id=176");
  ?>
  <!--retour vers la page d'origine -->
	<script type="text/javascript">
	window.opener.location.reload();
    window.close();
    </script> 
<?php } ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=latin1" />
<title>Mise à jour du Post'it</title>  
<link rel="shortcut icon" href="/favicon.ico" />
<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
<?php include('entete.php'); ?>
</head>

<body class="myorg">
<?php // récupération du Post'it actuel
$Q02 = $db->query("SELECT * FROM titles WHERE id=176");
$Q02->setFetchMode(PDO::FETCH_OBJ);
$R02 = $Q02->fetch();
?>
<form id="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="100%" border="1" cellspacing="0" cellpadding="1">
  <tr><td class="ui-state-error" align="center">MISE à JOUR DU POST'IT</td></tr>
  <tr><td><textarea name="desc"><?php echo $R02->des ; ?></textarea>
  <script type="text/javascript">CKEDITOR.replace( 'desc', { toolbar : 'Arti' }); </script></td></tr>
  <tr><td align="center"><input type="submit" name="valider" id="valider" value="Valider" /></td></tr>
</table>
<input type="hidden" name="MM_insert" value="form1" />
</form>
</body>
</html>
