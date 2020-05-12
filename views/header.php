
<?php
    require '../src/php/bootstrap.php';
    login_and_session_test();
    ob_start();

?>

<Doctype html>
<html>
<head>
    <meta charset ="UTF-8">
    
    <link rel="icon" href="" sizes="32x32" />

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="http://localhost/inscription_sport/public/css/style.css">

</head>

<body >

    <nav class="nav bg-dark navbar-dark justify-content-between">
        <a href="index.php"><img class="logo" src="https://placehold.it/170x40" alt="top left logo"></a>

        <?php if (userIsAdminOrCoach() === true) { ?>
            <a href="coach_space.php" class="btn btn-danger">Espace coach</a>
        <?php } ?>
        
        <a href="login.php" class="btn btn-dark" >Déconnexion</a>
    </nav>
