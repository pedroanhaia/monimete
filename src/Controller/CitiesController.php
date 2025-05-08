<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\Http\Client;
use Cake\Collection\Collection;

use Cake\Log\Log;
use SebastianBergmann\Invoker\TimeoutException;
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


    // Método alternativo utilizando cURL
    public function makeRequestWithCurl(string $method, string $url, array $data = [], array $headers = [])
    {
        $this->autorender = false;
        Log::write('debug', 'Executando requisição na URL: ' . $url);

        // Adicionar o token atual nos headers
        // $headers['Authorization'] = 'Bearer ' . $this->accessToken;
        // $headers['Content-Type'] = 'application/json';
        // $headers['Accept-Encoding'] = 'gzip, deflate';

        // Inicializar cURL
        $ch = curl_init();

        // Configurar o método HTTP
        $method = strtoupper($method);
        if ($method === 'POST' || $method === 'PUT' || $method === 'PATCH') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); // Enviar os dados como JSON
        }

        if ($method === 'GET' || $method === 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        }

        // Configurar as opções do cURL
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true, // Para capturar os cabeçalhos da resposta
            CURLOPT_HTTPHEADER => array_map(
                fn ($key, $value) => "$key: $value",
                array_keys($headers),
                $headers
            ),
            CURLOPT_ENCODING => '', // Suporte a gzip e deflate
            CURLOPT_TIMEOUT => 30,
        ]);

        // Executar a requisição
        $response = curl_exec($ch);
        if ($response === false) {
            throw new \Exception('Erro ao executar cURL: ' . curl_error($ch));
        }
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

        // Separar cabeçalhos e corpo
        $responseHeaders = substr($response, 0, $headerSize);
        $responseBody = substr($response, $headerSize);

        // Verificar erros de cURL
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            Log::write('error', 'Erro cURL: ' . $error);
            throw new \Exception('Erro na requisição cURL: ' . $error);
        }
        curl_close($ch);

        
        // Retornar a resposta como array
        return [
            'http_code' => $httpCode,
            'headers' => $responseHeaders,
            'body' => $responseBody,
        ];
    }
    public function addinpedata(){
        $this->autoRender = false; 
        set_time_limit(0);
        $adicionadas=0;
        $numRepetidas=0;
        $numErros=0;
        //itera de a-z duas vezes
        //pra pegar todas as cidades
        for ($letra = 'a';$letra != 'aa'; $letra++) {
        for ($segletra = 'a';$segletra != 'aa'; $segletra++) {
        $retorno = $this->makeRequestWithCurl('GET', "http://servicos.cptec.inpe.br/XML/listaCidades?city=$letra$segletra", [], []);
        
        if($retorno===null){
            throw new \Exception('Erro ao executar cURL: inpe fora do ar provavelmente');
        }
            // Testa se é XML
            libxml_use_internal_errors(true); // Para evitar warnings de XML inválido
            $xml = simplexml_load_string($retorno['body']);
          //  if ($this->request->is("get")) {
            foreach ($xml->cidade as $cidade) {
                    $city = $this->Cities->newEmptyEntity();
                    $city->name = $cidade->nome;
                    $city->cod_ibge = $cidade->id;
                    if($cidade->uf=='RS'){
                        $naors = false;
                    }else{
                        $naors = true;
                    }
                    $repetida=$this->Cities->find()->where(['cod_ibge' => $cidade->id])->first();//impede cadastrar duplicatas
                    if($repetida===null&&!$naors){
                        if ($this->Cities->save($city)) {
                           // $this->Flash->success(__('The city has been saved.'));
                            $adicionadas++;
                        }else{
                           // $this->Flash->error(__('The city could not be saved. Please, try again.'));
                            $numErros++;
                        }
                    }else{
                      //  $this->Flash->error(__('A cidade já existe na base de dados.'));
                        $numRepetidas++;
                    }
                }
         //   }
        }
    }$this->Flash->success(_("$adicionadas cidades foram adicionadas"));
    $this->Flash->error(_("$numRepetidas cidades já existiam na base de dados"));
    $this->Flash->error(_("$numErros cidades não foram adicionadas"));    

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
    public function removeDuplicates()
    {
        $this->autoRender = false;

        // Busca todas as cidades agrupadas pelo cod_ibge e conta quantas existem de cada
        $duplicates = $this->Cities->find()
            ->select(['cod_ibge', 'count' => 'COUNT(*)'])
            ->group(['cod_ibge'])
            ->having(['COUNT(*) >' => 1]) // Filtra apenas os que têm mais de uma ocorrência
            ->toArray();

        $deletedCount = 0;

        foreach ($duplicates as $duplicate) {
            // Busca todas as cidades com o mesmo cod_ibge
            $cities = $this->Cities->find()
                ->where(['cod_ibge' => $duplicate->cod_ibge])
                ->order(['id' => 'ASC']) // Ordena para manter o primeiro inserido e excluir os outros
                ->toArray();

            // Mantém a primeira cidade e deleta as outras
            array_shift($cities); // Remove o primeiro item da lista (mantém ele)

            foreach ($cities as $city) {
                if ($this->Cities->delete($city)) {
                    $deletedCount++;
                }
            }
        }

        //return $this->response->withType('application/json')
          //  ->withStringBody(json_encode([
            //    'message' => "Remoção de duplicatas concluída.",
              //  'deleted_count' => $deletedCount
            //]));
    }
    public function deletatudo(){
        $this->autoRender = false;
        $cities = $this->Cities->find()->all();
        foreach ($cities as $city) {
            if ($this->Cities->delete($city)) {
              //  $this->Flash->success(__('The city has been deleted.'));
            } else {
                $this->Flash->error(__('The city could not be deleted. Please, try again.'));
            }
        }
        return $this->redirect(['action' => 'index']);
    }

    public function dashboard()
    {
        $cities = $this->Cities->find()
            ->select(['id', 'name', 'cod_ibge'])
            ->toArray();

        
        if ($this->request->is(['post'])) {
            $this->viewBuilder()->setLayout('ajax'); // Define o layout para a resposta AJAX
        }
        $this->set(compact('cities'));
    }
}
