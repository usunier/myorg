<?php 
/**
 * M015 - ancien programme 'menu' 
 * affichage de la barre de menu horizontale
 * liste des modifications
 *		09/12/11 création du programme
 * 		10/12/11 généralisation PDO	
 *		04/01/12 suppression référence aux programmes 'uge'
 *		04/01/12 accès direct à myorg depuis le menu principal
 *		04/01/12 accès à la comptabilité depuis le menu principal
 * problèmes non résolus / améliorations à apporter
 *		uniformisation de la navigation
*/
require_once('connections/myorg_syno.php'); 
?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=latin1" />
<title>menu PDO</title>
<?php include('entete.php'); ?>
</head>

<body class="myorg"> 
<div id="page">

<!-- barre d'en-tête avec le titre de la page -->
<img name="XXIsEntete2009"  src="XXIsEntete2009.png" width="1000" height="72" border="0" style="z-index:0" id="XXIsEntete2009" usemap="#m_XXIsEntete2009" alt="" />
<map name="m_XXIsEntete2009" id="m_XXIsEntete2009"><area shape="rect" coords="0,0,160,67" href="index.php" alt="accueil" /></map>
<div class="ex0"><?php echo $TitPag; ?></div>

<ul id="MenuBar1" class="MenuBarHorizontal"> <?php // début de niveau 1 
	$Q01 = $db->query("SELECT * FROM nav1 ORDER BY ID_nav1 ASC");
	$Q01->setFetchMode(PDO::FETCH_OBJ);
	while($R01 = $Q01->fetch()){
	$par2 = $R01->ID_nav1 ; ?>
<li><a class="MenuBarItemSubmenu" href="<?php  echo $R01->nav1File ; ?>"><?php echo $R01->nav1Libel ; ?></a>

<?php // test niveau 2
	if (($par2 < 2) || ($par2 > 3)) {
	// début de niveau 2 ?>
    <ul>  <?php
	$Q02 = $db->query("SELECT * FROM nav2 WHERE nav2.ID_nav1 = $par2 ORDER BY nav2.nav2Libel ASC");
	$Q02->setFetchMode(PDO::FETCH_OBJ);
	while($R02 = $Q02->fetch()){
	$par3 = $R02->ID_nav2 ; ?>
<li><a class="MenuBarItemSubmenu" href="<?php  echo $R02->nav2File ; ?>"><?php echo $R02->nav2Libel ; ?></a>

<?php // début de niveau 3
	$C03 = $db->query("SELECT COUNT(*) AS nbe FROM nav3 WHERE nav3.ID_nav2 = $par3 ORDER BY nav3.nav3Libel ASC");
	$T03 = $C03->fetch(PDO::FETCH_OBJ);
	$N03 = $T03->nbe ; 
	if ($N03 > 0) {	?>
	<ul><?php
	$Q03 = $db->query("SELECT * FROM nav3 WHERE nav3.ID_nav2 = $par3 ORDER BY nav3.nav3Libel ASC"); 
	$Q03->setFetchMode(PDO::FETCH_OBJ);
	while($R03 = $Q03->fetch()) {
	$par4 = $R03->ID_nav3 ;  ?>
<li><a class="MenuBarItemSubmenu" href="<?php  echo $R03->nav3File ; ?>"><?php echo $R03->nav3Libel ; ?></a>
    
<?php // début de niveau 4
	$C04 = $db->query("SELECT COUNT(*) AS nbe FROM nav4 WHERE nav4.ID_nav3 = $par4 ORDER BY nav4.nav4Libel ASC");
	$T04 = $C04->fetch(PDO::FETCH_OBJ);
	$N04 = $T04->nbe ; 
	if ($N04 > 0) {	?>
	<ul><?php
	$Q04 = $db->query("SELECT * FROM nav4 WHERE nav4.ID_nav3 = $par4 ORDER BY nav4.nav4Libel ASC");
	$Q04->setFetchMode(PDO::FETCH_OBJ);
	while($R04 = $Q04->fetch()) {
	$par5 = $R04->ID_nav4 ; ?>
<li><a class="MenuBarItemSubmenu" href="<?php  echo $R04->nav4File ; ?>"><?php echo $R04->nav4Libel ; ?></a>

<?php // début de niveau 5
	$C05 = $db->query("SELECT COUNT(*) AS nbe FROM nav5 WHERE nav5.ID_nav4 = $par5 ORDER BY nav5.nav5Libel ASC");
	$T05 = $C05->fetch(PDO::FETCH_OBJ);
	$N05 = $T05->nbe ; 
	if ($N05 > 0) {	?>
	<ul><?php
	$Q05 = $db->query("SELECT * FROM nav5 WHERE nav5.ID_nav4 = $par5 ORDER BY nav5.nav5Libel ASC");
	$Q05->setFetchMode(PDO::FETCH_OBJ);
	while($R05 = $Q05->fetch()) {
	$par6 = $R04->ID_nav5 ; ?>
<li><a class="MenuBarItemSubmenu" href="<?php  echo $R05->nav5File ; ?>"><?php echo $R05->nav5Libel ; ?></a>

</li> <?php // fin d'un item de niveau 5 ?> 
<?php } // fin du test ?>
</ul> <?php } // fin de niveau 5 ?>

</li> <?php // fin d'un item de niveau 4 ?> 
<?php } // fin du test ?>
</ul> <?php } // fin de niveau 4 ?>

</li> <?php // fin d'un item de niveau 3 ?>
<?php } // fin du test ?>
</ul> <?php } // fin de niveau 3 ?>

</li> <?php // fin d'un item de niveau 2 ?>  
<?php } ?>
</ul> <?php // fin de niveau 2 ?>

<?php } // fin de la navigation if hors 'STRUCTURE' (2)

else {  
// navigation dans la 'STRUCTURE' (2 et 3)
if ($par2 == 2) {  // si 2 le système recherche toutes les occurences de la structure
	$S01 = "niv = 1" ; }
else {	// si pas 2 (en l'occurence 3) le système recherche les descendants de myorg (72)
	$S01 = "par = 72" ; }
// début du niveau 2 pour la structure ?>
	<ul> <?php
	$Q22 = $db->query("SELECT * FROM titles WHERE ".$S01." ORDER BY lib ASC"); 
	$Q22->setFetchMode(PDO::FETCH_OBJ); 
	while($R22 = $Q22->fetch()){
	$par2 = $R22->id ; ?>
<li><a class="MenuBarItemSubmenu" href="<?php  echo "M018.php?id=".$par2; ?>"><?php echo $R22->lib."-".$par2; ?></a>

<?php // début du niveau 3 pour la structure 
	$C23 = $db->query("SELECT COUNT(*) AS nbe FROM titles WHERE par = $par2");
	$T23 = $C23->fetch(PDO::FETCH_OBJ);
	$N23 = $T23->nbe ; 
	if ($N23 > 0) {	?>
	<ul><?php
	$Q23 = $db->query("SELECT * FROM titles WHERE par = $par2 ORDER BY lib ASC");
	$Q23->setFetchMode(PDO::FETCH_OBJ);
	while($R23 = $Q23->fetch()) {
	$par3 = $R23->id ;  ?>
<li><a class="MenuBarItemSubmenu" href="<?php  echo "M018.php?id=".$par3; ?>"><?php echo $R23->lib."-".$par3; ?></a>

<?php // début du niveau 4 pour la structure 
	$C24 = $db->query("SELECT COUNT(*) AS nbe FROM titles WHERE par = $par3");
	$T24 = $C24->fetch(PDO::FETCH_OBJ);
	$N24 = $T24->nbe ; 
	if ($N24 > 0) {	?>
	<ul><?php
	$Q24 = $db->query("SELECT * FROM titles WHERE par = $par3 ORDER BY lib ASC");
	$Q24->setFetchMode(PDO::FETCH_OBJ);
	while($R24 = $Q24->fetch()) {
	$par4 = $R24->id ;  ?>
<li><a class="MenuBarItemSubmenu" href="<?php  echo "M018.php?id=".$par4; ?>"><?php echo $R24->lib."-".$par4; ?></a>

<?php // début du niveau 5 pour la structure 
	$C25 = $db->query("SELECT COUNT(*) AS nbe FROM titles WHERE par = $par4");
	$T25 = $C25->fetch(PDO::FETCH_OBJ);
	$N25 = $T25->nbe ; 
	if ($N25 > 0) {	?>
	<ul><?php
	$Q25 = $db->query("SELECT * FROM titles WHERE par = $par4 ORDER BY lib ASC");
	$Q25->setFetchMode(PDO::FETCH_OBJ);
	while($R25 = $Q25->fetch()) {
	$par5 = $R25->id ;  ?>
<li><a class="MenuBarItemSubmenu" href="<?php  echo "M018.php?id=".$par5; ?>"><?php echo $R25->lib."-".$par5; ?></a>

<?php // début du niveau 6 pour la structure 
	$C26 = $db->query("SELECT COUNT(*) AS nbe FROM titles WHERE par = $par5");
	$T26 = $C26->fetch(PDO::FETCH_OBJ);
	$N26 = $T26->nbe ; 
	if ($N26 > 0) {	?>
	<ul><?php
	$Q26 = $db->query("SELECT * FROM titles WHERE par = $par5 ORDER BY lib ASC");
	$Q26->setFetchMode(PDO::FETCH_OBJ);
	while($R26 = $Q26->fetch()) {
	$par6 = $R26->id ;  ?>
<li><a class="MenuBarItemSubmenu" href="<?php  echo "M018.php?id=".$par6; ?>"><?php echo $R26->lib."-".$par6; ?></a>

<?php // début du niveau 7 pour la structure 
	$C27 = $db->query("SELECT COUNT(*) AS nbe FROM titles WHERE par = $par6");
	$T27 = $C27->fetch(PDO::FETCH_OBJ);
	$N27 = $T27->nbe ; 
	if ($N27 > 0) {	?>
	<ul><?php
	$Q27 = $db->query("SELECT * FROM titles WHERE par = $par6 ORDER BY lib ASC");
	$Q27->setFetchMode(PDO::FETCH_OBJ);
	while($R27 = $Q27->fetch()) {
	$par7 = $R27->id ;  ?>
<li><a class="MenuBarItemSubmenu" href="<?php  echo "M018.php?id=".$par7; ?>"><?php echo $R27->lib."-".$par7; ?></a>

</li> <?php // fin d'un item de niveau 7 ?>
<?php } // fin du test ?>
</ul> <?php } // fin de niveau 7 ?>

</li> <?php // fin d'un item de niveau 6 ?>
<?php } // fin du test ?>
</ul> <?php } // fin de niveau 6 ?>

</li> <?php // fin d'un item de niveau 5 ?>
<?php } // fin du test ?>
</ul> <?php } // fin de niveau 5 ?>


</li> <?php // fin d'un item de niveau 4 ?>
<?php } // fin du test ?>
</ul> <?php } // fin de niveau 4 ?>

</li> <?php // fin d'un item de niveau 3 ?>
<?php } // fin du test ?>
</ul> <?php } // fin de niveau 3 ?>

</li>
<?php } // fin du niveau 2 pour la structure ?>
</ul>

<?php } // fin du else   ?>
   
</li>   
<?php // fin d'un item de niveau 1 ?>  
<?php }  ?>
</ul> <?php // fin de niveau 1 ?>

<script type="text/javascript">
var MenuBar1 = new Spry.Widget.MenuBar("MenuBar1", {imgDown:"SpryAssets/SpryMenuBarDownHover.gif", imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
</script>

</div>
</body>
