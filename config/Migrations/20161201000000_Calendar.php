<?php
/* This adds password resets to the app */
use Migrations\AbstractMigration;

class Calendar extends AbstractMigration
{
    public $autoId = false;

    public function up()
    {
        $this->table('show_user_perms')
            ->addColumn('is_cal', 'boolean', [
                'default' => 0,
                'limit' => null,
                'null' => false,
            ])
            ->update();

        $this->table('calendars')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('show_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('date', 'date', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('start_time', 'time', [
                'default' => "08:00:00",
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('end_time', 'time', [
                'default' => "17:00:00",
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('all_day', 'boolean', [
                'default' => 0,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('title', 'string', [
                'default' => null,
                'limit' => 200,
                'null' => false,
            ])
            ->addColumn('category', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => true,
            ])
            ->addColumn('note', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('created_at', 'timestamp', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('updated_at', 'timestamp', [
                'default' => '1970-01-01 05:00:01',
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                [
                    'show_id',
                ]
            )
            ->create();

        $this->table('calendars')
            ->addForeignKey(
                'show_id',
                'shows',
                'id',
                    [
                        'update' => 'RESTRICT',
                        'delete' => 'CASCADE'
                    ]
                )
             ->update();
    }

    public function down()
    {
        $this->table('show_user_perms')
            ->removeColumn('is_cal')
            ->update();

        $this->table('calendars')
            ->dropForeignKey(
                'show_id'
            )
            ->update();

        $this->dropTable('calendars');
    }
}
