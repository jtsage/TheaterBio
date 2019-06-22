<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Purposes Model
 *
 * @property \App\Model\Table\BiosTable|\Cake\ORM\Association\HasMany $Bios
 * @property \App\Model\Table\HeadshotsTable|\Cake\ORM\Association\HasMany $Headshots
 *
 * @method \App\Model\Entity\Purpose get($primaryKey, $options = [])
 * @method \App\Model\Entity\Purpose newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Purpose[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Purpose|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Purpose|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Purpose patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Purpose[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Purpose findOrCreate($search, callable $callback = null, $options = [])
 */
class PurposesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('purposes');
        $this->setDisplayField('label');
        $this->setPrimaryKey('id');

        $this->hasMany('Bios', [
            'foreignKey' => 'purpose_id'
        ]);
        $this->hasMany('Headshots', [
            'foreignKey' => 'purpose_id'
        ]);
        $this->belongsToMany('Users', [
            'through' => 'Bios',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->uuid('id')
            ->allowEmptyString('id', 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 50)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', false);

        $validator
            ->scalar('description')
            ->maxLength('description', 150)
            ->allowEmptyString('description');

        return $validator;
    }
}
