<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports_Calls extends MY_Controller {

    public function data() {
        try {
            function folder($employee) {
                return [
                    'ID' => $employee['ID'],
                    'Name' => $employee['SName'].' '.$employee['FName'],
                    'Level' => 2
                ];
            }

            $this->json_response(["status" => 1, 'records' => array_map("folder", $this->getEmployeeModel()->employeeTranslatorGetList())]);
        } catch (Exception $e) {
            $this->json_response(['status' => 0, 'message' => $e->getMessage()]);
        }
    }

    /**
     * сохраняем звонок
     */
    public function save()
    {

    }
}
