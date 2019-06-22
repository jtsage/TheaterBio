<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Purpose Entity
 *
 * @property string $id
 * @property string $name
 * @property string|null $description
 *
 * @property \App\Model\Entity\Bio[] $bios
 * @property \App\Model\Entity\Headshot[] $headshots
 */
class Purpose extends Entity
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
        'name' => true,
        'description' => true,
        'bios' => true,
        'headshots' => true
    ];

    protected function _getLabel()
{
    return $this->_properties['name'] . ' - ' . $this->_properties['description'];
}
}
