
<?php 
    require '../views/header.php';

/* RECUP INFOS DE L'EVENT*/
    $sql_get_events_infos =  "SELECT * FROM event_base WHERE id = '" . $_GET['id'] . "'";
    //echo $sql_get_events_infos;
    $events_infos = exec_sql_fetch($sql_get_events_infos);
    foreach ($events_infos as $event_infos) {}
    //pre_print_r( $event_infos );


/* REDIRECTION SI ON NE TROUVE PAS D'EVENT */
    if (is_numeric($_GET['id']) === false || count($events_infos) === 0) {
        $_GET['id'] = '';
    }

    if (!isset($_GET['id']) || $_GET['id'] === '') { // si il n'y à pas d'id dans le $_GET => error404
        header('location: /inscription_sport/public/404.php');
    }


/* FORMATAGE DES DATES */
    $jour_semaine = array('Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi');
    $jours_dmY = (new DateTime($event_infos['start']))->format('d/m/Y');
    $jours_w = (new DateTime($event_infos['start']))->format('w');
    $jours_fr = $jour_semaine[$jours_w];


/* RECUP INFOS SU LIEU (GYM) */
    $sql_get_gym_infos =  "SELECT * FROM gym_base WHERE id = '" . $event_infos['gym_id'] . "'";
    //echo $sql_get_gym_infos;
    $gyms_infos = exec_sql_fetch($sql_get_gym_infos);
    foreach ($gyms_infos as $gym_infos) {}
    //pre_print_r( $gym_infos );


/* RECUP TOUT LES INSCRIS*/
    $sql_get_all_registered =  'SELECT p.id as p_id, u.forename as u_forename, u.lastname as u_lastname, s.name as s_name , p.registeredon as p_registeredon
                                FROM participation_base p
                                INNER JOIN user_base u ON p.user_id = u.id
                                INNER JOIN event_base e ON p.event_id = e.id
                                INNER JOIN society_base s ON s.id = u.society_id
                                WHERE e.id = ' . $_GET['id'] . ' ORDER BY p_registeredon ASC LIMIT ' . $event_infos['registrationlimit'];
    //echo $sql_get_all_registered;
    $all_registered = exec_sql_fetch($sql_get_all_registered);
    //pre_print_r( $all_registered );


/* RECUP TOUT LES PARTICIPANTS EN FILE D'ATTENTE*/
    $sql_get_queue_members =  "SELECT p.id as p_id, u.forename as u_forename, u.lastname as u_lastname, s.name as s_name , p.registeredon as p_registeredon
                                FROM participation_base p
                                INNER JOIN user_base u ON p.user_id = u.id
                                INNER JOIN event_base e ON p.event_id = e.id
                                INNER JOIN society_base s ON s.id = u.society_id
                                WHERE e.id = '" . $_GET['id'] . "' ORDER BY p_registeredon ASC LIMIT " . $event_infos['queuelimit'] . " OFFSET " . $event_infos['registrationlimit'];
    //echo $sql_get_queue_members;
    $queue_members = exec_sql_fetch($sql_get_queue_members);
    //pre_print_r( $queue_members );


/* User connecté dans l'evenement */
    $sql_users_in_event =  "SELECT * FROM participation_base WHERE event_id = '" . $_GET['id'] . "' AND user_id = '" . $_SESSION['userid'] . "'";
    //echo $sql_users_in_event;

    $pdo = get_pdo();
    $pdoStat = $pdo->prepare ($sql_users_in_event);
    $executeIsOK = $pdoStat->execute();
    $users_in_event = $pdoStat->fetchAll();
    /*
    echo "<pre>";
    print_r($users_in_event);
    echo "</pre>";
    */


    $bool_user_in_event = false;
    if (count($users_in_event) > 0) {
        $bool_user_in_event = true;
    } else {
        $bool_user_in_event = false;
    }
    //echo $bool_user_in_event;

?>

<title><?= $event_infos['name']; ?> - Séances de sport</title>

<!-- GAUCHE -->
<div class="col" style="width: 50%; height: 100%; float: left; text-align: center;">

    <!-- INFOS DE L'EVENEMENT -->
    <h1 class="h1pdt5"><?= $event_infos['name']; ?></h1>
    <br>
    <div class="container" style="width: 60%;">
        <div class="row" style="margin-bottom: 1%; border-bottom: 1px solid #CCC;">
            <div class="col-sm bg-danger text-white" style="padding: 1%; margin: auto; font-weight: bold;">
                Date
            </div>
            <div class="col-sm bg-light" style="padding: 1%; margin: auto;">
                <?= $jours_fr . ' ' . $jours_dmY; ?>
            </div>
        </div>

        <div class="row" style="margin-bottom: 1%; border-bottom: 1px solid #CCC;">
            <div class="col-sm bg-danger text-white" style="padding: 1%; margin: auto; font-weight: bold;">
                Heure de début
            </div>
            <div class="col-sm bg-light" style="padding: 1%; margin: auto;">
                <?= (new DateTime($event_infos['start']))->format('H:i'); ?>
            </div>
        </div>

        <div class="row" style="margin-bottom: 1%; border-bottom: 1px solid #CCC;">
            <div class="col-sm bg-danger text-white" style="padding: 1%; margin: auto; font-weight: bold;">
                Heure de fin
            </div>
            <div class="col-sm bg-light" style="padding: 1%; margin: auto;">
                <?= (new DateTime($event_infos['end']))->format('H:i'); ?>
            </div>
        </div>

        <div class="row" style="margin-bottom: 1%; border-bottom: 1px solid #CCC;">
            <div class="col-sm bg-danger text-white" style="padding: 1%; margin: auto; font-weight: bold;">
                Lieu
            </div>
            <div class="col-sm bg-light" style="padding: 1%; margin: auto;">
                <?= $gym_infos['name'] ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm bg-danger text-white" style="padding: 1%; margin: auto; font-weight: bold;">
                Description
            </div>
        </div>
        <div class="row" style="margin-bottom: 1%; border-bottom: 1px solid #CCC;">
            <div class="col-sm bg-light" style="padding: 1%; margin: auto;">
                <?= nl2br( $event_infos['description'] ); ?>
            </div>
        </div>

    </div>

    <!-- INFOS DU COACH -->

    <?php 
        $fiche_coach_link = '../views/fiches_coach/uid_' . $event_infos['coach_id'] . '.php';
        include $fiche_coach_link;
    ?>


</div>

<!-- DROITE -->
<div class="col" style="width: 50%; height: 100%; float: left; text-align: center;">

<!-- LISTE DES INSCRIS -->
    <h1 class="h1pdt5">Liste des inscris</h1>
    <br>
    <div class="container">
        <div class="row" style="margin-bottom: 1%; font-weight: bold; text-align: center; ">
            <div class="col-sm bg-danger text-white" style="padding: 1%;">
                Participant
            </div>
            <div class="col-sm bg-danger text-white" style="padding: 1%;">
                Agence
            </div>
            <div class="col-sm bg-danger text-white" style="padding: 1%;">
                Date d'inscription
            </div>
        </div>

        <?php if ( count( $all_registered ) > 0 ) {
            foreach ($all_registered as $registered): ?>
            <div class="row" style="text-align: left; color: black; margin-bottom: 1%; padding: 1%; border-bottom: 1px solid #CCC;">
                <div class="col-sm">
                    <?= $registered['u_forename'] . " " . strtoupper($registered['u_lastname']); ?>
                </div>
                <div class="col-sm">
                    <?= $registered['s_name']; ?>
                </div>
                <div class="col-sm">
                    <?= (new DateTime($registered['p_registeredon']))->format('d/m/Y H:i'); ?>
                </div>
            </div>
        <?php endforeach; } else { ?>
            <div class="row" style="margin-bottom: 1%;">
                <div class="col-sm" style="padding: 1%;">
                    Soyez le premier participant
                </div>
            </div>
        <?php } // fin du "if ( count( $all_registered ) > 0 )"?>
    </div>

    <br>

<!-- LISTE D'ATTENTE -->
    <?php if (count($queue_members) > 0) { // affiche la liste d'attente si il y à des membres dedans?>
    <h1 class="h1pdt5">Liste d'attente</h1>
    <br>
    <div class="container">
        <div class="row" style="margin-bottom: 1%; font-weight: bold; text-align: center; ">
            <div class="col-sm bg-dark text-white" style="padding: 1%;">
                Participant
            </div>
            <div class="col-sm bg-dark text-white" style="padding: 1%;">
                Agence
            </div>
            <div class="col-sm bg-dark text-white" style="padding: 1%;">
                Date d'inscription
            </div>
        </div>

        <?php foreach ($queue_members as $queue_member): ?>
            <div class="row" style="text-align: left; color: black; margin-bottom: 1%; padding: 1%; border-bottom: 1px solid #CCC;">
            <div class="col-sm">
                    <?= $queue_member['u_forename'] . " " . strtoupper($queue_member['u_lastname']); ?>
                </div>
                <div class="col-sm">
                    <?= $queue_member['s_name']; ?>
                </div>
                <div class="col-sm">
                    <?= (new DateTime($queue_member['p_registeredon']))->format('d/m/Y H:i'); ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php } ?>


<!-- BOUTONS -->
    <br>
    <div class="container">
    <?php if ($bool_user_in_event !== true) { //Si l'UserSession est pas dans l'event -> Afficher le bouton "S'inscrire" ?>

        <div class="row" style="margin-bottom: 1%; font-weight: bold; text-align: center;">
            <div class="col-sm">
                <form action="event.php?id=<?= $_GET['id'] ?>" method="POST">
                    <button name="action" value="register" style="width: 100%;" class="btn btn-outline-success" type="submit">S'inscrire</button>
                </form>
            </div>
        </div>
        <p><?= $event_infos['registrationlimit'] - count($all_registered)?> disponible(s)</p>

    <?php } else { //Si l'UserSession est dans l'event -> Afficher le bouton "Se desinscrire"?>

        <div class="row" style="margin-bottom: 1%; font-weight: bold; text-align: center;">
            <div class="col-sm">
                <form action="" method="POST">
                    <button name="action" value="unregister" style="width: 100%;" class="btn btn-outline-danger" type="submit">Se désinscrire</button>
                </form>
            </div>
        </div>

        <div class="row" style="margin-bottom: 1%; font-weight: bold; text-align: center;">
            <div class="col-sm">
                <button style="width: 100%;" class="btn btn-outline-secondary" type="submit">Envoyer sur mon calendrier Outlook</button>
            </div>
        </div>

    <?php }?>
    </div>


<?php

/*
    echo $bool_user_in_event;
    pre_print_r( $_POST );
*/

/* INSCRIPTION A L'EVENT */
if (isset($_POST['action']) && $_POST['action'] === 'register')
{
    $current_DateTime = date('Y-m-d H:i:s');
    //echo $current_DateTime;

    $sql_register = "INSERT INTO participation_base (user_id, event_id, registeredon) VALUES ('" . $_SESSION['userid'] . "', '" . $_GET['id'] . "', '" . $current_DateTime . "')";
    //echo $sql_register;
    exec_sql($sql_register);

    require '../views/loading_wheel.php';
    header('Refresh: 1');
}


/* DESINSCRIPTION A L'EVENT */
if (isset($_POST['action']) && $_POST['action'] === 'unregister')
{
    $sql_unregister = "DELETE FROM participation_base WHERE user_id = '" . $_SESSION['userid'] . "' AND event_id = '" . $_GET['id'] . "'";
    //echo $sql_unregister;
    exec_sql($sql_unregister);

    require '../views/loading_wheel.php';
    header('Refresh: 1');
}


?>


<?php if ( userIsAdminOrCoach() === true ) { ?>
    <div class="row" style="margin-bottom: 1%; font-weight: bold; text-align: center;">
        <div class="col-sm">
        <br>
            <a href="edit_event?id=<?= $_GET['id']; ?>"><button  class="btn btn-outline-warning" type="submit">Modifier cette séance</button></a>
        </div>
    </div>
<?php } ?>

</div>

<?php

    require '../views/footer.php';
?>