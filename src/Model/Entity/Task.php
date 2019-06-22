<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Task Entity
 *
 * @property int $id
 * @property int $created_by
 * @property int $assigned_to
 * @property int $show_id
 * @property \Cake\I18n\Time $due
 * @property int $priority
 * @property string $category
 * @property string $note
 * @property int $task_accepted
 * @property int $task_done
 * @property \Cake\I18n\Time $created_at
 * @property \Cake\I18n\Time $updated_at
 *
 * @property \App\Model\Entity\Show $show
 */
class Task extends Entity
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
        'id' => false
    ];
}
