<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * SiteImages Controller
 *
 * @property \App\Model\Table\SiteImagesTable $SiteImages
 *
 * @method \App\Model\Entity\SiteImage[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SiteImagesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Documents', 'VolunteerSites', 'Users', 'PublicPosts']
        ];
        $siteImages = $this->paginate($this->SiteImages);

        $this->set(compact('siteImages'));
    }

    /**
     * View method
     *
     * @param string|null $id Site Image id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $siteImage = $this->SiteImages->get($id, [
            'contain' => ['Documents', 'VolunteerSites', 'Users', 'PublicPosts']
        ]);

        $this->set('siteImage', $siteImage);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $siteImage = $this->SiteImages->newEntity();
        if ($this->request->is('post')) {
            $siteImage = $this->SiteImages->patchEntity($siteImage, $this->request->getData());
            if ($this->SiteImages->save($siteImage)) {
                $this->Flash->success(__('The site image has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The site image could not be saved. Please, try again.'));
        }
        $documents = $this->SiteImages->Documents->find('list', ['limit' => 200]);
        $volunteerSites = $this->SiteImages->VolunteerSites->find('list', ['limit' => 200]);
        $users = $this->SiteImages->Users->find('list', ['limit' => 200]);
        $publicPosts = $this->SiteImages->PublicPosts->find('list', ['limit' => 200]);
        $this->set(compact('siteImage', 'documents', 'volunteerSites', 'users', 'publicPosts'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Site Image id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $siteImage = $this->SiteImages->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $siteImage = $this->SiteImages->patchEntity($siteImage, $this->request->getData());
            if ($this->SiteImages->save($siteImage)) {
                $this->Flash->success(__('The site image has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The site image could not be saved. Please, try again.'));
        }
        $documents = $this->SiteImages->Documents->find('list', ['limit' => 200]);
        $volunteerSites = $this->SiteImages->VolunteerSites->find('list', ['limit' => 200]);
        $users = $this->SiteImages->Users->find('list', ['limit' => 200]);
        $publicPosts = $this->SiteImages->PublicPosts->find('list', ['limit' => 200]);
        $this->set(compact('siteImage', 'documents', 'volunteerSites', 'users', 'publicPosts'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Site Image id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $siteImage = $this->SiteImages->get($id);
        if ($this->SiteImages->delete($siteImage)) {
            $this->Flash->success(__('The site image has been deleted.'));
        } else {
            $this->Flash->error(__('The site image could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
