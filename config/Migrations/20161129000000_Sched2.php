<?php
/* This adds password resets to the app */
use Migrations\AbstractMigration;

class Sched2 extends AbstractMigration
{
    public $autoId = false;

    public function change()
    {

        $table = $this->table('schedules')
            ->addColumn('show_id', 'integer', [
                'default' => 0,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->update();
    }
}
