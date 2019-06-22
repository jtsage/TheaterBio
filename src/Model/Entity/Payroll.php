<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Payroll Entity.
 *
 * @property int $id
 * @property \Cake\I18n\Time $date_worked
 * @property \Cake\I18n\Time $start_time
 * @property \Cake\I18n\Time $end_time
 * @property bool $is_paid
 * @property string $notes
 * @property int $user_id
 * @property \App\Model\Entity\User $user
 * @property int $show_id
 * @property \App\Model\Entity\Show $show
 * @property \Cake\I18n\Time $created_at
 * @property \Cake\I18n\Time $updated_at
 */
class Payroll extends Entity
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
        '*' => true,
        'id' => false,
    ];
}
