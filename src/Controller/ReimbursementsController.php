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
        $this->hardDelete($this->Reimbursements, $id, $this->redirect(['action' => 'index']), 'reimbursement');
    }

    public function test() {
        $maxNumReceipts = 1;
        $this->loadModel('Documents');
        $documents = [];
        $receipts = [];
        for ($k=0; $k<$maxNumReceipts; $k++) {
            $documents[] = $this->Documents->newEntity();
            $receipts[] = $this->Reimbursements->Receipts->newEntity();
        }

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $numReceipts = $data['numreceipts'];
            $this->log('trying to save ' . $numReceipts . ' receipts', 'debug'); //FIXME remove
            $this->log($data, 'debug'); //FIXME remove
            for ($k=0; $k<$numReceipts; $k++) {
                $this->log('here', 'debug'); //FIXME remove
                $documents[$k] = $this->Documents->patchEntity($documents[$k], $data['documents'][$k]);
                $receipts[$k] = $this->Reimbursements->Receipts->patchEntity($documents[$k], $data['receipts'][$k]);
            }

            // if ($this->Documents->save($document)) {
            //     $this->Flash->success(__('The document has been saved.'));
            //     return $this->redirect(['action' => 'index']);
            // }
            // else
                $this->Flash->error(__("The document couldn't be saved"));
        }

        $this->set(compact('maxNumReceipts', 'documents', 'receipts'));
    }

    /**
    * Add method
    *
    * @return \Cake\Http\Response|null Redirects to index.
    */
    public function add() {
        //FIXME need to validate, make sure amount and file are nonempty for each receipt included. same with volunteer site
        $reimbursement = $this->Reimbursements->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $this->updateOtherRiderData($data);
            $reimbursement = $this->Reimbursements->patchEntity($reimbursement, $data, ['associated' => 'OtherRiders']);
            if (!$reimbursement->errors()) {
                $saveResult = $this->saveEverything($data, $reimbursement);
                if ($saveResult === true) {
                    $this->Flash->success(__('The reimbursement has been saved.'));
                    return $this->redirect(['action' => 'index']);
                }
                else if ($saveResult !== false)
                    $this->Flash->error(__($saveResult));
            }
        }

        $volunteerSites = $this->Reimbursements->VolunteerSites->find('list');
        //FIXME add selected site using SiteUsers
        $extraRiders = $this->Reimbursements->Users->find('list')
            ->where(['users.id !=' => 1])
            ->where(['users.id !=' => $this->Auth->user('id')]);
        $this->set(compact('reimbursement', 'volunteerSites', 'extraRiders'));
    }

    private function updateOtherRiderData(&$data) {
        $otherRiderData = &$data['other_riders'];
        $otherRiderIDs = $otherRiderData['user_ids'];
        if (!is_array($otherRiderIDs))
            return;
        foreach ($otherRiderIDs as $num => $id)
            $otherRiderData[$num]['user_id'] = $id;
        unset($otherRiderData['user_ids']);
    }

    private function saveEverything($data, $reimbursement) {
        //return ConnectionManager::get('default')->transactional(function () use ($data, $reimbursement) {
        $this->DocumentsHelper->startNewSession();
        try {
            $receipts = [];
            foreach ($data['receipts'] as $receiptIndex => $receiptData) {
                $receipt = $this->Receipts->newEntity(['amount' => $receiptData['amount']]);
                $receipts[] = $receipt;
                if ($receipt->errors())
                    break;
                $name = $this->getReceiptFilename($reimbursement->date, $receiptIndex + 1);
                $newDocumentResult = $this->DocumentsHelper->newDocumentEntity($receiptData['filestuff'], ['png', 'jpg', 'pdf'], $name);
                if (is_string($newDocumentResult)) {
                    $this->DocumentsHelper->deleteSessionFiles();
                    return $newDocumentResult;
                }
                $receipt->document = $newDocumentResult;
            }
            $reimbursement->receipts = $receipts;
            $saveSuccess = $this->Reimbursements->save($reimbursement, ['associated' => ['OtherRiders', 'Receipts', 'Receipts.Documents']]);
            if (!$saveSuccess) {
                $this->DocumentsHelper->deleteSessionFiles();
                if ($reimbursement->errors())
                    return false;
                return "Reimbursement couldn't be saved. ";
            }
        }
        catch (Exception $e) {
            $this->DocumentsHelper->deleteSessionFiles();
            return "Problem occurred. Reimbursement couldn't be saved. ";
        }
        return true;
    }

    private function getReceiptFilename($date, $receiptNum) {
        $userFirstName = 'Test'; //FIXME 
        $userLastName = 'Gal'; 
        $dateString = $date->year . '-' . $date->month . '-' . $date->day;
        return $dateString . ' ' . $userFirstName . ' ' . $userLastName . '' . $receiptNum;
    }
}
