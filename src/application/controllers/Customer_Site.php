<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_Site extends MY_Controller {

    public function data($CustomerID) {
        try {
            if (!isset($CustomerID))
                throw new RuntimeException("Не указан обязательный параметр");

            $records = $this->getCustomerModel()->siteGetList($CustomerID);

            // сотрудники, за которыми закреплён клиент по сайтам
            $employeesBySites = $this->getCustomerModel()->getEmployeesBySite($CustomerID);
            if(!empty($records) && !empty($employeesBySites)){
                foreach ($records as $rk => $row) {
                    $records[$rk]['EmployeesList'] = (!empty($employeesBySites[$row['SiteID']]))
                        ? $employeesBySites[$row['SiteID']]
                        : '';
                }
            }

            $this->json_response(array("status" => 1, 'records' => $records));
        } catch (Exception $e) {
            $this->json_response(array('status' => 0, 'message' => $e->getMessage()));
        }
    }

    public function save($CustomerID) {
        try {
            $data = $this->input->post('data');

            $insert = false;
            $update = false;

            if (!isset($CustomerID))
                throw new RuntimeException("Не указан обязательный параметр");

            if (isset($data['sites'])) {
                $records = $this->getCustomerModel()->siteGetList($CustomerID);

                foreach($data['sites'] as $idSite) {
                    $key = array_search($idSite, array_column($records, 'SiteID'));
                    if (false === $key) {
                        if (empty($insert)) {
                            $insert = array();
                        }
                        $insert[] = $this->getCustomerModel()->siteInsert($CustomerID, $idSite);
                    }
                }
            }

            if (isset($data['notes'])) {
                foreach($data['notes'] as $note) {
                    $this->getCustomerModel()->siteUpdate($note['id'], array('Note' => $note['note']));
                    $update = true;
                }
            }

            // Комментарии к сайтам клиентов
            if (isset($data['comments'])) {
                foreach($data['comments'] as $comment) {
                    $this->getCustomerModel()->siteUpdate($comment['id'], array('Comment' => $comment['comment']));
                    $update = true;
                }
            }

            if ($insert || $update) {
                $this->getCustomerModel()->customerUpdateNote($CustomerID, $this->getUserID(), ['Site']);
            }

            $this->json_response(array("status" => 1, "insert" => $insert));
        } catch (Exception $e) {
            $this->json_response(array('status' => 0, 'message' => $e->getMessage()));
        }
    }

    public function remove($CustomerID, $idRecord) {
        try {
            if (!isset($CustomerID, $idRecord))
                throw new RuntimeException("Не указан обязательный параметр");

            $this->getCustomerModel()->siteDelete($idRecord);
            $this->getCustomerModel()->customerUpdateNote($CustomerID, $this->getUserID(), ['Site']);

            $this->json_response(array("status" => 1));
        } catch (Exception $e) {
            $this->json_response(array('status' => 0, 'message' => $e->getMessage()));
        }
    }

}