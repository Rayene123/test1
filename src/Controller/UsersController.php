<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    public function initialize() {
        parent::initialize();
        $this->Auth->allow(['login', 'logout', 'new', 'index']);
    }

    public function index()
    {
        $user = $this->Auth->user();
        if (!is_null($user))
            $user = $this->Users->get($user['id'], ['contain' => ['Privileges']]);
        $this->set(compact('user')); 
    }

    public function login()
    {
        if ($this->Auth->user())
            return $this->redirect($this->referer());
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error('Your username or password is incorrect.');
        }
    }

    public function logout()
    {   
        if ($this->Auth->user())
            $this->Flash->success('You are now logged out.');
        return $this->redirect($this->Auth->logout());
    }

    public function new()
    {
        $user = $this->Users->newEntity();
        $location = $this->Users->Locations->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            $location = $this->Users->Locations->patchEntity($location, $this->request->getData());
            $user->location = $location;
           $success = $this->Users->getConnection()->transactional(function ($conn) use ($user) {
                $userSaved = $this->Users->save($user, ['associated' => 'Locations']);
                if ($userSaved !== false) {
                    $privileges = $this->Users->Privileges->newEntity();
                    $privileges->user_id = $user->id;
                    $userSaved = $this->Users->Privileges->save($privileges);
                }
                return $userSaved !== false;
            });
            if ($success) {
                $this->Flash->success(__("User account created."));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user', 'location'));
    }

    public function members()
    {
        //FIXME do this
        $this->paginate = [
            'contain' => ['Locations']
        ];
        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        //FIXME do this, update privileges
        $user = $this->Users->get($id, [
            'contain' => ['Locations']
        ]);

        $this->set('user', $user);
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        //FIXME do this, remove view
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $locations = $this->Users->Locations->find('list', ['limit' => 200]);
        $this->set(compact('user', 'locations'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        
        $errorMessage = __('The user could not be deleted. Please, try again.');
        if ($id != 1) {
            $user = $this->Users->get($id);
            if ($this->Users->delete($user))
                $this->Flash->success(__('The user has been deleted.'));
            else
                $this->Flash->error($errorMessage );
        }
        else
            $this->Flash->error($errorMessage );

        return $this->redirect(['action' => 'index']);
    }
}
