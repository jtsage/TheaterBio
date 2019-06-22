<?php
/* This adds salary users to the app */
use Migrations\AbstractMigration;

class Salary extends AbstractMigration
{
    public $autoId = false;

    public function change()
    {

        $table = $this->table('users')
            ->addColumn('is_salary', 'boolean', [
                'default' => 0,
                'limit' => null,
                'null' => false,
            ])
            ->update();
    }
}
