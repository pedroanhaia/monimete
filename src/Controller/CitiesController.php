<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\Http\Client;
use Cake\Collection\Collection;
/**
 * Cities Controller
 *
 * @property \App\Model\Table\CitiesTable $Cities
 */
class CitiesController extends AppController
{
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {

        $this->Authentication->addUnauthenticatedActions(['addinpedata']);
        parent::beforeFilter($event);
        
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $http = new Client();
        

        $query = $this->Cities->find();
        $cities = $this->paginate($query);

        $this->set(compact('cities'));
    }

    /**
     * View method
     *
     * @param string|null $id City id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $city = $this->Cities->get($id, contain: ['Locations', 'Users']);
        $this->set(compact('city'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $city = $this->Cities->newEmptyEntity();
        if ($this->request->is('post')) {
            $city = $this->Cities->patchEntity($city, $this->request->getData());
            if ($this->Cities->save($city)) {
                $this->Flash->success(__('The city has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The city could not be saved. Please, try again.'));
        }
        $this->set(compact('city'));
    }



    public function addinpedata(){
        $http = new Client();  
        $response = $http->get("http://servicos.cptec.inpe.br/XML/listaCidades");
        $xml = simplexml_load_string($response->getStringBody());
        if($this->request->is("get")) {
        foreach($xml ->cidade as $cidade) {
                $city = $this->Cities->newEmptyEntity();
                $city->name = $cidade->nome;
                $city->cod_ibge = $cidade->id;
                if ($this->Cities->save($city)) {
                    $this->Flash->success(__('The city has been saved.'));
                }else{
                    $this->Flash->error(__('The city could not be saved. Please, try again.'));
                }
            }}
            ;
       // $xml = $response->getBody()
        //$this->add();
        //$response = $http->get('http://servicos.cptec.inpe.br/XML/listaCidades');
        // if ($response->isOk()) {
            // Se o retorno for JSON, você pode decodificá-lo diretamente:
       //     $data = $xml;
        // } else {
            // Caso contrário, pode capturar o corpo da resposta ou tratar o erro
            
        // }
        
        // Passa os dados para a view
        //$this->set(compact('data'));
       // $xmlObject = simplexml_load_string($body, "SimpleXMLElement", LIBXML_NOCDATA);
        //$dataArray = json_decode(json_encode($xmlObject), true);
        //$collection = new Collection($dataArray);






    }

    /**
     * Edit method
     *
     * @param string|null $id City id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $city = $this->Cities->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $city = $this->Cities->patchEntity($city, $this->request->getData());
            if ($this->Cities->save($city)) {
                $this->Flash->success(__('The city has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The city could not be saved. Please, try again.'));
        }
        $this->set(compact('city'));
    }

    /**
     * Delete method
     *
     * @param string|null $id City id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $city = $this->Cities->get($id);
        if ($this->Cities->delete($city)) {
            $this->Flash->success(__('The city has been deleted.'));
        } else {
            $this->Flash->error(__('The city could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
