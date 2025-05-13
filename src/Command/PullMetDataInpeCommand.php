<?php
declare(strict_types=1);

namespace App\Command;


use App\Model\Entity\DataMetereological;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Database\Expression\OrderByExpression;
use Cake\ORM\TableRegistry;
use Cake\Log\Log;


class PullMetDataInpeCommand extends Command
{
    /**
     * The name of this command.
     *
     * @var string
     */
    protected string $name = 'pullmetdatainpe';

    /**
     * Get the default command name.
     *
     * @return string
     */
    public static function defaultName(): string
    {
            return 'pullmetdatainpe';
        }
    
        /**
         * Makes an HTTP request using cURL.
         *
         * @param string $method The HTTP method (e.g., 'GET', 'POST').
         * @param string $url The URL to make the request to.
         * @param array $headers The headers to include in the request.
         * @param array $data The data to send with the request.
         * @return array|null The response body and status code, or null on failure.
         */
        protected function makeRequestWithCurl(string $method, string $url, array $headers = [], array $data = []): ?array
        {
            $ch = curl_init();
    
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    
            if (!empty($headers)) {
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            }
    
            if (!empty($data) && in_array($method, ['POST', 'PUT', 'PATCH'])) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            }
    
            $responseBody = curl_exec($ch);
            $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
            if (curl_errno($ch)) {
                curl_close($ch);
                return null;
            }
    
            curl_close($ch);
    
            return [
                'body' => $responseBody,
                'status' => $statusCode,
            ];
        }
    

        

    /**
     * Get the command description.
     *
     * @return string
     */
  /*  public static function getDescription(): string
    {
        return 'Command description here.';
    }
*/
    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/5/en/console-commands/commands.html#defining-arguments-and-options
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
/*    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        return parent::buildOptionParser($parser)
            ->setDescription(static::getDescription());
    }
*/
    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return int|null|void The exit code or null for success
     */

    public function execute(Arguments $args, ConsoleIo $io)
    {
        log::write('error',"entrou");
        $adicionados = 0;
        $repetidos = 0;
        $erros = 0;
    
        $citiesTable = $this->fetchTable('Cities');
        $cidades = $citiesTable->find("all",[
            'limit' => 5,
            'order'=>['datelastsearch' => 'ASC'], 
        ],
        )->toArray();
       Log::write('debug',"Cidades: ".json_encode($cidades));
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
                $existe = TableRegistry::getTableLocator()->get('DataMetereological')->find()
                    ->where([
                        'location_id' => $cidade->cod_ibge, // assumindo que location_id mapeia para cities.id
                        'date_time' => $dataHora
                    ])
                    ->first();
    
                if ($existe) {
                    $repetidos++;
                    continue;
                }
                $metdataTable = TableRegistry::getTableLocator()->get('DataMetereological');
                $registrometdata = $metdataTable->newEntity([]);
            
    
                $registrometdata->date_time = $dataHora;
                $registrometdata->tempmax = isset($previsao->maxima) ? floatval($previsao->maxima) : null;
                $registrometdata->tempemin = isset($previsao->minima) ? floatval($previsao->minima) : null;
                $registrometdata->humidity = null;
                $registrometdata->precipitation = null;
                $registrometdata->wind_direction = null;
                $registrometdata->wind_speed = null;
                $registrometdata->latitude = null; // Não disponível nem na tabela Cities
                $registrometdata->longitude = null;
                $registrometdata->location_id = null;//$cidade->cod_ibge; // Cities → location_id
                $registrometdata->service_id = null; // ajustar depois conectando com o service certo
                $registrometdata->device_id = null; // ajustar depois conectando com o device certo
                $registrometdata->role = 0;
                $registrometdata->type = TEMPERATURA;
                
                
                $deucerto=TableRegistry::getTableLocator()->get('DataMetereological')->save($registrometdata);
                if ($deucerto) {
                    
                    $adicionados++;
                } else {
                    $erros++;
                }
            }
            $cidade->datelastsearch = date('Y-m-d H:i:s');
            $citiesTable->save($cidade);
             
        }
        log::write('error',"Erros: $erros");
        log::write('error',"Repetidos: $repetidos");
        log::write('error',"Adicionados: $adicionados");
        
    
    }
}
