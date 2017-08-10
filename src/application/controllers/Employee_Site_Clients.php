<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_Site_Clients extends MY_Controller {

    public function data($EmployeeID, $idWorkSite) {
        try {
            if (!isset($EmployeeID))
                throw new RuntimeException("Не указан обязательный параметр");

            $records = $this->getEmployeeModel()->employeeSiteCustomerGetList($idWorkSite);

            $this->json_response(array("status" => 1, 'records' => $records));
        } catch (Exception $e) {
            $this->json_response(array('status' => 0, 'message' => $e->getMessage()));
        }
    }

    public function find($EmployeeID, $idSite, $idUser) {
        try {
            if (!isset($EmployeeID, $idSite, $idUser))
                throw new RuntimeException("Не указан обязательный параметр");

            $customer = $this->getCustomerModel()->customerGet($idUser);

            if ($customer) {
                $work_sites = $this->getCustomerModel()->siteGetList($idUser);
                $key = array_search($idSite, array_column($work_sites, 'SiteID'));
                $customer['SiteExists'] = (false !== $key);
            }

            $this->json_response(array("status" => 1, 'records' => $customer));
        } catch (Exception $e) {
            $this->json_response(array('status' => 0, 'message' => $e->getMessage()));
        }
    }

    public function save($EmployeeID, $EmployeeSiteID, $idCustomer) {
        try {
            if (!isset($EmployeeID, $EmployeeSiteID, $idCustomer))
                throw new RuntimeException("Не указан обязательный параметр");

            if ($idCustomer > 0) {
                $id = $this->getEmployeeModel()->siteCustomerInsert($EmployeeSiteID, $idCustomer);
            } else {
                $siteCross = $this->getEmployeeModel()->siteGet($EmployeeSiteID);

                $records = $this->getCustomerModel()->findCustomerBySiteID($siteCross['SiteID']);

                foreach ($records as $record) {
                    $this->getEmployeeModel()->siteCustomerInsert($EmployeeSiteID, $record['ID']);
                }

                $id = 0;
            }

            $this->getEmployeeModel()->employeeUpdateNote($this->getUserID(), $EmployeeID, ['Site']);

            $this->json_response(array("status" => 1, "id" => $id));
        } catch (Exception $e) {
            $this->json_response(array('status' => 0, 'message' => $e->getMessage()));
        }
    }

    public function remove($EmployeeID, $idSite, $idCustomer, $return = true) {
        try {
            if (!isset($EmployeeID, $idSite, $idCustomer))
                throw new RuntimeException("Не указан обязательный параметр");

            $this->getEmployeeModel()->siteCustomerDelete($idCustomer);
            $this->getEmployeeModel()->employeeUpdateNote($this->getUserID(), $EmployeeID, ['Site']);

            if($return) $this->json_response(array("status" => 1));
        } catch (Exception $e) {
            if($return) $this->json_response(array('status' => 0, 'message' => $e->getMessage()));
        }
    }

    public function removeall($EmployeeID, $idSite, $idWorkSite) {
        // все клиенты
        $records = $this->getEmployeeModel()->employeeSiteCustomerGetList($idWorkSite);
        if(!empty($records)){
            $r_size = sizeof($records);
            $rm_size = 0;
            foreach($records as $record){
                $rm = $this->remove($EmployeeID, $idSite, $record['ID'], false);
                $rm_size++;
            }
            if($rm_size == $r_size){
                $this->json_response(array("status" => 1));
            }
            else{
                $this->json_response(array('status' => 0, 'message' => 'Удалено ' . $rm_size . ' записей из ' . $r_size));
            }

        }
    }

    /**
     * открыть доступ сотруднику к базе Клиентов
     * @param $EmployeeID
     */
    public function openbase($EmployeeID)
    {
        // TODO: Если в дальнейшем потребуются какие-то отчеты для Сотрудников по Клиентам – то нужно будет исключить удаление старых связей, т.к. это поломает отчеты. Нужно будет добавлять новых Клиентов и новые сайты, избегая дублирования.
        $return = 0;
        $employee = $this->getEmployeeModel()->employeeGet($EmployeeID);
        if(!empty($EmployeeID) && ($employee['UserRole'] == '10004')){
            // 1. Выбрать все ID связей EmployeeID - SiteID из таблицы assol_employee_site.
            $employeeSites = $this->getEmployeeModel()->getEmployeeSitesConnections($EmployeeID);
            if(!empty($employeeSites)){
                // 2. Если такие связи есть – удалить их, а также удалить все записи из таблицы assol_employee_site_customer,
                // в которых в поле EmployeeSiteID указаны полученные из таблицы assol_employee_site ID связей из п.1.
                $es_ids = array_keys(toolIndexArrayBy($employeeSites, 'ID'));
                $this->getEmployeeModel()->removeEmployeeSiteConnections($es_ids); // Сотрудник - Сайты
                $this->getEmployeeModel()->removeEmployeeSiteCustomerConnections($es_ids); // Сотрудник - Сайты - Клиенты
            }

            // 3. Получить ID активных сайтов в системе (assol_sites).
            $sites = $this->getEmployeeModel()->getSites();
            $sites_ids = array_keys(toolIndexArrayBy($sites, 'ID'));

            // 4. Создать в assol_employee_site новые связи EmployeeID - Site ID для ID Сотрудника и всех активных сайтов.
            $this->getEmployeeModel()->setEmployeeSitesConnections($EmployeeID, $sites_ids);

            // 5. Получить ID этих новых связей.
            $employeeSites = $this->getEmployeeModel()->getEmployeeSitesConnections($EmployeeID);

            // 6. Выполнить действия, аналогичные controllers/Employee_Site_Clients/save.
            if(!empty($employeeSites)){
                $employeeSitesIndexed = toolIndexArrayBy($employeeSites, 'ID'); // индексируем связи по ID
                $es_ids = array_keys($employeeSitesIndexed); // список ID связей Сотрудник - Сайт
                foreach ($es_ids as $es_id) {
                    // список Клиентов, привязанных к сайту
                    $customers = $this->getCustomerModel()->findCustomerBySiteID($employeeSitesIndexed[$es_id]['SiteID']);
                    foreach ($customers as $customer) {
                        // добавляем связь Сотрудник - Сайт - Клиент
                        $this->getEmployeeModel()->siteCustomerInsert($es_id, $customer['ID']);
                        $return++;
                    }
                }
            }

        }
        echo $return;
    }

}