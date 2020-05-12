<title>Calendrier - Séances de sport</title>

<?php
    require '../views/header.php';

?>


<div style="text-align: center;">
    <h1 class="h1pdt5">Veuillez changer de mot de passe</h1>
    <br>

    <div class="container">
    <form action="change_password.php" method="POST">

<!--
        <div class="row" style="margin-bottom: 1%;">
            <div class="col-sm bg-danger text-white" style="padding: 1%; margin: auto;">
                Mot de passe actuel
            </div>

            <div class="col-sm" style="padding: 1%;">
                <div class="col-sm" style="padding: 1%;">
                    <input class="form-control" type="password" name="current_password" value="" placeholder="" >
                </div>
            </div>
        </div>
-->

        <div class="row" style="margin-bottom: 1%;">
            <div class="col-sm bg-danger text-white" style="padding: 1%; margin: auto;">
                Nouveau mot de passe
            </div>

            <div class="col-sm" style="padding: 1%;">
                <div class="col-sm" style="padding: 1%;">
                    <input class="form-control" type="password" name="new_password1" value="" placeholder="" >
                </div>
            </div>
        </div>

        <div class="row" style="margin-bottom: 1%;">
            <div class="col-sm bg-danger text-white" style="padding: 1%; margin: auto;">
                Valider le nouveau mot de passe
            </div>

            <div class="col-sm" style="padding: 1%;">
                <div class="col-sm" style="padding: 1%;">
                    <input class="form-control" type="password" name="new_password2" value="" placeholder="" >
                </div>
            </div>

            <button style="width: 100%;" class="btn btn-outline-success" type="submit">Changer de mot de passe</button>
            <footer class="blockquote-footer">Ne communiquez ce mot de passe à personne.</footer>

        </div>

    </form>


<?php
/*
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
*/

    if ( isset($_POST['new_password1']) && isset($_POST['new_password2'])&& $_POST['new_password1'] !== '' && $_POST['new_password2'] !== '' ) {

        if ( $_POST['new_password1'] === $_POST['new_password2'] ) {
            $new_pwd = $_POST['new_password1'];

            if ( strlen($new_pwd) >= 6 ) {
                $sql_update_pwd = "UPDATE user_base SET isdefaultpassword = '0', password = '" . $_POST['new_password1'] . "' WHERE id = '" . $_SESSION['userid'] . "'";
                //echo $sql_update_pwd;
                exec_sql($sql_update_pwd);
                
                echo '<div class="alert alert-success" role="alert">Mot de passe changé avec succès !</div>';
                
                require '../views/loading_wheel.php';
                header('Refresh: 2; URL=index.php');
            } else {
                echo '<div class="alert alert-danger" role="alert">Le mot de passe est trop court. (6 caractères minimum)</div>';
            }
        } else {
            echo '<div class="alert alert-danger" role="alert">Les mots de passe sont differents.</div>';
        }

    }


?>


</div>
</div>

<?php

    require '../views/footer.php';

?>