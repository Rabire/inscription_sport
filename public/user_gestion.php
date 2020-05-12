<?php
    ob_start();
    require '../views/header.php';

    userIsAdminOrCoach_redirect();

    //pre_print_r( $_GET );

?>

<!-- Search bar -->
<form class="form-inline" style="justify-content: center; padding: 2%;" action="user_gestion.php" method="GET">
    <input style="width: 60%;" class="form-control mr-sm-2" type="text" name="search" value="" placeholder="Rechercher un collaborateur">
    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Rechercher</button>
</form>




<?php

    if (isset($_GET['search'])) {

        $get_search = $_GET['search'];

        $sql_search_users = "SELECT u.id as user_id, u.forename as u_forename, u.lastname as u_lastname, u.email as u_email, s.name as s_name, r.name as r_name
                            FROM user_base u
                            INNER JOIN society_base s ON s.id = u.society_id
                            INNER JOIN role_base r ON r.id = u.role_id
                            WHERE username like '%" . $get_search . "%' OR forename like '%" . $get_search . "%' OR lastname like '%" . $get_search . "%' ORDER BY forename";
        //echo $sql_search_users;
        $searched_users = exec_sql_fetch($sql_search_users);
        //pre_print_r( $searched_users );

?>


<div style="text-align: center;">
    <h1>Selectionnez un utilisateur</h1>
    <br>
    <div class="container">
        <div class="row" style="margin-bottom: 1%; font-weight: bold; text-align: center; ">
            <div class="col-sm bg-danger text-white" style="padding: 1%;">
                Nom complet
            </div>
            <div class="col-sm bg-danger text-white" style="padding: 1%;">
                Agence
            </div>
        </div>

        <?php foreach ($searched_users as $searched_user): ?>
            <a href="user_gestion.php?userid=<?= $searched_user['user_id']; ?>">
            <div class="row" style="text-align: left; color: black; margin-bottom: 1%; padding: 1%; border-bottom: 1px solid #CCC;">
                <div class="col-sm">
                    <?= $searched_user['u_forename'] . " " . strtoupper($searched_user['u_lastname']); ?>
                </div>
                <div class="col-sm">
                    <?= $searched_user['s_name']; ?>
                </div>
            </div>
            </a>
        <?php endforeach; ?>

    </div>
</div>

<?php } //sin du if(isset($_GET['search'])) ?>

















<?php
    if (isset($_GET['userid'])) {
        $searched_user_id = $_GET['userid'];

        $sql_get_userinfos = "SELECT u.id as u_id, u.forename as u_forename, u.lastname as u_lastname, u.email as u_email, s.name as s_name, r.name as r_name
                            FROM user_base u
                            INNER JOIN society_base s ON s.id = u.society_id
                            INNER JOIN role_base r ON r.id = u.role_id
                            WHERE u.id = '" . $searched_user_id . "'";
        //echo $sql_get_userinfos;
        $usersinfos = exec_sql_fetch($sql_get_userinfos);

        foreach ($usersinfos as $userinfos) {}
        //pre_print_r( $userinfos );


        $new_password = substr ( strtolower($userinfos['u_forename'] ) , 0, 3) . ".209";


        $sql_get_societys = "SELECT * FROM society_base ORDER BY name";
        //echo $sql_get_societys;
        $societys = exec_sql_fetch($sql_get_societys);

        //pre_print_r( $societys );

?>

<div style="text-align: center;">
    <h1>Résumé de l'utilisateur</h1>
    <br>

    <div class="container">

        <div class="row" style="margin-bottom: 1%; border-bottom: 1px solid #CCC;">
            <div class="col-sm bg-danger text-white" style="padding: 1%; margin: auto;">
                Prénom
            </div>
            <div class="col-sm" style="padding: 1%; margin: auto;">
                <?= $userinfos['u_forename']; ?>
            </div>
            <div class="col-sm" style="padding: 1%;">
                <form style="margin: 0px;" style="justify-content: center; padding: 2%;" action="user_gestion.php?userid=<?= $_GET['userid']; ?>" method="POST">
                    <input class="form-control" type="text" name="new_forename" value="" placeholder="Nouveau prénom" >
            </div>
        </div>

        <div class="row" style="margin-bottom: 1%; border-bottom: 1px solid #CCC;">
            <div class="col-sm bg-danger text-white" style="padding: 1%; margin: auto;">
                Nom de famille
            </div>
            <div class="col-sm" style="padding: 1%; margin: auto;">
                <?= $userinfos['u_lastname']; ?>
            </div>
            <div class="col-sm" style="padding: 1%;">
                <input class="form-control" type="text" name="new_lastname" value="" placeholder="Nouveau nom de famille" >
            </div>
        </div>

        <div class="row" style="margin-bottom: 1%; border-bottom: 1px solid #CCC;">
            <div class="col-sm bg-danger text-white" style="padding: 1%; margin: auto;">
                Courrier éléctronique
            </div>
            <div class="col-sm" style="padding: 1%; margin: auto;">
                <?= $userinfos['u_email']; ?>
            </div>
            <div class="col-sm" style="padding: 1%;">
               <input class="form-control"  type="text" name="new_email" value="" placeholder="Nouvelle adresse mail" >
            </div>
        </div>

        <div class="row" style="margin-bottom: 1%; border-bottom: 1px solid #CCC;">
            <div class="col-sm bg-danger text-white" style="padding: 1%; margin: auto;">
                Agence
            </div>
            <div class="col-sm" style="padding: 1%; margin: auto;">
                <?= $userinfos['s_name']; ?>
            </div>
            <div class="col-sm" style="padding: 1%;">
                <select name="new_agence" class="custom-select" id="inputGroupSelect01">
                    <option selected>Nouvelle agence</option>
                    <?php foreach ($societys as $society) { ?>
                        <option value="<?= $society['id']; ?>"><?= $society['name']; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="row" style="margin-bottom: 1%; border-bottom: 1px solid #CCC;">
            <div class="col-sm bg-danger text-white" style="padding: 1%; margin: auto;">
                Rôle
            </div>
            <div class="col-sm" style="padding: 1%; margin: auto;">
                <?= $userinfos['r_name']; ?>
            </div>
            <div class="col-sm" style="padding: 1%;">
                <select name="new_role" class="custom-select" id="r_name">
                    <option selected>Nouveau rôle</option>
                    <option value="2">Coach</option>
                    <option value="3">Salarié</option>
                </select>
            </div>
        </div>

        <div class="row" style="margin-bottom: 1%;">
            <button style="width: 100%;" class="btn btn-outline-success" type="submit">Soummetre les modifications</button>
            </form>
        </div>


        <form action="user_gestion.php?userid=<?= $_GET['userid']; ?>" method="POST">
                <button name="action" value="reset_pwd" style="width: 100%;" class="btn btn-outline-secondary" type="submit">Réinitialiser mot de passe (<?= $new_password ?>)</button>
            </form>
            
        



<?php } //fin du if(isset($_GET['userid'])) ?>



<?php
    //pre_print_r($_POST);

    if ( (isset($_POST['new_forename']) && $_POST['new_forename'] !== '') 
        || (isset($_POST['new_lastname']) && $_POST['new_lastname'] !== '')
        || (isset($_POST['new_email']) && $_POST['new_email'] !== '')
        || (isset($_POST['new_agence']) && $_POST['new_agence'] !== 'Nouvelle agence')
        || (isset($_POST['new_role']) && $_POST['new_role'] !== 'Nouveau rôle') ) {

        if (isset($_POST['new_forename']) && $_POST['new_forename'] !== '') {

            $sql_new_forename = "UPDATE user_base SET forename = '" . $_POST['new_forename'] . "' WHERE id = '" . $_GET['userid'] . "'";
            //echo $sql_new_forename;
            exec_sql($sql_new_forename);

        }

        if (isset($_POST['new_lastname']) && $_POST['new_lastname'] !== '') {

            $_POST['new_lastname'] = strtoupper($_POST['new_lastname']);
            $sql_new_lastname = "UPDATE user_base SET lastname = '" . $_POST['new_lastname'] . "' WHERE id = '" . $_GET['userid'] . "'";
            //echo $sql_new_lastname;
            exec_sql($sql_new_lastname);

        }

        if (isset($_POST['new_email']) && $_POST['new_email'] !== '') {

            $sql_new_email = "UPDATE user_base SET email = '" . $_POST['new_email'] . "' WHERE id = '" . $_GET['userid'] . "'";
            //echo $sql_new_email;
            exec_sql($sql_new_email);
        }

        if (isset($_POST['new_agence']) && $_POST['new_agence'] !== 'Nouvelle agence') {
 
            $sql_new_agence = "UPDATE user_base SET society_id = '" . $_POST['new_agence'] . "' WHERE id = '" . $_GET['userid'] . "'";
            //echo $sql_new_agence;
            exec_sql($sql_new_agence);

        }

        if (isset($_POST['new_role']) && $_POST['new_role'] !== 'Nouveau rôle') {
 
            $sql_new_role = "UPDATE user_base SET role_id = '" . $_POST['new_role'] . "' WHERE id = '" . $_GET['userid'] . "'";
            //echo $sql_new_role;
            exec_sql($sql_new_role);

        }


        echo '<div class="alert alert-success" role="alert">Modifications soumises avec succès !</div>';
        require '../views/loading_wheel.php';

        header('Refresh: 1.5');

    }


    if (isset($_POST['action']) && $_POST['action'] === 'reset_pwd') {

        $sql_reset_password = "UPDATE user_base SET password = '" . $new_password . "', isdefaultpassword = 1 WHERE id = '" . $_GET['userid'] . "'";
        //echo $sql_reset_password;
        exec_sql($sql_reset_password);
        
        echo '<div class="alert alert-success" role="alert">Mot de passe réinitialisé avec succès !</div>';
        require '../views/loading_wheel.php';

        header('Refresh: 1.5');

    }


?>

</div>
</div>

<?php
    require '../views/footer.php';

?>
<title>Gestion des utilisateurs</title>
