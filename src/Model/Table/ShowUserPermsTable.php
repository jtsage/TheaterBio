<?php
namespace App\Model\Table;

use App\Model\Entity\ShowUserPerm;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ShowUserPerms Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\BelongsTo $Shows
 */
class ShowUserPermsTable extends Table
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

        $this->getTable('show_user_perms');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Shows', [
            'foreignKey' => 'show_id',
            'joinType' => 'INNER'
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
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');

        $validator
            ->add('is_pay_admin', 'valid', ['rule' => 'boolean'])
            ->requirePresence('is_pay_admin', 'create')
            ->notEmpty('is_pay_admin');

        $validator
            ->add('is_paid', 'valid', ['rule' => 'boolean'])
            ->requirePresence('is_paid', 'create')
            ->notEmpty('is_paid');

        $validator
            ->add('is_budget', 'valid', ['rule' => 'boolean'])
            ->requirePresence('is_budget', 'create')
            ->notEmpty('is_budget');

        $validator
            ->add('is_task_admin', 'valid', ['rule' => 'boolean'])
            ->requirePresence('is_task_admin', 'create')
            ->notEmpty('is_task_admin');

        $validator
            ->add('is_task_user', 'valid', ['rule' => 'boolean'])
            ->requirePresence('is_task_user', 'create')
            ->notEmpty('is_task_user');

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
        $rules->add($rules->existsIn(['show_id'], 'Shows'));
        return $rules;
    }
}
