<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;

/**
 * Documents Controller
 *
 * @property \App\Model\Table\DocumentsTable $Documents
 *
 * @method \App\Model\Entity\Document[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DocumentsController extends AppController
{
    public function initialize() {
        parent::initialize();

        $this->loadModel('Users');
        $this->loadModel('Folders');
    }

    public function view($id = null) {
        if (!\is_null($id)) {
            $doc = $this->Documents->get($id);
            if (!\is_null($doc) && ($this->Auth->user('id') === $doc->user_id || $this->isTreasurer())) {
                $this->response->withFile($doc->full_path);
                //$this->response->header('Content-Disposition', 'inline');
                $this->response = $this->response->withType($doc->extension);
                $this->log($doc->full_path, 'debug'); //FIXME remove
                return $this->response;
            }
        }
        $this->Flash->error(__("You can't view this file."));
        return $this->redirect($this->referer());
    }


    //FIXME make this a component or something. 
    //FIXME very inefficient
    private function isTreasurer() {
        $currentUser = $this->getCurrentUser();
        return !\is_null($currentUser) && $currentUser->privilege->treasurer;
    }

    //FIXME make this a component or something. 
    //FIXME very inefficient
    private function getCurrentUser() {
        return $this->Users
            ->find()
            ->where(['user_id' => $this->Auth->user('id')])
            ->contain(['Privileges'])
            ->first();
    }
}