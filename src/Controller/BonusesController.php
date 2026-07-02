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
        $bonus = $this->Bonuses->get($id, [
            'contain' => ['Employees'],
        ]);

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

            // ================================
            // Validation 1 : Past Month
            // ================================

            $currentMonth = date('n');
            $currentYear  = date('Y');

            if (
        $data['payroll_year'] < $currentYear ||
        (
            $data['payroll_year'] == $currentYear &&
            $data['payroll_month'] < $currentMonth
        )
    ) {
                $this->Flash->error(
                    __('Bonus cannot be assigned for a past payroll month.')
                );
            } else {

        // ================================
                // Validation 2 : Payroll Already Generated
                // ================================

                $payslipExists = $this->Bonuses
            ->Employees
            ->Payslips
            ->find()
            ->where([
                'payroll_month' => $data['payroll_month'],
                'payroll_year'  => $data['payroll_year']
            ])
            ->count();

                if ($payslipExists > 0) {
                    $this->Flash->error(
                        __('Payroll has already been generated for this month. Bonus cannot be added.')
                    );
                } else {

            // ================================
                    // Save Bonus
                    // ================================

                    $bonus = $this->Bonuses->patchEntity($bonus, $data);

                    if ($this->Bonuses->save($bonus)) {
                        $this->Flash->success(__('The bonus has been saved.'));

                        return $this->redirect(['action' => 'index']);
                    }

                    $this->Flash->error(
                        __('The bonus could not be saved. Please, try again.')
                    );
                }
            }
        }

        $employees = $this->Bonuses->Employees->find('list', ['limit' => 200]);
        $employees = $this->Bonuses->Employees->find()
        ->select(['id','employee_code','name'])
        ->where(['status' => 'active'])
        ->order(['employee_code' => 'ASC'])
        ->all()
        ->combine(
            'id',
            function ($employee) {
                return $employee->employee_code . ' - ' . $employee->name;
            }
        )
        ->toArray();
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
        $bonus = $this->Bonuses->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $bonus = $this->Bonuses->patchEntity($bonus, $this->request->getData());
            if ($this->Bonuses->save($bonus)) {
                $this->Flash->success(__('The bonus has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The bonus could not be saved. Please, try again.'));
        }
        $employees = $this->Bonuses->Employees
        ->find()
        ->select(['id','employee_code','name'])
        ->where(['status' => 'active'])
        ->order(['employee_code' => 'ASC'])
        ->all()
        ->combine(
            'id',
            function ($employee) {
                return $employee->employee_code . ' - ' . $employee->name;
            }
        )
        ->toArray();
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
