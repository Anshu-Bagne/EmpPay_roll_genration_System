<?php

namespace App\Controller;

/**
 * Bonuses Controller
 *
 * @property \App\Model\Table\BonusesTable $Bonuses
 *
 * @method \App\Model\Entity\Bonus[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class BonusesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Employees'],
        ];
        $bonuses = $this->paginate($this->Bonuses);

        $this->set(compact('bonuses'));
    }

    /**
     * View method
     *
     * @param string|null $id Bonus id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $bonus = $this->Bonuses->get($id, ['contain' => ['Employees'],]);
        $this->set('bonus', $bonus);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $bonus = $this->Bonuses->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();

            // Validation 1 : Past Month
            $validation = $this->Bonuses->validateBonusMonth($data['payroll_month'], $data['payroll_year']);
            if (!$validation['success']) {
                $this->Flash->error(__($validation['message']));
            } else {

             // Validation 2 : Payroll Already Generated
                if ($this->Bonuses->payrollExists($data['payroll_month'], $data['payroll_year'])) {
                    $this->Flash->error(
                        __('Payroll has already been generated for this month. Bonus cannot be added.')
                    );
                } else {
                    // Save Bonus
                    if ($this->Bonuses->saveBonus($data)) {
                        $this->Flash->success(__('The bonus has been saved.'));
                        return $this->redirect(['action' => 'index']);
                    }
                    $this->Flash->error(
                        __('The bonus could not be saved. Please, try again.')
                    );
                }
            }
        }
        //getting the emp having bonus
        $employees = $this->Bonuses->Employees->getBonusEmployees();

        $this->set(compact('bonus', 'employees'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Bonus id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $bonus = $this->Bonuses->get($id, ['contain' => [],]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $bonus = $this->Bonuses->patchEntity($bonus, $this->request->getData());
            if ($this->Bonuses->save($bonus)) {
                $this->Flash->success(__('The bonus has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The bonus could not be saved. Please, try again.'));
        }
        $employees = $this->Bonuses->Employees->getBonusEmployees();
        $this->set(compact('bonus', 'employees'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Bonus id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $bonus = $this->Bonuses->get($id);
        if ($this->Bonuses->delete($bonus)) {
            $this->Flash->success(__('The bonus has been deleted.'));
        } else {
            $this->Flash->error(__('The bonus could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
