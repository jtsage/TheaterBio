<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class TaskUtilComponent extends Component
{
    /**
     * getAllCounts()
     *
     * Grab a simple list of task counts per show.
     *
     * @return Array of arrays, (done/total/accept_notdone/overdue/new/yours) via show ID
     */
    public function getAllCounts($usrNum = 0) {
        $this->Shows = TableRegistry::get('Shows');

        $tasktotal = $this->Shows->find('all')
            ->select([
                'show_id' => 'Shows.id',
                'show_name' => 'Shows.name',
                'total' => 'count(Tasks.id)'
            ])
            ->join([
                'table' => 'tasks',
                'alias' => 'Tasks',
                'type' => 'LEFT',
                'conditions' => ['Shows.id = Tasks.show_id'],
            ])
            ->group('Shows.id');
        
        $taskdone = $this->Shows->find('all')
            ->select([
                'show_id' => 'Shows.id',
                'show_name' => 'Shows.name',
                'total' => 'count(Tasks.id)'
            ])
            ->join([
                'table' => 'tasks',
                'alias' => 'Tasks',
                'type' => 'LEFT',
                'conditions' => ['Shows.id = Tasks.show_id', 'Tasks.task_done = 1'],
            ])
            ->group('Shows.id');

        $tasknotdone = $this->Shows->find('all')
            ->select([
                'show_id' => 'Shows.id',
                'show_name' => 'Shows.name',
                'total' => 'count(Tasks.id)'
            ])
            ->join([
                'table' => 'tasks',
                'alias' => 'Tasks',
                'type' => 'LEFT',
                'conditions' => ['Shows.id = Tasks.show_id', 'Tasks.task_done = 0', 'Tasks.task_accepted = 1'],
            ])
            ->group('Shows.id');

        $tasknew = $this->Shows->find('all')
            ->select([
                'show_id' => 'Shows.id',
                'show_name' => 'Shows.name',
                'total' => 'count(Tasks.id)'
            ])
            ->join([
                'table' => 'tasks',
                'alias' => 'Tasks',
                'type' => 'LEFT',
                'conditions' => ['Shows.id = Tasks.show_id', 'Tasks.task_accepted = 0', 'Tasks.due >= "' . date('Y-m-d') . '"'],
            ])
            ->group('Shows.id');

        $taskoverdue = $this->Shows->find('all')
            ->select([
                'show_id' => 'Shows.id',
                'show_name' => 'Shows.name',
                'total' => 'count(Tasks.id)'
            ])
            ->join([
                'table' => 'tasks',
                'alias' => 'Tasks',
                'type' => 'LEFT',
                'conditions' => ['Shows.id = Tasks.show_id', 'Tasks.task_done = 0', 'Tasks.due < "' . date('Y-m-d') . '"'],
            ])
            ->group('Shows.id');

        $taskyours = $this->Shows->find('all')
            ->select([
                'show_id' => 'Shows.id',
                'show_name' => 'Shows.name',
                'total' => 'count(Tasks.id)'
            ])
            ->join([
                'table' => 'tasks',
                'alias' => 'Tasks',
                'type' => 'LEFT',
                'conditions' => ['Shows.id = Tasks.show_id', 'Tasks.created_by = ' . $usrNum],
            ])
            ->group('Shows.id');

        $showtask = ['yours' => [], 'new' => [], 'total' => [], 'done' => [], 'accept_notdone' => [], 'overdue' => []];

        foreach ( $tasktotal as $show ) {
            $showtask['total'][$show->show_id] = $show->total;
        }
        foreach ( $taskdone as $show ) {
            $showtask['done'][$show->show_id] = $show->total;
        }
        foreach ( $tasknew as $show ) {
            $showtask['new'][$show->show_id] = $show->total;
        }
        foreach ( $tasknotdone as $show ) {
            $showtask['accept_notdone'][$show->show_id] = $show->total;
        }
        foreach ( $taskoverdue as $show ) {
            $showtask['overdue'][$show->show_id] = $show->total;
        }
        foreach ( $taskyours as $show ) {
            $showtask['yours'][$show->show_id] = $show->total;
        }

        return $showtask;
    }

}
?>
