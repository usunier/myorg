<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>organisation de la documentation</title>
<?php include('entete.php'); ?>
</head>
<body class="myorg">
<div class="docbase">
  <p>
		Ouvrir le code dans <span class="ui-state-active"> Notepad++</span><br />Faire 
<span class="commandes">Compl&eacute;ments &gt; NppExport &gt; Export to RTF</span><br />Enregistrer sous
<span class="commandes">NomFichier.rtf</span><br />
		Ouvrir export.rtf avec <span class="ui-state-active">Open Office</span><br />
Copier la partie &agrave; int&eacute;grer au html <span class="commandes">Ctrl C</span><br />
<span class="ui-state-active">Dreamweaver</span>et faire <span class="commandes">clic droit &gt; collage sp&eacute;cial</span><br />Choisir
<span class="commandes">Texte structur&eacute; avec formatage complet</span><br />
		Le r&eacute;sultat se pr&eacute;sente ainsi :</p>
  <div class="doccode">      
  <p><font color="#ff0000"><font face="monospace"><font size="2">&lt;?php</font></font></font><font color="#000000"><font face="monospace"><font size="2"> </font></font></font>
<p style="margin-bottom: 0cm"><font color="#ff8000"><font face="monospace"><font size="2">/**</font></font></font></p>
  <p style="margin-bottom: 0cm"><font color="#ff8000"> <font face="monospace"><font size="2">*  M014 - ancien programme 'ugeteda' </font></font></font></p>
  <p style="margin-bottom: 0cm"><font color="#ff8000"> <font face="monospace"><font size="2">*</font></font></font><font color="#ff8000"><font face="monospace"><font size="2"> affichage des actes pour une date donnée</font></font></font></p>
<p style="margin-bottom: 0cm"><font color="#ff8000"> <font face="monospace"><font size="2">*  liste des modifications</font></font></font></p>
  <p style="margin-bottom: 0cm"><font color="#ff8000"> <font face="monospace"><font size="2">*		08/12/11  création du programme</font></font></font></p>
  <p style="margin-bottom: 0cm"><font color="#ff8000"> <font face="monospace"><font size="2">*  		11/12/11 remplacement de menu par M015</font></font></font></p>
  <p style="margin-bottom: 0cm"><font color="#ff8000"> <font face="monospace"><font size="2">*  		21/12/11 intégration du pied de page</font></font></font></p>
  <p style="margin-bottom: 0cm"><font color="#ff8000"> <font face="monospace"><font size="2">*		21/12/11  suppression de la date dans la table des actes de la journée</font></font></font></p>
  <p style="margin-bottom: 0cm"><font color="#ff8000"> <font face="monospace"><font size="2">*		21/12/11  suppression des boutons voir/edit/supprimer</font></font></font></p>
  <p style="margin-bottom: 0cm"><font color="#ff8000"><font face="monospace"><font size="2">*/</font></font></font><font color="#000000"><font face="monospace"><font size="2"> </font></font></font></p>
  <p style="margin-bottom: 0cm">&nbsp;</p>
  <p style="margin-bottom: 0cm"><font color="#ff8000"><font face="monospace"><font size="2">//  nom du programme</font></font></font></p>
  <p style="margin-bottom: 0cm"><font color="#000000"><font face="monospace"><font size="2"> </font></font></font><font color="#0080c0"><font face="monospace"><font size="2">$prog</font></font></font><font color="#000000"><font face="monospace"><font size="2"> </font></font></font><font color="#8000ff"><font face="monospace"><font size="2">=</font></font></font><font color="#000000"><font face="monospace"><font size="2"> </font></font></font><font color="#ff0000"><font face="monospace"><font size="2">&quot;M014.php&quot;</font></font></font><font color="#000000"><font face="monospace"><font size="2"> </font></font></font><font color="#8000ff"><font face="monospace"><font size="2">;</font></font></font></p>
  <p style="margin-bottom: 0cm"><font color="#ff8000"><font face="monospace"><font size="2">//  date de la dernière modification</font></font></font></p>
  <p style="margin-bottom: 0cm"><font color="#000000"><font face="monospace"><font size="2"> </font></font></font><font color="#0080c0"><font face="monospace"><font size="2">$dmod</font></font></font><font color="#000000"><font face="monospace"><font size="2"> </font></font></font><font color="#8000ff"><font face="monospace"><font size="2">=</font></font></font><font color="#000000"><font face="monospace"><font size="2"> </font></font></font><font color="#ff0000"><font face="monospace"><font size="2">&quot;21/12/11&quot;</font></font></font><font color="#8000ff"><font face="monospace"><font size="2">;</font></font></font><font color="#000000"><font face="monospace"><font size="2"> </font></font></font></p>
  <p style="margin-bottom: 0cm"><font color="#000000"><font face="monospace"><font size="2"> </font></font></font></p>
  <p style="margin-bottom: 0cm"><font color="#ff8000"><font face="monospace"><font size="2">//  connexion à la base de données</font></font></font></p>
  <p style="margin-bottom: 0cm"><font color="#008000"><font face="monospace"><font size="2"><strong>require_once</strong></font></font></font><font color="#8000ff"><font face="monospace"><font size="2">(</font></font></font><font color="#ff0000"><font face="monospace"><font size="2">'connections/myorg_syno.php'</font></font></font><font color="#8000ff"><font face="monospace"><font size="2">);</font></font></font><font color="#000000"><font face="monospace"><font size="2"> </font></font></font><font color="#000000"><font face="monospace"><font size="2"> </font></font></font><font color="#ff0000"><font face="monospace"><font size="2">?&gt;</font></font></font></p>
  <p>
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
    <HTML>
    <!--  		@page { margin: 2cm }  		P { margin-bottom: 0.21cm }  		A:link { so-language: zxx }  	-->
    <BODY DIR="LTR">
  </p>
  <p style="margin-bottom: 0cm"><font color="#0000ff"><font face="monospace"><font size="2">&lt;body</font></font></font><font color="#000000"><font face="monospace"><font size="2"> </font></font></font><font color="#008000"><font face="monospace"><font size="2">class</font></font></font><font color="#000000"><font face="monospace"><font size="2">=</font></font></font><font color="#8000ff"><font face="monospace"><font size="2"><strong>&quot;myorg&quot;</strong></font></font></font><font color="#0000ff"><font face="monospace"><font size="2">&gt;</font></font></font><font color="#000080"><font face="monospace"><font size="2"> </font></font></font></p>
  <p style="margin-bottom: 0cm"><font color="#0000ff"><font face="monospace"><font size="2">&lt;div</font></font></font><font color="#000000"><font face="monospace"><font size="2"> </font></font></font><font color="#008000"><font face="monospace"><font size="2">id</font></font></font><font color="#000000"><font face="monospace"><font size="2">=</font></font></font><font color="#8000ff"><font face="monospace"><font size="2"><strong>&quot;page&quot;</strong></font></font></font><font color="#0000ff"><font face="monospace"><font size="2">&gt;</font></font></font></p>
  <p style="margin-bottom: 0cm"><font color="#ff0000"><font face="monospace"><font size="2">&lt;?php</font></font></font><font color="#000000"><font face="monospace"><font size="2"> </font></font></font><font color="#008000"><font face="monospace"><font size="2"><strong>include</strong></font></font></font><font color="#8000ff"><font face="monospace"><font size="2">(</font></font></font><font color="#ff0000"><font face="monospace"><font size="2">'M015.php'</font></font></font><font color="#8000ff"><font face="monospace"><font size="2">);</font></font></font><font color="#000000"><font face="monospace"><font size="2"> </font></font></font><font color="#ff0000"><font face="monospace"><font size="2">?&gt;</font></font></font></p>
  <p style="margin-bottom: 0cm"><br />
  </p>
  <p style="margin-bottom: 0cm"><font color="#c0c0c0"><font face="monospace"><font size="2">&lt;!--  début de barre gauche --&gt;</font></font></font></p>
  <p style="margin-bottom: 0cm"><font color="#0000ff"><font face="monospace"><font size="2">&lt;div</font></font></font><font color="#000000"><font face="monospace"><font size="2"> </font></font></font><font color="#008000"><font face="monospace"><font size="2">id</font></font></font><font color="#000000"><font face="monospace"><font size="2">=</font></font></font><font color="#8000ff"><font face="monospace"><font size="2"><strong>&quot;sidebar&quot;</strong></font></font></font><font color="#0000ff"><font face="monospace"><font size="2">&gt;</font></font></font></p>
  <p style="margin-bottom: 0cm"><font color="#ff0000"><font face="monospace"><font size="2">&lt;?php</font></font></font><font color="#000000"><font face="monospace"><font size="2"> </font></font></font><font color="#008000"><font face="monospace"><font size="2"><strong>include</strong></font></font></font><font color="#8000ff"><font face="monospace"><font size="2">(</font></font></font><font color="#ff0000"><font face="monospace"><font size="2">'M009.php'</font></font></font><font color="#8000ff"><font face="monospace"><font size="2">);</font></font></font><font color="#000000"><font face="monospace"><font size="2"> </font></font></font><font color="#ff0000"><font face="monospace"><font size="2">?&gt;</font></font></font></p>
  <p style="margin-bottom: 0cm"><font color="#c0c0c0"><font face="monospace"><font size="2">&lt;!--  fin de barre gauche --&gt;</font></font></font></p>
  <p style="margin-bottom: 0cm"><font color="#0000ff"><font face="monospace"><font size="2">&lt;/div&gt;</font></font></font></p>
</div>
  <p>La documentation est intégrée dans des titres de niveau 5 avec le code suivant : <br/>    
<div class="doccode">
  <span style="margin-bottom: 0cm"><font color="#0000ff"><font face="monospace"><font size="2">&lt;p&gt;&lt;object data=&quot;D001.php&quot; height=&quot;600&quot; type=&quot;text/html&quot; width=&quot;760&quot;&gt;&lt;/object&gt;&lt;/p&gt;</font></font></font></span>&nbsp;</p>
</div>

</body>
</html>
