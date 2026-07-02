<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Employees Controller
 *
 * @property \App\Model\Table\EmployeesTable $Employees //php doc comments for IDEs
 *
 * @method \App\Model\Entity\Employee[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class EmployeesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
      // Get search keyword from URL
      $search = $this->request->getQuery('search');
      $department = $this->request->getQuery('department');
      $status = $this->request->getQuery('status');

      // Start query
      $query = $this->Employees->find()
        ->contain(['Departments', 'Designations']);

     // Apply search if user entered something
     if (!empty($search)) {

        $query->where([
            'OR' => [
                'Employees.employee_code LIKE' => '%' . $search . '%',
                'Employees.name LIKE' => '%' . $search . '%',
                'Employees.email LIKE' => '%' . $search . '%',
                'Employees.mobile LIKE' => '%' . $search . '%',
            ]
        ]);
     }
     //4 deaprtment filter
    if (!empty($department)) 
        {
         $query->where(['Employees.department_id' => $department]);
        }

    if (!empty($status))
    {
      $query->where(['Employees.status' => $status]);
    }
    // ⭐ 5. LOAD DEPARTMENTS HERE ⭐
    $departments = $this->Employees
        ->Departments
        ->find('list')
        ->order(['name' => 'ASC']);

    // 6. Pagination  
    $this->paginate = ['limit' => 10, 'order'=>['Employees.name'=>'ASC']];
    $this->request = $this->request->withAttribute('paging',$this->request->getAttribute('paging'));
    $employees = $this->paginate($query);
    $this->set(compact('employees','search','department','departments','status'));
    }

    /**
     * View method
     *
     * @param string|null $id Employee id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)// called when /employees/view/5
    {
        $employee = $this->Employees->get($id, [
            'contain' => ['Departments', 'Designations', 'Attendances', 'Payslips'],
            ]);// access the other tables too using orm

        $this->set('employee', $employee);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $employee = $this->Employees->newEntity();
        if ($this->request->is('post')) {
            $employee = $this->Employees->patchEntity($employee, $this->request->getData());// it trigers the employee entity wiht the post data and checks valiadtion roles too
            if ($this->Employees->save($employee)) {
           $this->Flash->success(__('Employee added successfully.'));
             return $this->redirect(['action' => 'index']);

             } else {

             debug($employee->getErrors());
             die();

            }
      
            }
        $departments = $this->Employees->Departments->find('list', ['limit' => 200]);
        $designations = $this->Employees->Designations->find('list', ['limit' => 200]);
        $this->set(compact('employee', 'departments', 'designations'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Employee id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $employee = $this->Employees->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $employee = $this->Employees->patchEntity($employee, $this->request->getData());
            if ($this->Employees->save($employee)) {
                $this->Flash->success(__('The employee has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The employee could not be saved. Please, try again.'));
        }
        $departments = $this->Employees->Departments->find('list', ['limit' => 200]);
        $designations = $this->Employees->Designations->find('list', ['limit' => 200]);
        $this->set(compact('employee', 'departments', 'designations'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Employee id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);// deletion can be from post or delete req
        $employee = $this->Employees->get($id, ['contain' => ['Attendances', 'Payslips']]);

        if (!empty($employee->attendances) ||!empty($employee->payslips)) {
              $this->Flash->error(__('Employee cannot be deleted because attendance or payroll records exist.'));
                return $this->redirect(['action' => 'index']);
            }
        if ($this->Employees->delete($employee)) {
            $this->Flash->success(__('The employee has been deleted.'));
        } 
        else {
            $this->Flash->error(__('The employee could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
