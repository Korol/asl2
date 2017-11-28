<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports_Calls extends MY_Controller {

    public function data() {
        $return = '';
        $year = $this->input->post('year', true);
        $month = $this->input->post('month', true);
        if(empty($year) || empty($month)){
            echo $return; return;
        }
        $calls = $this->getCallsModel()->getCalls($year, $this->normalizeDate($month));
        if(!empty($calls)){
            $return = $this->load->view(
                'form/reports/calls/calls_table_rows',
                array(
                    'calls' => $calls,
                    'can_remove' => $this->isDirector(),
                ),
                true
            );
        }
        echo $return; return;
    }

    /**
     * сохраняем звонок
     */
    public function save()
    {
        $return = '';
        $CustomerID = $this->input->post('CustomerID', true);
        $CustomerName = $this->input->post('CustomerName', true);
        $Comment = $this->input->post('Comment', true);
        if(empty($CustomerID) || empty($CustomerName)){
            echo $return; return;
        }

        $employee = $this->getEmployeeModel()->employeeGet($this->getUserID());
        $data = array(
            'CustomerID' => (int)$CustomerID,
            'CustomerName' => $CustomerName,
            'EmployeeID' => $this->getUserID(),
            'EmployeeName' => ((!empty($employee)) ? $employee['SName'] . ' ' . $employee['FName'] : ''),
            'CreatedDate' => date('Y-m-d'),
            'CreatedTS' => date('Y-m-d H:i:s'),
            'Comment' => ((!empty($Comment)) ? strip_tags($Comment) : ''),
        );
        $id = $this->getCallsModel()->saveCall($data);
        if($id > 0){
            $data['ID'] = $id;
            $return = $this->load->view(
                'form/reports/calls/calls_table_row',
                array(
                    'call' => $data,
                    'can_remove' => $this->isDirector(),
                    'load_single' => true,
                ),
                true
            );
        }
        echo $return; return;
    }

    public function remove()
    {
        $return = 0;
        $ID = $this->input->post('ID', true);
        if(!empty($ID) && $this->isDirector()){
            $return = $this->getCallsModel()->removeCall($ID);
        }
        echo $return; return;
    }

    private function normalizeDate($item) {
        if (strlen($item) === 1) {
            $item = '0'.$item;
        }

        return $item;
    }
}
