<?php
/* This adds payroll reminders on shows */
use Migrations\AbstractMigration;

class Remind extends AbstractMigration
{
    public $autoId = false;

    public function change()
    {

        $table = $this->table('shows')
            ->addColumn('is_reminded', 'boolean', [
                'default' => 0,
                'limit' => null,
                'null' => false,
            ])
            ->update();
    }
}
