<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_ServicesAccess extends MY_Controller {

    /**
     * Функция проверки прав доступа
     */
    function assertUserRight() {
        if ($this->user['ID'] > 1)
            show_error('Данный раздел доступен только для роли "Директор" с ID=1', 403, 'Доступ запрещен');
    }

    public function template() {
        try {
            // 1. Проверка прав доступа
            $this->assertUserRight();

            // 2. Получение данных
            $res = $this->getEmployeeModel()->getListByRoles(array(10001, 10002, 10003));
            $access = $this->getEmployeeModel()->getServicesAccessList();
            $users = array();
            if(!empty($res)){
                foreach ($res as $row) {
                    $row['Access'] = 0;
                    if(!empty($access)){
                        foreach ($access as $arow) {
                            if($arow['EmployeeID'] == $row['ID']){
                                $row['Access'] = 1;
                            }
                        }
                    }
                    $users[] = array(
                        'ID' => $row['ID'],
                        'Name' => trim($row['SName']) . ' ' . trim($row['FName']),
                        'Access' => $row['Access'],
                        'Role' => $this->user['role_description'][$row['UserRole']],
                    );
                }
            }

            $this->json_response(array("status" => 1, 'users' => $users));
        } catch (Exception $e) {
            $this->json_response(array('status' => 0, 'message' => $e->getMessage()));
        }
    }

    public function template_update() {
        try {
            // 1. Проверка прав доступа
            $this->assertUserRight();

            $id = $this->input->post('id');
            $checked = $this->input->post('checked');

            if (empty($id))
                throw new RuntimeException("Не указан обязательный параметр");

            if(!empty($checked)){
                $this->getEmployeeModel()->addServicesAccessId($id);
            }
            else{
                $this->getEmployeeModel()->removeServicesAccessId($id);
            }

            $this->json_response(array("status" => 1));
        } catch (Exception $e) {
            $this->json_response(array('status' => 0, 'message' => $e->getMessage()));
        }
    }

}