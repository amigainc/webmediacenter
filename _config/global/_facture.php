<?php

function DisplayFacture($id_commande)
{

	global $pdf;
	SetConnection();
	$query="SELECT * FROM commandes WHERE id_commande=".$id_commande;
    $result = mysql_query($query);

    while($row = mysql_fetch_array($result))
    {
        $id_par=$row['id_particulier'];
        $id_pro=$row['id_professionnel'];
        $date=FormatDate($row['date_validation']);
    }
	//Création du facture
    $pdf=new FPDF();
    SetNewPage('Facture '.$id_commande);
    AddDate($date);
    $query="SELECT * FROM personnes WHERE id_compte=".$id_par;
    $result = mysql_query($query);

    while($row = mysql_fetch_array($result))
    {
        $nom=utf8_encode($row['prenom'].' '.$row['nom']);
        $adresse=utf8_encode($row['adresse']);
        $cp=$row['cp'];
        $ville=$row['ville'];
    }
    WriteCoordonnees($nom,$adresse,$cp,$ville,$date);
    $query="SELECT * FROM personnes p INNER JOIN comptes c WHERE c.id_compte=".$id_pro;
    $result = mysql_query($query);
	$nom="";

    while($row = mysql_fetch_array($result))
    {
        $nom=$row['prenom'].' '.$row['nom'];
    }

	if(!$nom) $nom="Services-Conjugaux.com";
    WriteLettre("Commande de services",
        'Bonjour,
Veuillez trouver ci-dessous le montant demandé par '.$nom.' pour valider votre commande auprès de nos services.
(TVA. non applicable, art. 293 B du CGI.)
Le moyen de paiement retenu est le CHEQUE, à envoyer à :
Béatrice BOTHEMINE
23 rue des Acacias
53160 St Martin de Connée.
Cordialement,');
    $query="SELECT * FROM produits_commandes pc
        INNER JOIN prestations p ON p.id_prestation=pc.id_prestation
        WHERE pc.id_commande=".$id_commande;
    $result = mysql_query($query);

    while($row = mysql_fetch_array($result))
    {
        $t=array();
        $t[]=$row['titre_prestation'];
        $t[]=round($row['prix_unitaire_ht']*1.196,2);
        $t[]=$row['quantite'];
        $t[]=$row['remise_pc'];
        $t[]=round($row['total_ht']*1.196,2);
        $tableau[]=$t;
    }
    $pdf->SetXY(10,130);
    /*TableEstimation(
            array('Produit','PU HT','Qté','Remise','Total HT'),
            $tableau,
            array(65,30,30,30,30));*/
    TableEstimation(
            array('Produit','PU TTC','Qté','Remise %','Total TTC'),
            $tableau,
            array(65,30,30,30,30));
    //Affichage
    $pdf->Output();
	CloseConnection();
    die();
}