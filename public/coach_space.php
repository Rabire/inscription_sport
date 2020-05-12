<?php
    require '../views/header.php';

    userIsAdminOrCoach_redirect();

?>

<title>Espace Coach</title>

<div class="container">
    <div class="row" style="margin-top:5%;">

        <a href="coach_space.php" class="col-sm btn btn-danger" style="margin: 1%; padding: 50px 0 50px 0;">
            Acceder à un autre calendrier<br>(à faire)
        </a>

        <a href="user_gestion.php" class="col-sm btn btn-danger" style="margin: 1%; padding: 50px 0 50px 0;">
            Gerer les utilisateurs
        </a>

        <a href="add_event.php" class="col-sm btn btn-danger" style="margin: 1%; padding: 50px 0 50px 0;">
            Ajouter un événement
        </a>

        <a href="coach_space.php" class="col-sm btn btn-danger" style="margin: 1%; padding: 50px 0 50px 0;">
            Gerer les salles de sport<br>(à faire)
        </a>

    </div>
    
</div>


<?php

    require '../views/footer.php';

?>