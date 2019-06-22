<?php
/* This adds password resets to the app */
use Migrations\AbstractMigration;

class CalICS extends AbstractMigration
{
    public $autoId = false;

    public function change()
    {
        $this->table('shows')
            ->addColumn('sec_string', 'string', [
                'default' => null,
                'limit' => 40,
                'null' => true,
            ])
            ->addIndex(
                [
                    'sec_string',
                ],
                ['unique' => true]
            )
            ->update();
    }
}
