<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Séances de sport</title>
    <link href="./src/login_menu.less" rel="stylesheet" type="text/css" />
    <link href="./css/css_extranet_login.css" rel="stylesheet"/>
    <link href="./css/login.css" rel="stylesheet"/>
</head>

<?php
    require '../src/php/bootstrap.php';
    session_start(); //in bootstrap.php
    session_destroy(); //in bootstrap.php
?>

<body>

<div class="row"></div>
<div class="col-md-12">
    <div class="logo_xefi" style="height:800px; text-align:center">
        <div class="col-md-6 col-md-offset-3 col-xs-12">
            <img src="https://placehold.it/2173x933" height="20%" width="100%" max-height="20%" max-width="37%" style="margin-top: 10px">
        </div>
    <div class="wrapper">
        <div class="containerLogin">
            <section id="loginForm">

                <form action="http://localhost/inscription_sport/public/login.php" class="form-horizontal" method="post" role="form">

                    <h2 id="connexion">CONNEXION</h2>
                    <hr />

                    <div class="form-group">
                        <label Style="line-height:0.8" class="col-md-4 col-xs-4 control-label" for="UserName">Nom d’utilisateur</label>
                        <div class="col-md-8 col-xs-8">
                            <input Style="max-width: 280px; width:90%" class="form-control" name="username" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label Style="line-height:0.8" class="col-md-4 col-xs-4 control-label" for="Password">Mot de passe</label>
                        <div class="col-md-8 col-xs-8">
                            <input Style="max-width: 280px; width:90%" class="form-control" name="password" type="password" />
                        </div>
                    </div>
                    
                    <br />
                    <div class="form-group">
                        <div class="col-md-offset-1 col-md-10">
                            <input type="submit" value="Connexion" class="btn btn-default" />
                        </div>
                    </div>
                    <br />
                    <p style="font-size:12px">Rapprochez-vous de votre coach si vous ne disposez pas d'accès.</p>
                </form>
            </section>
        </div>
        <ul class="bg-bubbles">
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </ul>
    </div>


</div>

</body>
</html>


<?php 
/*
    //infos renseignées dans le formulaire de login
    echo "<pre>";
    echo "form POST infos ";
    print_r($_POST);
    echo "</pre>";
*/

    if (isset($_POST['username']) && isset($_POST['password'])) {
        $form_username = $_POST['username'];
        $form_password = $_POST['password'];
    } else {
        $form_username = NULL;
        $form_password = NULL;
    }

    $sql_users = "SELECT * FROM user_base WHERE (username = '" . $form_username . "' OR email = '" . $form_username . "') AND password = '" . $form_password . "'"; // requete recup le users
    //echo $sql_users;

    $users = exec_sql_fetch($sql_users);
    //pre_print_r( $users );


    $isconnected = false;

    if(count($users) == 1 ) // si les logins du user existent 
    {
        session_start(); //creation d'un session
        $isconnected = true; // session isconnected = true
        foreach ($users as $user) { $userid = $user['id']; } // recup l'id du user connecté

        $_SESSION['isconnected'] = $isconnected;
        $_SESSION['userid'] = $userid;

    } else {
        $isconnected = false;
        $_SESSION['isconnected'] = $isconnected;
        if (isset($_POST['username'])) {
            echo "Identifiant ou mot de passe incorrect.";
        }
    }

    //pre_print_r( $_SESSION );


    // si l'utilisateur esr connecté, le rediriger vers l'index
    if ($isconnected === true) {
        header('location: /inscription_sport/public/index.php');
    }

    require '../views/footer.php';

?>
