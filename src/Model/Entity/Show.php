<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Show Entity.
 *
 * @property int $id
 * @property string $name
 * @property string $location
 * @property \Cake\I18n\Time $end_date
 * @property bool $is_active
 * @property \Cake\I18n\Time $created_at
 * @property \Cake\I18n\Time $updated_at
 * @property \App\Model\Entity\Budget[] $budgets
 * @property \App\Model\Entity\Payroll[] $payrolls
 * @property \App\Model\Entity\ShowUserPerm[] $show_user_perms
 */
class Show extends Entity
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
