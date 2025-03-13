<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * DataMetereological Controller
 *
 * @property \App\Model\Table\DataMetereologicalTable $DataMetereological
 */
class DataMetereologicalController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->DataMetereological->find()
            ->contain(['Locations', 'Services', 'Devices']);
        $dataMetereological = $this->paginate($query);

        $this->set(compact('dataMetereological'));
    }

    /**
     * View method
     *
     * @param string|null $id Data Metereological id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $dataMetereological = $this->DataMetereological->get($id, contain: ['Locations', 'Services', 'Devices']);
        $this->set(compact('dataMetereological'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $dataMetereological = $this->DataMetereological->newEmptyEntity();
        if ($this->request->is('post')) {
            $dataMetereological = $this->DataMetereological->patchEntity($dataMetereological, $this->request->getData());
            if ($this->DataMetereological->save($dataMetereological)) {
                $this->Flash->success(__('The data metereological has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The data metereological could not be saved. Please, try again.'));
        }
        $locations = $this->DataMetereological->Locations->find('list', limit: 200)->all();
        $services = $this->DataMetereological->Services->find('list', limit: 200)->all();
        $devices = $this->DataMetereological->Devices->find('list', limit: 200)->all();
        $this->set(compact('dataMetereological', 'locations', 'services', 'devices'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Data Metereological id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $dataMetereological = $this->DataMetereological->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $dataMetereological = $this->DataMetereological->patchEntity($dataMetereological, $this->request->getData());
            if ($this->DataMetereological->save($dataMetereological)) {
                $this->Flash->success(__('The data metereological has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The data metereological could not be saved. Please, try again.'));
        }
        $locations = $this->DataMetereological->Locations->find('list', limit: 200)->all();
        $services = $this->DataMetereological->Services->find('list', limit: 200)->all();
        $devices = $this->DataMetereological->Devices->find('list', limit: 200)->all();
        $this->set(compact('dataMetereological', 'locations', 'services', 'devices'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Data Metereological id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $dataMetereological = $this->DataMetereological->get($id);
        if ($this->DataMetereological->delete($dataMetereological)) {
            $this->Flash->success(__('The data metereological has been deleted.'));
        } else {
            $this->Flash->error(__('The data metereological could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
