<?php

function SetTableAJ() {
    $t=array();
    $query="SELECT * FROM calcul_aj ORDER BY montant_min ASC";
    $result = mysql_query($query);

    while($row = mysql_fetch_array($result)) {
        $nb='k0';
        $min=$row['montant_min'];
        $max=$row['montant_max'];
        $cle=$nb.'_'.$min.'_'.$max;
        $t[$cle]=$row['taux_aj'];
        $cumul=0;
        $query="SELECT * FROM regles_aj ORDER BY nombre_personnes ASC";
        $result2 = mysql_query($query);

        while($row2 = mysql_fetch_array($result2)) {
            $cumul+=$row2['rajout_par_personne'];
            $nb='k'.$row2['nombre_personnes'];
            $min=0;
            $max=0;

            if ($row['montant_min']!=0) $min=$row['montant_min']+$cumul;

            if ($row['montant_max']!=0) $max=$row['montant_max']+$cumul;
            $cle=$nb.'_'.$min.'_'.$max;
            $t[$cle]=$row['taux_aj'];
        }
    }
    return $t;
}

function CalculTxAJ($tAJ, $revenu, $nb_enfants) {
    $revenu=parseInt($revenu);

    foreach($tAJ as $cle=>$taux) {
        $t=split('_',$cle);
        //Nombre d'enfants

        if ($t[0]=='k'.$nb_enfants) {
            $min=parseInt($t[1]);
            $max=parseInt($t[2]);

            if($min<=$revenu) {

                if ($max==0 || ($max>0 && $max>=$revenu)) {
                    return $taux;
                }
            }
        }
    }
    return 0;
}

function GlobalAJ($demandeur,$vous,$conj,$nombre_enfants,$residence_enfants) {
    $tAJ=SetTableAJ();
    // FIltrage
    // Vous
    $pays_N_vous=$vous['pays_N'];
    $pays_R_vous=$vous['pays_R'];
    $revenus_vous=$vous['revenus'];
    $pays_N_conj=$conj['pays_N'];
    $pays_R_conj=$conj['pays_R'];
    $revenus_conj=$conj['revenus'];
    $ret=array();
    $testV=true;
    $testC=true;

    if ($pays_R_vous!='FR')
        $testV=false;

    if ($demandeur=='C')
        $testV=false;
    //Conjoint

    if ($pays_R_conj!='FR')
        $testC=false;

    if ($demandeur=='V')
        $testC=false;

    if ($testV && $testC) {

        if ($demandeur=='2') {

            if ($residence_enfants=='NON' || $residence_enfants=='ALT') {
                //Calcul du meilleur taux en répartissant les enfants
                $moyenne=0;
                $meilleur=0;
                $nb=0;
                $tTaux='';

                for($i=0;$i<=$nombre_enfants;$i++) {
                    $tTaux[$i]['vous']=CalculTxAJ($tAJ, $revenus_vous, $i);
                    $tTaux[$i]['conj']=CalculTxAJ($tAJ, $revenus_conj, $nombre_enfants-$i);
                    $tTaux[$i]['moyenne']=($tTaux[$i]['vous']+$tTaux[$i]['conj'])/2;

                    if ($meilleur<$tTaux[$i]['moyenne']) {
                        $meilleur=$tTaux[$i]['moyenne'];
                        $nb=$i;
                    }
                }
                //Meilleur taux
                $tx_conj=$tTaux[$nb]['conj'];
                $tx_vous=$tTaux[$nb]['vous'];
            }

            if ($residence_enfants=='VOU') {
                $tx_vous=CalculTxAJ($tAJ, $revenus_vous, $nombre_enfants);
                $tx_conj=CalculTxAJ($tAJ, $revenus_conj, 0);
                $nb=$nombre_enfants;
            }

            if ($residence_enfants=='CNJ') {
                $tx_vous=CalculTxAJ($tAJ, $revenus_vous, 0);
                $tx_conj=CalculTxAJ($tAJ, $revenus_conj, $nombre_enfants);
                $nb=0;
            }
        }
    }
    else {

        if ($testV) {

            if ($residence_enfants!='CNJ') {
                $tx_vous=CalculTxAJ($tAJ, $revenus_vous, $nombre_enfants);
                $nb=$nombre_enfants;
            }
            else {
                $tx_vous=CalculTxAJ($tAJ, $revenus_vous, 0);
                $nb=0;
            }
        }
        else {
            $tx_vous=-1;
            $nb=0;
        }

        if ($testC) {

            if ($residence_enfants!='VOU') {
                $tx_conj=CalculTxAJ($tAJ, $revenus_conj, $nombre_enfants);
                $nb=0;
            }
            else {
                $tx_conj=CalculTxAJ($tAJ, $revenus_conj, 0);
                $nb=$nombre_enfants;
            }
        }
        else {
            $tx_conj=-1;
            $nb=$nombre_enfants;
        }
    }

    if ($tx_vous!=-1) {
        $ret['vous']['tx']=$tx_vous;
        $ret['vous']['nb']=$nb;
    }
    else {

        if ($demandeur!='C') {
            $ret['vous']['tx']=-1;
        }
    }

    if ($tx_conj!=-1) {
        $ret['conj']['tx']=$tx_conj;
        $ret['conj']['nb']=($nombre_enfants-$nb);
    }
    else {

        if ($demandeur!='V') {
            $ret['conj']['tx']=-1;
        }
    }
    return $ret;
}