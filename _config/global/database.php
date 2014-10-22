<?php

global $con;

session_start();

$sess = session_encode();
//var_dump($sess);
session_write_close();
session_start();
session_decode($sess);

function VerifierAdresseMail($adresse) {
    $Syntaxe = '#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#';

    if (preg_match($Syntaxe, $adresse))
        return true;
    else
        return false;
}

function generatePassword($length = 9, $strength = 0) {
    $vowels = 'aeuy';
    $consonants = 'bdghjmnpqrstvz';

    if ($strength & 1) {
        $consonants .= 'BDGHJLMNPQRSTVWXZ';
    }

    if ($strength & 2) {
        $vowels .= "AEUY";
    }

    if ($strength & 4) {
        $consonants .= '23456789';
    }

    if ($strength & 8) {
        $consonants .= '@#$%';
    }
    $password = '';
    $alt = time() % 2;

    for ($i = 0; $i < $length; $i++) {

        if ($alt == 1) {

            $password .= $consonants[(rand() % strlen($consonants))];

            $alt = 0;
        } else {

            $password .= $vowels[(rand() % strlen($vowels))];

            $alt = 1;
        }
    }

    return $password;
}

/* * *******************

 *

 * DATABASE CONNECTION

 *

 * ******************** */

function SaveVisiteur($mobile = 0) {

    $ip = getenv("REMOTE_ADDR");

    $date = date('Y-m-d');

    if ($ip) {

        $query = "SELECT * FROM visiteurs WHERE ip='" . $ip . "' AND date='" . $date . "' AND depuis_mobile=" . $mobile;

        $result = mysql_query($query);

        if (mysql_num_rows($result) == 0) {

            $query = "INSERT INTO visiteurs (ip,date,depuis_mobile) VALUES ('" . $ip . "','" . $date . "'," . $mobile . ")";

            $result = mysql_query($query);
        }

        $query = "UPDATE visiteurs SET pages_vues=pages_vues+1 WHERE ip='" . $ip . "' AND date='" . $date . "' AND depuis_mobile=" . $mobile;

        $result = mysql_query($query);
    }
}

function GetReferer() {

    if (isset($_SERVER['HTTP_REFERER'])) {

        $ref = parse_url($_SERVER['HTTP_REFERER']);

        $host = str_replace('www.', '', $ref['host']);

        //echo 'Vous arrivez de <b>'.$host.'</b><hr/><br/>';



        $query = "SELECT * FROM liens WHERE url='" . $host . "'";

        $result = mysql_query($query);

        if (mysql_num_rows($result) > 0) {

            while ($row = mysql_fetch_array($result)) {

                $url = $row['id_lien'];

                $query = "UPDATE liens SET clicks_depuis_url=clicks_depuis_url+1 WHERE id_lien=" . $url;

                $result = mysql_query($query);

                break;
            }
        } else {

            $query = "INSERT INTO liens(url,clicks_depuis_url)

				VALUES ('" . $host . "',1)";

            $result = mysql_query($query);
        }
    }
}

function CloseConnection() {



    global $con;

    if ($con) {
        $result = mysql_query("SHOW FULL PROCESSLIST");
        while ($row = mysql_fetch_array($result)) {
            $process_id = $row["Id"];
            if ($row["Time"] > 200) {
                $sql = "KILL $process_id";
                mysql_query($sql);
            }
        }

        @mysql_close($con);
    }
}

function LogIn($table = 'comptes', $id = 'id_compte', $email_field = 'email', $pass_field = 'password') {

    $email = '';

    $pass = '';



    if (isset($_POST['log_email'])) {

        $email = $_POST['log_email'];



        if (isset($_POST['log_pass'])) {

            $pass = $_POST['log_pass'];

            $query = "SELECT * FROM " . $table . " WHERE " . $email_field . "='" . $email . "' and " . $pass_field . "='" . md5($pass) . "'";



            //echo $query;



            $result = mysql_query($query);



            while ($row = mysql_fetch_array($result)) {

                $_SESSION['id_visiteur'] = $row[$id];
            }
        }
    }
}

function LogOut() {



    if (isset($_GET['logout'])) {

        unset($_SESSION['id_visiteur']);

        header('Location:/index.php');
    }
}

function TestLogged($type = '', $mobile = false, $dbCompte = 'comptes', $inscription = '/compte/inscription.php') {



    if (!isset($_SESSION['id_visiteur'])) {

        echo '<div class="form">Cette page n\'est accessible que depuis un compte.<br/>Merci de vous identifier.<br/>

            <form name="login" method="post">

            <hr/>

            <b>Email : </b><input type="text" name="log_email" /><br/>

            <b>Mot de passe : </b><input type="password" name="log_pass" /><br/>

                <input type="submit" value="S\'identifier" /><br/>';



        if ($mobile) {

            echo '   <a href="/m' . $inscription . '">Inscription</a> /

                <a href="/m/compte/recup.php">Mot de passe perdu ?</a>';
        } else {

            echo '   <a href="' . $inscription . '">Je ne suis pas encore inscrit</a> /

                <a href="./compte/recup.php">Mot de passe perdu ?</a>';
        }

        echo '<br/>
        
        		<div class="fb-login-button" onlogin="javascript:CallAfterLogin();" size="medium" scope="email,manage_notifications">
        		Connectez-vous avec Facebook
        		</div>
        	</form><hr/>

            <a href="javascript:history.go(-1);">Revenir &agrave; la page pr&eacute;c&eacute;dente</a>

			</div>';

        return false;
    } else {

        $ok = true;



        if ($type) {

            $ok = false;

            $query = "SELECT type_compte FROM $dbCompte WHERE id_compte=" . $_SESSION['id_visiteur'];

            $result = mysql_query($query);



            if ($result) {



                while ($row = mysql_fetch_array($result)) {



                    if ($row['type_compte'] == $type) {

                        $ok = true;
                    }

                    break;
                }
            }
        }



        if ($ok == false) {



            if ($type == 'PAR')
                $type = 'Particulier';



            if ($type == 'PRO')
                $type = 'Professionnel';

            if ($type == 'USR')
                $type = 'Utilisateur';



            if ($type == 'PRA')
                $type = 'Praticien';

            if ($type == 'ADM')
                $type = 'Administrateur';

            echo 'Cette page n\'est accessible que depuis un compte de type <b>' . $type . '</b>.<br/>

                <a href="javascript:history.go(-1);">Revenir &agrave; la page pr&eacute;c&eacute;dente</a>';

            return false;
        }
    }

    return true;
}