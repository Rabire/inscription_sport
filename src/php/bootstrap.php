<?php
    function dd(...$vars)
    {
        foreach($vars as $var){
            echo "<pre>";
            print_r($var);
            echo "<pre>";
        }
    }

    function get_pdo (): PDO
    {
        return new PDO('mysql:host=localhost;dbname=inscription_sport', 'root', '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // si erreur = exception
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC //données dans un tableau associatif
        ]);
    }

    function exec_sql_fetch ($sql): array {
        $pdo = get_pdo();
        $pdoStat = $pdo->prepare ($sql);
        $executeIsOK = $pdoStat->execute();
        $sql_answer = $pdoStat->fetchAll();

        return $sql_answer;
    }

    function pre_print_r ($array_to_print) {
        echo "<pre>";
        print_r($array_to_print);
        echo "</pre>";
    }

    function exec_sql ($sql) {
        $pdo = get_pdo();
        $pdoStat = $pdo->prepare ($sql);
        $executeIsOK = $pdoStat->execute();
    }

    function login_and_session_test()
    {
        session_start();
/*
        echo "Session <pre>";
        print_r($_SESSION);
        echo "</pre>";
*/
        if ($_SESSION['isconnected'] !== true) {
            header('location: /inscription_sport/public/login.php');
        }

    }

    function sendto_change_password () {
        $sql_userinfos = "SELECT * FROM user_base WHERE id = '" . $_SESSION['userid'] . "'"; // requete recup infos user
        //echo $sql_userinfos; //affiche la requete au dessus avec les parametres

        $pdo = get_pdo();
        $pdoStat = $pdo->prepare ($sql_userinfos);
        $executeIsOK = $pdoStat->execute();
        $users = $pdoStat->fetchAll();

        foreach ($users as $user) {}
/*
        //infos sql du user
        echo "<pre>";
        echo "users infos ";
        print_r($user);
        echo "</pre>";
*/
        if($user['isdefaultpassword'] === '1'){
            header('location: /inscription_sport/public/change_password.php');
        }
    }

    function getUserRole () {   // recup le role_id de l'user connecté
        $userid = $_SESSION['userid'];
    
        $sql_userinfos = "SELECT role_id FROM user_base WHERE id = '" . $userid . "'";
    
        $pdo = get_pdo();
        $pdoStat = $pdo->prepare ($sql_userinfos);
        $executeIsOK = $pdoStat->execute();
        $user_role = $pdoStat->fetchAll();

        foreach ($user_role as $uuser_role) { $role = $uuser_role['role_id']; }
        return $role;
    
    }

    function userIsAdminOrCoach ():bool { //retourne false su l'user connecté n'est pas un coach ou un admin
        getUserRole();
        if (getUserRole() === '1' OR getUserRole() === '2')
        {
            return true;
        } else {
            return false;
        }
    }

    function userIsAdminOrCoach_redirect (){ // redirige à l'index si l'user n'est pas un coach ou un admin
        userIsAdminOrCoach();
        if (userIsAdminOrCoach() === false)
        {
            header('location: /inscription_sport/public/index.php');
        }
    }



?>