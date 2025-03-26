<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\Log\Log;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use App\Service\UsersPlatformService;
/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {

        $this->Authentication->addUnauthenticatedActions(['login', 'add','indexapi']);
        parent::beforeFilter($event);
        
    }
    public function indexapi() {
        $this->autoRender = false; // Desativa a renderização automática da visão
        $users = $this->Users->find('all')->toArray();
        /* $this->jsonResponse($users, 200);*/
        $this->response = $this->response->withType('application/json')
        ->withStringBody(json_encode($users    ));
        return $this->response;
    }
    /** 
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('user'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    public function login() {
		$this->viewBuilder()->setLayout("login");
		$this->request->allowMethod(['get', 'post']);
		$result = $this->Authentication->getResult();
		// regardless of POST or GET, redirect if user is logged in
		if ($result && $result->isValid()) {
			$_SESSION['bLogin'] = true;
			$redirect = $this->request->getQuery('redirect', [
				'controller' => 'Logs',
				'action' => 'index',
			]);
			return $this->redirect($redirect);
		}

		if ($this->request->is('post') && !$result->isValid()) {
			$this->Flash->error(__('Usuário ou senha inválidos!'));
		}
	}

	public function logout() {
		$this->Authentication->logout();
		return $this->redirect(['controller' => 'Pages', 'action' => 'display', 'indexhome']);
	}

	public function atualizardarkmode() {
		$this->autoRender = false; // Desativa a renderização automática da visão

		if ($this->request->is('ajax')) {
			$userData = $this->Authentication->getIdentity()->getOriginalData();
			$usuarioId = $userData->id; // Obtém o ID do usuário autenticado
			$darkMode = $this->request->getData('darkmode'); // Obtém o valor do campo darkmode enviado por AJAX

			$usuario = $this->Users->findById($usuarioId)->first();
			$usuario->darkmode = $darkMode;
			$this->Users->save($usuario);
			return $this->jsonResponse(['Msg' => 'Alterado com sucesso'], 200);
		}
	}    
}
