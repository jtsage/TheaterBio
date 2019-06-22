<?php
use Migrations\AbstractMigration;

class Install extends AbstractMigration
{
    public $autoId = false;

    public function up()
    {
        $table = $this->table('budgets');
        $table
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('category', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('vendor', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('description', 'string', [
                'default' => null,
                'limit' => 150,
                'null' => false,
            ])
            ->addColumn('date', 'date', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('price', 'float', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('show_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
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
            ->addIndex(
                [
                    'show_id',
                ]
            )
            ->create();

        $table = $this->table('messages');
        $table
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('user_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('note', 'text', [
                'default' => null,
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
            ->addIndex(
                [
                    'user_id',
                ]
            )
            ->create();

        $table = $this->table('payrolls');
        $table
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('date_worked', 'date', [
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
            ->addColumn('worked', 'float', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('is_paid', 'boolean', [
                'default' => 0,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('notes', 'string', [
                'default' => null,
                'limit' => 150,
                'null' => true,
            ])
            ->addColumn('user_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('show_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
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
            ->addIndex(
                [
                    'show_id',
                ]
            )
            ->addIndex(
                [
                    'user_id',
                ]
            )
            ->create();

        $table = $this->table('sessions', ['id' => false, 'primary_key' => ['id']]);
        $table
            ->addColumn('id', 'string', [
                'default' => '',
                'limit' => 40,
                'null' => false,
            ])
            ->addColumn('data', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('expires', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->create();

        $table = $this->table('show_user_perms');
        $table
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('user_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('show_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('is_pay_admin', 'boolean', [
                'default' => 0,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('is_paid', 'boolean', [
                'default' => 1,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('is_budget', 'boolean', [
                'default' => 0,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('is_task_admin', 'boolean', [
                'default' => false,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('is_task_user', 'boolean', [
                'default' => true,
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                [
                    'user_id',
                    'show_id',
                ],
                ['unique' => true]
            )
            ->addIndex(
                [
                    'show_id',
                ]
            )
            ->addIndex(
                [
                    'user_id',
                ]
            )
            ->create();

        $table = $this->table('shows');
        $table
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 45,
                'null' => false,
            ])
            ->addColumn('location', 'string', [
                'default' => null,
                'limit' => 45,
                'null' => false,
            ])
            ->addColumn('end_date', 'date', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('is_active', 'boolean', [
                'default' => 1,
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

        $table = $this->table('users');
        $table
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('username', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('password', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('first', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('last', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('phone', 'biginteger', [
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('is_active', 'boolean', [
                'default' => 1,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('is_password_expired', 'boolean', [
                'default' => 0,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('is_notified', 'boolean', [
                'default' => 0,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('is_admin', 'boolean', [
                'default' => 0,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('last_login_at', 'timestamp', [
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
            ->addColumn('time_zone', 'string', [
                'default' => 'UTC',
                'limit' => 100,
                'null' => false,
            ])
            ->addIndex(
                [
                    'username',
                ],
                ['unique' => true]
            )
            ->create();

        $this->table('tasks')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('created_by', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('assigned_to', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('show_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('due', 'date', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('priority', 'integer', [
                'default' => 1,
                'limit' => 10,
                'null' => false,
                'signed' => false,
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
                'null' => false,
            ])
            ->addColumn('task_accepted', 'boolean', [
                'default' => false,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('task_done', 'boolean', [
                'default' => false,
                'limit' => null,
                'null' => false,
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
                    'assigned_to',
                ]
            )
            ->addIndex(
                [
                    'created_by',
                ]
            )
            ->addIndex(
                [
                    'show_id',
                ]
            )
            ->create();

        $this->table('budgets')
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

        $this->table('messages')
            ->addForeignKey(
                'user_id',
                'users',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'CASCADE'
                ]
            )
            ->update();

        $this->table('payrolls')
            ->addForeignKey(
                'show_id',
                'shows',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'CASCADE'
                ]
            )
            ->addForeignKey(
                'user_id',
                'users',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'RESTRICT'
                ]
            )
            ->update();

        $this->table('show_user_perms')
            ->addForeignKey(
                'show_id',
                'shows',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'CASCADE'
                ]
            )
            ->addForeignKey(
                'user_id',
                'users',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'RESTRICT'
                ]
            )
            ->update();
            
        $this->table('tasks')
             ->addForeignKey(
                 'assigned_to',
                 'users',
                 'id',
                 [
                     'update' => 'RESTRICT',
                     'delete' => 'RESTRICT'
                 ]
             )
             ->addForeignKey(
                 'created_by',
                 'users',
                 'id',
                 [
                     'update' => 'RESTRICT',
                     'delete' => 'RESTRICT'
                 ]
             )
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
        $this->table('budgets')
            ->dropForeignKey(
                'show_id'
            )
            ->update();

        $this->table('messages')
            ->dropForeignKey(
                'user_id'
            )
            ->update();

        $this->table('payrolls')
            ->dropForeignKey(
                'show_id'
            )
            ->dropForeignKey(
                'user_id'
            )
            ->update();

        $this->table('show_user_perms')
            ->dropForeignKey(
                'show_id'
            )
            ->dropForeignKey(
                'user_id'
            )
            ->update();

        $this->table('tasks')
             ->dropForeignKey(
                 'assigned_to'
             )
             ->dropForeignKey(
                 'created_by'
             )
             ->dropForeignKey(
                 'show_id'
             )
             ->update();

        $this->dropTable('budgets');
        $this->dropTable('messages');
        $this->dropTable('payrolls');
        $this->dropTable('sessions');
        $this->dropTable('show_user_perms');
        $this->dropTable('shows');
        $this->dropTable('users');
        $this->dropTable('tasks');
    }
}
