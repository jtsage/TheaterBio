<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class CalUtilComponent extends Component
{
    /**
     * getAllCounts()
     *
     * Grab a simple list of calendar counts per show.
     *
     * @return Array of arrays, (today/future/past) via show ID
     */
    public function getAllCounts($usrNum = 0) {
        $this->Shows = TableRegistry::get('Shows');

        $cal_future = $this->Shows->find('all')
            ->select([
                'show_id' => 'Shows.id',
                'show_name' => 'Shows.name',
                'total' => 'count(Calendars.id)'
            ])
            ->join([
                'table' => 'calendars',
                'alias' => 'Calendars',
                'type' => 'LEFT',
                'conditions' => ['Shows.id = Calendars.show_id', 'Calendars.date > CURDATE()'],
            ])
            ->group('Shows.id');

        $cal_past = $this->Shows->find('all')
            ->select([
                'show_id' => 'Shows.id',
                'show_name' => 'Shows.name',
                'total' => 'count(Calendars.id)'
            ])
            ->join([
                'table' => 'calendars',
                'alias' => 'Calendars',
                'type' => 'LEFT',
                'conditions' => ['Shows.id = Calendars.show_id', 'Calendars.date < CURDATE()'],
            ])
            ->group('Shows.id');

        $cal_today = $this->Shows->find('all')
            ->select([
                'show_id' => 'Shows.id',
                'show_name' => 'Shows.name',
                'total' => 'count(Calendars.id)'
            ])
            ->join([
                'table' => 'calendars',
                'alias' => 'Calendars',
                'type' => 'LEFT',
                'conditions' => ['Shows.id = Calendars.show_id', 'Calendars.date = CURDATE() '],
            ])
            ->group('Shows.id');
        

        $showcal = ['today' => [], 'future' => [], 'past' => []];

        foreach ( $cal_future as $show ) {
            $showcal['future'][$show->show_id] = $show->total;
        }
        foreach ( $cal_today as $show ) {
            $showcal['today'][$show->show_id] = $show->total;
        }
        foreach ( $cal_past as $show ) {
            $showcal['past'][$show->show_id] = $show->total;
        }

        return $showcal;
    }

}
?>
