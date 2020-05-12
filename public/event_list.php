<?php
    require '../views/header.php';


    $begin = date( 'Y-m-d', strtotime("-3 days") );
    echo $begin;
    echo"<br>";
    $end = date( 'Y-m-d', strtotime("+30 days") );
    echo $end;

    
/* Récupération des evenements d'aujourd'hui et à venir */
    $sql_get_coming_events =  "SELECT * FROM event_base ORDER BY start ASC";
    //$sql_get_coming_events =  "SELECT * FROM event_base WHERE date_format(start, '%d') >= '" . date('d') . "' AND date_format(start, '%m') >= '" . date('m') ."' AND date_format(start, '%Y') >= '" . date('Y') . "' ORDER BY start DESC";
    //echo $sql_get_coming_events;
    $coming_events = exec_sql_fetch($sql_get_coming_events);
    pre_print_r( $coming_events );

    


?>


<div class="container">
<?php foreach ($all_registered as $registered): ?>
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
<?php endforeach; ?>

</div>

<?php
    require '../views/footer.php';
?>

