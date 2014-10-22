<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$root=realpath(dirname(__FILE__));
include $root.'/../_config/index.php';
include $root.'/../_config/databases/local.php';
SetConnection();

function setHead($title = 'Media Center') {
    ?>
    <html>
        <head>
            <title><?php echo $title ?></title>
            <meta charset="utf-8"/>
			<meta name="viewport" content="width=480px, user-scalable=no">
			<script src="http://code.jquery.com/jquery.js"></script>
            <style>
                body {
                    font-family:Verdana;
                    font-size:200%;
                    background-image:url(http://cdn.fansided.com/wp-content/blogs.dir/277/files/2013/06/Theater_wallpapers_175.jpg);
                    background-size:cover;
                    background-position: center center;
                    background-repeat: no-repeat;
                    margin:0;
                    height:100%;
                    overflow-y: auto;
                    background-attachment: fixed;
					color:white;
                }

                div {
                    margin:10px;
                    padding:5px;
                    border-radius: 20px;
                    background-color: rgba(0,0,0,0.5);
                    color:white;
                    text-align:center;
                }

                div.affiche {
                    display:inline-block;
                    width:190px;
                    font-size: 18px;
                    vertical-align:top;
                    margin:5px;
					border-top-left-radius:0;
					border-top-right-radius:0;
                }
				
				div.affiche > a:first-child > img {
					width:150px;
					height:200px;
					float:left;
				}
				
				div.affiche div.acteur {
					width:40px;
					height:40px;
					background-position:center center;
					background-size:cover;
					margin:0;
					padding:0;
					border-radius:0;
					float:right;
					cursor:pointer;
				}
				select{width:120px;}
				option[selected] {font-weight:bold;}
                
                div.affiche:hover {
                    box-shadow: 0 0 10px rgba(255,255,255,0.7);
                    background-color:black;
                }
                
                a {
                    color:#ddd;
                }
				tr.titre td {text-align:center;font-size:20px;font-weight:bold;}
            </style>    
        </head>
        <body>
            
            <?php
        }

        function SetFoot() {
            ?>
        </body>
    </html>
    <?php
    CloseConnection();
}
