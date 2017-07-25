<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Services extends MY_Controller {

    public function index() {
        // проверка прав доступа
        // $this->checkServicesAccess();

        $data = array(
            'sites' => $this->getSiteModel()->getRecords(),
            'employees' => $this->getEmployeeModel()->employeeGetActiveList($this->getUserID(), $this->getUserRole())
        );

        $this->viewHeader($data);
        $this->view('form/services/index');
        $this->viewFooter([
            'js_array' => [
                'public/js/assol.services.js'
            ]
        ]);
    }

    public function find()
    {
        // get form data
        $user = $this->input->post('user', true);
        $role = $this->input->post('role', true);
        // test data
        // $user = 'ана';
        // $role = 'user';
        // tables by role
        $tables = array(
            'employee' => 'assol_employee',
            'user' => 'assol_customer',
        );
        // default response
        $res = array('status' => 0, 'message' => 'Users not found!');
        if(!empty($user)){
            // get users
            if($role == 'employee'){
                $users = $this->get_users($tables[$role], $user, 10003); // 10003 - переводчик
            }
            elseif($role == 'user'){
                $users = $this->get_users($tables[$role], $user);
            }
            // build response
            if(!empty($users)){
                $res = array('status' => 1);
                foreach($users as $row){
                    $res['records'][] = array(
                        'id' => $row['ID'],
                        'name' => $row['SName'] . ' ' . $row['FName'],
                    );
                }
            }

        }
        // send response
        $this->json_response($res);
    }

    /**
     * Клиенты или сотрудники для автокомплита встреч
     * @param string $table table name
     * @param string|int $user user name or ID
     * @param string $role
     * @return array
     */
    public function get_users($table, $user, $role = '')
    {
        $this->load->database(); // ?
        $this->db->select('ID, SName, FName');
        if(is_numeric($user)){
            $this->db->where(array(
                'ID' => $user,
                'IsDeleted' => 0,
            ));
        }
        else{
            $this->db->where(array(
                'IsDeleted' => 0,
            ));
            $this->db
                ->group_start()
                    ->like('FName', $user)
                    ->or_like('SName', $user)
                ->group_end();
        }
        if(!empty($role)){
            $this->db->where('UserRole', $role);
        }
        $this->db->limit(5);
        $res = $this->db->get($table)->result_array();//print $this->db->last_query();
        return (!empty($res)) ? $res : array();
    }

    public function checkServicesAccess()
    {
        $user_id = $this->getUserID();
        if($user_id == 1){
            $check = true;
        }
        else{
            $check = $this->getEmployeeModel()->checkServicesAccess($user_id);
        }
        if(empty($check)){
            show_error('Данный раздел для вас не доступен!', 403, 'Доступ запрещен');
        }
    }
}
