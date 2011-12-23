<?php 
/**
 * M018 programme créé le 23/12/11 dans la version 2012 de MyOrg
 * il remplace 'ugearaf' pour afficher un titre et tout ce qui lui est rattaché
 * liste des modifications
 *		23/12/11 création du programme en version provisoire
*/

// nom du programme
$prog = "M018.php" ;

require_once('connections/myorg_syno.php'); 
include('GetSQLValueString.php');

// récupération du n° du titre
$ida = -1; if (isset($_GET['id'])) { $ida = $_GET['id']; } 

// récupération du n° de l'onglet à ouvrir
$ong = 0; 
if ($_COOKIE['onglet']!="") { $ong = $_COOKIE['onglet'] ; setcookie('onglet'); } 
if (isset($_GET['ong'])) { $ong = $_GET['ong']; }

//recherche du titre dans 'titles' pour afficher le sommaire
mysql_select_db($database_myorg_syno, $myorg_syno);
$query_R008a = sprintf("SELECT * FROM titles WHERE id = %s", GetSQLValueString($ida, "int"));
$R008a = mysql_query($query_R008a, $myorg_syno);
$row_R008a = mysql_fetch_assoc($R008a);
$idt = $row_R008a['id']; 
$niv = $row_R008a['niv']; 
$par = $idt;
$para = $row_R008a['par'];
$niv1 = $niv + 1;
$TitPag = $row_R008a['lib'] ;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=latin1" />
<title><?php echo $row_R008a['lib']; ?></title>

<!-- ouverture du js et du css du tableau à onglets--> 
	<script src="SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
	<link href="SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
   
<!-- ouverture d'un popup-->    
    <script language="JavaScript">
    function OuvrirPop(url,nom,haut,Gauche,largeur,hauteur,options) { 
    ouvpop=window.open(url,nom,"top="+haut+",left="+Gauche+",width="+largeur+",height="+hauteur+","+options);
    } 
    </script>  
   
<!-- insertion de l'entete commune de myorg -->     
<?php include('M016.php'); ?>
</head>

<body class="myorg" onload="TabbedPanels2.showPanel(<?php echo $ong; ?>)">  

<div id="page">
<?php include('M015.php'); ?>

<!-- début de barre gauche --> 
<div id="sidebar">
<?php include('M009.php'); ?>
<!-- fin de barre gauche --> 
</div>

<!-- début de contenu à droite -->
<div id="mainContent">
  <table width="790" class="ui-corner-all ui-state-active" align="center" style="margin-bottom:10px; margin-top:10px">
  <tr>
      <td width="195"><?php echo "créé le : ".$row_R008a['ini']; ?></td>
      <td width="195">
      <a href="ugeared.php?id=<?php echo $row_R008a['id']; ?>" >modifier l'article</a></td>
      <td width="195"><a href="ugearaf.php?id=<?php echo $para; ?>&ong=1">voir le parent</a></td>
      <td width="195"><a href="index.php" >aller à l'accueil</a></td>
  </tr>
  </table>
  
  <div id="TabbedPanels2" class="TabbedPanels">
    <ul class="TabbedPanelsTabGroup">
      	<li class="TabbedPanelsTab" tabindex="0">TEXTE</li>
      	<li class="TabbedPanelsTab" tabindex="1">SOMMAIRE</li>
        <li class="TabbedPanelsTab" tabindex="2">ACTES EN COURS</li>
        <li class="TabbedPanelsTab" tabindex="3">TOUS LES ACTES</li>
        <li class="TabbedPanelsTab" tabindex="4">NOTES</li>
        <li class="TabbedPanelsTab" tabindex="5">LIENS</li>
        <li class="TabbedPanelsTab" tabindex="6">TEMPS</li>
        <li class="TabbedPanelsTab" tabindex="7">FRAIS</li>
    </ul>
    	<div class="TabbedPanelsContentGroup">
        <div class="TabbedPanelsContent"><!-- onglet 0 --><div align="left"><?php echo $row_R008a['des']; ?></div></div>
      	<div class="TabbedPanelsContent"><!-- onglet 1 --><?php include('ugearti.php'); ?></div>
        <div class="TabbedPanelsContent"><!-- onglet 2 --><?php include('ugetelinc.php'); ?></div>   
        <div class="TabbedPanelsContent"><!-- onglet 3 --><?php include('ugetelitt.php'); ?></div>
        <div class="TabbedPanelsContent"><!-- onglet 4 --><?php include('ugearyn.php'); ?></div>
        <div class="TabbedPanelsContent"><!-- onglet 5 --><?php include('ugearli.php'); ?></div>
        <div class="TabbedPanelsContent"><!-- onglet 6 -->contenu3</div>
        <div class="TabbedPanelsContent"><!-- onglet 7 -->contenu3</div>     
    </div>
  </div>

<!-- fin de contenu à droite -->
</div>
<br class="clearfloat" />
<!-- fin de style page -->
</div>

<script type="text/javascript">
var TabbedPanels2 = new Spry.Widget.TabbedPanels("TabbedPanels2");
</script>
</body>
</html>