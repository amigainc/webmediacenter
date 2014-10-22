<?php

function DisplayDemande()
{

	global $pdf;
	$id=$_SESSION['id_visiteur'];
	$nom_vous='';
	$prenom_vous='';
	$date_vous='';
	$pays_vous='';
	$pays_R_vous='';
	$adresse_vous='';
	$cp_vous='';
	$ville_vous='';
	$tel_vous='';
	$email_vous='';
	$nat_vous='';
	$profession_vous='';
	$revenus_vous='';
	$aj_vous='';
	$nom_conj='';
	$prenom_conj='';
	$date_conj='';
	$pays_conj='';
	$pays_R_conj='';
	$adresse_conj='';
	$cp_conj='';
	$ville_conj='';
	$tel_conj='';
	$email_conj='';
	$nat_conj='';
	$profession_conj='';
	$revenus_conj='';
	$aj_conj='';
	$motif='';
	$nature='';
	$demandeur='';
	$autour='';
	$desaccord='';
	$besoin='';
	$id_vous=0;
	$id_conj=0;
	$mess='';
	/////////////////////////
	// Demande précédente ?
	$id_demande=0;
	$modif=true;

	if (isset($_GET['id_demande'])) $id_demande=$_GET['id_demande'];
	SetConnection();

	if ($id_demande)
	{
		$query="SELECT * FROM demandes WHERE id_demande=".$id_demande;
		$result = mysql_query($query);

		while($row = mysql_fetch_array($result))
		{
			$id_demande=$row['id_demande'];
			$nature=($row['nature_demande']?$row['nature_demande']:"JUR");
			$date_demande=$row['date_demande'];
			$demandeur=$row['demandeurs'];
			$motif=$row['motif'];
			$autour=$row['codes_details'];
			$desaccord=$row['codes_divergences'];
			$besoin=$row['codes_besoins'];
			$residence_enfants=$row['residence_enfants'];
			$pension=$row['pension_alimentaire'];
			$texte_libre=utf8_encode($row['texte_libre']);
		}
		//////////////////////////////////////
		// Recherche compte
		$query="SELECT * FROM personnes p
			INNER JOIN demandes d ON d.id_compte=p.id_compte
			WHERE d.id_demande=".$id_demande;
		$result = mysql_query($query);

		while($row = mysql_fetch_array($result))
		{
			$id=$row['id_compte'];
			$nom_vous=utf8_encode($row['nom']);
			$prenom_vous=utf8_encode($row['prenom']);
			$sexe_vous=utf8_encode($row['sexe']);
			$date_vous=$row['date_naiss'];
			$pays_vous=$row['pays_naiss'];
			$pays_R_vous=$row['pays_resid'];
			$nat_vous=utf8_encode($row['nationalite']);
			$adresse_vous=utf8_encode($row['adresse']);
			$cp_vous=$row['cp'];
			$ville_vous=utf8_encode($row['ville']);
			$tel_vous=$row['tel'];
			$email_vous=utf8_encode($row['email']);
			$profession_vous=utf8_encode($row['profession']);
			$revenus_vous=utf8_encode($row['revenus']);
		}
		$query="SELECT * FROM personnes WHERE conjoint_de=".$id;
		$result = mysql_query($query);

		while($row = mysql_fetch_array($result))
		{
			$nom_conj=utf8_encode($row['nom']);
			$prenom_conj=utf8_encode($row['prenom']);
			$sexe_conj=utf8_encode($row['sexe']);
			$date_conj=$row['date_naiss'];
			$pays_conj=$row['pays_naiss'];
			$pays_R_conj=$row['pays_resid'];
			$nat_conj=utf8_encode($row['nationalite']);
			$adresse_conj=utf8_encode($row['adresse']);
			$cp_conj=$row['cp'];
			$ville_conj=utf8_encode($row['ville']);
			$tel_conj=$row['tel'];
			$email_conj=utf8_encode($row['email']);
			$profession_conj=utf8_encode($row['profession']);
			$revenus_conj=utf8_encode($row['revenus']);
		}

		if($nb_enfants==0)
		{
			$query="SELECT id_personne FROM personnes WHERE enfant_de=".$id;
			$result = mysql_query($query);
			$nb_enfants=mysql_num_rows($result);
		}

		if (!$pays_conj) $pays_conj='FR';

		if (!$pays_vous) $pays_vous='FR';

		if (!$pays_R_conj) $pays_R_conj='FR';

		if (!$pays_R_vous) $pays_R_vous='FR';
		// PDF

		if (isset($_GET['genpdf']))
		{
			$pdf=new FPDF();
			SetNewPage('Demande '.$id_demande);
			AddDate(FormatDate($date_demande));
			WriteCoordonnees($nom_vous.' '.$prenom_vous,$adresse_vous,$cp_vous,$ville_vous,$date);
			$texte = "[Demande]
Motif : ".RetrieveFields("demandes_motifs",$motif,"PDF").
"Nature : ".RetrieveFields("demandes_natures",$nature,"PDF").
"Demandeurs : ".RetrieveFields("demandeurs",$demandeur,"PDF").
"
[Demandeur]
Nom : $nom_vous, $prenom_vous
Date de naissance : ".FormatDate($date_vous)."
Adresse : $adresse_vous, $cp_vous $ville_vous
Tél : $tel_vous
Situation professionnelle : ".RetrieveFields("professions",$profession_vous,"PDF").
"Revenus : $revenus_vous euros/mois
[Conjoint]
Nom : $nom_conj, $prenom_conj
Date de naissance : ".FormatDate($date_conj)."
Adresse : $adresse_conj, $cp_conj $ville_conj
Tél : $tel_conj
Situation professionnelle : ".RetrieveFields("professions",$profession_conj,"PDF").
"Revenus : $revenus_conj euros/mois
[Enfants]
##ENFANTS##
[Autour]
".RetrieveFields("demandes_details", $autour,'PDF')."
[Divergences]
".RetrieveFields("demandes_divergences", $desaccord,'PDF')."";
			//Enfants
			 $query="SELECT * FROM personnes WHERE enfant_de=".$id;
			$result = mysql_query($query);
			$enfants="";

			while($row = mysql_fetch_array($result))
			{
				$enfants.='- '.utf8_encode($row['nom'].' '.$row['prenom']).', né(e) le '.FormatDate($row['date_naiss']).'
';
			}

			if(!$enfants)
			{
				$enfants="Aucun";
			}
			else
			{
				$enfants.="Mode de résidence des enfants : ".RetrieveFields('residence_enfants', $residence_enfants,'PDF')."Montant de la pension alimentaire : $pension";
			}
			$texte=str_replace("##ENFANTS##",$enfants,$texte);
			//Autour
			//Désaccords
			//Display
			$pdf->SetXY(10,68);
			$pdf->SetFont('Arial','B',16);
			$pdf->Write(6,'Objet : '.utf8_decode("Synthèse de la demande"));
			$pdf->SetXY(10,80);
			$pdf->SetFont('Arial','',12);

			if(strpos($texte,chr(10))!==false) $t=split(chr(10),$texte);

			if(strpos($texte,chr(13))!==false) $t=split(chr(13),$texte);

			for($i=0;$i<count($t);$i++)
			{
				$pdf->Write(5,str_replace("\'","'",utf8_decode($t[$i])));
				$pdf->Ln();
			}
			//Affichage
			$pdf->Output();
			CloseConnection();
			die();
		}	
	}
}