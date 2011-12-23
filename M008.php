<?php 
// M008 programme créé le 04/12/11 dans la version 2012 de MyOrg
	$prog = "M008.php" ;
// il s'agit d'une liste des ACTES DONT LE TITRE de rattachement N'EXISTE PAS
// liste des modifications
	// 
// date de la dernière modification
	$dmod = "04/12/11";	

require_once('dbconnect.php');
require_once('connections/myorg_syno.php'); // pour menu.php tant qu'il n'est pas passé en PDO
include('GetSQLValueString.php'); // pour menu.php tant qu'il n'est pas passé en PDO

$TitPag = "Liste des ACTES sans TITRE de rattachement";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $TitPag ; ?></title>
<script language="JavaScript"> function OuvrirPop(url,nom,haut,Gauche,largeur,hauteur,options) { ouvpop=window.open(url,nom,"top="+haut+",left="+Gauche+",width="+largeur+",height="+hauteur+","+options); } 
</script>
<?php include('entete.php'); ?>
</head>

<body class="myorg"> 
<div id="page">
<?php include('menu.php'); ?>

<table width="980" border="1" align="center" cellpadding="1" cellspacing="0" class="ui-corner-all" style="position:relative; top:10px">
  <tr class="ui-state-default">
    <tr>
    <td class="ui-state-active" colspan="2" >Libell&eacute; de l'acte</td>    
    <td class="ui-state-active" align="center">Priorit&eacute;</td>
    <td class="ui-state-active" align="center">Date</td>      
    <td class="ui-state-active" align="center">Titre</td>
    <td class="ui-state-active" align="center">Edit</td>
    <td class="ui-state-active" align="center">Supp</td>
    <td class="ui-state-active" align="center">Fichier</td>
  </tr>
  <?php 
  		// on balaie l'ensemble des actes
  $P01 = "SELECT * FROM acts" ;
  foreach ($db->query($P01) as $R01) { 
  		// avec leur titre de rattachement ID_job
  $JOB = $R01['ID_job'];
  		// on va chercher si celui existe dans la table 'titles'
  $P02= $db->query("SELECT * FROM titles WHERE id = '$JOB'");
  $P02->setFetchMode(PDO::FETCH_OBJ);
  $R02 = $P02->fetch() ;
  		// on teste si le titre existe
  if ($R02) { 
  		// si le titre existe on ne fait rien
  } else {
	  	// si le titre n'existe pas on édite la ligne 	  
  ?>
      <tr>
        <td colspan="2"  class="ui-state-default"><a href="#" class="info"><?php echo $R01['actLibel']; ?>
        <span><?php echo $R01['actDesc']; ?></span></a></td>
        <?php 
			$PRI = $R01['ID_urgence']; $IDA = $R01['ID_act']; $JOB = $R01['ID_job'];
			$P03= $db->query("SELECT * FROM urgence WHERE ID_urgence = '$PRI'");
			$P03->setFetchMode(PDO::FETCH_OBJ);
			$R03 = $P03->fetch() ;
			$URG = $R03->urgenceLibel;
			if ( $R01['ID_urgence'] == 1 ) { ?>	  
            <td align="center" class="ui-state-error" style="font-size:80%"><?php echo $URG; ?></td>
            <?php  } elseif ( $PRI == 2 ) { ?>
            <td align="center" class="ui-state-important" style="font-size:80%"><?php echo $URG; ?></td>
            <?php  } elseif ( $PRI == 3 ) { ?>
            <td align="center" class="ui-state-active" style="font-size:80%"><?php echo $URG; ?></td>
            <?php  } elseif ( $PRI == 6 ) { ?>
            <td align="center" class="ui-state-default style="font-size:80%""><?php echo $URG; ?></td>
			<?php  } else { ?>
            <td class="ui-state-default" align="center" style="font-size:80%"><?php echo $URG; } ?></td>
            <td class="ui-state-default" align="center"><?php if ($R01['dateCre'] < $dat) { ?>
            <span class="ui-state-error"><?php echo $R01['dateCre']; ?></span><?php }
			else{ echo $R01['dateCre'];
				} ?>
            <td class="ui-state-default" align="center"><?php echo $JOB;?></td>
        	<td class="ui-state-default" align="center"><span class="hasTip" title="modifier l'acte <?php echo $IDA; ?>"> 
            <a href="ugeacmo.php?id=<?php echo $IDA; ?>&amp;idt=<?php echo $IDT; ?>">
            <img src="images/edit.png" alt="modifier" border="0"/></a></span></td>
        	<td class="ui-state-default" align="center"><span class="hasTip" title="supprimer cet acte">
            <a href="ugetesu.php?ID_act=<?php echo $IDA; ?>" > <img src="images/del16.png" alt="modifier" border="0"/></a></span></td>
        	<td class="ui-state-default" align="center">
            <form id="boutonG2" name="boutonG" method="post" action="documents/<?php echo $row_req01['fichier']; ?>">
            <input type="submit" name="ALLER2" id="ALLER2" value="<?php echo $row_req01['fichier']; ?>" />
        	</form></td>
      	</tr>
      <?php } } ?>

</table>
<?php	//insertion du pied de page
include('M012.php'); 
		// fin de la balise CSS 'page' ?>
</div>

</body>
</html>

