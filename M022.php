<?php 
/**
 * M022 - ancien programme 'ugelecr' 
 * création d'une lettre en pdf
 * liste des modifications
 *		23/12/11 création du programme en version provisoire (sans PDO)
*/
require_once('connections/myorg_syno.php'); 

// récupération de l'id de l'acte (la lettre dans le cas présent)
	$act = "-1";
	if (isset($_GET['id'])) { $act = $_GET['id']; }

// récupération des éléments de la table acts [rqAct]
	mysql_select_db($database_myorg_syno, $myorg_syno);
	$query_rqAct = sprintf("SELECT * FROM acts WHERE ID_act = '$act'");
	$rqAct = mysql_query($query_rqAct, $myorg_syno) or die(mysql_error());
	$row_rqAct = mysql_fetch_assoc($rqAct);
	$totalRows_rqAct = mysql_num_rows($rqAct);
	
// récupération des éléments du fichier des entités [rqEnt]
	$ent = $row_rqAct['entite']; 
	mysql_select_db($database_myorg_syno, $myorg_syno);
	$query_rqEnt = sprintf("SELECT * FROM entites WHERE ID_entite = '$ent'");
	$rqEnt = mysql_query($query_rqEnt, $myorg_syno) or die(mysql_error());
	$row_rqEnt = mysql_fetch_assoc($rqEnt);
	$imgh = $row_rqEnt['imgTete']; 
	$foot = $row_rqEnt['entLibel']; 
	$fju = $row_rqEnt['forju']; 
	$capi = $row_rqEnt['capital']; 
	if ($fju =! 'sans') { $foot = $foot." - ".$fju ; }
	if ($capi =! 'sans') { $foot = $foot." au capital de ".$capi ; }
	setcookie("piedpage");
	setcookie("piedpage", $foot , time()+60);
	
	// préparation de la référence et de l'objet pour insertion dans la lettre
	$lref = "N/Ref : ".$act."    -    Objet : ".$row_rqAct['actLibel'];
	// description de l'acte (texte html parsé ensuite)
	$txt = $row_rqAct['actDesc'];
	$txt = str_replace("&#39;", "'", $txt);

// création et mise en forme de la date du courrier
	// TEMPS
	$temps = time($row_rqAct['dateCre']); // date du fichier acts
	// JOURS - nom du jour en français
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
	$datmail = "$jours_complet $NumeroDuJour $mois_complet $annee";

	
// récupération du contact lié à cet acte
	// récupération de l'id du contact lié [rqLien]
	$query_lien = "SELECT * FROM actqui WHERE ida = '$act' ORDER BY ida LIMIT 1";
	mysql_select_db($database_myorg_syno, $myorg_syno);
	$rqLien = mysql_query($query_lien, $myorg_syno) or die(mysql_error());
	$row_rqLien = mysql_fetch_assoc($rqLien);
	$totalRows_rqLien = mysql_num_rows($rqLien);
	$qui = $row_rqLien['idq'];
	// récupération des infos du contact [rqQui]
	$query_Qui = "SELECT * FROM contacts WHERE ID_contact = '$qui' ";
	mysql_select_db($database_myorg_syno, $myorg_syno);
	$rqQui = mysql_query($query_Qui, $myorg_syno) or die(mysql_error());
	$row_rqQui = mysql_fetch_assoc($rqQui);
	$totalRows_rqQui = mysql_num_rows($rqQui);
	// récupération des l'adresse
	$lig1 = $row_rqQui['Société'];
	$lig2 = $row_rqQui['Prenom']." ".$row_rqQui['Nom'];
	$lig3 = $row_rqQui['Adress1'];
	$lig4 = $row_rqQui['Adress2'];
	$lig5 = $row_rqQui['CP']." ".$row_rqQui['Ville'];

// ____________________________________________________
// appel de fpdf
require('../fpdf/fpdf.php');

// ____________________________________________________
// fonctions annexes pour parser le html
	// fonction hex2dec
	function hex2dec($couleur = "#000000"){
		$R = substr($couleur, 1, 2);
		$rouge = hexdec($R);
		$V = substr($couleur, 3, 2);
		$vert = hexdec($V);
		$B = substr($couleur, 5, 2);
		$bleu = hexdec($B);
		$tbl_couleur = array();
		$tbl_couleur['R']=$rouge;
		$tbl_couleur['V']=$vert;
		$tbl_couleur['B']=$bleu;
		return $tbl_couleur;
	}
	//conversion pixel -> millimètre en 72 dpi
	function px2mm($px){
		return $px*25.4/72;
	}
	function txtentities($html){
		$trans = get_html_translation_table(HTML_ENTITIES);
		$trans = array_flip($trans);
		return strtr($html, $trans);
	}
// ____________________________________________________

// ajout de classes qui étendent fpdf
class PDF extends FPDF {
	
// parseur html	
	//variables du parseur html
	var $B;
	var $I;
	var $U;
	var $HREF;
	var $fontList;
	var $issetfont;
	var $issetcolor;
	function PDF_HTML($orientation='P', $unit='mm', $format='A4')
	{
	//Appel au constructeur parent
		$this->FPDF($orientation,$unit,$format);
	//Initialisation
		$this->B=0;
		$this->I=0;
		$this->U=0;
		$this->HREF='';
		$this->fontlist=array('arial', 'times', 'courier', 'helvetica', 'symbol');
		$this->issetfont=false;
		$this->issetcolor=false;
	}
	function WriteHTML($html)
	{
		//Parseur HTML
		$html=strip_tags($html,"<b><u><i><a><img><p><br><strong><em><font><tr><blockquote>"); //supprime tous les tags sauf ceux reconnus
		$html=str_replace("\n",' ',$html); //remplace retour à la ligne par un espace
		$a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE); //éclate la chaîne avec les balises
		foreach($a as $i=>$e)
		{
			if($i%2==0)
			{
				//Texte
				if($this->HREF)
					$this->PutLink($this->HREF,$e);
				else
					$this->Write(5,stripslashes(txtentities($e)));
			}
			else
			{
				//Balise
				if($e[0]=='/')
					$this->CloseTag(strtoupper(substr($e,1)));
				else
				{
					//Extraction des attributs
					$a2=explode(' ',$e);
					$tag=strtoupper(array_shift($a2));
					$attr=array();
					foreach($a2 as $v)
					{
						if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
							$attr[strtoupper($a3[1])]=$a3[2];
					}
					$this->OpenTag($tag,$attr);
				}
			}
		}
	}
	function OpenTag($tag, $attr)
	{
		//Balise ouvrante
		switch($tag){
			case 'STRONG':
				$this->SetStyle('B',true);
				break;
			case 'EM':
				$this->SetStyle('I',true);
				break;
			case 'B':
			case 'I':
			case 'U':
				$this->SetStyle($tag,true);
				break;
			case 'A':
				$this->HREF=$attr['HREF'];
				break;
			case 'IMG':
				if(isset($attr['SRC']) && (isset($attr['WIDTH']) || isset($attr['HEIGHT']))) {
					if(!isset($attr['WIDTH']))
						$attr['WIDTH'] = 0;
					if(!isset($attr['HEIGHT']))
						$attr['HEIGHT'] = 0;
					$this->Image($attr['SRC'], $this->GetX(), $this->GetY(), px2mm($attr['WIDTH']), px2mm($attr['HEIGHT']));
				}
				break;
			case 'TR':
			case 'BLOCKQUOTE':
			case 'BR':
				$this->Ln(4);
				break;
			case 'P':
				$this->Ln(8);
				break;
			case 'FONT':
				if (isset($attr['COLOR']) && $attr['COLOR']!='') {
					$coul=hex2dec($attr['COLOR']);
					$this->SetTextColor($coul['R'],$coul['V'],$coul['B']);
					$this->issetcolor=true;
				}
				if (isset($attr['FACE']) && in_array(strtolower($attr['FACE']), $this->fontlist)) {
					$this->SetFont(strtolower($attr['FACE']));
					$this->issetfont=true;
				}
				break;
		}
	}
	function CloseTag($tag)
	{
		//Balise fermante
		if($tag=='STRONG')
			$tag='B';
		if($tag=='EM')
			$tag='I';
		if($tag=='B' || $tag=='I' || $tag=='U')
			$this->SetStyle($tag,false);
		if($tag=='A')
			$this->HREF='';
		if($tag=='FONT'){
			if ($this->issetcolor==true) {
				$this->SetTextColor(0);
			}
			if ($this->issetfont) {
				$this->SetFont('arial');
				$this->issetfont=false;
			}
		}
	}
	function SetStyle($tag, $enable)
	{
		//Modifie le style et sélectionne la police correspondante
		$this->$tag+=($enable ? 1 : -1);
		$style='';
		foreach(array('B','I','U') as $s)
		{
			if($this->$s>0)
				$style.=$s;
		}
		$this->SetFont('',$style);
	}
	function PutLink($URL, $txt)
	{
		//Place un hyperlien
		$this->SetTextColor(0,0,255);
		$this->SetStyle('U',true);
		$this->Write(5,$txt,$URL);
		$this->SetStyle('U',false);
		$this->SetTextColor(0);
	}

// ____________________________________________________
// mise en forme de la lettre
	// ____________________________________________________
	//En-tête
	function Entete($a)
	{
		//Logo
		$this->Image($a,7,7,196);
	}
	// ____________________________________________________
	//Pied de page
	function Footer($b)
	{
		//Positionnement à 1,5 cm du bas
		$this->SetY(-10);
		$this->SetX(7);
		//Police Arial italique 8
		$this->SetFont('Arial','I',8);
		//Numéro de page
		$this->Cell(196,5,$b.'         -         Page '.$this->PageNo().'/{nb}',1,0,'C',true);
	}
	// ____________________________________________________
	// adresse envoyeur
	function Expediteur($e1, $e2, $e3, $e4, $e5) 
	{
		$this->SetFont('Arial','I',11);
		$this->SetXY(10, 57);
		$this->Cell(0,0,$e1,0,0,'L');
		$this->SetXY(10, 62);
		$this->Cell(0,0,$e2,0,0,'L');
		$this->SetXY(10, 67);
		$this->Cell(0,0,$e3,0,0,'L');
		$this->SetXY(10, 72);
		$this->Cell(0,0,$e4,0,0,'L');
		$this->SetXY(10, 77);
		$this->Cell(0,0,$e5,0,0,'L');
	}
	// ____________________________________________________
	// adresse destinataire
	function Adresse($l1, $l2, $l3, $l4, $l5) 
	{
		$this->SetFont('Arial','B',14);
		$this->SetXY(115, 57);
		$this->Cell(0,0,$l1,0,0,'L');
		$this->SetXY(115, 62);
		$this->Cell(0,0,$l2,0,0,'L');
		$this->SetXY(115, 67);
		$this->Cell(0,0,$l3,0,0,'L');
		$this->SetXY(115, 72);
		$this->Cell(0,0,$l4,0,0,'L');
		$this->SetXY(115, 77);
		$this->Cell(0,0,$l5,0,0,'L');
	}
	// ____________________________________________________		
	// références
	function Reference($ref)
	{
		$this->SetFont('Arial','',11);
		$this->SetFillColor(230, 230, 250);
		$this->SetY(100);
		$this->Cell(196,7,$ref,1,0,'L',true);
	}
	// ____________________________________________________
	// texte de la lettre
	function CorpsLettre($corps)
	{
		$this->SetFont('Arial','',10);
		$this->SetTopMargin(45);
		$this->SetLeftMargin(25);
		$this->SetY(105);
		$this->WriteHTML($corps);
	}

	}

// ____________________________________________________
//Instanciation de la classe dérivée ; création du pdf
$pdf=new PDF();
$pdf->AddPage();
$pdf->Entete($imgh);
$pdf->Expediteur('Jean-François USUNIER', 'Le Gaz - Route de Saint André', '73190 APREMONT', 'tel et fax 04 79 62 67 83', $datmail);
$pdf->Adresse($lig1, $lig2, $lig3, $lig4, $lig5);
$pdf->Reference($lref);
$pdf->CorpsLettre($txt);
$pdf->AliasNbPages();
$pdf->Output();
?> 

</body>
</html>
<?php
mysql_free_result($rqAct);
mysql_free_result($rqEnt);
?>
