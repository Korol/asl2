<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports_Daily extends MY_Controller {

    public function meta() {
        try {
            $from = $this->input->post('from');
            $to = $this->input->post('to');
            $employee = $this->isDirector()
                ? $this->input->post('employee')
                : $this->getUserID();

            $data = [
                'work_sites' => $this->getEmployeeModel()->siteGetList($employee),
                //'customers' => $this->getEmployeeModel()->employeeCustomerGetList($employee)
            ];

            $from = $this->prepareDate($from);
            $to = $this->prepareDate($to);

            if($from === $to){
                // отчёт за 1 день
                $data['customers'] = $this->getEmployeeModel()->employeeCustomerGetListByDate($employee, $from);
            }
            elseif(strtotime($to) < strtotime($from)){
                // дата $to некорректна – она меньше, чем дата $from
                // в этом случае показываем отчёт за сегодня
                $data['customers'] = $this->getEmployeeModel()->employeeCustomerGetListByDate($employee, date('Y-m-d'));
            }
            else{
                // отчет за период с $from по $to
                $data['customers'] = $this->getEmployeeModel()->employeeCustomerGetListByPeriod($employee, $from, $to);
            }

            $this->json_response(array("status" => 1, 'records' => $data));
        } catch (Exception $e) {
            $this->json_response(array('status' => 0, 'message' => $e->getMessage()));
        }
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

    public function data() {
        try {
            $employee = $this->isDirector()
                ? $this->input->post('employee')
                : $this->getUserID();

            $from = $original_from = $this->input->post('from');
            $to = $original_to = $this->input->post('to');

            $from = $this->prepareDate($from);
            $to = $this->prepareDate($to);
            if($from === $to){
                // отчёт за 1 день
                $data = $this->getReportModel()->reportDaily($employee, $from);
                $title = 'Показан отчет за ' . $original_from;
            }
            elseif(strtotime($to) < strtotime($from)){
                // дата $to некорректна – она меньше, чем дата $from
                // в этом случае показываем отчёт за сегодня
                $data = $this->getReportModel()->reportDaily($employee, date('Y-m-d'));
                $title = '<span style="color: red;">Вы указали некорректные даты: дата в поле «До» должна быть больше, чем дата в поле «С»!</span><br/>Показан отчет за сегодня, ' . date('d-m-Y');
            }
            else{
                // отчет за период с $from по $to
                $data = $this->getReportModel()->reportDailyGroupPeriod($employee, $from, $to);
                $title = 'Показан отчет за период с ' . $original_from . ' до ' . $original_to;
            }

            $this->json_response(array("status" => 1, 'records' => $data, 'title' => $title));
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
        $from = $original_from = $this->input->post('from', true);
        $to = $original_to = $this->input->post('to', true);
        $html = '';
        $daily_reports = array();

        if ($this->isDirector() || $this->isSecretary()){

            $from = $this->prepareDate($from);
            $to = $this->prepareDate($to);
            if($from === $to){
                // отчёт за 1 день
                $daily_reports = $this->getEmployeeModel()->getReportDailyEmployeesPeriod($from);
                $title = 'Показан отчет за ' . $original_from;
            }
            elseif(strtotime($to) < strtotime($from)){
                // дата $to некорректна – она меньше, чем дата $from
                // в этом случае показываем отчёт за сегодня
                $daily_reports = $this->getEmployeeModel()->getReportDailyEmployeesPeriod(date('Y-m-d'));
                $title = '<span style="color: red;">Вы указали некорректные даты: дата в поле «До» должна быть больше, чем дата в поле «С»!</span><br/>Показан отчет за сегодня, ' . date('d-m-Y');
            }
            else{
                // отчет за период с $from по $to
                $daily_reports = $this->getEmployeeModel()->getReportDailyEmployeesPeriod('', $from, $to);
                $title = 'Показан отчет за период с ' . $original_from . ' до ' . $original_to;
            }

            $data = array(
                'sites' => $this->getSiteModel()->getRecords(),
                'translators' => $this->getEmployeeModel()->employeeTranslatorGetList(),
                'daily_reports' => $daily_reports,
                'title' => $title,
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

    /**
     * данные для таблицы Ежедневный отчет
     */
    public function report()
    {
        $year = $this->input->post('year', true);
        $month = $this->input->post('month', true);
        $day = $this->input->post('day', true);
        $mode = $this->input->post('mode', true);
        $html = '';
        $daily_reports = array();
        $employee = $this->isDirector()
            ? (int)$this->session->userdata('translator_id')
            : $this->getUserID();
        
//        echo $year . ' ' . $month . ' ' . $day . ' ' . $mode . ' ' . $employee; return; // test

        if ($this->isDirector() || $this->isTranslate()){
            // проверяем данные
            if(in_array($mode, array('year', 'month'))){
                if(!empty($year) && !empty($month)){
                    // данные за месяц, без дня
                    $daily_reports = $this->getReportModel()->reportDailyGroupMonthNew($employee, $year, $month);
                }
            }
            elseif($mode == 'day'){
                if(!empty($year) && !empty($month) && !empty($day)){
                    // данные за указанную дату
                    $date = date("Y-m-d", mktime(0, 0, 0, $month, $day, $year));
                    $daily_reports = $this->getReportModel()->reportDailyNew($employee, $date);
                }
                elseif(!empty($year) && !empty($month)){
                    // данные за месяц, без дня
                    $daily_reports = $this->getReportModel()->reportDailyGroupMonthNew($employee, $year, $month);
                }
            }
            else{
                echo $html; return;
            }

            $data = array(
                'sites' => $this->getEmployeeModel()->siteGetListNew($employee),
                'daily_reports' => $daily_reports,
            );

            if(!empty($year) && !empty($month)){
                $date = $year . '-'
                    . $this->normalizeDate($month) . '-'
                    . ((empty($day)) ? date('d') : $this->normalizeDate($day));
                $data['customers'] = $this->getEmployeeModel()->employeeCustomerGetListByDate($employee, $date);
            }
            else{
                $data['customers'] = $this->getEmployeeModel()->employeeCustomerGetList($employee);
            }

            $data['isTranslate'] = $this->isTranslate();

            $html = $this->load->view('form/reports/individual/dr_table', $data, true);
        }

        echo $html;
    }

    public function savetranslator()
    {
        $id = $this->input->post('id', true);
        if(!empty($id)){
            $this->session->set_userdata('translator_id', $id);
        }
        return;
    }
}
