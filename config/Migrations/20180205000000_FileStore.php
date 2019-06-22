<?php
/* This adds password resets to the app */
use Migrations\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class FileStore extends AbstractMigration
{
    public $autoId = false;

    public function up()
    {
        $this->table('files')
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
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('dsc', 'string', [
                'default' => null,
                'limit' => 250,
                'null' => false,
            ])
            ->addColumn('fle', 'blob', [
                'default' => null,
                'limit' => MysqlAdapter::BLOB_LONG,
                'null' => false,
            ])
            ->addColumn('fle_size', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
                'signed' => true,
            ])
            ->addColumn('fle_type', 'string', [
                'default' => null,
                'limit' => 255,
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
            ->create();
    }

    public function down()
    {
        $this->dropTable('files');
    }
}
