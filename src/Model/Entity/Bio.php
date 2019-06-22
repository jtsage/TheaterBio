<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Bio Entity
 *
 * @property string $id
 * @property string $user_id
 * @property string $purpose_id
 * @property string $text
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Purpose $purpose
 */
class Bio extends Entity
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
        'user_id' => true,
        'purpose_id' => true,
        'text' => true,
        'user' => true,
        'purpose' => true
    ];
}
