<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Logs Controller
 *
 * @property \App\Model\Table\LogsTable $Logs
 */
class LogsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Logs->find()
            ->contain(['Devices', 'Platforms']);
        $logs = $this->paginate($query);

        $this->set(compact('logs'));
    }

    /**
     * View method
     *
     * @param string|null $id Log id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $log = $this->Logs->get($id, contain: ['Devices', 'Platforms']);
        $this->set(compact('log'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $log = $this->Logs->newEmptyEntity();
        if ($this->request->is('post')) {
            $log = $this->Logs->patchEntity($log, $this->request->getData());
            if ($this->Logs->save($log)) {
                $this->Flash->success(__('The log has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The log could not be saved. Please, try again.'));
        }
        $devices = $this->Logs->Devices->find('list', limit: 200)->all();
        $platforms = $this->Logs->Platforms->find('list', limit: 200)->all();
        $this->set(compact('log', 'devices', 'platforms'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Log id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $log = $this->Logs->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $log = $this->Logs->patchEntity($log, $this->request->getData());
            if ($this->Logs->save($log)) {
                $this->Flash->success(__('The log has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The log could not be saved. Please, try again.'));
        }
        $devices = $this->Logs->Devices->find('list', limit: 200)->all();
        $platforms = $this->Logs->Platforms->find('list', limit: 200)->all();
        $this->set(compact('log', 'devices', 'platforms'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Log id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $log = $this->Logs->get($id);
        if ($this->Logs->delete($log)) {
            $this->Flash->success(__('The log has been deleted.'));
        } else {
            $this->Flash->error(__('The log could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
