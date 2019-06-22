<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * Users2Fixture
 *
 */
class Users2Fixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'users2';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'username' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'password' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'first' => ['type' => 'string', 'length' => 50, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'last' => ['type' => 'string', 'length' => 50, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'phone' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'is_active' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '1', 'comment' => '', 'precision' => null],
        'is_password_expired' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        'is_admin' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        'last_login_at' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => '1970-01-01 05:00:01', 'comment' => '', 'precision' => null],
        'created_at' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => 'CURRENT_TIMESTAMP', 'comment' => '', 'precision' => null],
        'updated_at' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => '1970-01-01 05:00:01', 'comment' => '', 'precision' => null],
        'time_zone' => ['type' => 'string', 'length' => 100, 'null' => false, 'default' => 'UTC', 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'reset_hash' => ['type' => 'string', 'length' => 60, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'reset_hash_time' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => '1970-01-01 05:00:01', 'comment' => '', 'precision' => null],
        'verify_hash' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'username' => ['type' => 'unique', 'columns' => ['username'], 'length' => []],
            'reset_hash' => ['type' => 'unique', 'columns' => ['reset_hash'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_general_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $this->records = [
            [
                'id' => '7cf88358-6f45-4da2-9f66-baf7ac270d5d',
                'username' => 'Lorem ipsum dolor sit amet',
                'password' => 'Lorem ipsum dolor sit amet',
                'first' => 'Lorem ipsum dolor sit amet',
                'last' => 'Lorem ipsum dolor sit amet',
                'phone' => 1,
                'is_active' => 1,
                'is_password_expired' => 1,
                'is_admin' => 1,
                'last_login_at' => 1561168028,
                'created_at' => 1561168028,
                'updated_at' => 1561168028,
                'time_zone' => 'Lorem ipsum dolor sit amet',
                'reset_hash' => 'Lorem ipsum dolor sit amet',
                'reset_hash_time' => 1561168028,
                'verify_hash' => 'Lorem ipsum dolor sit amet'
            ],
        ];
        parent::init();
    }
}
