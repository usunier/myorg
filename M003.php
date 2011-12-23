<?php 
// M003 programme créé le 01/12/11 dans la version 2012 de MyOrg
$prog = "M003.php" ;
// ce programme permet le création rapide d'un acte
// liste des modifications
	// 
// date de la dernière modification
$dmod = "01/12/11";	
require_once('connections/myorg_syno.php');
include('GetSQLValueString.php'); 

// insertion du nouvel acte
$editFormAction = $_SERVER['PHP_SELF']; if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	$typ = 14 ;
	$urg = 5 ;
  $insertSQL = sprintf("INSERT INTO acts (actLibel, actDesc, ID_job, dateCre, heureDeb, heureFin, ID_typa, ID_urgence) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['lib'], "text"),
					   GetSQLValueString($_POST['desc'], "text"),
					   GetSQLValueString($_POST['idtit'], "int"),
                       GetSQLValueString($_POST['dateC'], "date"),
                       GetSQLValueString($_POST['hdeb'], "date"),
                       GetSQLValueString($_POST['hfin'], "date"),
                       GetSQLValueString($_POST['typ'], "int"),
                       GetSQLValueString($_POST['urg'], "int"));

  mysql_select_db($database_myorg_syno, $myorg_syno);
  $Result1 = mysql_query($insertSQL, $myorg_syno) or die(mysql_error());
  ?>
  <!--retour vers la page d'origine -->
	<script type="text/javascript">
	window.opener.location.reload();
    window.close();
    </script> 
  <?php   
}

mysql_select_db($database_myorg_syno, $myorg_syno);
$query_req001 = "SELECT * FROM acts";
$req001 = mysql_query($query_req001, $myorg_syno) or die(mysql_error());
$row_req001 = mysql_fetch_assoc($req001);
$totalRows_req001 = mysql_num_rows($req001);

mysql_select_db($database_myorg_syno, $myorg_syno);
$query_req002 = "SELECT * FROM type_act ORDER BY type_act.typaLibel ASC";
$req002 = mysql_query($query_req002, $myorg_syno) or die(mysql_error());
$row_req002 = mysql_fetch_assoc($req002);
$totalRows_req002 = mysql_num_rows($req002);

mysql_select_db($database_myorg_syno, $myorg_syno);
$query_req003 = "SELECT * FROM urgence ORDER BY urgence.urgenceLibel";
$req003 = mysql_query($query_req003, $myorg_syno) or die(mysql_error());
$row_req003 = mysql_fetch_assoc($req003);
$totalRows_req003 = mysql_num_rows($req003);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=latin1" />
<title>Saisie Rapide</title>  
<link rel="shortcut icon" href="/favicon.ico" />
<script language=JavaScript>
     function Show(a,b) {  
	 document.forms["form1"].elements["titre"].value = a ;
	 document.forms["form1"].elements["idtit"].value = b ;
	 }
</script>
<?php include('datepicker.inc.php'); 
	  include('entete.php'); ?>
<link href="css/dh/jquery-ui-1.8.7.custom.css" rel="stylesheet" type="text/css" />
</head>

<body class="myorg">

<form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
<table width="620" border="1" cellspacing="0" cellpadding="1">
  <tr>
    <td colspan="6" class="ui-state-error">SAISIE RAPIDE D'UN ACTE</td>
  </tr>
 <tr>
    <td colspan="1">Cat&eacute;gorie</td>
    <td colspan="2">  <ul id="MenuBar2" class="MenuBarHorizontal">
  <!-- début du premier niveau -->
      <li><a class="ui-state-active" href="#">Choisir un TITRE</a>
        
        <!-- début du premier niveau -->
        <?php 
  	// recherche des éléments du premier niveau
	$niv1= 1;
	mysql_select_db($database_myorg_syno, $myorg_syno);
	$query_rqniv1 = sprintf("SELECT * FROM titles WHERE titles.niv = %s ORDER BY titles.lib", GetSQLValueString($niv1, "int"));
	$rqniv1 = mysql_query($query_rqniv1, $myorg_syno) or die(mysql_error());
	$row_rqniv1 = mysql_fetch_assoc($rqniv1);
	$totalRows_rqniv1 = mysql_num_rows($rqniv1); 
	?>
        <ul>
          <?php 
	// boucle sur les éléments de niveau 1
	do {
		$t2 = $row_rqniv1['id']; $t1 = $row_rqniv1['lib']; $par1 = $t2 ; ?>
          <li><a href="#" onClick="Show('<?php echo $t1; ?>','<?php echo $t2; ?>')"><?php echo $t2."-".$t1; ?></a>
            
            <!-- début du niveau 2 -->
            <?php 
  		// recherche des éléments du niveau 2
        $niv2 = 2;
		mysql_select_db($database_myorg_syno, $myorg_syno);
		$query_rqniv2 = sprintf("SELECT * FROM titles WHERE par = %s AND niv = %s ORDER BY lib", GetSQLValueString($par1, "int"), GetSQLValueString($niv2, "int"));
		$rqniv2 = mysql_query($query_rqniv2, $myorg_syno) or die(mysql_error());
		$row_rqniv2= mysql_fetch_assoc($rqniv2);
		$totalRows_rqniv2 = mysql_num_rows($rqniv2); ?>
            <ul>
              <?php 
		// boucle sur les éléments de niveau 2 
		do { 
		$t2 = $row_rqniv2['id']; $t1 = $row_rqniv2['lib']; $par2 = $t2 ; ?>
              <li><a href="#" onClick="Show('<?php echo $t1; ?>','<?php echo $t2; ?>')"><?php echo $t2."-".$t1; ?></a>
                <!-- début du niveau 3 -->
                <?php 
  		// recherche des éléments du niveau 3
        $niv3 = 3;
		mysql_select_db($database_myorg_syno, $myorg_syno);
		$query_rqniv3 = sprintf("SELECT * FROM titles WHERE par = %s AND niv = %s ORDER BY lib", GetSQLValueString($par2, "int"), GetSQLValueString($niv3, "int"));
		$rqniv3 = mysql_query($query_rqniv3, $myorg_syno) or die(mysql_error());
		$row_rqniv3= mysql_fetch_assoc($rqniv3);
		$totalRows_rqniv3 = mysql_num_rows($rqniv3); ?>
                <ul>
                  <?php 
		// boucle sur les éléments de niveau 3 
		do { 
		if ($totalRows_rqniv3 >= 1) {
		$t2 = $row_rqniv3['id']; $t1 = $row_rqniv3['lib']; $par3 = $t2 ; ?>
                  <li><a href="#" onClick="Show('<?php echo $t1; ?>','<?php echo $t2; ?>')"><?php echo $t2."-".$t1; ?></a>
                    <?php } ?>
                    
                    <!-- début du niveau 4 -->
                    <?php 
  		// recherche des éléments du niveau 4
        $niv4 = 4;
		mysql_select_db($database_myorg_syno, $myorg_syno);
		$query_rqniv4 = sprintf("SELECT * FROM titles WHERE par = %s AND niv = %s ORDER BY lib", GetSQLValueString($par3, "int"), GetSQLValueString($niv4, "int"));
		$rqniv4 = mysql_query($query_rqniv4, $myorg_syno) or die(mysql_error());
		$row_rqniv4= mysql_fetch_assoc($rqniv4);
		$totalRows_rqniv4 = mysql_num_rows($rqniv4); ?>
                    <ul>
                      <?php 
		// boucle sur les éléments de niveau 4
		do { 
		if ($totalRows_rqniv4 >= 1) {
		$t2 = $row_rqniv4['id']; $t1 = $row_rqniv4['lib']; $par4 = $t2 ; ?>
                      <li><a href="#" onClick="Show('<?php echo $t1; ?>','<?php echo $t2; ?>')"><?php echo $t2."-".$t1; ?></a>
                        <?php } ?>
                        
                        <!-- début du niveau 5 -->
                        <?php 
  		// recherche des éléments du niveau 5
        $niv5 = 5;
		mysql_select_db($database_myorg_syno, $myorg_syno);
		$query_rqniv5 = sprintf("SELECT * FROM titles WHERE par = %s AND niv = %s ORDER BY lib", GetSQLValueString($par4, "int"), GetSQLValueString($niv5, "int"));
		$rqniv5 = mysql_query($query_rqniv5, $myorg_syno) or die(mysql_error());
		$row_rqniv5= mysql_fetch_assoc($rqniv5);
		$totalRows_rqniv5 = mysql_num_rows($rqniv5); ?>
                        <ul>
                          <?php 
		// boucle sur les éléments de niveau 5
		do { 
		if ($totalRows_rqniv5 >= 1) {
		$t2 = $row_rqniv5['id']; $t1 = $row_rqniv5['lib']; $par5 = $t2 ; ?>
                          <li><a href="#" onClick="Show('<?php echo $t1; ?>','<?php echo $t2; ?>')"><?php echo $t2."-".$t1; ?></a>
                            <?php } ?>
                            
                            <!-- début du niveau 6 -->
                            <?php 
  		// recherche des éléments du niveau 6
        $niv6 = 6;
		mysql_select_db($database_myorg_syno, $myorg_syno);
		$query_rqniv6 = sprintf("SELECT * FROM titles WHERE par = %s AND niv = %s ORDER BY lib", GetSQLValueString($par5, "int"), GetSQLValueString($niv6, "int"));
		$rqniv6 = mysql_query($query_rqniv6, $myorg_syno) or die(mysql_error());
		$row_rqniv6= mysql_fetch_assoc($rqniv6);
		$totalRows_rqniv6 = mysql_num_rows($rqniv6); ?>
                            <ul>
                              <?php 
		// boucle sur les éléments de niveau 6
		do { 
		if ($totalRows_rqniv6 >= 1) {
		$t2 = $row_rqniv6['id']; $t1 = $row_rqniv6['lib']; $par6 = $t2 ; ?>
                              <li><a href="#" onClick="Show('<?php echo $t1; ?>','<?php echo $t2; ?>')"><?php echo $t2."-".$t1; ?></a>
                                <?php } ?>
                                
                                <?php } while ($row_rqniv6 = mysql_fetch_assoc($rqniv6)); ?> 
                                </li>
                              </ul> <!-- fin du niveau 6 -->  
                            
                            <?php } while ($row_rqniv5 = mysql_fetch_assoc($rqniv5)); ?> 
                            </li>
                          </ul> <!-- fin du niveau 5 -->          
                        
                        <?php } while ($row_rqniv4 = mysql_fetch_assoc($rqniv4)); ?> 
                        </li>
                      </ul> <!-- fin du niveau 4 -->  
                    
                    <?php } while ($row_rqniv3 = mysql_fetch_assoc($rqniv3)); ?> 
                    </li>
                  </ul> <!-- fin du niveau 3 -->  
                
                <?php } while ($row_rqniv2 = mysql_fetch_assoc($rqniv2)); ?> 
                </li>
              </ul> <!-- fin du niveau 2 -->  
            
            <?php } while ($row_rqniv1 = mysql_fetch_assoc($rqniv1)); ?>
            </li>
          </ul>  <!-- fin du niveau 1 -->
        
  </li>
  </ul> <!-- fin du niveau 0 -->
      
      
<script type="text/javascript">
<!--
var MenuBar2 = new Spry.Widget.MenuBar("MenuBar2", {imgDown:"SpryAssets/SpryMenuBarDownHover.gif", imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
//-->
</script></td>
  	<td colspan="2"><input name="desc" type="text" id="titre" value="A affecter" size="60" maxlength="35" /></td>
    <td><input name="idtit" type="hidden" id="idtit" value="1" /></td>
  </tr>
  <tr>
    <td width="102" colspan="1">Libell&eacute;</td>
    <td width="510" colspan="5" bgcolor="#FFFFFF"><label for="lib"></label>
      <input name="lib" type="text" id="lib" size="100" maxlength="100" /></td>
  </tr>
  <tr>
    <td colspan="1">Description</td>
    <td colspan="5" bgcolor="#FFFFFF"><label for="desc"></label>
      <textarea name="desc" id="desc" cols="63" rows="3">...</textarea></td>
  </tr>
 
  <tr>
    <td align="center">Date</td>
    <td align="center">
    	<input name="dateC" type="text" id="dateC" value="2011-12-31" size="14" />
      <input type=button value="CHOISIR" onClick="displayDatePicker('dateC', this);"></td>
    <td align="center">heure début</td>
    <td align="center"><input name="hdeb" type="text" value="24:00" size="10" maxlength="5" /></td>
    <td align="center">heure fin</td>
    <td align="center"><input name="hfin" type="text" value="24:00" size="10" maxlength="5" /></td>
  </tr>
  <tr>
    <td colspan="1" align="center">Type</td>
    <td colspan="2" align="center"><label for="typ"></label>
      <select name="typ" id="typ">
        <?php
do {  
?>
        <option value="<?php echo $row_req002['ID_typa']?>"<?php if (!(strcmp($row_req002['ID_typa'], 14))) {echo "selected=\"selected\"";} ?>><?php echo $row_req002['typaLibel']?></option>
        <?php
} while ($row_req002 = mysql_fetch_assoc($req002));
  $rows = mysql_num_rows($req002);
  if($rows > 0) {
      mysql_data_seek($req002, 0);
	  $row_req002 = mysql_fetch_assoc($req002);
  }
?>
      </select></td>
    <td colspan="1" align="center">Urgence</td>
    <td colspan="2" align="center"><label for="urg"></label>
      <select name="urg" id="urg">
        <?php
do {  
?>
        <option value="<?php echo $row_req003['ID_urgence']?>"<?php if (!(strcmp($row_req003['ID_urgence'], 5))) {echo "selected=\"selected\"";} ?>><?php echo $row_req003['urgenceLibel']?></option>
        <?php
} while ($row_req003 = mysql_fetch_assoc($req003));
  $rows = mysql_num_rows($req003);
  if($rows > 0) {
      mysql_data_seek($req003, 0);
	  $row_req003 = mysql_fetch_assoc($req003);
  }
?>
      </select></td>
  </tr>
  <tr>
    <td colspan="6" align="center">
    <input type="submit" name="valider" id="valider" value="Valider" /></td>
  </tr>
</table>
<input type="hidden" name="MM_insert" value="form1" />
</form>
</body>
</html>
<?php
mysql_free_result($req001);

mysql_free_result($req002);

mysql_free_result($req003);
?>
