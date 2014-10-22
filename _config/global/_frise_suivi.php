<?php

function DisplayFrise($id_demande, $id_demandeur)
{
	?>
	<table width="100%" class="parcours">
		<tr>
			<td rowspan="2" style="border:none;text-align:center;vertical-align:middle">
				<img src="http://images.services-conjugaux.com/?w=100&dossier=_admin&image=menu/demandes.png"><br/>
				Ma demande
			</td>
			<td style="border:none;text-align:center;vertical-align:middle">
				<?php						
				$class=FindDatas("SELECT count(demandes.id_demande) as total FROM demandes_docs
					INNER JOIN demandes ON demandes.id_demande=demandes_docs.id_demande
					WHERE NOT demandes_docs.id_type_doc IN (36, 37) AND demandes.id_demande=".$id_demande." AND demandes_docs.chemin=''");
				?>
				<img <?php echo $class ?> src="http://images.services-conjugaux.com/?dossier=_admin&w=50&alpha=1&image=lettres-types.png"><br/>
				Docs reçus
			</td>			
			<td rowspan="2" style="border:none;text-align:center;vertical-align:middle">
				<?php						
				$class=FindDatas("SELECT count(id_demande) as total FROM demandes
					WHERE demandes.id_demande=".$id_demande." AND id_pro_associe<>0");
				?>
				<img <?php echo $class ?> src="http://images.services-conjugaux.com/?dossier=_admin&w=100&image=prospects.png"><br/>
				Recherche avocat
			</td>
			<td style="border:none;text-align:center;vertical-align:middle">
				<?php						
				$class=FindDatas("SELECT count(id_demande) as total FROM demandes
					WHERE demandes.id_demande=".$id_demande." AND id_pro_associe=0");
				?>
				<img <?php echo $class ?> src="http://images.services-conjugaux.com/?dossier=_admin&w=50&image=prospects.png"><br/>
				Avocat trouvé
			</td>
			<td rowspan="2" style="border:none;text-align:center;vertical-align:middle">
				<?php						
				$class=FindDatas("SELECT 1-count(id_doc) as total
					from demandes_docs dd
					INNER JOIN demandes d ON d.id_demande=dd.id_demande
					where dd.id_type_doc IN (36, 37) AND dd.chemin='' AND d.id_demande=".$id_demande."");
				?>
				<img <?php echo $class ?> src="http://images.services-conjugaux.com/?w=100&dossier=_admin&image=demandes.png"><br/>
				Rédaction d'actes
			</td>
			<td style="border:none;text-align:center;vertical-align:middle">
				<?php						
				$class=FindDatas("SELECT 1-count(id_doc) as total
					from demandes_docs dd
					INNER JOIN demandes d ON d.id_demande=dd.id_demande
					where dd.id_type_doc IN (36, 37) AND dd.chemin<>'' AND d.id_demande=".$id_demande."");
				?>
				<img <?php echo $class ?> src="http://images.services-conjugaux.com/?dossier=_admin&w=50&image=lettres-types.png"><br/>
				Actes signés
			</td>
			<td rowspan="2" style="border:none;text-align:center;vertical-align:middle">
				<img <?php echo $class ?> src="http://images.services-conjugaux.com/?w=100&dossier=_admin&image=menu/justice.png"><br/>
				RDV au tribunal
			</td>
			<td rowspan="2" style="border:none;text-align:center;vertical-align:middle">
				<img <?php echo $class ?> src="http://images.services-conjugaux.com/?w=100&dossier=_admin&image=menu/separation.png"><br/>
				Divorcés !
				<br/>&nbsp;
			</td>
		</tr>
		<tr>
			<td style="border:none;text-align:center;vertical-align:middle">
				<?php						
				$class=FindDatas("SELECT  1-ifnull(sum(montant_paiement),0)/ifnull(sum(c.montant_ttc),1) as total
					from paiements p right join commandes c on p.id_compte=c.id_particulier
					where id_particulier=".$id_demandeur."");
				?>
				<img <?php echo $class ?> src="http://images.services-conjugaux.com/?w=50&dossier=_admin&image=menu/financier.png"><br/>
				Paiement 1 OK
			</td>
			<td style="border:none;text-align:center;vertical-align:middle">
				<?php						
				$class=FindDatas("SELECT  1-ifnull(sum(montant_paiement),0)/ifnull(sum(c.montant_ttc),1) as total
					from paiements p right join commandes c on p.id_compte=c.id_particulier
					where id_particulier=".$id_demandeur."");
				?>
				<img <?php echo $class ?> src="http://images.services-conjugaux.com/?w=50&dossier=_admin&image=menu/financier.png"><br/>
				Paiement 2 OK
			</td>
			<td style="border:none;text-align:center;vertical-align:middle">
				<?php						
				$class=FindDatas("SELECT  1-ifnull(sum(montant_paiement),0)/ifnull(sum(c.montant_ttc),1) as total
					from paiements p right join commandes c on p.id_compte=c.id_particulier
					where id_particulier=".$id_demandeur."");
				?>
				<img <?php echo $class ?> src="http://images.services-conjugaux.com/?w=50&dossier=_admin&image=menu/financier.png"><br/>
				Paiement 3 OK
			</td>
		</tr>
	<table>
	<?php
}
/////////////

function FindDatas($query)
{
	$class=' class="transparent"';
	$result=mysql_query($query);

	if(mysql_num_rows($result)==0) return "";
	$row=mysql_fetch_array($result);

	if($row['total']<=0) return "";
	return $class;
}