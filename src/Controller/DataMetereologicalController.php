<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * DataMetereological Controller
 *
 * @property \App\Model\Table\DataMetereologicalTable $DataMetereological
 */
use App\Model\Table\CitiesTable;
use App\Controller\AppController;
use Cake\Log\Log;

class DataMetereologicalController extends AppController
{
    /**
     * @var \App\Model\Table\CitiesTable
     */
    private $Cities;

    public function initialize(): void    
    {    

        parent::initialize();
        $this->Cities = $this->fetchTable('Cities');
    }
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

    /**
     * Método para adicionar dados meteorológicos
   
     */

    public function addmetereological()
{
    $this->autoRender = false;
    set_time_limit(0);

    $adicionados = 0;
    $repetidos = 0;
    $erros = 0;

    $this->fetchTable('Cities');
    $cidades = $this->Cities->find()->all();

    foreach ($cidades as $cidade) {
        $url = "http://servicos.cptec.inpe.br/XML/cidade/7dias/{$cidade->cod_ibge}/previsao.xml";
        $retorno = $this->makeRequestWithCurl('GET', $url, [], []);

        if ($retorno === null || empty($retorno['body'])) {
            $erros++;
            continue;
        }

        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($retorno['body']);

        if ($xml === false || !isset($xml->previsao)) {
            $erros++;
            continue;
        }

        foreach ($xml->previsao as $previsao) {
            $dataHora = date('Y-m-d H:i:s', strtotime($previsao->data . ' ' . $previsao->hora));

            // Evita duplicação
            $existe = $this->DataMetereological->find()
                ->where([
                    'location_id' => $cidade->id, // assumindo que location_id mapeia para cities.id
                    'date_time' => $dataHora
                ])
                ->first();

            if ($existe) {
                $repetidos++;
                continue;
            }

            $registro = $this->DataMetereological->newEmptyEntity();

            $registro->date_time = $dataHora;
            $registro->temperature = isset($previsao->maxima) ? floatval($previsao->maxima) : null;
            $registro->humidity = null;
            $registro->precipitation = null;
            $registro->wind_direction = null;
            $registro->wind_speed = null;
            $registro->latitude = null; // Não disponível nem na tabela Cities
            $registro->longitude = null;
            $registro->location_id = $cidade->id; // Cities → location_id
            $registro->service_id = 1; // ajuste conforme o ID do serviço CPTEC
            $registro->device_id = null;
            $registro->created = date('Y-m-d H:i:s');
            $registro->modified = date('Y-m-d H:i:s');
            $registro->role = 0;
            $registro->type = 0;

            if ($this->DataMetereological->save($registro)) {
                $adicionados++;
            } else {
                $erros++;
            }
        }
    }

    $this->Flash->success(__("$adicionados registros meteorológicos adicionados"));
    $this->Flash->error(__("$repetidos registros já existiam"));
    $this->Flash->error(__("$erros falharam ao serem salvos"));
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
