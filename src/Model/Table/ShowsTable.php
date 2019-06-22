<?php
namespace App\Model\Table;

use App\Model\Entity\Show;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Shows Model
 *
 * @property \Cake\ORM\Association\HasMany $Budgets
 * @property \Cake\ORM\Association\HasMany $Payrolls
 * @property \Cake\ORM\Association\HasMany $ShowUserPerms
 */
class ShowsTable extends Table
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

        $this->getTable('shows');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('Budgets', [
            'foreignKey' => 'show_id'
        ]);
        $this->hasMany('Payrolls', [
            'foreignKey' => 'show_id'
        ]);
        $this->hasMany('ShowUserPerms', [
            'foreignKey' => 'show_id'
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
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->requirePresence('location', 'create')
            ->notEmpty('location');

        $validator
            ->add('end_date', 'valid', ['rule' => ['date', 'ymd']])
            ->requirePresence('end_date', 'create')
            ->notEmpty('end_date');

        $validator
            ->add('is_active', 'valid', ['rule' => 'boolean']);

        return $validator;
    }
}
