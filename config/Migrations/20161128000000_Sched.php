<?php
/* This adds password resets to the app */
use Migrations\AbstractMigration;

class Sched extends AbstractMigration
{
    public $autoId = false;

    public function up()
    {
        $table = $this->table('schedules');
        $table
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('jobtype', 'string', [
                'default' => null,
                'limit' => 10,
                'null' => false,
            ])
            ->addColumn('sendto', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => true,
            ])
            ->addColumn('start_time', 'timestamp', [
                'default' => '1970-01-01 00:00:01',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('period', 'integer', [
                'default' => 7,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('last_run', 'timestamp', [
                'default' => '1970-01-01 00:00:01',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('created_at', 'timestamp', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('updated_at', 'timestamp', [
                'default' => '1970-01-01 00:00:01',
                'limit' => null,
                'null' => false,
            ])
            ->create();
    }
    public function down()
    {
        $this->dropTable('schedules');
    }
}
