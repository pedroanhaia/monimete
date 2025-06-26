<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\ORM\TableRegistry;
use App\Model\Entity\Logs;
/**
 * Devices Controller
 *
 * @property \App\Model\Table\DevicesTable $Devices
 */
class DevicesController extends AppController
{
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {

        $this->Authentication->addUnauthenticatedActions(['inputdatadevices']);
        parent::beforeFilter($event);
        
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Devices->find()
            ->contain(['Locations']);
        $devices = $this->paginate($query);

        $this->set(compact('devices'));
    }

    /**
     * View method
     *
     * @param string|null $id Device id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $device = $this->Devices->get($id, contain: ['Locations', 'DataMetereological', 'DataSatellite', 'Logs', 'Settings']);
        $this->set(compact('device'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $device = $this->Devices->newEmptyEntity();
        if ($this->request->is('post')) {
            $device = $this->Devices->patchEntity($device, $this->request->getData());
            if ($this->Devices->save($device)) {
                $this->Flash->success(__('The device has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The device could not be saved. Please, try again.'));
        }
        $locations = $this->Devices->Locations->find('list', limit: 200)->all();
        $this->set(compact('device', 'locations'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Device id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $device = $this->Devices->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $device = $this->Devices->patchEntity($device, $this->request->getData());
            if ($this->Devices->save($device)) {
                $this->Flash->success(__('The device has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The device could not be saved. Please, try again.'));
        }
        $locations = $this->Devices->Locations->find('list', limit: 200)->all();
        $this->set(compact('device', 'locations'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Device id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $device = $this->Devices->get($id);
        if ($this->Devices->delete($device)) {
            $this->Flash->success(__('The device has been deleted.'));
        } else {
            $this->Flash->error(__('The device could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    
    public function inputdatadevices($deviceId = null)
    {
        $this->autoRender = false;
        // Identificar dispositivo
        //$deviceId = $this->request->getQuery('device_id');
        if (!$deviceId) {
            $this->response = $this->response->withStatus(400)->withStringBody('Device ID is required.');
            return $this->response;
        }

        $device = $this->Devices->find()
            ->where(['id' => $deviceId])
            ->first();

        if (!$device) {
            $this->response = $this->response->withStatus(404)->withStringBody('Device not found.');
            return $this->response;
        }

        
        // Decodificar JSON
        $jsonData = $this->request->getData('payload');
        if (!$jsonData) {
            $this->response = $this->response->withStatus(400)->withStringBody('Payload is required.');
            return $this->response;
        }
        
        $decodedData = json_decode($jsonData, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->response = $this->response->withStatus(400)->withStringBody('Invalid JSON payload.');
            return $this->response;
        }
        
        $this->fetchTable('Logs');
        $logsTable = TableRegistry::getTableLocator()->get('Logs');
        // Salvar na base de dados
        $dataEntity = $logsTable->newEmptyEntity();
        $dataEntity->device_id = $deviceId;
        $dataEntity->date_time = $decodedData['date_time'];
        $dataEntity->message = $jsonData;
        $dataEntity->status = $decodedData['status'];
        $dataEntity->type = $decodedData['data_type'];
        $dataEntity->platform_id = $device->platform_id;
        $sRet = $logsTable->save($dataEntity);
        if ($sRet) {
            $this->response = $this->response->withStatus(200)->withStringBody('Data saved successfully.');
        } else {
            $errors = $dataEntity->getErrors();
            Log::write('error', 'Erro ao salvar log: ' . json_encode($errors));
            $this->response = $this->response->withStatus(500)->withStringBody('Erro ao salvar dados.');
            return $this->response;
            // $this->response = $this->response->withStatus(500)->withStringBody('Failed to save data.');
        }

        return $this->response;
    }
}
