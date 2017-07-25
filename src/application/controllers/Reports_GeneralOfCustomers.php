<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports_GeneralOfCustomers extends MY_Controller {

    public function meta() {
        try {
            $from = $this->input->post('from');
            $to = $this->input->post('to');
            $from = $this->prepareDate($from);
            $to = $this->prepareDate($to);

//            $get = $this->input->get();
            $data = [
                'work_sites' => $this->getEmployeeModel()->siteAllEmployeeGetList(),
            ];

//            if(!empty($get['year']) && isset($get['month'])){
//                $date = $get['year'] . '-'
//                    . $this->normalizeDate(++$get['month']) . '-'
//                    . ((empty($get['day'])) ? date('d') : $this->normalizeDate($get['day']));
//                $data['customers'] = $this->getEmployeeModel()->allEmployeeCustomerGetListByDate($date);
//            }
//            else{
//                $data['customers'] = $this->getEmployeeModel()->allEmployeeCustomerGetList();
//            }

            if($from === $to){
                // отчёт за 1 день
                $data['customers'] = $this->getEmployeeModel()->allEmployeeCustomerGetListByDate($from);
//                $data['customers'] = $this->getEmployeeModel()->employeeCustomerGetListByDate($employee, $from);
            }
            elseif(strtotime($to) < strtotime($from)){
                // дата $to некорректна – она меньше, чем дата $from
                // в этом случае показываем отчёт за сегодня
                $data['customers'] = $this->getEmployeeModel()->allEmployeeCustomerGetListByDate(date('Y-m-d'));
//                $data['customers'] = $this->getEmployeeModel()->employeeCustomerGetListByDate($employee, date('Y-m-d'));
            }
            else{
                // отчет за период с $from по $to
                $data['customers'] = $this->getEmployeeModel()->allEmployeeCustomerGetListByPeriod($from, $to);
//                $data['customers'] = $this->getEmployeeModel()->employeeCustomerGetListByPeriod($employee, $from, $to);
            }

            $this->json_response(array("status" => 1, 'records' => $data));
        } catch (Exception $e) {
            $this->json_response(array('status' => 0, 'message' => $e->getMessage()));
        }
    }

    public function data() {
        try {
//            $year = $this->input->post('year');
//            $month = $this->input->post('month') + 1; // Месяца на клиенте 0-11
//            $day = $this->input->post('day');
//
//            if (empty($day)) {
//                $data = $this->getReportModel()->reportGeneralOfCustomersGroupMonth($year, $month);
//            } else {
//                $date = date("Y-m-d", mktime(0, 0, 0, $month, $day, $year));
//                $data = $this->getReportModel()->reportGeneralOfCustomers($date);
//            }
//
//            $this->json_response(array("status" => 1, 'records' => $data));
            $from = $original_from = $this->input->post('from');
            $to = $original_to = $this->input->post('to');

            $from = $this->prepareDate($from);
            $to = $this->prepareDate($to);
            if($from === $to){
                // отчёт за 1 день
                $data = $this->getReportModel()->reportGeneralOfCustomers($from);
                $title = 'Показан отчет за ' . $original_from;
            }
            elseif(strtotime($to) < strtotime($from)){
                // дата $to некорректна – она меньше, чем дата $from
                // в этом случае показываем отчёт за сегодня
                $data = $this->getReportModel()->reportGeneralOfCustomers(date('Y-m-d'));
                $title = '<span style="color: red;">Вы указали некорректные даты: дата в поле «До» должна быть больше, чем дата в поле «С»!</span><br/>Показан отчет за сегодня, ' . date('d-m-Y');
            }
            else{
                // отчет за период с $from по $to
                $data = $this->getReportModel()->reportGeneralOfCustomersGroupPeriod($from, $to);
                $title = 'Показан отчет за период с ' . $original_from . ' до ' . $original_to;
            }

            $this->json_response(array("status" => 1, 'records' => $data, 'title' => $title));
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

    /**
     * Перевод даты из формата d-m-Y в формат Y-m-d
     * @param $date - дата в формате d-m-Y
     * @return string - дата в формате Y-m-d
     */
    public function prepareDate($date)
    {
        $date_ex = array();
        if(strpos($date, '-') !== false)
            $date_ex = explode('-', $date);
        return (count($date_ex) == 3) ? $date_ex[2] . '-' . $date_ex[1] . '-' . $date_ex[0] : date('Y-m-d');
    }

}
