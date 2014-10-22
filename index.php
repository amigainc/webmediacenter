<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include '_display.php';

$titre='';
$where='';

$result=mysql_query("SELECT count(id_film) AS nb FROM films_films WHERE chemin_film LIKE '%.mp4'");
$row=mysql_fetch_array($result);
$totalFilms=$row['nb'];

if($_GET) {
    
	if(isset($_GET['corrompu'])) {
		mysql_query("UPDATE films_films SET corrompu=1 WHERE id_film=".$_GET['corrompu']);		
		header("Location: .");
	}
	if(isset($_GET['plus1'])) {
		mysql_query("UPDATE films_films SET plus1=plus1+1 WHERE id_film=".$_GET['plus1']);		
		header("Location: .");
	}
	if(isset($_GET['moins1'])) {
		mysql_query("UPDATE films_films SET moins1=moins1+1 WHERE id_film=".$_GET['moins1']);		
		header("Location: .");
	}
	
	if(isset($_GET['plustard'])) {
		mysql_query("UPDATE films_films SET plustard=1 WHERE id_film=".$_GET['plustard']);		
	}	if(isset($_GET['avoir'])) {
		$where=' AND f.plustard=1';
		$titre='A voir';
	}
	
    foreach($_GET as $key=>$val) {
        if($key=='annee' && $val) {
            $where.=" AND f.annee_film=$val "; 
        }
        if($key=='genre' && $val) {
            $where.=" AND f.id_film IN (SELECT id_film FROM films_genre_film WHERE id_genre=$val) "; 
        }
        if($key=='acteur' && $val) {
            $where.=" AND f.id_film IN (SELECT id_film FROM films_acteur_film WHERE id_acteur=$val) "; 
        }
		if($key=='genre2' && $val) {
			if(substr($val,0,1)=='-'){
				$val=substr($val,1);
				$where.=" AND f.id_film NOT IN (SELECT id_film FROM films_genre_film WHERE id_genre=$val) "; 
			}else
				$where.=" AND f.id_film IN (SELECT id_film FROM films_genre_film WHERE id_genre=$val) "; 
        }
        if($key=='acteur2' && $val) {
            $where.=" AND f.id_film IN (SELECT id_film FROM films_acteur_film WHERE id_acteur=$val) "; 
        }
        if($key=='real' && $val) {
            $where.=" AND f.id_film IN (SELECT id_film FROM films_real_film WHERE id_real=$val) "; 
        }
		if($key=='search' && $val) {
            $where.=" AND LOWER(f.nom_film) LIKE '%".Encoding::toLatin1(strtolower($val))."%' "; 
        }
		if($key=='lang' && $val) {
            $where.=" AND f.lang = '$val'"; 
        }
		if($key=='duree' && $val) {
			$t=explode('-',$val);
            $where.=" AND f.duree >= '".$t[0]."' AND f.duree<='".$t[1]."'"; 
        }
		if($key=='hauteur' && $val) {
			$t=explode('-',$val);
            $where.=" AND f.hauteur >= '".$t[0]."' AND f.hauteur<='".$t[1]."'"; 
        }
    }
}

if($where) {
    $query="SELECT * FROM films_films f WHERE chemin_film LIKE'%.mp4' $where ORDER BY f.annee_film DESC, f.nom_film DESC";
    $titre="Résultat de la recherche";
}
else
{
    $query="SELECT * FROM films_films WHERE chemin_film LIKE'%.mp4' ORDER BY rand() LIMIT 12";
    //$query="SELECT * FROM films_films f ORDER BY date_modif DESC LIMIT 12";
    $titre='Films au hasard';
    //$titre='Derniers films';
}

$result0=  mysql_query($query);

SetHead($titre);
?>
<div>
                <form method="GET">
                    <select name="genre" onchange="document.forms[0].submit();">
                        
                        <?php
                        $result = mysql_query("SELECT * FROM films_genres ORDER BY nom_genre ASC");
                        ?>
						<option value="">Par genre [<?php echo mysql_num_rows($result) ?>]</option>
						<?php while ($row = mysql_fetch_array($result)):
                            ?>
                        <option value="<?php echo $row['id_genre'] ?>"<?php echo (isset($_GET['genre']) && $_GET['genre']==$row['id_genre'] ? ' selected="selected"' : '') ?>><?php echo Encoding::toUTF8($row['nom_genre']) ?></option>
                            <?php
                        endwhile;
                        ?>
                    </select>
					<select name="genre2" onchange="document.forms[0].submit();">
                        
                        <?php
                        $result = mysql_query("SELECT * FROM films_genres ORDER BY nom_genre ASC");
                        ?>
						<option value="">Par genre [<?php echo mysql_num_rows($result) ?>]</option>
						<?php while ($row = mysql_fetch_array($result)):
                            ?>
                        <option value="<?php echo $row['id_genre'] ?>"<?php echo (isset($_GET['genre2']) && $_GET['genre2']==$row['id_genre'] ? ' selected="selected"' : '') ?>>+ <?php echo Encoding::toUTF8($row['nom_genre']) ?></option>
                        <option value="-<?php echo $row['id_genre'] ?>"<?php echo (isset($_GET['genre2']) && $_GET['genre2']=='-'.$row['id_genre'] ? ' selected="selected"' : '') ?>>- <?php echo Encoding::toUTF8($row['nom_genre']) ?></option>
                            <?php
                        endwhile;
                        ?>
                    </select>
                    <select name="acteur" onchange="document.forms[0].submit();">
                        
                        <?php
                        $result = mysql_query("SELECT * FROM films_acteurs WHERE id_acteur IN (SELECT DISTINCT id_acteur FROM films_acteur_film) ORDER BY nom_acteur ASC");
                        ?>
						<option value="">Par acteur [<?php echo mysql_num_rows($result) ?>]</option>
						<?php while ($row = mysql_fetch_array($result)):
                            ?>
                        <option value="<?php echo $row['id_acteur'] ?>"<?php echo (isset($_GET['acteur']) && $_GET['acteur']==$row['id_acteur'] ? ' selected="selected"' : '') ?>><?php echo Encoding::toUTF8($row['nom_acteur']) ?></option>
                            <?php
                        endwhile;
                        ?>
                    </select>
					<select name="acteur2" onchange="document.forms[0].submit();">
                        
                        <?php
                        $result = mysql_query("SELECT * FROM films_acteurs WHERE id_acteur IN (SELECT DISTINCT id_acteur FROM films_acteur_film) ORDER BY nom_acteur ASC");
                        ?>
						<option value="">Par acteur [<?php echo mysql_num_rows($result) ?>]</option>
						<?php while ($row = mysql_fetch_array($result)):
                            ?>
                        <option value="<?php echo $row['id_acteur'] ?>"<?php echo (isset($_GET['acteur2']) && $_GET['acteur2']==$row['id_acteur'] ? ' selected="selected"' : '') ?>><?php echo Encoding::toUTF8($row['nom_acteur']) ?></option>
                            <?php
                        endwhile;
                        ?>
                    </select>
                    <select name="real" onchange="document.forms[0].submit();">
                        
                        <?php
                        $result = mysql_query("SELECT * FROM films_reals ORDER BY nom_real ASC");
                        ?>
						<option value="">Par réalisateur [<?php echo mysql_num_rows($result) ?>]</option>
						<?php while ($row = mysql_fetch_array($result)):
                            ?>
                        <option value="<?php echo $row['id_real'] ?>"<?php echo (isset($_GET['real']) && $_GET['real']==$row['id_real'] ? ' selected="selected"' : '') ?>><?php echo Encoding::toUTF8($row['nom_real']) ?></option>
                            <?php
                        endwhile;
                        ?>
                    </select>
                    <select name="annee" onchange="document.forms[0].submit();">
                        <option value="">Par année</option>
                        <?php
                        $result = mysql_query("SELECT DISTINCT annee_film FROM films_films ORDER BY annee_film DESC");
                        while ($row = mysql_fetch_array($result)):
                            ?>
                            <option value="<?php echo $row['annee_film'] ?>"<?php echo (isset($_GET['annee']) && $_GET['annee']==$row['annee_film'] ? ' selected="selected"' : '') ?>><?php echo $row['annee_film'] ?></option>
                            <?php
                        endwhile;
                        ?>
                    </select>
					<select name="lang" onchange="document.forms[0].submit();">
                        <option value="">Par langue</option>
                        <option value="FR"<?php echo (isset($_GET['lang']) && $_GET['lang']=='FR' ? ' selected="selected"' : '') ?>>Français</option>
                        <option value="GB"<?php echo (isset($_GET['lang']) && $_GET['lang']=='GB' ? ' selected="selected"' : '') ?>>Anglais</option>
                        <option value="ES"<?php echo (isset($_GET['lang']) && $_GET['lang']=='ES' ? ' selected="selected"' : '') ?>>Espagnol</option>
                        <option value="JP"<?php echo (isset($_GET['lang']) && $_GET['lang']=='JP' ? ' selected="selected"' : '') ?>>Japonais</option>
                    </select>
					<select name="duree" onchange="document.forms[0].submit();">
                        <option value="">Par durée</option>
                        <option value="0:00-1:00"<?php echo (isset($_GET['duree']) && $_GET['duree']=='0:00-1:00' ? ' selected="selected"' : '') ?>>&lt; 1h00</option>
                        <option value="1:00-1:30"<?php echo (isset($_GET['duree']) && $_GET['duree']=='1:00-1:30' ? ' selected="selected"' : '') ?>>&lt; 1h30</option>
                        <option value="1:30-2:00"<?php echo (isset($_GET['duree']) && $_GET['duree']=='1:30-2:00' ? ' selected="selected"' : '') ?>>&lt; 2h00</option>
                        <option value="2:00-6:00"<?php echo (isset($_GET['duree']) && $_GET['duree']=='2:00-6:00' ? ' selected="selected"' : '') ?>>&gt; 2h00</option>
                    </select>
					<select name="hauteur" onchange="document.forms[0].submit();">
                        <option value="">Résolution</option>
                        <option value="0-320"<?php echo (isset($_GET['hauteur']) && $_GET['hauteur']=='0-320' ? ' selected="selected"' : '') ?>>SD</option>
                        <option value="321-719"<?php echo (isset($_GET['hauteur']) && $_GET['hauteur']=='321-720' ? ' selected="selected"' : '') ?>>MD</option>
                        <option value="720-2160"<?php echo (isset($_GET['hauteur']) && $_GET['hauteur']=='721-2160' ? ' selected="selected"' : '') ?>>HD</option>
                        </select>
					<input type="text" name="search" placeholder="Recherche libre" value="<?php echo (isset($_GET['search']) ? $_GET['search'] : '') ?>"/>
                    <input type="button" onclick="location.href='?'" value="Effacer le filtre"/>
                    <input type="button" onclick="location.href='?avoir'" value="A voir"/>
                </form>
            </div>
<div>
    <?php echo $titre ?> [<?php echo mysql_num_rows($result0).'/'.$totalFilms ?>]<br/>
    <?php
    
    while($row=  mysql_fetch_array($result0)):
		//R2cup meta-datas
		$reals='';
		$acteurs='';
		$genres='';
		$genresI=array();
		$photos=array();
		$r=mysql_query("SELECT * FROM films_real_film rf INNER JOIN films_reals r ON rf.id_real=r.id_real WHERE rf.id_film=".$row['id_film']);
		while($r2=mysql_fetch_array($r)):
			$reals.=' ; '.$r2['nom_real'];
		endwhile;
		$r=mysql_query("SELECT * FROM films_acteur_film rf INNER JOIN films_acteurs r ON rf.id_acteur=r.id_acteur WHERE rf.id_film=".$row['id_film']."");
		while($r2=mysql_fetch_array($r)):
			$acteurs.=' ; '.$r2['nom_acteur'].' ('.str_replace('"',"'",$r2['role']).')';
			$photos[$r2['id_acteur']]=$r2;
		endwhile;
		$r=mysql_query("SELECT * FROM films_genre_film rf INNER JOIN films_genres r ON rf.id_genre=r.id_genre WHERE rf.id_film=".$row['id_film']);
		while($r2=mysql_fetch_array($r)):
			$genres.=' ; '.$r2['nom_genre'];
			if($r2['icone']) $genresI[$r2['nom_genre']]=$r2['icone'];
		endwhile;
	?>
    <div class="affiche">
        <a title="Réalisé par <?php echo substr($reals,3)."\n"; ?>Avec <?php echo substr($acteurs,3)."\n"; ?>Genre : <?php echo substr($genres,3)."\n"; ?><?php echo str_replace('"','', Encoding::toUTF8($row['synopsis'])) ?>" target="blank" 
			href="lecture.php?id=<?php echo $row['id_film'] ?>"><img src="<?php echo $row['affiche'] ?>"/>
			</a>
			
			<span style="float:right;display:block;color:white;z-index:2;background-color:black;font-size:50%;text-align:right;width:40px;height:80px">
			
				<img src="http://pix.suiterre.fr/flags/rect/<?php echo strtolower($row['lang']) ?>.png" style="width:20px;height:auto;"/><?php if ($row['soustitres']): ?><img src="http://pix.suiterre.fr/flags/rect/<?php echo strtolower($row['soustitres']) ?>.png" style="width:20px;height:auto;"/><?php endif; ?>
				<?php 
				if(!$row['largeur'] || $row['largeur']==320) {
					$getID3 = new getID3;
					$file = $getID3->analyze($row['chemin_film']);
					if(isset($file['video']['resolution_x'])) {
						$l=$file['video']['resolution_x'];
						$h=$file['video']['resolution_y'];
						$row['largeur']=$l;
						$row['hauteur']=$h;
						mysql_query("UPDATE films_films SET largeur='$l', hauteur='$h' WHERE id_film=".$row['id_film']);
					}
				}
				
				
				
				if(!$row['duree']) {
					$getID3 = new getID3;
					$file = $getID3->analyze($row['chemin_film']);
					if(isset($file['playtime_string']) && strpos($file['playtime_string'],':')!==false) {
						$duree=explode(':', $file['playtime_string']);
						if(count($duree)==3)
							$duree=$duree[0].':'.str_pad($duree[1],2,'0',STR_PAD_LEFT);
						else
							$duree='0:'.str_pad($duree[0],2,'0',STR_PAD_LEFT);
						$row['duree']=$duree;
						mysql_query("UPDATE films_films SET duree='$duree' WHERE id_film=".$row['id_film']);
					}
					
				}
				$t=explode(':',$row['duree']);
				echo '<strong style="font-size:150%">'.$row['duree'] . '</strong> (<span title="Fin estimée du film" class="fini" data-length="'.($t[0]*3600+$t[1]*60).'">'.date('H:i',mktime($t[0]+date('H'), $t[1]+date('i'))) . '</span>)';
				?>
				<hr/>
				<?php echo 'L:'.$row['largeur'].' H:'.$row['hauteur'];?>
			</span><?php $i=0; foreach($photos as $id=>$acteur): ?>
			<div class="acteur" title="<?php echo $acteur['nom_acteur'] ?>" style="background-image:url('<?php echo $acteur['photo'] ?>');" onclick="location.href='?acteur=<?php echo $id ?>';"></div>
			<?php $i++;if($i==3) break; endforeach; ?><?php 
				$plus1=$row['plus1']+1;
				$moins1=$row['moins1']+1;
				?><span style="font-size:10px;text-align:left;display:inline-block;height:16px;width:<?php echo (99*$moins1)/($moins1+$plus1) ?>%;background-color:red;">
					<img onclick="location.href='?moins1=<?php echo $row['id_film'] ?>';" title="Je n'ai pas plus envie que ça de le revoir" style="vertical-align:middle;cursor:pointer;height:16px;width:auto;" src="http://pix.suiterre.fr/icones/16x16/sign_remove.png"/>
					<?php echo $row['moins1'] ?>
				</span><span style="font-size:10px;text-align:right;display:inline-block;height:16px;width:<?php echo (99*$plus1)/($moins1+$plus1) ?>%;background-color:darkgreen;">
					<?php echo $row['plus1'] ?>
					<img onclick="location.href='?plus1=<?php echo $row['id_film'] ?>';" title="J'ai envie de le revoir" style="vertical-align:middle;cursor:pointer;height:16px;width:auto;" src="http://pix.suiterre.fr/icones/16x16/sign_add.png"/>
				</span>	
			
			<br/>
        <?php if($row['corrompu']):?><img style="width:16px" src="http://pix.suiterre.fr/icones/16x16/sign_remove.png"/><?php endif; ?>
        <?php if($row['plustard']):?>
			<img style="width:16px" src="http://pix.suiterre.fr/icones/16x16/clock.png"/>
		<?php else: ?>
			<a target="_blank" href="?plustard=<?php echo $row['id_film'] ?>"><img style="width:16px" src="http://pix.suiterre.fr/icones/16x16/save.png" title="Regarder plus tard"/></a>
		<?php endif; ?>
		<?php foreach($genresI as $nom=>$icone): ?>
			<img style="width:16px" src="<?php echo $icone ?>" title="<?php echo $nom ?>"/>
		<?php endforeach; ?>
		<?php echo Encoding::toUTF8($row['nom_film']) ?><br/>
        (<?php echo $row['annee_film'] ?>)<br/>
		
        
    </div>
    <?php
    endwhile;
    ?>
</div>
<script>
	$(function() {
		setInterval(function() {
			$('span.fini').each(function() {
				var duree=$(this).attr('data-length')*1000;
				var d = new Date();
				var d0 = new Date(d.getFullYear(), d.getMonth(), d.getDate(), 0, 0, 0, 0);
				var n = d.getTime()+duree-d0.getTime();
				$(this).text(Seconds2Time(n/1000));
			});
		},10000);
	});
	
	function Seconds2Time(seconds) {
		time=Math.floor(seconds);
		hour=Math.floor(time/3600); time-=(hour*3600);
		if(hour>23) hour=24-hour;
		minute=Math.floor(time/60); time-=(minute*60);
		return hour+':'+(minute<10 ? '0':'')+minute;
	}
</script>
<?php
SetFoot();