<title>Calendrier - Séances de sport</title>

<?php
    require '../views/header.php';
    require '../src/php/Month.php';
    require '../src/php/Events.php';
    
    $pdo = get_pdo();
    $events = new App\Calendar\Events($pdo);
    $month = new App\Calendar\Month($_GET['month'] ?? null, $_GET['year'] ?? null);
    $start = $month->getStartingDay();
    $start = $start->format('N') === '1' ? $start : $month->getStartingDay()->modify('last monday'); //corrige l'affichage pour les mois commancant par un lundi && renvoie le premier jours du mois
    $weeks = $month->getWeeks(); //nb de semaines dans le mois
    $end = (clone $start)->modify('+' . (6 + 7 * ($weeks -1)) . ' days'); // renvoie la date du denier jour du mois
    $events = $events->getEventsBetweenByDay($start, $end); //tableau avec tout les evenements du mois
    /*
    echo "<pre>";
    print_r($events);
    echo "</pre>";
    */

    sendto_change_password();
?>



<div class="d-flex flex-row align-items-center justify-content-between mx-sm-3">
    <h1><?= $month->tostring(); ?></h1>

    <div>
        <a href="event_list.php" class="btn btn-outline-secondary">Liste</a>
        <a href="index.php?month=<?= $month->previousMonth()->month; ?>&year=<?= $month->previousMonth()->year; ?>" class="btn btn-outline-danger">&lt;</a>
        <a href="index.php?month=<?= $month->nextMonth()->month; ?>&year=<?= $month->nextMonth()->year; ?>" class="btn btn-outline-danger">&gt;</a>
    </div>
</div>

<table class="calendar__table calendar__table--<?= $weeks; ?>weeks">
    <?php for ($i = 0; $i < $weeks; $i++): ?>
        <tr>

            <?php
            foreach($month->days as $k => $day):
                $date = (clone $start)->modify("+" . (+$k + $i * 7) . "days");
                $eventsForDay = $events[$date->format('Y-m-d')] ?? [];
            ?>

                <td class="<?= $month->withinMonth($date) ? '' : 'calendar__othermonth text-muted'; ?>">
                    <?php if ($i === 0): ?>
                        <div class="calendar__weekday"><?= $day; ?></div>
                    <?php endif; ?>

                    <div class="calendar__day"><?= $date->format('d'); ?></div>

                    <?php foreach($eventsForDay as $event): ?>
                    

<?php
    $sql_get_all_RegisteredByEvents =  "SELECT COUNT(*) FROM participation_base WHERE event_id = '" . $event['id'] . "'";
    //echo $sql_get_all_RegisteredByEvents;

    $all_registered_byevent = exec_sql_fetch($sql_get_all_RegisteredByEvents);
    //pre_print_r( $all_registered_byevent );

    $pdoStat = $pdo->prepare ($sql_get_all_RegisteredByEvents);
    $executeIsOK = $pdoStat->execute();
    $all_registered_byevent = $pdoStat->fetchAll();

    foreach($all_registered_byevent as $participations_count) { $participations_count = $participations_count['COUNT(*)']; }

?>


                        <a class="acalendar__event" href="/inscription_sport/public/event.php?id=<?= $event['id']; ?>">
                            <div class="calendar__event">
                                <?= (new DateTime($event['start']))->format('H:i'); ?> - <?= $event['name']; ?> (<?= $participations_count ?>/<?= $event['registrationlimit'];?>)
                            </div>
                        </a>
                    <?php endforeach; ?>
                </td>

            <?php endforeach ?>
        </tr>
    <?php endfor; ?>
</table>

<?php require '../views/footer.php'; ?>