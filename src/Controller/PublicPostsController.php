<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * PublicPosts Controller
 *
 * @property \App\Model\Table\PublicPostsTable $PublicPosts
 *
 * @method \App\Model\Entity\PublicPost[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PublicPostsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $publicPosts = $this->paginate($this->PublicPosts);

        $this->set(compact('publicPosts'));
    }

    /**
     * View method
     *
     * @param string|null $id Public Post id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $publicPost = $this->PublicPosts->get($id, [
            'contain' => []
        ]);

        $this->set('publicPost', $publicPost);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $publicPost = $this->PublicPosts->newEntity();
        if ($this->request->is('post')) {
            $publicPost = $this->PublicPosts->patchEntity($publicPost, $this->request->getData());
            if ($this->PublicPosts->save($publicPost)) {
                $this->Flash->success(__('The public post has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The public post could not be saved. Please, try again.'));
        }
        $this->set(compact('publicPost'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Public Post id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $publicPost = $this->PublicPosts->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $publicPost = $this->PublicPosts->patchEntity($publicPost, $this->request->getData());
            if ($this->PublicPosts->save($publicPost)) {
                $this->Flash->success(__('The public post has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The public post could not be saved. Please, try again.'));
        }
        $this->set(compact('publicPost'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Public Post id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $publicPost = $this->PublicPosts->get($id);
        if ($this->PublicPosts->delete($publicPost)) {
            $this->Flash->success(__('The public post has been deleted.'));
        } else {
            $this->Flash->error(__('The public post could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
