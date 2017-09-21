<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports_Salary extends MY_Controller {

    public function data() {
        try {
            $employee = $this->isDirector()
                ? $this->input->post('employee')
                : $this->getUserID();

            $year = $this->input->post('year');
            $month = $this->input->post('month') + 1; // Месяца на клиенте 0-11

            // проверяем, был ли уже отправлен отчет за этот месяц в сводную таблицу
            // если был отправлен – не сохраняем изменения и выдаём исключение
            $overlaySalaryRecord = $this->getReportModel()->reportOverlaySalaryFindWithoutSite($employee, $year, $month);

            $data = $this->getReportModel()->reportSalary($employee, $year, $month);

            $this->json_response(["status" => 1, 'records' => $data, 'noEdit' => ((!empty($overlaySalaryRecord)) ? 1 : 0)]);
        } catch (Exception $e) {
            $this->json_response(['status' => 0, 'message' => $e->getMessage()]);
        }
    }

    public function save() {
        try {
            $year = $this->input->post('year');
            $month = $this->input->post('month') + 1; // Месяца на клиенте 0-11
            $idEmployeeSite = $this->input->post('idEmployeeSite');
            $type = $this->input->post('type');
            $value = $this->input->post('value');

            // проверяем, был ли уже отправлен отчет за этот месяц в сводную таблицу
            // если был отправлен – не сохраняем изменения и выдаём исключение
            // проверяем сводный отчет этого сотрудника по конкретному сайту за год и месяц – не используем
//            $employeeSiteInfo = $this->getEmployeeModel()->siteGet($idEmployeeSite);
//            $overlaySalaryRecord = $this->getReportModel()->reportOverlaySalaryFind($this->getUserID(), $employeeSiteInfo['SiteID'], $year, $month);
            // проверяем сводный отчет этого сотрудника за год и месяц - используем
            $overlaySalaryRecord = $this->getReportModel()->reportOverlaySalaryFindWithoutSite($this->getUserID(), $year, $month);

            if (empty($overlaySalaryRecord)) {
                $record = $this->getReportModel()->reportSalaryFind($year, $month, $idEmployeeSite);

                if (empty($record)) {
                    $this->getReportModel()->reportSalaryInsert($year, $month, $idEmployeeSite, $type, $value);
                } else {
                    $this->getReportModel()->reportSalaryUpdate($record['id'], $type, $value);
                }
            } else {
                throw new RuntimeException('Данные уже отправлены в сводную таблицу');
            }

            $this->json_response(["status" => 1]);
        } catch (Exception $e) {
            $this->json_response(['status' => 0, 'message' => $e->getMessage()]);
        }
    }

    public function close() {
        try {
            $employee = $this->getUserID();
            $year = $this->input->post('year');
            $month = $this->input->post('month') + 1; // Месяца на клиенте 0-11

            // проверяем, был ли уже отправлен отчет за этот месяц в сводную таблицу
            // если был отправлен – не сохраняем данные в сводную таблицу и выдаём исключение
            $overlaySalaryRecord = $this->getReportModel()->reportOverlaySalaryFindWithoutSite($employee, $year, $month);

            if(empty($overlaySalaryRecord)){
                $this->getReportModel()->reportSalaryClose($employee, $year, $month);
            }
            else{
                throw new RuntimeException('Данные уже отправлены в сводную таблицу');
            }

            $this->json_response(["status" => 1]);
        } catch (Exception $e) {
            $this->json_response(['status' => 0, 'message' => $e->getMessage()]);
        }
    }

    public function checkoverlay()
    {
        $employee = $this->getUserID();
        $year = $this->input->post('year');
        $month = $this->input->post('month') + 1; // Месяца на клиенте 0-11
        $overlaySalaryRecord = $this->getReportModel()->reportOverlaySalaryFindWithoutSite($employee, $year, $month);
        echo (!empty($overlaySalaryRecord)) ? 1 : 0;
    }
}
