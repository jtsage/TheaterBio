<?php
/* This adds password resets to the app */
use Migrations\AbstractMigration;

class PWReset extends AbstractMigration
{
    public $autoId = false;

    public function change()
    {

        $table = $this->table('users')
            ->addColumn('reset_hash', 'string', [
                'default' => null,
                'limit' => 60,
                'null' => true,
            ])
            ->addColumn('reset_hash_time', 'timestamp', [
                'default' => '1970-01-01 00:00:01',
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                [
                    'reset_hash',
                ],
                ['unique' => true]
            )
            ->update();
    }
}
