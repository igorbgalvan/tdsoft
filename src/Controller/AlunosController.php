<?php

namespace App\Controller;

use App\Controller\AppController;

/**
 * Alunos Controller
 *
 * @property \App\Model\Table\AlunosTable $Alunos
 *
 * @method \App\Model\Entity\Aluno[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AlunosController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function requestReceiver()
    {

        if ($this->request->is('get')) {

            $limite = $this->request->getQuery('limite');
            $pagina = $this->request->getQuery('pagina');
            $nome = $this->request->getQuery('nome');

            return $this->index($limite, $pagina, $nome);
        } else if ($this->request->is('post')) {
            $data = $this->request->getData();

            return $this->add($data);
        } else {
            $this->response = $this->response->withStatus(405);
            $data = [
                'message' => 'Este método não é permitido.'
            ];
        }
        $this->set(compact('data'));
        $this->set('_serialize', 'data');
    }

    function index($limite, $pagina, $nome)
    {

        if ($limite == null)
            $limite = 25;
        if ($nome == null)
            $alunos = $this->paginate($this->Alunos, ['limit' => $limite, 'page' => $pagina]);
        else {
            $alunos = $this->paginate(
                $this->Alunos->find('all')->where(['nome like' => "%" . $nome . "%"]),
                ['limit' => $limite, 'page' => $pagina]
            );
        }

        $this->response = $this->response->withStatus(200);
        $data = [
            'alunos' => $alunos
        ];

        $this->set(compact('data'));
        $this->set('_serialize', 'data');
    }

    /**
     * View method
     *
     * @param string|null $id Aluno id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $aluno = $this->Alunos->get($id, [
            'contain' => [],
        ]);

        $this->set('aluno', $aluno);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($data)
    {
        $aluno = $this->Alunos->newEntity();

        $aluno = $this->Alunos->patchEntity($aluno, $data);
        if ($this->Alunos->save($aluno)) {

            $this->response = $this->response->withStatus(201);
            $data = [
                'message' => 'O aluno foi cadastrado com sucesso'
            ];
        } else {

            $this->response = $this->response->withStatus(400);
            $data = [
                'message' => 'O aluno foi não foi cadastrado, tente novamente'
            ];
        }


        $this->set(compact('data'));
        $this->set('_serialize', 'data');
    }

    /**
     * Edit method
     *
     * @param string|null $id Aluno id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $aluno = $this->Alunos->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $aluno = $this->Alunos->patchEntity($aluno, $this->request->getData());
            if ($this->Alunos->save($aluno)) {
                $this->Flash->success(__('The aluno has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The aluno could not be saved. Please, try again.'));
        }
        $this->set(compact('aluno'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Aluno id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $aluno = $this->Alunos->get($id);
        if ($this->Alunos->delete($aluno)) {
            $this->Flash->success(__('The aluno has been deleted.'));
        } else {
            $this->Flash->error(__('The aluno could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
