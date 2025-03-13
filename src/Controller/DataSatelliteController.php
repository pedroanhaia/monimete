<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * DataSatellite Controller
 *
 * @property \App\Model\Table\DataSatelliteTable $DataSatellite
 */
class DataSatelliteController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->DataSatellite->find()
            ->contain(['Devices']);
        $dataSatellite = $this->paginate($query);

        $this->set(compact('dataSatellite'));
    }

    /**
     * View method
     *
     * @param string|null $id Data Satellite id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $dataSatellite = $this->DataSatellite->get($id, contain: ['Devices']);
        $this->set(compact('dataSatellite'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $dataSatellite = $this->DataSatellite->newEmptyEntity();
        if ($this->request->is('post')) {
            $dataSatellite = $this->DataSatellite->patchEntity($dataSatellite, $this->request->getData());
            if ($this->DataSatellite->save($dataSatellite)) {
                $this->Flash->success(__('The data satellite has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The data satellite could not be saved. Please, try again.'));
        }
        $devices = $this->DataSatellite->Devices->find('list', limit: 200)->all();
        $this->set(compact('dataSatellite', 'devices'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Data Satellite id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $dataSatellite = $this->DataSatellite->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $dataSatellite = $this->DataSatellite->patchEntity($dataSatellite, $this->request->getData());
            if ($this->DataSatellite->save($dataSatellite)) {
                $this->Flash->success(__('The data satellite has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The data satellite could not be saved. Please, try again.'));
        }
        $devices = $this->DataSatellite->Devices->find('list', limit: 200)->all();
        $this->set(compact('dataSatellite', 'devices'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Data Satellite id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $dataSatellite = $this->DataSatellite->get($id);
        if ($this->DataSatellite->delete($dataSatellite)) {
            $this->Flash->success(__('The data satellite has been deleted.'));
        } else {
            $this->Flash->error(__('The data satellite could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
