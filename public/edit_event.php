<?php 
    require '../views/header.php';

    userIsAdminOrCoach_redirect();

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

/* Formatage des dates */
    $event_date = substr( $event_infos['start'], 0, 10);
    $start_h = substr( $event_infos['start'], 11, 5);
    $end_h = substr( $event_infos['end'], 11, 5);


/* RECUP INFOS DU GYM */
    $sql_get_gym_infos =  "SELECT * FROM gym_base WHERE id = '" . $event_infos['gym_id'] . "'";
    //echo $sql_get_gym_infos;
    $gyms_infos = exec_sql_fetch($sql_get_gym_infos);
    foreach ($gyms_infos as $gym_infos) {}
    //pre_print_r( $gym_infos );


/* RECUP INFOS DU COACH */
    $sql_get_coachs_infos =  "SELECT u.id, u.forename, u.lastname FROM user_base u
                            INNER JOIN event_base e ON e.coach_id = u.id
                            WHERE u.id = '" . $event_infos['coach_id'] . "' AND e.id = '" . $_GET['id'] . "'";
    //echo $sql_get_coachs_infos;
    $coachs_infos = exec_sql_fetch($sql_get_coachs_infos);
    foreach ($coachs_infos as $coach_infos) {}
    //pre_print_r( $coachs_infos );
    $coach_fullname = $coach_infos['forename'] . " " . strtoupper( $coach_infos['lastname'] );


/* Récupération des gyms */
    $sql_get_gyms = "SELECT * FROM gym_base WHERE id <> '" . $event_infos['gym_id'] . "'";
    //echo $sql_get_gyms;
    $gyms = exec_sql_fetch($sql_get_gyms);
    //pre_print_r( $gyms );


/* Récupération des coach */
    $sql_get_coachs = "SELECT * FROM user_base WHERE role_id = 2 AND id <> '" . $event_infos['coach_id'] . "'";
    //echo $sql_get_coachs;
    $coachs = exec_sql_fetch($sql_get_coachs);
    //pre_print_r( $coachs );


/* Nombre d'inscris dans la séance */
    $sql_get_all_RegisteredByEvents =  "SELECT COUNT(*) FROM participation_base WHERE event_id = '" . $_GET['id'] . "'";
    //echo $sql_get_all_RegisteredByEvents;

    $all_registered_byevent = exec_sql_fetch($sql_get_all_RegisteredByEvents);
    //pre_print_r( $all_registered_byevent );

    foreach($all_registered_byevent as $participations_count) { $participations_count = $participations_count['COUNT(*)']; }
    //echo $participations_count;


/* RECUP TOUT LES INSCRIS*/
    $sql_get_all_registered =  "SELECT u.id as user_id, p.id as p_id, u.forename as u_forename, u.lastname as u_lastname, s.name as s_name , p.registeredon as p_registeredon
                                FROM participation_base p
                                INNER JOIN user_base u ON p.user_id = u.id
                                INNER JOIN event_base e ON p.event_id = e.id
                                INNER JOIN society_base s ON s.id = u.society_id
                                WHERE e.id = '" . $_GET['id'] . "' ORDER BY p_registeredon ASC";
    //echo $sql_get_all_registered;
    $all_registered = exec_sql_fetch($sql_get_all_registered);
    //pre_print_r( $all_registered );
?>









<div style="text-align: center;">
    <h1 class="h1pdt5">Modification d'une séance de sport</h1>
    <br>

    <div class="container">

        <div class="row" style="margin-bottom: 1%; border-bottom: 1px solid #CCC;">
            <div class="col-sm bg-danger text-white" style="padding: 1%; margin: auto;">
                Titre
            </div>
            <div class="col-sm" style="padding: 1%;">
                <form style="margin: 0px;" style="justify-content: center; padding: 2%;" action="edit_event.php?id=<?= $_GET['id']; ?>" method="POST">
                    <input class="form-control" type="text" name="name" value="<?= $event_infos['name']; ?>" >
            </div>
        </div>

        <div class="row" style="margin-bottom: 1%; border-bottom: 1px solid #CCC;">
            <div class="col-sm bg-danger text-white" style="padding: 1%; margin: auto;">
                Date
            </div>
            <div class="col-sm" style="padding: 1%;">
                <input class="form-control" name="date" type="date" value="<?= $event_date; ?>" id="example-datetime-local-input">
            </div>
            <div class="col-sm bg-danger text-white" style="padding: 1%; margin: auto;">
                Heure de début
            </div>
            <div class="col-sm" style="padding: 1%;">
               <input class="form-control"  type="time" name="start_h" value="<?= $start_h; ?>" placeholder="de" >
            </div>

            <div class="col-sm bg-danger text-white" style="padding: 1%; margin: auto; height: 100%;">
                Heure de fin
            </div>
            <div class="col-sm" style="padding: 1%;">
               <input class="form-control"  type="time" name="end_h" value="<?= $end_h; ?>" placeholder="HH:mm" >
            </div>
        </div>


        <div class="row" style="margin-bottom: 1%; border-bottom: 1px solid #CCC;">
            <div class="col-sm bg-danger text-white" style="padding: 1%; margin: auto;">
                Coach
            </div>
            <div class="col-sm" style="padding: 1%;">
                <select name="coach_id" class="custom-select" id="inputGroupSelect01">
                    <option selected><?= $coach_fullname; ?></option>
                    <?php foreach ($coachs as $coach) { ?>
                        <option value="<?= $coach['id']; ?>"><?= $coach['forename']; ?> <?= $coach['lastname']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-sm bg-danger text-white" style="padding: 1%; margin: auto;">
                Lieu
            </div>
            <div class="col-sm" style="padding: 1%;">
                <select name="gym_id" class="custom-select" id="inputGroupSelect01">
                    <option selected><?= $gym_infos['name']; ?></option>
                    <?php foreach ($gyms as $gym) { ?>
                        <option value="<?= $gym['id']; ?>"><?= $gym['name']; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="row" style="margin-bottom: 1%; border-bottom: 1px solid #CCC;">
            <div class="col-sm bg-danger text-white" style="padding: 1%; margin: auto;">
                Nombre limite d'inscris
            </div>
            <div class="col-sm" style="padding: 1%;">
                <input class="form-control" type="number" name="registrationlimit" value="<?= $event_infos['registrationlimit']; ?>" placeholder="Nombre entier" >
            </div>
            <div class="col-sm bg-danger text-white" style="padding: 1%; margin: auto;">
                Nombre limite liste d'attente
            </div>
            <div class="col-sm" style="padding: 1%;">
               <input class="form-control"  type="number" name="queuelimit" value="<?= $event_infos['queuelimit']; ?>" placeholder="Nombre entier" >
            </div>
        </div>


        <div class="row" style="margin-bottom: 1%; border-bottom: 1px solid #CCC;">
            <div class="col-sm bg-danger text-white" style="padding: 1%; margin: auto;">
                Description
            </div>
            <div class="col-sm" style="padding: 1%;">
                <textarea class="form-control" id="exampleFormControlTextarea1" rows="1" name="description"><?=  $event_infos['description']; ?></textarea>
            </div>
        </div>

        <div class="row" style="margin-bottom: 1%;">
            <button style="width: 100%;" class="btn btn-outline-success" type="submit">Modifier la séance de sport</button>
            </form>
        </div>

    
    







<?php

    if ( ( isset( $_POST['date'] ) && $_POST['date'] !== $event_date ) 
        || ( isset( $_POST['start_h'] ) && $_POST['start_h'] !== $start_h )
        || ( isset( $_POST['end_h'] ) && $_POST['end_h'] !== $end_h )
        || ( isset( $_POST['name'] ) && $_POST['name'] !== $event_infos['name'] )
        || ( isset( $_POST['gym_id'] ) && $_POST['gym_id'] !== $gym_infos['name'] )
        || ( isset( $_POST['coach_id'] ) && $_POST['coach_id'] !== $coach_fullname )
        || ( isset( $_POST['description'] ) && $_POST['description'] !== $event_infos['description'] )
        || ( isset( $_POST['registrationlimit'] ) && $_POST['registrationlimit'] !== $event_infos['registrationlimit'] )
        || ( isset( $_POST['queuelimit'] ) && $_POST['queuelimit'] !== $event_infos['queuelimit'] ) ) {



        if ( $_POST['date'] !== $event_date  || $_POST['start_h'] !== $start_h ) {

            $event_start = $_POST['date'] . " " . $_POST['start_h'];
            $sql_new_start = "UPDATE event_base SET start = '" . $event_start . "' WHERE id = '" . $_GET['id'] . "'";
            //echo $sql_new_start;
            exec_sql($sql_new_start);
        }

        if ( $_POST['end_h'] !== $end_h ) {
            $event_end = $_POST['date'] . " " . $_POST['end_h'];
            $sql_new_end = "UPDATE event_base SET end = '" . $event_end . "' WHERE id = '" . $_GET['id'] . "'";
            //echo $sql_new_end;
            exec_sql($sql_new_end);
        }

        if ( $_POST['name'] !== $event_infos['name'] ) {
            $sql_new_name = "UPDATE event_base SET name = '" . $_POST['name'] . "' WHERE id = '" . $_GET['id'] . "'";
            //echo $sql_new_name;
            exec_sql($sql_new_name);
        }

        if ( $_POST['gym_id'] !== $gym_infos['name'] ) {
            $sql_new_gym = "UPDATE event_base SET gym_id = '" . $_POST['gym_id'] . "' WHERE id = '" . $_GET['id'] . "'";
            //echo $sql_new_gym;
            exec_sql($sql_new_gym);
        }

        if ( $_POST['coach_id'] !== $coach_fullname ) {
            $sql_new_coach = "UPDATE event_base SET coach_id = '" . $_POST['coach_id'] . "' WHERE id = '" . $_GET['id'] . "'";
            //echo $sql_new_coach;
            exec_sql($sql_new_coach);
        }

        if ( $_POST['description'] !== $event_infos['description'] ) {
            $sql_new_description = "UPDATE event_base SET description = '" . $_POST['description'] . "' WHERE id = '" . $_GET['id'] . "'";
            //echo $sql_new_description;
            exec_sql($sql_new_description);
        }

        if ( $_POST['registrationlimit'] !== $event_infos['registrationlimit'] ) {
            $sql_new_registrationlimit = "UPDATE event_base SET registrationlimit = '" . $_POST['registrationlimit'] . "' WHERE id = '" . $_GET['id'] . "'";
            //echo $sql_new_registrationlimit;
            exec_sql($sql_new_registrationlimit);
        }

        if ( $_POST['queuelimit'] !== $event_infos['queuelimit'] ) {
            $sql_new_queuelimit = "UPDATE event_base SET queuelimit = '" . $_POST['queuelimit'] . "' WHERE id = '" . $_GET['id'] . "'";
            //echo $sql_new_queuelimit;
            exec_sql($sql_new_queuelimit);
        }

        echo '<div class="alert alert-success" role="alert">Séance mise à jour avec succès !</div>';
        require '../views/loading_wheel.php';

        header('Refresh: 2 URL=event.php?id=' . $_GET['id']);

    }


?>










<!-- Ajouter un participant -->
<?php
    $total_registered_in_event = $event_infos['queuelimit'] + $event_infos['registrationlimit'];
    
    /*
    echo "total_registered_in_event : " . $total_registered_in_event;
    echo "<br>";
    echo "participations_count : " . $participations_count;
    */

    if ( $participations_count < $total_registered_in_event ) {
?>


<div style="text-align: center;">
    <h1 id="add_to_event" class="h1pdt5">Ajouter un participant</h1>
    <br>

    <form class="form-inline" style="justify-content: center; padding: 2%;" action="edit_event.php?id=<?= $_GET['id']; ?>#add_to_event" method="POST">
        <input style="width: 60%;" class="form-control mr-sm-2" type="text" name="search" value="" placeholder="Rechercher un collaborateur">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Rechercher</button>
    </form>



<?php

    if ( isset( $_POST['search'] ) ) {

        $get_search = $_POST['search'];

        $sql_search_users = "SELECT u.id as user_id, u.forename as u_forename, u.lastname as u_lastname, u.email as u_email, s.name as s_name, r.name as r_name
                            FROM user_base u
                            INNER JOIN society_base s ON s.id = u.society_id
                            INNER JOIN role_base r ON r.id = u.role_id
                            WHERE username like '%" . $get_search . "%' OR forename like '%" . $get_search . "%' OR lastname like '%" . $get_search . "%' ORDER BY forename";
        //echo $sql_search_users;
        $searched_users = exec_sql_fetch($sql_search_users);
        //pre_print_r( $searched_users );

?>


    <div class="row" style="margin-bottom: 1%; font-weight: bold; text-align: center; ">
        <div class="col-sm bg-danger text-white" style="padding: 1%;">
            Nom complet
        </div>
        <div class="col-sm bg-danger text-white" style="padding: 1%;">
            Agence
        </div>
        <div class="col-sm bg-danger text-white" style="padding: 1%;"> </div>
    </div>

    <?php foreach ($searched_users as $searched_user): ?>
        <div class="row" style="text-align: left; color: black; margin-bottom: 1%; padding: 1%; border-bottom: 1px solid #CCC;">
            <div class="col-sm">
                <?= $searched_user['u_forename'] . " " . strtoupper($searched_user['u_lastname']); ?>
            </div>
            <div class="col-sm">
                <?= $searched_user['s_name']; ?>
            </div>

            <div class="col-sm">
                <form style="margin: 0; " action="edit_event.php?id=<?= $_GET['id']; ?>#add_to_event" method="POST">
                    <button style="width: 100%;" class="btn btn-outline-success" name="add_to_event" value="<?= $searched_user['user_id']; ?>" type="submit">Ajouter</button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>

    </div>
</div>

<?php
    } // fin du "if ( isset( $_POST['search'] ) )"
} // fin du "if ( $participations_count < $total_registered_in_event )"

    //pre_print_r($_POST);

    if ( isset( $_POST['add_to_event'] ) ) {

        $current_DateTime = date('Y-m-d H:i:s');
        //echo $current_DateTime;

        $sql_add_to_event = "INSERT INTO participation_base (user_id, event_id, registeredon) VALUES ('" . $_POST['add_to_event'] . "', '" . $_GET['id'] . "', '" . $current_DateTime . "')";
        //echo $sql_add_to_event;
        exec_sql($sql_add_to_event);

        echo '<div class="alert alert-success" role="alert">Participant ajouté avec succès !</div>';
        require '../views/loading_wheel.php';

        header('Refresh: 2 URL=event.php?id=' . $_GET['id']);

    }

?>








<!-- LISTE DES INSCRIS -->
<?php if ( $participations_count > 0 ) { ?>
<h1 id="delete_from_event" class="h1pdt5">Retirer un participant</h1>
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
            <div class="col-sm bg-danger text-white" style="padding: 1%;"> </div>
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
                <div class="col-sm">
                <form style="margin: 0; " action="edit_event.php?id=<?= $_GET['id']; ?>#delete_from_event" method="POST">
                    <button style="width: 100%;" class="btn btn-outline-danger" name="delete_from_event" value="<?= $registered['user_id']; ?>" type="submit">Retirer</button>
                </form>
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



<?php

    } // fin du "if ( $participations_count > 0 )"

    //pre_print_r($_POST);

    if ( isset( $_POST['delete_from_event'] ) ) {

        $sql_unregister = "DELETE FROM participation_base WHERE user_id = '" . $_POST['delete_from_event'] . "' AND event_id = '" . $_GET['id'] . "'";
        //echo $sql_unregister;
        exec_sql($sql_unregister);

        echo '<div class="alert alert-success" role="alert">Participant retiré avec succès !</div>';
        require '../views/loading_wheel.php';

        header('Refresh: 2 URL=event.php?id=' . $_GET['id']);

    }
?>


<?php
    require '../views/footer.php';
?>

<title>Modification - Séance de sport</title>