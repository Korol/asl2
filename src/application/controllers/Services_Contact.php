<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Services_Contact extends MY_Controller {

    public function data() {
        try {
            $records = $this->getServiceModel()->getFullContactsList();

            $this->json_response(array("status" => 1, 'records' => $records));
        } catch (Exception $e) {
            $this->json_response(array('status' => 0, 'message' => $e->getMessage()));
        }
    }

    public function customer() {
        try {
            $id = $this->input->post('id', true);
            if (empty($id))
                throw new RuntimeException("Не указана Клиенка");

            $records = $this->getServiceModel()->getCustomerContactsList($id);

            $this->json_response(array("status" => 1, 'records' => $records));
        } catch (Exception $e) {
            $this->json_response(array('status' => 0, 'message' => $e->getMessage()));
        }
    }

    public function add($CustomerID = 0) {
        // 1. Обработка формы
        if (!empty($_POST)) {
            try {
                $date = $this->input->post('date');
                $site = $this->input->post('site');
                $employeeID = $this->input->post('userTranslate');
                $men = $this->input->post('men');
                $girl = $this->input->post('girl');
                $description = $this->input->post('description');

                if (empty($date))
                    throw new RuntimeException("Не указана дата");

                if (empty($site))
                    throw new RuntimeException("Не указан сайт");

                if (empty($employeeID))
                    throw new RuntimeException("Не указан переводчик");

                if (empty($men))
                    throw new RuntimeException("Не указан мужчина");

                if (empty($girl))
                    throw new RuntimeException("Не указана девушка");

//                if (empty($description))
//                    throw new RuntimeException("Не указано описание");

                $girl_ex = explode(' ', trim($girl));
                $girlInfo = $this->getServiceModel()->findCustomerByName(trim($girl_ex[0]));

                $id = $this->getServiceModel()->contactInsert(
                        $this->getUserID(),
                        $date,
                        $site,
                        $employeeID,
                        $men,
                        (!empty($girlInfo['ID']) ? $girlInfo['ID'] : 0),
                        $description
                    );

                $this->json_response(array("status" => 1, 'id' => $id));
            } catch (Exception $e) {
                $this->json_response(array('status' => 0, 'message' => $e->getMessage()));
            }
        }

        $data = array(
            'translators' => $this->getEmployeeModel()->employeeTranslatorGetList(),
            'sites' => $this->getSiteModel()->getRecords()
        );

        // если указан ID клиентки – получаем данные для заполнения поля Девушка
        if(!empty($CustomerID)){
            $customer = $this->getCustomerModel()->customerGet($CustomerID);
            $data['CustomerName'] = trim($customer['SName']) . ' ' . trim($customer['FName']);
        }

        // 2. Загрузка шаблона
        $this->load->view('form/services/add_contact', $data);
    }

    public function edit($id) {
        // 1. Обработка формы
        if (!empty($_POST)) {
            try {
                $date = $this->input->post('date');
                $site = $this->input->post('site');
                $employeeID = $this->input->post('userTranslate');
                $men = $this->input->post('men');
                $girl = $this->input->post('girl');
                $description = $this->input->post('description');

                if (empty($date))
                    throw new RuntimeException("Не указана дата");

                if (empty($site))
                    throw new RuntimeException("Не указан сайт");

                if (empty($employeeID))
                    throw new RuntimeException("Не указан переводчик");

                if (empty($men))
                    throw new RuntimeException("Не указан мужчина");

                if (empty($girl))
                    throw new RuntimeException("Не указана девушка");

//                if (empty($description))
//                    throw new RuntimeException("Не указано описание");

                $girl_ex = explode(' ', trim($girl));
                $girlInfo = $this->getServiceModel()->findCustomerByName(trim($girl_ex[0]));

                $this->getServiceModel()->contactUpdate(
                        $id,
                        $date,
                        $site,
                        $employeeID,
                        $men,
                        (!empty($girlInfo['ID']) ? $girlInfo['ID'] : 0),
                        $description
                    );

                $this->json_response(array("status" => 1));
            } catch (Exception $e) {
                $this->json_response(array('status' => 0, 'message' => $e->getMessage()));
            }
        }

        $data = array(
            'translators' => $this->getEmployeeModel()->employeeTranslatorGetList(),
            'sites' => $this->getSiteModel()->getRecords(),
            'record' => $this->getServiceModel()->contactGet($id),
        );

        // 2. Загрузка шаблона
        $this->load->view('form/services/edit_contact', $data);
    }

    public function done() {
        $isAdmin = IS_LOVE_STORY
            ? ($this->isDirector() || $this->isSecretary())
            : ($this->isDirector() || $this->isSecretary());

        if (!$isAdmin)
            show_error('Данный раздел не доступен для текущего пользователя', 403, 'Доступ запрещен');

        try {
            $id = $this->input->post('id');

            if (empty($id))
                throw new Exception('Нет данных для сохранения');

            $this->getServiceModel()->deliveryDone($id);

            $this->json_response(array('status' => 1));
        } catch (Exception $e) {
            $this->json_response(array('status' => 0, 'message' => $e->getMessage()));
        }
    }

    public function remove() {
        $isAdmin = IS_LOVE_STORY
            ? ($this->isDirector() || $this->isSecretary())
            : ($this->isDirector() || $this->isSecretary());

        if (!$isAdmin)
            show_error('Данный раздел не доступен для текущего пользователя', 403, 'Доступ запрещен');

        try {
            $id = $this->input->post('id');

            if (empty($id))
                throw new Exception('Нет данных для удаления');

            $this->getServiceModel()->contactDelete($id);

            $this->json_response(array('status' => 1));
        } catch (Exception $e) {
            $this->json_response(array('status' => 0, 'message' => $e->getMessage()));
        }
    }

}
