<?php
namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\Event\Event;
use ArrayObject;

class UserIdBehavior extends Behavior {

    public function initialize(array $config) {
        parent::initialize($config);
        //$this->loadComponent('Auth'); //FIXME can't do this
        //FIXME might need to pass user id into config instead
    }

    protected $_defaultConfig = [
        'user_id_field' => 'user_id'
    ];

    private function userIdField() {
        return $this->getConfig()['user_id_field'];
    }

    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options) {
        $data[$this->userIdField()] = 2; //FIXME
    }
}