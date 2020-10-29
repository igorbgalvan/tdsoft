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
                'message' => 'Este método não é permitido.',
                'code' => 405
            ];
        }
        $this->set(compact('data'));
        $this->set('_serialize', 'data');
    }

    function idRequestReceiver($id = null)
    {
        if ($this->request->is('get')) {
            $this->view($id);
        } else if ($this->request->is('put')) {
            $this->edit($id);
        } else if ($this->request->is('delete')) {
            $this->delete($id);
        } else {
            $this->response = $this->response->withStatus(405);
            $data = [
                'message' => 'Este método não é permitido.',
                'code' => 405
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

        if (sizeof($alunos) == 0) {
            $this->response = $this->response->withStatus(200);
            $data = [
                'alunos' => "Nenhum aluno foi cadastrado no banco ainda.",
                'code' => 200
            ];
        } else {
            $this->response = $this->response->withStatus(200);
            $data = [
                'alunos' => $alunos,
                'code' => 200
            ];
        }



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
        $aluno = $this->Alunos->find('all', ['conditions' => ['id' => $id]])->first();

        if ($aluno) {
            $this->response = $this->response->withStatus(200);
            $data = ['aluno' => $aluno, 'code' => 200];
        } else {
            $this->response = $this->response->withStatus(404);
            $data = ['message' => 'Usuário não encontrado.', 'code' => 404];
        }

        $this->set(compact('data'));
        $this->set('_serialize', 'data');
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
                'message' => 'O aluno foi cadastrado com sucesso',
                'code' => 201
            ];
        } else {

            $this->response = $this->response->withStatus(400);
            $data = [
                'message' => 'O aluno foi não foi cadastrado, tente novamente',
                'code' => 400
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
        $aluno = $this->Alunos->find('all', ['conditions' => ['id' => $id]])->first();
        if ($aluno) {
            $aluno = $this->Alunos->patchEntity($aluno, $this->request->getData());
            if ($this->Alunos->save($aluno)) {
                $this->response = $this->response->withStatus(200);
                $data = ['aluno' => $aluno, 'code' => 200];
            }
        } else {
            $this->response = $this->response->withStatus(404);

            $data = ['message' => 'Aluno não encontrado.', 'code' => 404];
        }



        $this->set(compact('data'));
        $this->set('_serialize', 'data');
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
        $aluno = $this->Alunos->find('all', ['conditions' => ['id' => $id]])->first();
        if ($aluno) {
            if ($this->Alunos->delete($aluno)) {
                $this->response = $this->response->withStatus(200);

                $data = ['aluno' => $aluno, 'code' => 200];
            }
        } else {
            $this->response = $this->response->withStatus(404);

            $data = ['message' => 'Aluno não encontrado.', 'code' => 404];
        }

        $this->set(compact('data'));
        $this->set('_serialize', 'data');
    }
}
