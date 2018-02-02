<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends MY_Controller {

    public function index() {
        $this->viewHeader();
        $this->view('form/customers/index');
        $this->viewFooter();
    }

    public function meta() {
        try {
            $data = [
                'minMaxAge' => $this->getCustomerModel()->getMinMaxAge(),
                'isLoveStoryCustomerCard' => IS_LOVE_STORY,
                'isAssolCustomerCard' => !IS_LOVE_STORY
            ];
            
            $this->json_response(["status" => 1, 'data' => $data]);
        } catch (Exception $e) {
            $this->json_response(['status' => 0, 'message' => $e->getMessage()]);
        }
    }

    public function data() {
        try {
            $data = $this->input->post('data');
            if (IS_LOVE_STORY) {
                $filterUserId = $this->isTranslate() ? $this->getUserID() : FALSE;
            } else {
                $filterUserId = ($this->isTranslate() || $this->isEmployee()) ? $this->getUserID() : FALSE;
            }

            // перехватываем Status на предмет фильтрации анкет клиентов по статусам «В очереди», «На утверждение» и «Подтвержденные»
            if(!empty($data['Status']) && ((int)$data['Status'] > 1)){
                if($this->isDirector()){
                    // для Директора – запрашиваем клиентов с ssdStatus = 1, ssdStatus = 2 или ssdStatus = 3 - зависит от выбранного фильтра
                    $data['ssdStatus'] = ($data['Status'] - 1); // в фильтре значения статуса больше на 1 - для этого отнимаем 1
                    $data['ssdResponsibleStaff'] = 0;
                    unset($data['Status']); // удаляем ненужный более Status
                }
                elseif($this->isTranslate() && ((int)$data['Status'] == 2)){
                    // для Переводчика - запрашиваем клиентов с ssdStatus = 1
                    $data['ssdStatus'] = array(1);
                    $data['ssdResponsibleStaff'] = $this->getUserID();
                    unset($data['Status']); // удаляем ненужный более Status
                }
            }

            // добавляем метку «на встрече»
            $customerList = $this->getCustomerModel()->customerGetList($filterUserId, $data);
            $todayMeetings = $this->getServiceModel()->getMeetingByDate(date('Y-m-d'));
            if(!empty($customerList['records']) && !empty($todayMeetings)){
                foreach ($customerList['records'] as $ckey => $customer) {
                    foreach ($todayMeetings as $tm) {
                        if(mb_strpos($tm['Girl'], $customer['SName'], 0, 'UTF-8') !== false){
                            $customerList['records'][$ckey]['onMeeting'] = 1;
                        }
                        else{
                            $customerList['records'][$ckey]['onMeeting'] = 0;
                        }
                    }
                }
            }
            // для Директора: кто заполняет Анкету клиента
            if($this->isDirector() && !empty($customerList['records'])){
                foreach ($customerList['records'] as $cckey => $ccustomer){
                    if(in_array($ccustomer['ssdStatus'], array(1, 2)) && !empty($ccustomer['ssdResponsibleStaff'])){
                        $employeeInfo = $this->getEmployeeModel()->employeeGet($ccustomer['ssdResponsibleStaff']);
                        $customerList['records'][$cckey]['ssdStaffFio'] = (!empty($employeeInfo))
                            ? $employeeInfo['SName'] . ' ' . mb_substr($employeeInfo['FName'], 0, 1) . '.'
                            : '';
                    }
                    else{
                        $customerList['records'][$cckey]['ssdStaffFio'] = '';
                    }
                }
            }

            $this->json_response(array("status" => 1, 'data' => $customerList));
        } catch (Exception $e) {
            $this->json_response(array('status' => 0, 'message' => $e->getMessage()));
        }
    }

    /**
     * Функция проверки прав доступа
     */
    function assertUserRight() {
        if (IS_LOVE_STORY) {
            if (!$this->isDirector() && !$this->isSecretary())
                show_error('Данный раздел доступен только для ролей "Директор" и "Секретарь"', 403, 'Доступ запрещен');
        } else {
            if (!$this->isDirector() && !$this->isSecretary() && !$this->isTranslate())
                show_error('Данный раздел доступен только для ролей "Директор", "Секретарь, и "Переводчик""', 403, 'Доступ запрещен');
        }
    }

    public function add() {
        try {
            // 1. Проверка прав доступа
            $this->assertUserRight();

            $sName = $this->input->post('sName');
            $fName = $this->input->post('fName');
            $mName = $this->input->post('mName');

            if (empty($sName))
                throw new Exception('Не указана фамилия клиента');

            if (empty($fName))
                throw new Exception('Не указано имя клиента');

            $id = $this->getCustomerModel()->customerInsert($sName, $fName, $mName);

            $this->json_response(["status" => 1, 'id' => $id]);
        } catch (Exception $e) {
            $this->json_response(['status' => 0, 'message' => $e->getMessage()]);
        }
    }

    public function avatar($CustomerID) {
        // 1. Проверка прав доступа
        // $this->assertUserRight();

        try {
            $data = $this->input->post('data');

            $image = $this->getImage();
            if (is_numeric($image['id'])) {
                $this->clearAvatar($CustomerID);
                $data = array('Avatar' => $image['id']);
            }

            if (empty($data))
                throw new Exception('Нет данных для сохранения');

            $this->getCustomerModel()->customerUpdate($CustomerID, $data);
            $this->getCustomerModel()->customerUpdateNote($CustomerID, $this->getUserID(), ['Avatar']);

            $this->json_response(array('status' => 1, 'id' => $image['id'], 'FileName' => $image['id'].'.'.$image['ext']));
        } catch (Exception $e) {
            $this->json_response(array('status' => 0, 'message' => $e->getMessage()));
        }
    }

    public function update($id) {
        // 1. Проверка прав доступа
        // $this->assertUserRight();

        try {
            $data = $this->input->post('data');

            if (empty($data))
                throw new Exception('Нет данных для сохранения');

            // Предворительная обработка данных - обнуление пустых полей
            $nullFields = array('WishesForManAgeMin', 'WishesForManAgeMax', 'WishesForManWeight', 'WishesForManHeight', 'WishesForManText', 'DateRegister');
            foreach ($nullFields as $field) {
                if (isset($data[$field]) && empty($data[$field])) {
                    $data[$field] = null;
                }
            }

            // Сбор полей для истории
            $fields = [];
            foreach ($data as $key => $value)
                $fields[] = $key;

            // Привязка клиентов к сайту «В очереди» (ID=15) при назначении Ответственного
            // по заполнению Анкеты клиента
            if(!empty($data['ssdStatus']) && ($data['ssdStatus'] == 1) && !empty($data['ssdResponsibleStaff'])){
                //$this->getCustomerModel()->toQueue($id, $data['ssdResponsibleStaff']);
            }

            // проверяем, выбран ли сотрудник при изменении ssdStatus на 1 и 3
            if(!empty($data['ssdStatus']) && in_array($data['ssdStatus'], array(1, 3)) && empty($data['ssdResponsibleStaff'])){
                throw new Exception('Не выбран ответственный сотрудник!');
            }

            // Обновление профиля
            $this->getCustomerModel()->customerUpdate($id, $data);
            $this->getCustomerModel()->customerUpdateNote($id, $this->getUserID(), $fields);

            $this->json_response(array('status' => 1));
        } catch (Exception $e) {
            $this->json_response(array('status' => 0, 'message' => $e->getMessage()));
        }
    }

    public function profile($id) {
        $data = array(
            'customer' => $this->getCustomerModel()->customerGet($id),
            'work_sites' => $this->getCustomerModel()->siteGetList($id),
            'video_sites' => $this->getCustomerModel()->videoSiteGetList($id),
            'forming' => $this->getReferencesModel()->getReference(REFERENCE_FORMING),
            'eye_color' => $this->getReferencesModel()->getReference(REFERENCE_EYE_COLOR),
            'language' => $this->getReferencesModel()->getReference(REFERENCE_LANGUAGE),
            'child_sex' => $this->getReferencesModel()->getReference(REFERENCE_CHILD_SEX),
            'marital' => $this->getReferencesModel()->getReference(REFERENCE_MARITAL),
            'body_build' => $this->getReferencesModel()->getReference(REFERENCE_BODY_BUILD),
            'sites' => $this->getSiteModel()->getRecords()
        );

        // встречи и доставки клиентки
        if(!empty($data['customer']['SName'])){
            $data['meetings'] = $this->getServiceModel()->findServiceByCustomerSName($data['customer']['SName'], 'meeting');
            $data['deliverys'] = $this->getServiceModel()->findServiceByCustomerSName($data['customer']['SName'], 'delivery');
            $data['today_meetings'] = $this->getServiceModel()->getMeetingByDateAndSName(date('Y-m-d'), 'Girl', $data['customer']['SName']);
        }

        // Установка прав доступа к договорам и паспорту только для assol
        if (!IS_LOVE_STORY) {
            // список сотрудников, ответственных за самоописание для сайта
            $data['ssdStaffs'] = $this->getEmployeeModel()
                ->employeeGetFilterRoleList($id,
//                    [USER_ROLE_DIRECTOR, USER_ROLE_SECRETARY, USER_ROLE_TRANSLATE, USER_ROLE_EMPLOYEE]
                    [USER_ROLE_TRANSLATE]
                );

            // Контроль доступа к просмотру новой анкеты самоописание
            $data['ssdCanView'] = false;
            // самоописание могут видеть Директор и Секретарь
            if($this->isDirector() || $this->isSecretary()){
                $data['ssdCanView'] = true;
            }
            elseif ($this->isTranslate()){
                // также самоописание может видеть Переводчик,
                // назначенный ответственным за заполнение анкеты самоописание
                if(
                    (
                        !empty($data['customer']['ssdResponsibleStaff']) &&
                        ($data['customer']['ssdStatus'] != 3)
                    ) &&
                    ($data['customer']['ssdResponsibleStaff'] == $this->getUserID())
                ){
                    $data['ssdCanView'] = true;
                }
                // также анкету самоописание может видеть Переводчик,
                // за которым закреплена эта клиентка
                if(!$data['ssdCanView']){
                    // список клиенток, которые закреплены за текущим Переводчиком
                    $my_customers = $this->getEmployeeModel()->employeeCustomerGetList($this->getUserID());
                    $my_customers = (!empty($my_customers))
                        ? toolIndexArrayBy($my_customers, 'CustomerID')
                        : array();
                    if(!empty($my_customers) && in_array($id, array_keys($my_customers))){
                        $data['ssdCanView'] = true;
                    }
                }
            }//var_dump($this->getCustomerModel()->getEmployeesBySite($id, true));

            // Редактирование прав доступа к договорам и паспорту
            $data['isEditDocumentAccess'] = $this->isDirector() || $this->isSecretary();

            // Доступ к документам для ролей "Директор" и "Секретарь" + для пользователей с назначенными правами
            if ($data['isEditDocumentAccess']) {
                $data['employees'] = $this->getEmployeeModel()->employeeGetFilterRoleList($id, [USER_ROLE_TRANSLATE, USER_ROLE_EMPLOYEE]);
                $data['rights'] = $this->getCustomerModel()->rightsGetList($id);
                $data['isDocAccess'] = true;
            } else {
                $right = $this->getCustomerModel()->rightsGet($id, $this->getUserID());
                $data['isDocAccess'] = !empty($right);
                $data['employees'] = [];
                $data['rights'] = [];
            }

            // для Сотрудников – право доступа к редактированию вкладки Фото/Видео
            if($this->isEmployee()){
                $data['photoVideoEdit'] = $this->getEmployeeModel()->checkServicesAccess($this->getUserID());
            }
            else{
                $data['photoVideoEdit'] = false;
            }

        } else {
            $data['isEditDocumentAccess'] = false; // Скрываем форму настройки прав доступа для LoveStory
            $data['isDocAccess'] = true; // Открываем полный доступ к документам для LoveStory
            $data['employees'] = [];
            $data['rights'] = [];
        }

        $this->viewHeader($data);
        $this->view('form/customers/profile');
        $this->viewFooter([
            'js_array' => [
                'public/js/assol.customer.card.js'
            ]
        ]);
    }

    public function rights($CustomerID) {
        try {
            if (!isset($CustomerID))
                throw new RuntimeException("Не указан обязательный параметр");

            $employees = $this->input->post('Employees');

            // Отключенных прав, не пришедших в списке
            $this->getCustomerModel()->rightsRemove($CustomerID, $employees);

            // Добавление необходимых прав
            foreach ($employees as $employee) {
                $this->getCustomerModel()->rightsInsert($CustomerID, $employee);
            }

            $this->json_response(array("status" => 1));
        } catch (Exception $e) {
            $this->json_response(array('status' => 0, 'message' => $e->getMessage()));
        }
    }

    public function remove($CustomerID) {
        try {
            if (!isset($CustomerID))
                throw new RuntimeException("Не указан обязательный параметр");

            $isFull = $this->input->post('IsFull');

            if ($isFull) {
                $this->clearAvatar($CustomerID);
                $this->getCustomerModel()->customerDelete($CustomerID);
            } else {
                $this->getCustomerModel()->customerUpdate($CustomerID, array(
                    'DateRemove' => date('Y-m-d'), 'IsDeleted' => 1, 'WhoDeleted' => $this->getUserID()));
                $this->getCustomerModel()->customerUpdateNote($CustomerID, $this->getUserID(), ['IsDeleted']);
            }

            $this->json_response(array("status" => 1));
        } catch (Exception $e) {
            $this->json_response(array('status' => 0, 'message' => $e->getMessage()));
        }
    }

    private function clearAvatar($EmployeeID) {
        $customer = $this->getCustomerModel()->customerGet($EmployeeID);

        if ($customer['Avatar'] > 0) {
            $this->getImageModel()->remove($customer['Avatar']);
        }
    }

    public function restore() {
        try {
            $this->assertUserRight();

            $id = $this->input->post('id');

            if (!isset($id))
                throw new RuntimeException("Не указан обязательный параметр");

            $this->getCustomerModel()->customerUpdate($id, array(
                'ReasonForDeleted' => null, 'DateRemove' => null, 'IsDeleted' => 0, 'WhoDeleted' =>  null));
            $this->getCustomerModel()->customerUpdateNote($id, $this->getUserID(), ['IsDeleted']);

            $this->json_response(array("status" => 1));
        } catch (Exception $e) {
            $this->json_response(array('status' => 0, 'message' => $e->getMessage()));
        }
    }

    private function getImage() {
        if (!empty($_FILES)) {
            $file = $_FILES['thumb'];

            if (!isset($file['error']) || is_array($file['error']))
                throw new RuntimeException('Ошибка загрузки файла на сервер');

            switch ($file['error']) {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    return null;
//                    throw new RuntimeException('Файл не загружен на сервер');
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new RuntimeException('Превышен размер файла');
                default:
                    throw new RuntimeException('Неизвестная ошибка');
            }

            $ext = $this->assertFileType($file['tmp_name']);

            return [
                'id' => $this->getImageModel()->imageInsert($this->getFileContent($file['tmp_name']), $ext),
                'ext' => $ext
            ];
        }

        return null;
    }

    protected function getFileTypes() {
        return array(
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif'
        );
    }
}
