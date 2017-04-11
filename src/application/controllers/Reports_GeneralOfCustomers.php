<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports_GeneralOfCustomers extends MY_Controller {

    public function meta() {
        try {
            $get = $this->input->get();
            $data = [
                'work_sites' => $this->getEmployeeModel()->siteAllEmployeeGetList(),
                //'customers' => $this->getEmployeeModel()->allEmployeeCustomerGetList()
            ];

            if(!empty($get['year']) && isset($get['month'])){
                $date = $get['year'] . '-'
                    . $this->normalizeDate(++$get['month']) . '-'
                    . ((empty($get['day'])) ? date('d') : $this->normalizeDate($get['day']));
                $data['customers'] = $this->getEmployeeModel()->allEmployeeCustomerGetListByDate($date);
            }
            else{
                $data['customers'] = $this->getEmployeeModel()->allEmployeeCustomerGetList();
            }

            $this->json_response(array("status" => 1, 'records' => $data));
        } catch (Exception $e) {
            $this->json_response(array('status' => 0, 'message' => $e->getMessage()));
        }
    }

    public function data() {
        try {
            $year = $this->input->post('year');
            $month = $this->input->post('month') + 1; // Месяца на клиенте 0-11
            $day = $this->input->post('day');

            if (empty($day)) {
                $data = $this->getReportModel()->reportGeneralOfCustomersGroupMonth($year, $month);
            } else {
                $date = date("Y-m-d", mktime(0, 0, 0, $month, $day, $year));
                $data = $this->getReportModel()->reportGeneralOfCustomers($date);
            }

            $this->json_response(array("status" => 1, 'records' => $data));
        } catch (Exception $e) {
            $this->json_response(array('status' => 0, 'message' => $e->getMessage()));
        }
    }

    private function normalizeDate($item) {
        if (strlen($item) === 1) {
            $item = '0'.$item;
        }

        return $item;
    }

}
