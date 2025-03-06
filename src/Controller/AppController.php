<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;

use Cake\View\JsonView;
use Cake\View\XmlView;
use Cake\Event\EventInterface;
use Authentication\AuthenticationService;
use Cake\Controller\Component\AuthComponent;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/4/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('FormProtection');`
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        //$this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('Authentication.Authentication');
		$this->loadComponent('Authorization.Authorization');

        $this->fetchTable('Users');

		$this->Authorization->skipAuthorization();

        /*
         * Enable the following component for recommended CakePHP form protection settings.
         * see https://book.cakephp.org/4/en/controllers/components/form-protection.html
         */
        //$this->loadComponent('FormProtection');
    }

    
	public function beforeFilter(\Cake\Event\EventInterface $event) {
		parent::beforeFilter($event);

		$userLogado = $this->Authentication->getIdentity();
		// Skip authorization for DebugKit
		if ($this->request->getParam('plugin') === 'DebugKit') {
			$this->Authorization->skipAuthorization();
		}

		if(!empty($userLogado)) {
			$userData = $this->Authentication->getIdentity()->getOriginalData();
			$usuarioId = $userData->id; // Obtém o ID do usuário autenticado
			$this->userObj = $this->Users->get($usuarioId);
			$this->set('darkMode', $this->userObj->darkmode);
			$this->set('iduserLogado', $this->userObj->id);
			$this->set('role', $this->userObj->role);

			$currentController = $this->request->getParam('controller');
			$currentAction = $this->request->getParam('action');
			$this->set('currentAction', $currentAction);
			$this->set('currentController', $currentController);
		}
	}

	public function isAuthorized($user)	{
		return true;
	}

	public function viewClasses(): array {
		return [JsonView::class];
	}

	public function jsonResponse($responseData = [], $responseStatusCode = 200) {
		return $this->response
		->withType('json', ['application/json'])
		->withStatus($responseStatusCode)
		->withStringBody(json_encode($responseData));
		
	}
}
