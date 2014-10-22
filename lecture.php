<?php

include '_display.php';

mysql_query("UPDATE films_films SET plustard=0 WHERE id_film=".$_GET['id']);
$result=mysql_query("SELECT * FROM films_films WHERE id_film=".$_GET['id']);
$row=mysql_fetch_array($result);

//Précédent
$r2=mysql_query("SELECT * FROM films_films WHERE nom_film<'".mysql_real_escape_string($row['nom_film'])."' ORDER BY nom_film DESC LIMIT 1");
$prev=mysql_fetch_array($r2);
//Suivant
$r2=mysql_query("SELECT * FROM films_films WHERE nom_film>'".mysql_real_escape_string($row['nom_film'])."' ORDER BY nom_film ASC LIMIT 1");
$next=mysql_fetch_array($r2);

$film=str_replace('\\','/',substr($row['chemin_film'],24));
//$film=urlencode($film);
$film=str_replace(' ','%20',$film);
?>
<html>
<head>
<title><?php echo $row['nom_film'] ?></title>
<script src="http://code.jquery.com/jquery.js"></script>
<script src="mediaelement/build/mediaelement-and-player.min.js"></script>
<script src="http://www.aurelien-stride.fr/projets/7f/7framework/js/jquery.wipetouch.js"></script>
<link rel="stylesheet" href="mediaelement/build/mediaelementplayer.css" />
 <link href="video-js/video-js.css" rel="stylesheet" type="text/css">
  <!-- video.js must be in the <head> for older IEs to work. -->
  <script src="video-js/video.js"></script>
  <script src="video-js/jquery.object-fit.min.js"></script>
  <style>
	video{
		object-fit: cover;
	}
	video.c43 { 
	  -webkit-transform: scaleX(1.33); 
	  -moz-transform: scaleX(1.33);
	}
	video.c34 { 
	  -webkit-transform: scaleX(0.75); 
	  -moz-transform: scaleX(0.75);
	}
	body {
		overflow:hidden;
		margin:0;background-color:black;
		color:white;
		text-align:center;
		font-family:verdana;
		text-transform:uppercase;
		font-weight:bold;
		font-size:18px;
		background-image:url(http://i.imgur.com/JuU08.gif);
		background-position:center center;
		background-repeat:no-repeat;
	}
	#controls {
		width:100%;
		height:10%;
		display:none;
		position:absolute;
		top:90%;
		left:0;
		z-index:210;
		background-color:rgba(0,0,0,0.5);
	}
	#progress, #buffer{
		position:absolute;
		top:99%;
		height:1%;
		width:0;
		left:0;
		z-index:0;
	}
	#progress{background-color:red;}
	#buffer{background-color:#888;}
  </style>
</head>
<body style="">
<div id="buffer"></div>
<div id="progress"></div>


<video id="video" class="video-js vjs-default-skin" src="/films/<?php echo $film ?>" 
	autoplay preload width="100%" height="99%"></video>

<div id="controls">
	<a id="prev" title="<?php echo $prev['nom_film'] ?>" href="?id=<?php echo $prev['id_film'] ?>"><img src="./img/left-round-128.png" height="100%" align="left"/></a>
	<a id="next" title="<?php echo $next['nom_film'] ?>" href="?id=<?php echo $next['id_film'] ?>"><img src="./img/right-round-128.png" height="100%" align="right"/></a>
	<?php echo $row['nom_film'] ?> /
	<img src="http://pix.suiterre.fr/flags/rect/<?php echo strtolower($row['lang']) ?>.png" height="18"/>
	<?php if($row['soustitres']):?>ST<img src="http://pix.suiterre.fr/flags/rect/<?php echo strtolower($row['soustitres']) ?>.png" height="16"/><?php endif; ?>
		
	<!-- <span id="code"></span> -->
	<?php if(!$row['corrompu']):?><a style="color:#aaa" href="./?corrompu=<?php echo $row['id_film'] ?>" title="Signaler pb"><img src="http://pix.suiterre.fr/icones/koloria/Warning_2.png" height="16"/></a><?php endif; ?>
	<a style="color:#aaa" href="./admin/?edit=<?php echo $row['id_film'] ?>"  target="_blank" title="Editer"><img src="http://pix.suiterre.fr/icones/koloria/Pencil_2.png" height="16"/></a>
	<input type="range" id="progression" min="0" max="100" value="0" style="width:80%">
</div>
<script>
var MyProject = {};
var titre='<?php echo str_replace("'","\'",$row['nom_film']) ?>';

MyProject.currentAudio = document.getElementById('video');
MyProject.indPlay = 0;

var tps = MyProject.currentAudio;
var currTime=0;
var isLoading=false;
var erreur=false;
var xRefr;

var mm;
$(document).mousemove(function() {
	if(mm) clearTimeout(mm);
	$('#controls').fadeIn(300);
	mm=setTimeout(function(){$('#controls').fadeOut(300);},2000);
});

$('#progression').change(function() {
	tps.currentTime=$(this).val()*tps.duration/100;
});

$('*').wipetouch({
	  tapToClick: true, // if user taps the screen, triggers a click event
	  wipeLeft: function(result) { 	
		tps.currentTime-=30;
	  },
	  wipeRight: function(result) {
		tps.currentTime+=30;
	  },
	  wipeUp: function(result) {
		$('#video').css('object-fit','cover');
	  },
	  wipeDown: function(result) {
		$('#video').css('object-fit','contain');
	  }
	});

function timer() {
	xRefr = setInterval(function() {
		if (!tps)
			return;
			
		var pc=100*tps.currentTime/tps.duration;
		$('title').text(Seconds2Time(tps.duration-tps.currentTime)+' - '+titre);
		$('#progression').val(Math.floor(pc));
		$('#progress').css('width', pc+'%');
		$('#buffer').css('width', (100*(tps.buffered.end(tps.buffered.length-1))/tps.duration)+'%');

		if (currTime > 0 && tps.currentTime ==currTime)
		{
			$('#video').css('opacity',0.5);
			if(erreur==true) {
				//tps.muted=true;
				tps.load();
				tps.play();
				isLoading=true;
				erreur=false;
				var sIn=setInterval(function(){
					if(tps.currentTime>0){
						clearInterval(sIn);
						tps.currentTime = currTime-2; 
						isLoading=false;
						//tps.muted=false
					}
				},1000);	
			} 
		}
		if (currTime > 0 && tps.currentTime !=currTime){
			$('#video').css('opacity',1);
		}
		if (currTime == 0){
			$('#video').css('opacity',0.5);
		}
		
		if (!isLoading) 
			currTime=tps.currentTime;
	}, 300);
}

function Seconds2Time(seconds) {
	time=Math.floor(seconds);
	hour=Math.floor(time/3600); time-=(hour*3600);
	minute=Math.floor(time/60); time-=(minute*60);
	return hour+':'+(minute<10 ? '0':'')+minute+':'+(time<10?'0':'')+time;
}

document.getElementById('video').addEventListener('error', function (e) {
	erreur=true;
});

timer();

$('#video').click(function() {
	if(xRefr!==null)
	 {
		$('#video').css('opacity',0.5);
		clearTimeout(xRefr);
		xRefr=null;
		tps.pause();
	 }
	 else {
		$('#video').css('opacity',1);
		tps.play();
		timer();
	 }
});
$(document).keydown(function( event ) {
	$('#code').text(event.which);
	
	//event.preventDefault();
  if ( event.which == 39 ) { // Flèches
	tps.currentTime+=30;
  }  if ( event.which == 37 ) {
	tps.currentTime-=30;
  }  if ( event.which == 38 ) {
	tps.currentTime+=300;
  }  if ( event.which == 40 ) {
	tps.currentTime-=300;
  }
  
  //4/3 vers 16/9
  if ( event.which == 67 ) { // C
	if($('video.c43').length>0)
		$('video').removeClass('c43').addClass('c34');
	else if($('video.c34').length>0)
		$('video').removeClass('c34');
	else
		$('video').addClass('c43');
  }
  
  //Cover ou pas
  if ( event.which == 71 ) { // G
	if($('#video').css('object-fit')=='cover')
		$('#video').css('object-fit','contain');
	else
		$('#video').css('object-fit','cover');
  }
  
  //fullscreen
  if ( event.which == 70 ) { // F
	vid=document.getElementById('video');
	if(vid.fullscreenElement) {
		backFromFullScreen();
	}else {
		goToFullScreen();
	}
  }
  
  
  
  //Lecture/pause
  if ( event.which == 32 || event.which==179 ) {
	 if(xRefr!==null)
	 {
		$('#video').css('opacity',0.5);
		clearTimeout(xRefr);
		xRefr=null;
		tps.pause();
	 }
	 else {
		$('#video').css('opacity',1);
		tps.play();
		timer();
	 }
  }
  
  //Précédent / Suivant
   if ( event.which == 177) {
	location.href=$('#prev').attr('href');
   }
   if ( event.which == 176) {
	location.href=$('#next').attr('href');
   }
});

function goToFullScreen(){
	vid=document.getElementById('video');
	if(vid.exitFullscreen){
			vid.exitFullscreen();
		} else if(vid.mozCancelFullScreen){
			vid.mozCancelFullScreen();
		} else if(vid.webkitExitFullscreen){
			vid.webkitExitFullscreen();
		}
  }
  
  function backFromFullScreen() {
	vid=document.getElementById('video');
	if(vid.requestFullScreen){
			vid.requestFullScreen();
		} else if(vid.webkitRequestFullScreen){
			vid.webkitRequestFullScreen();
		} else if(vid.mozRequestFullScreen){
			vid.mozRequestFullScreen();
		}
  }

</script>
</body>
</html>
<?php

CloseCOnnection();