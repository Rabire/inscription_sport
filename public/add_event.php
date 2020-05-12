<?php
    require '../views/header.php';

    userIsAdminOrCoach_redirect();

    /* Récupération des gyms */
    $sql_get_gyms = "SELECT * FROM gym_base";
    $gyms = exec_sql_fetch($sql_get_gyms);
    //pre_print_r( $gyms );

    /* Récupération des coach */
    $sql_get_coachs = "SELECT * FROM user_base WHERE role_id = 2";
    $coachs = exec_sql_fetch($sql_get_coachs);
    //pre_print_r( $coachs );
?>





<div style="text-align: center;">
    <h1 class="h1pdt5">Création d'une séance de sport</h1>
    <br>

    <div class="container">

        <div class="row" style="margin-bottom: 1%; border-bottom: 1px solid #CCC;">
            <div class="col-sm bg-danger text-white" style="padding: 1%; margin: auto;">
                Titre
            </div>
            <div class="col-sm" style="padding: 1%;">
                <form style="margin: 0px;" style="justify-content: center; padding: 2%;" action="add_event.php" method="POST">
                    <input class="form-control" type="text" name="name" value="" placeholder="Titre de la séance de sport" >
            </div>
        </div>

        <div class="row" style="margin-bottom: 1%; border-bottom: 1px solid #CCC;">
            <div class="col-sm bg-danger text-white" style="padding: 1%; margin: auto;">
                Date
            </div>
            <div class="col-sm" style="padding: 1%;">
                <input class="form-control" name="date" type="date" value="2011-08-19T13:00:00" id="example-datetime-local-input">
            </div>
            <div class="col-sm bg-danger text-white" style="padding: 1%; margin: auto;">
                Heure de début
            </div>
            <div class="col-sm" style="padding: 1%;">
               <input class="form-control"  type="time" name="start_h" value="" placeholder="de" >
            </div>

            <div class="col-sm bg-danger text-white" style="padding: 1%; margin: auto; height: 100%;">
                Heure de fin
            </div>
            <div class="col-sm" style="padding: 1%;">
               <input class="form-control"  type="time" name="end_h" value="" placeholder="HH:mm" >
            </div>
        </div>


        <div class="row" style="margin-bottom: 1%; border-bottom: 1px solid #CCC;">
            <div class="col-sm bg-danger text-white" style="padding: 1%; margin: auto;">
                Coach
            </div>
            <div class="col-sm" style="padding: 1%;">
                <select name="coach_id" class="custom-select" id="inputGroupSelect01">
                    <option selected disabled hidden>À définir</option>
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
                    <option selected disabled hidden>À définir</option>
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
                <input class="form-control" type="number" name="registrationlimit" value="10" placeholder="Nombre entier" >
            </div>
            <div class="col-sm bg-danger text-white" style="padding: 1%; margin: auto;">
                Nombre limite liste d'attente
            </div>
            <div class="col-sm" style="padding: 1%;">
               <input class="form-control"  type="number" name="queuelimit" value="10" placeholder="Nombre entier" >
            </div>
        </div>


        <div class="row" style="margin-bottom: 1%; border-bottom: 1px solid #CCC;">
            <div class="col-sm bg-danger text-white" style="padding: 1%; margin: auto;">
                Description
            </div>
            <div class="col-sm" style="padding: 1%;">
                <textarea class="form-control" id="exampleFormControlTextarea1" rows="1" name="description" placeholder="Déscription de la séance"></textarea>
            </div>
        </div>

        <div class="row" style="margin-bottom: 1%;">
            <button style="width: 100%;" class="btn btn-outline-success" type="submit">Créer la séance de sport</button>
            </form>
            <footer class="blockquote-footer">Tous les champs doivent être remplis.</footer>
        </div>

    </div>

</div>
        

<?php

    //pre_print_r($_POST);

    
    if ( (isset($_POST['date']) && $_POST['date'] !== '') 
        && (isset($_POST['start_h']) && $_POST['start_h'] !== '')
        && (isset($_POST['end_h']) && $_POST['end_h'] !== '')
        && (isset($_POST['name']) && $_POST['name'] !== '')
        && (isset($_POST['gym_id']) && $_POST['gym_id'] !== 'À définir')
        && (isset($_POST['coach_id']) && $_POST['coach_id'] !== 'À définir')
        && (isset($_POST['description']) && $_POST['description'] !== '')
        && (isset($_POST['registrationlimit']) && $_POST['registrationlimit'] !== '')
        && (isset($_POST['queuelimit']) && $_POST['queuelimit'] !== '') ) {


        $event_start = $_POST['date'] . " " . $_POST['start_h'];
        $event_end = $_POST['date'] . " " . $_POST['end_h'];

        $sql_insert_event = "INSERT INTO event_base(name, description, start, end, registrationlimit, queuelimit, coach_id, gym_id)
                            VALUES ('" . $_POST['name'] . "', '" . $_POST['description'] . "', '" . $event_start . "', '" . $event_end . "', '" . $_POST['registrationlimit'] . "', '" . $_POST['queuelimit'] . "', '" . $_POST['coach_id'] . "', '" . $_POST['gym_id'] . "')";
        echo $sql_insert_event;

        exec_sql($sql_insert_event);


        echo '<div class="alert alert-success" role="alert">Séance créé avec succès !</div>';
        require '../views/loading_wheel.php';

        header('Refresh: 2');

    }


?>

<?php
    require '../views/footer.php';
?>
<title>Création - Séance de sport</title>