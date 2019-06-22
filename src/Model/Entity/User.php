<?php
namespace App\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;

/**
 * User Entity.
 *
 * @property string $id
 * @property string $username
 * @property string $password
 * @property string $first
 * @property string $last
 * @property int $phone
 * @property bool $is_active
 * @property bool $is_password_expired
 * @property bool $is_notified
 * @property bool $is_admin
 * @property \Cake\I18n\Time $last_login_at
 * @property \Cake\I18n\Time $created_at
 * @property \Cake\I18n\Time $updated_at
 */
class User extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'username' => true,
        'password' => true,
        'first' => true,
        'last' => true,
        'print_name' => true,
        'is_active' => true,
        'is_password_expired' => true,
        'is_admin' => true,
        'last_login_at' => true,
        'created_at' => true,
        'updated_at' => true,
        'time_zone' => true,
        'reset_hash' => true,
        'reset_hash_time' => true,
        'verify_hash' => true
    ];

    protected function _setPassword($password)
    {
        return (new DefaultPasswordHasher)->hash($password);
    }

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password',
        'verify_hash',
        'reset_hash',
        'reset_hash_time'
    ];
    
}
