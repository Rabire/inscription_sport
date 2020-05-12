<?php

namespace App\Calendar;

class Events
    {
        private $pdo;

        public function __construct(\PDO $pdo)
        {
            $this->pdo = $pdo;
        }

        public function getEventsBetween (\DateTime $start, \DateTime $end): array  //recup tout les evenements entre 2 dates
        {
            $sql = "SELECT * FROM event_base WHERE start BETWEEN '{$start->format('Y-m-d 00:00:00')}' AND '{$end->format('Y-m-d 23:59:59')}'";
            $statement = $this->pdo->query($sql);
            $results = $statement->fetchAll();
            return $results;
        }

        public function getEventsBetweenByDay (\DateTime $start, \DateTime $end): array  //recup tout les evenements entre 2 dates indexé par jour
        {
            $events = $this->getEventsBetween($start, $end);
            $days = [];
            foreach($events as $event) {
                $date = explode(' ', $event['start'])[0];
                if (!isset($days[$date])) { 
                    $days[$date] = [$event];
                } else {
                    $days[$date][] = $event;
                }
            }
            return $days;
        }

        public function find (int $eventid): array // recup un evenement
        {
            return $this->pdo->query("SELECT * FROM event_base WHERE id = $eventid LIMIT 1")->fetch();
        }


        public function find_participations (int $eventid): array // recup les participations paar evenements
        {
            return $this->pdo->query("SELECT * FROM participations_base WHERE participation_event_id = $eventid")->fetch();
        }

        public function getEventparticipations (int $eventid): array // recup les participations paar evenements
        {
            return $this->pdo->query("SELECT COUNT(*) FROM participation_base WHERE event_id = '" . $event['id'] . "'")->fetch();
        }

    }

?>


