<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports_Daily extends MY_Controller {

    public function meta() {
        try {
            $year = $this->input->post('year');
            $month = $this->input->post('month') + 1;// Месяца на клиенте 0-11
            $day = $this->input->post('day');
            $employee = $this->isDirector()
                ? $this->input->post('employee')
                : $this->getUserID();

            $data = [
                'work_sites' => $this->getEmployeeModel()->siteGetList($employee),
                //'customers' => $this->getEmployeeModel()->employeeCustomerGetList($employee)
            ];

            if(!empty($year) && !empty($month)){
                $date = $year . '-'
                    . $this->normalizeDate($month) . '-'
                    . ((empty($day)) ? date('d') : $this->normalizeDate($day));
                $data['customers'] = $this->getEmployeeModel()->employeeCustomerGetListByDate($employee, $date);
            }
            else{
                $data['customers'] = $this->getEmployeeModel()->employeeCustomerGetList($employee);
            }

            $this->json_response(array("status" => 1, 'records' => $data));
        } catch (Exception $e) {
            $this->json_response(array('status' => 0, 'message' => $e->getMessage()));
        }
    }

    public function data() {
        try {
            $employee = $this->isDirector()
                ? $this->input->post('employee')
                : $this->getUserID();

            $year = $this->input->post('year');
            $month = $this->input->post('month') + 1; // Месяца на клиенте 0-11
            $day = $this->input->post('day');

            if (empty($day)) {
                $data = $this->getReportModel()->reportDailyGroupMonth($employee, $year, $month);
            } else {
                $date = date("Y-m-d", mktime(0, 0, 0, $month, $day, $year));
                $data = $this->getReportModel()->reportDaily($employee, $date);
            }

            $this->json_response(array("status" => 1, 'records' => $data));
        } catch (Exception $e) {
            $this->json_response(array('status' => 0, 'message' => $e->getMessage()));
        }
    }

    public function save() {
        try {
            $dateRecord = $this->input->post('dateRecord');
            $idCross = $this->input->post('idCross');
            $mails = $this->input->post('mails');
            $chat = $this->input->post('chat');

            $record = $this->getReportModel()->reportDailyFind($dateRecord, $idCross);

            if (empty($record)) {
                $this->getReportModel()->reportDailyInsert($dateRecord, $idCross, $mails, $chat);
            } else {
                $this->getReportModel()->reportDailyUpdate($record['id'], $mails, $chat);
            }

            $this->json_response(array("status" => 1));
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
     * данные для таблицы Ежедневный отчет по сотрудникам
     */
    public function employees()
    {
        $year = $this->input->post('year', true);
        $month = $this->input->post('month', true);
        $day = $this->input->post('day', true);
        $mode = $this->input->post('mode', true);
        $html = '';
        $daily_reports = array();

        if ($this->isDirector() || $this->isSecretary()){
            // проверяем данные
            if(in_array($mode, array('year', 'month'))){
                if(!empty($year) && !empty($month)){
                    // данные за месяц, без дня
                    $daily_reports = $this->getEmployeeModel()->getReportDailyEmployees($year, $month);
                }
            }
            elseif($mode == 'day'){
                if(!empty($year) && !empty($month) && !empty($day)){
                    // данные за указанную дату
                    $daily_reports = $this->getEmployeeModel()->getReportDailyEmployees($year, $month, $day);
                }
                elseif(!empty($year) && !empty($month)){
                    // данные за месяц, без дня
                    $daily_reports = $this->getEmployeeModel()->getReportDailyEmployees($year, $month);
                }
            }
            else{
                echo $html; return;
            }

            $data = array(
                'sites' => $this->getSiteModel()->getRecords(),
                'translators' => $this->getEmployeeModel()->employeeTranslatorGetList(),
                'daily_reports' => $daily_reports,
            );
            $html = $this->load->view('form/reports/general/de_table', $data, true);
        }

        echo $html;
    }

    /**
     * options для выпадающего списка Дней в фильтре
     */
    public function days()
    {
        $year = $this->input->post('year', true);
        $month = $this->input->post('month', true);
        $html = '';

        if(!empty($year) && !empty($month)){
            $days = date('t', strtotime($year . '-' . $month));
            $html .= '<option value="0" selected="selected">за месяц</option>';
            for ($i = 1; $i <= $days; $i++){
                $html .= '<option value="' . $this->normalizeDate($i) . '">' . $i . '</option>';
            }
        }
        echo $html;
    }
}
