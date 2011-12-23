<?php 
/**
 * M011 - ancien programme 'ugeacmo' 
 * gestion d'un acte
 * liste des modifications
 *		07/12/11 création du programme
 *		13/12/11 intégration de M015 (menu) et M009 (barre latérale) 
 *		23/12/11 suppression de quelques références aux programmes de la série 'uge'
 *		23/12/11 intégration du changement de titre
 *		23/12/11 insertion du pied de page 
*/
// nom du programme
	$prog = "M011.php" ;
// date de la dernière modification
	$dmod = "13/12/11";	
	
/** connexion à la base de données
*/
require_once('connections/myorg_syno.php'); 

include('GetSQLValueString.php');
 
$TitPag = "Gestion d'un Acte";
$idact = "-1";
if (isset($_GET['id'])) {
  $idact = $_GET['id'];
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
$updateSQL = sprintf("UPDATE acts SET actLibel=%s, actDesc=%s, ID_job=%s, dateCre=%s, heureDeb=%s, heureFin=%s, ID_typa=%s, ID_urgence=%s, fichier=%s, entite=%s WHERE ID_act=%s",
                       GetSQLValueString($_POST['actLibel'], "text"),
                       GetSQLValueString($_POST['actDesc'], "text"),
                       GetSQLValueString($_POST['idtit'], "int"),
                       GetSQLValueString($_POST['dateCre'], "date"),
                       GetSQLValueString($_POST['heureDeb'], "date"),
                       GetSQLValueString($_POST['Heurefin'], "date"),
                       GetSQLValueString($_POST['ID_typa'], "int"),
                       GetSQLValueString($_POST['ID_urgence'], "int"),
                       GetSQLValueString($_POST['fichier'], "text"),
					   GetSQLValueString($_POST['entite'], "int"),
                       GetSQLValueString($_POST['ID_act'], "int"));

  mysql_select_db($database_myorg_syno, $myorg_syno);
  $Result1 = mysql_query($updateSQL, $myorg_syno) or die(mysql_error());

  $today = time();
  $jour = date("Y-m-d", $today);
  $demainstamp = $today + 86400;
  $dat = date("Y-m-d", $demainstamp); 
  $hierstamp = $today - 86400;
  $hier = date("Y-m-d", $hierstamp); 
  
  switch ($_POST['suite']) {
  case 1:
  $updateGoTo = "M014.php?date=".$jour;
  header(sprintf("Location: %s", $updateGoTo));
  break;
  case 2:
  $updateGoTo = "M014.php?date=".$hier;
  header(sprintf("Location: %s", $updateGoTo));
  break;
  case 3:
  $updateGoTo = "ugeacaf.php?id=".$_POST['ID_act']."&idt=".$idt;
  header(sprintf("Location: %s", $updateGoTo));
  break;
  case 4:
  $updateGoTo = "M019.php?id=".$_POST['ID_act'];
  header(sprintf("Location: %s", $updateGoTo));
  break;
  case 5:
  $updateGoTo = "M011.php?id=".$_POST['ID_act']."&idt=".$idt;
  header(sprintf("Location: %s", $updateGoTo));
  break;
  case 6:
  $updateGoTo = "M021.php?id=".$_POST['ID_act']."&idt=".$idt;
  header(sprintf("Location: %s", $updateGoTo));
  break;
  case 7:
  $updateGoTo = "ugecacr.php?id=".$_POST['ID_act']."&idt=".$idt;
  header(sprintf("Location: %s", $updateGoTo));
  break;
  case 8:
  // création d'une relance
  	// recherche de l'id du futur enregistrement
	mysql_select_db($database_myorg_syno, $myorg_syno);
	$req02 = "SELECT * FROM acts ORDER BY ID_act DESC LIMIT 1";
	$res02 = mysql_query($req02, $myorg_syno) or die(mysql_error());
	$row_res02 = mysql_fetch_assoc($res02);
	if(isset($row_res02['ID_act'])) {
	$ida = $row_res02['ID_act']; $ida++; }
	else { $ida = 1; }
	// données à entrer
  	$lib = $_POST['actLibel']." (relance)"; 
	$ori = "<a href='M011.php?id=".$idact."'>".$idact."</a></br>";
	$des = "ceci est une relance de l'acte".$idact.">acte</a></br>".$_POST['actDesc']; 
	$qui = 74; $hord = "24:00"; $horf = "24:00" ; $typ = 51 ; $urg = 4; $fic = "";
	$req01 = sprintf("INSERT INTO acts (actLibel, actDesc, ID_job, ID_contact, dateCre, heureDeb, heureFin, ID_typa, ID_urgence, fichier) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
					   GetSQLValueString($lib, "text"),
					   GetSQLValueString($ori, "text"),
					   GetSQLValueString($idt, "int"),
					   GetSQLValueString($qui, "int"),
					   GetSQLValueString($dat, "date"),
					   GetSQLValueString($hord, "text"),
					   GetSQLValueString($horf, "text"),
					   GetSQLValueString($typ, "int"),
					   GetSQLValueString($urg, "int"),
					   GetSQLValueString($fic, "text"));
	
	mysql_select_db($database_myorg_syno, $myorg_syno);
	$res01 = mysql_query($req01, $myorg_syno) or die(mysql_error());
  $goto01 = "M011.php?id=".$ida;
  header(sprintf("Location: %s", $goto01));
  break;
  case 9:
  $updateGoTo = "M004.php";
  header(sprintf("Location: %s", $updateGoTo));
  break;
  }
}

mysql_select_db($database_myorg_syno, $myorg_syno);
$query_rs_act = sprintf("SELECT * FROM acts WHERE ID_act = %s", GetSQLValueString($idact, "int"));
$rs_act = mysql_query($query_rs_act, $myorg_syno) or die(mysql_error());
$row_rs_act = mysql_fetch_assoc($rs_act);
$totalRows_rs_act = mysql_num_rows($rs_act);
$auj = $row_rs_act['dateCre'];
$tit = $row_rs_act['ID_job'];

mysql_select_db($database_myorg_syno, $myorg_syno);
$query_rs_contact = "SELECT ID_contact, Nom_complet FROM contacts ORDER BY Nom_complet ASC";
$rs_contact = mysql_query($query_rs_contact, $myorg_syno) or die(mysql_error());
$row_rs_contact = mysql_fetch_assoc($rs_contact);
$totalRows_rs_contact = mysql_num_rows($rs_contact);

mysql_select_db($database_myorg_syno, $myorg_syno);
$query_rs_typa = "SELECT * FROM type_act ORDER BY type_act.typaLibel";
$rs_typa = mysql_query($query_rs_typa, $myorg_syno) or die(mysql_error());
$row_rs_typa = mysql_fetch_assoc($rs_typa);
$totalRows_rs_typa = mysql_num_rows($rs_typa);

mysql_select_db($database_myorg_syno, $myorg_syno);
$query_rs_urgence = "SELECT * FROM urgence ORDER BY urgence.urgenceLibel";
$rs_urgence = mysql_query($query_rs_urgence, $myorg_syno) or die(mysql_error());
$row_rs_urgence = mysql_fetch_assoc($rs_urgence);
$totalRows_rs_urgence = mysql_num_rows($rs_urgence);

mysql_select_db($database_myorg_syno, $myorg_syno);
$query_rqent = "SELECT * FROM entites ORDER BY entLibel ASC";
$rqent = mysql_query($query_rqent, $myorg_syno) or die(mysql_error());
$row_rqent = mysql_fetch_assoc($rqent);
$totalRows_rqent = mysql_num_rows($rqent);

// recherche de l'heure de fin du dernier enregistrement
mysql_select_db($database_myorg_syno, $myorg_syno);
$query_rs_last = "SELECT * FROM acts WHERE dateCre = '$auj' AND ID_urgence = '6' AND ID_act < '$idact' ORDER BY heurefin DESC LIMIT 1";
$rs_last = mysql_query($query_rs_last, $myorg_syno) or die(mysql_error());
$row_rs_last = mysql_fetch_assoc($rs_last);
$totalRows_rs_last = mysql_num_rows($rs_last);

// création du titre de la page
$pagetit = "Gestion d'un acte";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=latin1" />
<title><?php echo $pagetit; ?></title>
<?php include('entete.php'); ?>
<?php include('datepicker.inc.php'); ?>
<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
<script language="JavaScript">
function OuvrirPop(url,nom,haut,Gauche,largeur,hauteur,options) {
ouvpop=window.open(url,nom,"top="+haut+",left="+Gauche+",width="+largeur+",height="+hauteur+","+options);
}
</script>
<script language="JavaScript">
function TitleSearch() {
tac=document.forms["form1"].elements["tache"].value;
lib=document.forms["form1"].elements["actLibel"].value;
act=document.forms["form1"].elements["ID_act"].value;
dest="ugetich.php?id="+tac+"&lib="+lib+"&act="+act;
OuvrirPop(dest, 'titre',100,100,640,530,'menubar=no,scrollbars=yes,statusbar=no');
}
</script>

<script language="JavaScript">
	function Addfile() {
	prov = document.forms["form1"].elements["fichier"].value;
	naf = document.forms["form1"].elements["nomfic"].value;
	document.forms["form1"].elements["fichier"].value = naf ;
	document.forms["form1"].elements["nomfic"].value = prov ;
	}
</script>

<script language="JavaScript">
	function Lastend() {
	las1 = document.forms["form1"].elements["last"].value;
	document.forms["form1"].elements["heureDeb"].value = las1 ;
	}
</script>

<script language="JavaScript">
	function Now() {
	currentTime = new Date();
	hours = currentTime.getHours();
	minutes = currentTime.getMinutes();
	if (hours < 10){ hours = "0" + hours }
	if (minutes < 10){ minutes = "0" + minutes}
	endtime = hours + ":" + minutes + ":00"  ;
	document.forms["form1"].elements["Heurefin"].value = endtime ;
	}
</script>

<script language="JavaScript">
	function createInstance()
	{
        var req = null;
		if (window.XMLHttpRequest)
		{
 			req = new XMLHttpRequest();
		} 
		else if (window.ActiveXObject) 
		{
			try {
				req = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e)
			{
				try {
					req = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (e) 
				{
					alert("XHR not created");
				}
			}
	        }
        return req;
	};

	function storing(data)
	{
		var element = document.getElementById('storage');
		element.innerHTML = data;
	}
	
	function submitForm(element)
	{ 
		var req =  createInstance();
		var acn = document.form1.ID_act.value;
		var qui = document.ajax.who.value;
		var data =  "var1=" + acn + "&var2=" + qui;
		req.onreadystatechange = function()
		{ 
			if(req.readyState == 4)
			{
				if(req.status == 200)
				{
					storing(req.responseText);	
				}	
				else	
				{
					alert("Error: returned status code " + req.status + " " + req.statusText);
				}	
			} 
		}; 
      
		req.open("POST", "lieContact.php", true); 
		req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		req.send(data); 
	} 
	
		function findWho(element)
	{ 
		var req =  createInstance();
		var acn = document.form1.ID_act.value;
		var data =  "var1=" + acn + "&var2=" + 0;
		req.onreadystatechange = function()
		{ 
			if(req.readyState == 4)
			{
				if(req.status == 200)
				{
					storing(req.responseText);	
				}	
				else	
				{
					alert("Error: returned status code " + req.status + " " + req.statusText);
				}	
			} 
		}; 
      
		req.open("POST", "lieContact.php", true); 
		req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		req.send(data); 
	}
</script>
<script language=JavaScript>
     function Show(a,b) {  
	 document.forms["form1"].elements["titre"].value = a ;
	 document.forms["form1"].elements["idtit"].value = b ;
	 }
</script>
</head>

<body class="myorg" onload="findWho()"> 


<div id="page">
<?php include('M015.php'); ?>

<!-- début de barre gauche -->
<div id="sidebar">
<?php include('M009.php'); ?>
<!-- fin de barre gauche -->
</div>

<!-- début de contenu à droite -->
<div id="mainContent">

<p>&nbsp;</p>
<table width="782" border="1" align="center" cellpadding="2" cellspacing="0" class="ui-corner-all" bgcolor="#D3D3D3" style="font-size:12px">
<form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
<tr>
<td colspan="4" align="center">Acte rattach&eacute; au titre &nbsp; : &nbsp;
<?php // recherche du libellé du type de l'acte
$P01= $db->query("SELECT * FROM titles WHERE id = '$tit'");
$P01->setFetchMode(PDO::FETCH_OBJ);
$R01 = $P01->fetch() ;  
$TLI = $R01->lib ;?>
<input name="desc" type="text" id="titre" value="<?php echo $TLI;?>" maxlength="35" size="40" />
<input name="idtit" type="hidden" id="idtit" value="1" /></td>
<td colspan="2" style="font-size:10px">
<ul id="MenuBar2" class="MenuBarHorizontal" style="left:40px" > 
  <!-- début du premier niveau -->
      <li><a class="ui-state-active" href="#">Changer le Titre</a>
        
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
</script>
</td>
<tr>
<td colspan="6" align="center">Libell&eacute; :   &nbsp;
<input name="actLibel" type="text" id="actLibel"  value="<?php echo $row_rs_act['actLibel']; ?>" size="120" /></td>
</tr>		
<tr>
<td colspan="2" width="260" align="center">Date :   &nbsp;
<input name="dateCre" type="text" id="dateCre" value="<?php echo $row_rs_act['dateCre']; ?>" size="10" />
<input type=button value="CHOISIR" onclick="displayDatePicker('dateCre', this);"></td>
<td colspan="2" width="260" align="center">D&eacute;but :&nbsp;
<input type="hidden" name="last" id="last" value="<?php echo $row_rs_last['heureFin']; ?>" />
<input name="heureDeb" type="text" id="heureDeb" value="<?php echo $row_rs_act['heureDeb']; ?>" size="10" />
<input type=button value="DERNIER" onclick="Lastend()" /></td>
<td colspan="2" width="260" align="center">Fin :&nbsp;
<input name="Heurefin" type="text" id="Heurefin" value="<?php echo $row_rs_act['heureFin']; ?>" size="10" />
<input type=button value="MAINTENANT" onclick="Now()" /></td>
</tr>
<tr>
<td colspan="6"><textarea name="actDesc" id="actDesc"><?php echo $row_rs_act['actDesc']; ?></textarea>
<script type="text/javascript">
CKEDITOR.replace( 'actDesc', 
    {
        toolbar : 'Arti'
    });
</script></td>
</tr>
<tr>
<td colspan="2" align="center">Type &nbsp;
<label for="ID_typa"></label>
<select name="ID_typa" id="ID_typa">
<?php do { ?>
<option value="<?php echo $row_rs_typa['ID_typa']?>"<?php if (!(strcmp($row_rs_typa['ID_typa'], $row_rs_act['ID_typa']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_typa['typaLibel']?></option>
<?php
} while ($row_rs_typa = mysql_fetch_assoc($rs_typa));
$rows = mysql_num_rows($rs_typa);
if($rows > 0) {
  mysql_data_seek($rs_typa, 0);
  $row_rs_typa = mysql_fetch_assoc($rs_typa);
} ?>
</select></td>
<td colspan="2" align="center">Priorit&eacute;   &nbsp;<select name="ID_urgence" id="ID_urgence">
<?php do {  ?>
<option value="<?php echo $row_rs_urgence['ID_urgence']?>"<?php if (!(strcmp($row_rs_urgence['ID_urgence'], $row_rs_act['ID_urgence']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_urgence['urgenceLibel']?></option>
            <?php
} while ($row_rs_urgence = mysql_fetch_assoc($rs_urgence));
  $rows = mysql_num_rows($rs_urgence);
  if($rows > 0) {
      mysql_data_seek($rs_urgence, 0);
	  $row_rs_urgence = mysql_fetch_assoc($rs_urgence);
  }
?>
        </select></td>
        <td colspan="2" align="center"><a href="#" onclick="Addfile()">Fichier</a>
		<input name="fichier" type="text" id="fichier" value="<?php echo $row_rs_act['fichier']; ?>" />
		<?php if ($row_rs_act['fichier'] < "!") {
		$nfic = $row_rs_act['ID_act'].".pdf"; 	}
		else { $nfic = ""; } ?> 
		<input type="hidden" name="nomfic" id="nomfic" value="<?php echo $nfic; ?>" />
</td>
</tr>
<tr>
		<td colspan="2" align="center">Entit&eacute;  &nbsp;<select name="entite" id="entite">
		<?php do {  ?>
		<option value="<?php echo $row_rqent['ID_entite']?>"<?php if (!(strcmp($row_rqent['ID_entite'], $row_rs_act['entite']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rqent['entLibel']?></option>
		<?php } while ($row_rqent = mysql_fetch_assoc($rqent));
		  $rows = mysql_num_rows($rqent);
		  if($rows > 0) {
			mysql_data_seek($rqent, 0);
			$row_rqent = mysql_fetch_assoc($rqent);
		}
?>
        </select></td>
		<td colspan=4 align="center">Page &agrave; afficher apr&egrave;s validation
        <label><select name="suite" id="suite" onchange="this.form.submit()"  class="ui-state-default" style="font-stretch:extra-expanded; font-variant:small-caps">
		<option value="0" selected="selected">CHOISIR LA PAGE A AFFICHER EN VALIDANT</option>
        <option value="1">aujourd'hui</option>
        <option value="3">afficher</option>
        <option value="7">agenda</option>
		<option value="2">hier</option>
		<option value="6">lettre</option>
		<option value="4">mailer</option>
		<option value="5">page actuelle</option>
        <option value="8">relancer</option>
        <option value="9">liste todo</option>
		</select></label>
		<input name="ID_act" type="hidden" id="ID_act" value="<?php echo $row_rs_act['ID_act']; ?>" />
		<input type="hidden" name="MM_update" value="form1" /></td>
	  	</form>
	  </tr>
<td colspan="4" align="center"><span id="storage"> Contacts : </span></td>
<td colspan="2" align="center">
<FORM name="ajax" method="POST" action="" >
<select name="who" id="who" onchange="submitForm()">
    <?php do { ?>
    <option value="<?php echo $row_rs_contact['ID_contact']?>"<?php if (!(strcmp($row_rs_contact['ID_contact'], "95")))
    {echo "selected=\"selected\"";} ?>><?php echo $row_rs_contact['Nom_complet']?></option>
    <?php } while ($row_rs_contact = mysql_fetch_assoc($rs_contact));
    $rows = mysql_num_rows($rs_contact);
    if($rows > 0) {
    mysql_data_seek($rs_contact, 0);
    $row_rs_contact = mysql_fetch_assoc($rs_contact); } ?>
    </select>
</FORM></td>
</tr>
</table>

<!-- fin de #mainContent -->
</div>
<br class="clearfloat" />

<?php	//insertion du pied de page
include('M012.php');
 
		// fin de la balise CSS 'page' ?>        
</div>

</body>
</html>
<?php
mysql_free_result($rs_act);

mysql_free_result($rs_contact);

mysql_free_result($rs_typa);

mysql_free_result($rs_urgence);

mysql_free_result($rqent);
?>
