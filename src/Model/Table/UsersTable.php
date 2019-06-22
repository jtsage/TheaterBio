<?php
namespace App\Model\Table;

use App\Model\Entity\User;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @property \Cake\ORM\Association\HasMany $Messages
 * @property \Cake\ORM\Association\HasMany $Payrolls
 * @property \Cake\ORM\Association\HasMany $ShowUserPerms
 */
class UsersTable extends Table
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

        $this->getTable('users');
        $this->setDisplayField('print_name');
        $this->setPrimaryKey('id');

        $this->hasMany('Bios', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('Headshots', [
            'foreignKey' => 'user_id'
        ]);

        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created_at' => 'new',
                    'updated_at' => 'always',
                ],
                'Users.afterLogin' => [
                    'last_login_at' => 'always'
                ]
            ]
        ]);
    }

    public function findAuth(\Cake\ORM\Query $query, array $options)
    {
        //NOT CURRENTLY USED!!!
        $query
            ->select(['id', 'username', 'password', 'is_active'])
            ->where(['Users.is_active' => 1]);

        return $query;
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
            ->requirePresence('username', 'create')
            ->notEmpty('username')
            ->add('username', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->requirePresence('password', 'create')
            ->notEmpty('password');

        $validator
            ->requirePresence('first', 'create')
            ->notEmpty('first');

        $validator
            ->requirePresence('last', 'create')
            ->notEmpty('last');

        $validator
            ->requirePresence('print_name', 'create')
            ->notEmpty('print_name');

        $validator
            ->add('is_active', 'valid', ['rule' => 'boolean']);

        $validator
            ->add('is_password_expired', 'valid', ['rule' => 'boolean']);

        $validator
            ->add('is_admin', 'valid', ['rule' => 'boolean']);

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
        return $rules;
    }

}
