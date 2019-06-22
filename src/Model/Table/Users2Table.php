<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users2 Model
 *
 * @method \App\Model\Entity\Users2 get($primaryKey, $options = [])
 * @method \App\Model\Entity\Users2 newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Users2[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Users2|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Users2|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Users2 patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Users2[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Users2 findOrCreate($search, callable $callback = null, $options = [])
 */
class Users2Table extends Table
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

        $this->setTable('users2');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
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
            ->scalar('username')
            ->maxLength('username', 255)
            ->requirePresence('username', 'create')
            ->allowEmptyString('username', false)
            ->add('username', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('password')
            ->maxLength('password', 255)
            ->requirePresence('password', 'create')
            ->allowEmptyString('password', false);

        $validator
            ->scalar('first')
            ->maxLength('first', 50)
            ->requirePresence('first', 'create')
            ->allowEmptyString('first', false);

        $validator
            ->scalar('last')
            ->maxLength('last', 50)
            ->requirePresence('last', 'create')
            ->allowEmptyString('last', false);

        $validator
            ->allowEmptyString('phone');

        $validator
            ->boolean('is_active')
            ->requirePresence('is_active', 'create')
            ->allowEmptyString('is_active', false);

        $validator
            ->boolean('is_password_expired')
            ->requirePresence('is_password_expired', 'create')
            ->allowEmptyString('is_password_expired', false);

        $validator
            ->boolean('is_admin')
            ->requirePresence('is_admin', 'create')
            ->allowEmptyString('is_admin', false);

        $validator
            ->dateTime('last_login_at')
            ->requirePresence('last_login_at', 'create')
            ->allowEmptyDateTime('last_login_at', false);

        $validator
            ->dateTime('created_at')
            ->requirePresence('created_at', 'create')
            ->allowEmptyDateTime('created_at', false);

        $validator
            ->dateTime('updated_at')
            ->requirePresence('updated_at', 'create')
            ->allowEmptyDateTime('updated_at', false);

        $validator
            ->scalar('time_zone')
            ->maxLength('time_zone', 100)
            ->requirePresence('time_zone', 'create')
            ->allowEmptyString('time_zone', false);

        $validator
            ->scalar('reset_hash')
            ->maxLength('reset_hash', 60)
            ->allowEmptyString('reset_hash')
            ->add('reset_hash', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->dateTime('reset_hash_time')
            ->requirePresence('reset_hash_time', 'create')
            ->allowEmptyDateTime('reset_hash_time', false);

        $validator
            ->scalar('verify_hash')
            ->maxLength('verify_hash', 100)
            ->allowEmptyString('verify_hash');

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
        $rules->add($rules->isUnique(['username']));
        $rules->add($rules->isUnique(['reset_hash']));

        return $rules;
    }
}
