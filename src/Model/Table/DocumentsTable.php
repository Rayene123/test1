<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Documents Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\FoldersTable|\Cake\ORM\Association\BelongsTo $Folders
 *
 * @method \App\Model\Entity\Document get($primaryKey, $options = [])
 * @method \App\Model\Entity\Document newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Document[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Document|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Document saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Document patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Document[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Document findOrCreate($search, callable $callback = null, $options = [])
 */
class DocumentsTable extends Table
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
        $this->belongsTo('Users')->setJoinType('INNER');
        $this->belongsTo('Folders');
        $this->addBehavior('UserId');
        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'uploaded' => 'new'
                ]
            ]
        ]);

        $this->hasMany('Receipts')
            ->setJoinType('INNER');
        //FIXME add other relationships
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
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('filename')
            ->maxLength('filename', 40)
            ->requirePresence('filename', 'create')
            ->notEmptyFile('filename')
            ->add('filename', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('extension')
            ->maxLength('extension', 10)
            ->requirePresence('extension', 'create')
            ->notEmptyString('extension');

        $validator
            ->boolean('is_private')
            ->notEmptyString('is_private');

        $validator
            ->dateTime('uploaded')
            ->notEmptyDateTime('uploaded');

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
        $rules->add($rules->existsIn(['folder_id'], 'Folders'));

        return $rules;
    }
}
