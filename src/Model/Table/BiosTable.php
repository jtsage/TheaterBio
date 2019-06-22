<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Bios Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\PurposesTable|\Cake\ORM\Association\BelongsTo $Purposes
 *
 * @method \App\Model\Entity\Bio get($primaryKey, $options = [])
 * @method \App\Model\Entity\Bio newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Bio[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Bio|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Bio|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Bio patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Bio[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Bio findOrCreate($search, callable $callback = null, $options = [])
 */
class BiosTable extends Table
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

        $this->setTable('bios');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Purposes', [
            'foreignKey' => 'purpose_id',
            'joinType' => 'INNER'
        ]);

        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created_at' => 'new',
                    'updated_at' => 'always',
                ]
            ]
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
            ->scalar('text')
            ->requirePresence('text', 'create')
            ->allowEmptyString('text', false);

        $validator->add('purpose_id', [
                'unique' => [
                    'rule' => ['validateUnique', ['scope' => 'user_id']],
                    'provider' => 'table',
                    'message' => 'You may only have 1 bio per purpose!'
                ]
            ]);
        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['purpose_id'], 'Purposes'));

        return $rules;
    }
}
