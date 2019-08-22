<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;

/**
 * Reimbursements Controller
 *
 * @property \App\Model\Table\ReimbursementsTable $Reimbursements
 *
 * @method \App\Model\Entity\Reimbursement[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ReimbursementsController extends AppController
{
    public function initialize()
    {
        parent::initialize();

        $this->loadModel('Users');
        $this->loadModel('Receipts');
        $this->loadModel('OtherRiders');
        $this->loadModel('Documents');

        $this->loadComponent('DocumentsHelper');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {   
        $query = $this->getReimbursementsWithTotals($this->Auth->user('id'));
        $this->paginate = ['contain' => ['VolunteerSites']];
        $reimbursements = $this->paginate($query);
        $financeStats = $this->getAllReimbursementsStats($reimbursements);
        $this->set(compact('reimbursements', 'financeStats'));
    }

    private function getAllReimbursementsStats($reimbursements) {
        $sum = 0.0;
        $sumApproved = 0.0;
        foreach ($reimbursements as $reimbursement) {
            $reimbursement = $reimbursement->toArray();
            $sum += $reimbursement['total'];
            $sumApproved += $reimbursement['approved_total'];
        }
        return ['sum'=>$sum,'sum_approved'=>$sumApproved];
    }

    private function getReimbursementsWithTotals($userID)
    {
        $baseQuery = $this->Reimbursements->find();
        if (!is_null($userID)) {
            $baseQuery = $baseQuery->where(['reimbursements.user_id' => $userID]);
        }
        return $baseQuery
            ->contain(['Users', 'VolunteerSites', 'Receipts'])
            ->formatResults(function (\Cake\Collection\CollectionInterface $reimbursements) {
                return $reimbursements->map(function ($reimbursement) {
                    $total = 0.0;
                    $approved_total = 0.0;
                    if ($reimbursement->has('receipts')) {
                        $total = 0.0;
                        foreach ($reimbursement->receipts as $receipt) {
                            $total += $receipt->amount;
                            if ($receipt['approved'] === true) {
                                $approved_total += $receipt->amount;
                            }
                        }
                    }
                    $reimbursement['total'] = $total;
                    $reimbursement['approved_total'] = $approved_total;
                    return $reimbursement;
                });
            });
    }

    /**
     * All method
     *
     * @return \Cake\Http\Response|null
     */
    public function all()
    {   
        $this->request->allowMethod(['post', 'get']);
        $userID = null;
        if ($this->request->is('post')) {
            $requestedID = $this->request->getData('user_id');
            if ($requestedID !== null && $requestedID > 0) {
                $userID = $requestedID;
            }
        }
        $query = $this->getReimbursementsWithTotals($userID);
        $this->paginate = [
            'contain' => ['Users', 'VolunteerSites']
        ]; //FIXME make contain included in method above?
        $reimbursements = $this->paginate($query);
        $financeStats = $this->getAllReimbursementsStats($reimbursements);
        $allUsers = $this->Users->find('list')
            ->where(['users.id !=' => 1]);
        $this->set(compact('reimbursements', 'financeStats', 'allUsers'));
    }

    /**
     * View method
     *
     * @param string|null $id Reimbursement id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $reimbursement = $this->Reimbursements
            ->get($id, [
                'contain' => ['VolunteerSites', 'Receipts', 'Receipts.Documents']
            ]);

        $this->set(compact('reimbursement'));
    }

    public function viewpdf($id = null) {
        $trueFilename = WWW_ROOT . '2019-5-7 Test Gal1.png';
        $filename = WWW_ROOT . 'tmp-reimb.png';
        copy($trueFilename, $filename);
        $response = $this->response->withFile($filename);
        $this->log($filename, 'debug'); //FIXME remove
        $this->set(['filename', 'webroot/' . 'tmp-reimb.png']);
        return $response;
    }

    /**
     * Edit method
     *
     * @param string|null $id Reimbursement id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $reimbursement = $this->Reimbursements->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $reimbursement = $this->Reimbursements->patchEntity($reimbursement, $this->request->getData());
            if ($this->Reimbursements->save($reimbursement)) {
                $this->Flash->success(__('The reimbursement has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The reimbursement could not be saved. Please, try again.'));
        }
        $users = $this->Reimbursements->Users->find('list', ['limit' => 200]);
        $volunteerSites = $this->Reimbursements->VolunteerSites->find('list', ['limit' => 200]);
        $this->set(compact('reimbursement', 'users', 'volunteerSites'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Reimbursement id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
        //FIXME make sure its the user's reimbursement or the treasurer
        $this->hardDelete($this->Reimbursements, $id, $this->redirect(['action' => 'index']), 'reimbursement');
    }

    public function test() {
        $maxNumReceipts = 4;
        $reimbursement = $this->Reimbursements->newEntity();
        $documents = $this->newEntities($this->Documents, $maxNumReceipts);
        $receipts = $this->newEntities($this->Receipts, $maxNumReceipts);

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $numReceipts = $data['numreceipts'];
            $includedDocuments = \array_slice($documents, 0, $numReceipts);
            $includedReceipts = \array_slice($receipts, 0, $numReceipts);
            $this->patchAll($this->Documents, $includedDocuments, $data['documents']);
            $this->patchAll($this->Receipts, $includedReceipts, $data['receipts']);

            $saveSuccess = $this->saveDocuments($includedDocuments);

            if ($saveSuccess) {
                foreach ($includedReceipts as $k => $receipt)
                    $receipt->document_id = $includedDocuments[$k]->id;

                $saveSuccess = $this->createReimbursement($data, $reimbursement, $includedReceipts);
                if ($saveSuccess === false) {
                    foreach ($includedDocuments as $document)
                        $this->Documents->delete($document);
                }
            }

            if ($saveSuccess) {
                $this->Flash->success(__('The reimbursement has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            else {
                $this->log("Couldn't save reimbursement. Reimbursement, then document errors: ", 'debug');
                $this->log($reimbursement->getErrors(), 'debug');
                foreach ($includedDocuments as $document)
                    $this->log($document->getErrors(), 'debug');
                $this->Flash->error(__("The reimbursement couldn't be saved"));
            }
        }

        $volunteerSites = $this->Reimbursements->VolunteerSites->find('list');
        //FIXME add selected site using SiteUsers
        $extraRiders = $this->Reimbursements->Users->find('list')
            ->where(['users.id !=' => 1])
            ->where(['users.id !=' => $this->Auth->user('id')]);
        $this->set(compact('reimbursement', 'documents', 'receipts', 'volunteerSites', 'extraRiders'));
    }

    private function newEntities($table, $num) {
        $entities = [];
        for ($k = 0; $k < $num; $k++)
            $entities[] = $table->newEntity();
        return $entities;
    }

    private function patchAll($table, &$entities, $data) {
        for ($k = 0; $k < count($entities); $k++)
            $entities[$k] = $table->patchEntity($entities[$k], $data[$k]);
    }

    private function saveDocuments($documents) {
        return $this->Documents->getConnection()->transactional(function($connection) use ($documents) {
            $success = true;
            foreach ($documents as $document) 
                $success = $success && $this->Documents->save($document);
            return $success;
        });
    }

    private function createReimbursement($data, $reimbursement, $receipts) {
        $this->updateOtherRiderData($data);
        $reimbursement = $this->Reimbursements->patchEntity($reimbursement, $data, ['associated' => 'OtherRiders']);
        $reimbursement->receipts = $receipts;
        return $this->Reimbursements->save($reimbursement, ['associated' => ['OtherRiders', 'Receipts']]);
    }
}
