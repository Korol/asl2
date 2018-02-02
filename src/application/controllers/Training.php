<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Training extends MY_Controller {

    public $employeeGroups = array(
        '10001' => array('role' => 'директор', 'label' => 'ВСЕ ДИРЕКТОРА', 'value' => '--10001'),
        '10002' => array('role' => 'секретарь', 'label' => 'ВСЕ СЕКРЕТАРИ', 'value' => '--10002'),
        '10003' => array('role' => 'переводчик', 'label' => 'ВСЕ ПЕРЕВОДЧИКИ', 'value' => '--10003'),
        '10004' => array('role' => 'сотрудник', 'label' => 'ВСЕ СОТРУДНИКИ', 'value' => '--10004'),
    );

    /**
     * Функция проверки прав доступа
     */
    function assertUserRight() {
        if (!$this->role['isDirector'])
            show_error('Данный раздел доступен только для роли "Директор"', 403, 'Доступ запрещен');
    }

    public function index($idFolder = 0) {
        $data = array(
            'Parent' => $idFolder
        );

        $this->viewHeader($data);
        $this->view('form/training/index');
        $this->viewFooter([
            'js_array' => [
                'public/js/assol.training.js'
            ]
        ]);
    }

    public function data() {
        try {
            $parent = $this->input->post('Parent');

            $data = array(
                'bread' => $this->getTrainingModel()->breadGetList($parent)
            );

            $isFullAccess = IS_LOVE_STORY && $this->isDirector(); // Для директора LoveStory полный доступ

            if ($isFullAccess || $this->getTrainingModel()->checkRights($parent, $this->getUserID())) {
                $data['data'] = [];

                $objects = $this->getTrainingModel()->trainingGetList($parent);

                foreach ($objects as $object) {
                    // Если нет прав, то пропускаем
                    if (!$isFullAccess && !$this->getTrainingModel()->checkRights($object['ID'], $this->getUserID()))
                        continue;

                    $data['data'][] = $object;
                }

                // files
                $files = $this->getTrainingModel()->fileGetList($parent);
                foreach($files as $file){
                    $file['isFile'] = 1;
                    $data['data'][] = $file;
                }

            } else {
                $data['AccessDenied'] = true;
            }

            $this->json_response(array("status" => 1, 'records' => $data));
        } catch (Exception $e) {
            $this->json_response(array('status' => 0, 'message' => $e->getMessage()));
        }
    }

    public function add_folder() {
        // 1. Проверка прав доступа
        $this->assertUserRight();

        // 2. Обработка данных формы
        if (!empty($_POST)) {
            try {
                $Name = $this->input->post('Name');
                $Parent = $this->input->post('Parent');
                $Employees = $this->input->post('Employees');

                if (!empty($Employees) && !in_array($this->getUserID(), $Employees)) {
                    $Employees[] = $this->getUserID(); // Добавление текущего пользователя к объекту прав
                }

                // проверяем наличие групп пользователей в Employees
                // если они там есть – назначаем права всем юзерам в группе
                if(!empty($Employees) && is_array($Employees)){
                    $Employees = $this->checkEmployeesGroups($Employees);
                }

                if (empty($Name))
                    throw new Exception('Не указано имя папки');

                $id = $this->getTrainingModel()->trainingInsert($Name, $Parent, $this->getUserID());
                $this->getTrainingModel()->trainingRightInsert($id, $Employees);

                $res = array('status' => 1, 'id' => $id);
            } catch (Exception $e) {
                $res = array('status' => 0, 'message' => $e->getMessage());
            }

            $this->json_response($res);
        }

        $data = array(
            'folders' => $this->getTrainingModel()->folderGetList(),
            'employees' => $this->getEmployeeModel()->employeeGetActiveList($this->getUserID(), $this->getUserRole()),
            'employee_groups' => $this->employeeGroups,
        );

        // 3. Загрузка шаблона
        $this->load->view('form/training/add_folder', $data);
    }

    public function edit_folder($id) {
        // 1. Проверка прав доступа
        $this->assertUserRight();

        // 2. Обработка данных формы
        if (!empty($_POST)) {
            try {
                $Name = $this->input->post('Name');
                $Parent = $this->input->post('Parent');
                $Employees = $this->input->post('Employees');
                $IsSub = $this->input->post('IsSub');

                if (!empty($Employees) && !in_array($this->getUserID(), $Employees)) {
                    $Employees[] = $this->getUserID(); // Добавление текущего пользователя к объекту прав
                }

                // проверяем наличие групп пользователей в Employees
                // если они там есть – назначаем права всем юзерам в группе
                if(!empty($Employees) && is_array($Employees)){
                    $Employees = $this->checkEmployeesGroups($Employees);
                }

                if (empty($Name))
                    throw new Exception('Не указано имя папки');

                $this->getTrainingModel()->folderUpdate($id, $Name, $Parent);
                $this->getTrainingModel()->trainingRightUpdate($id, $Employees, $IsSub);

                $res = array('status' => 1, 'id' => $id);
            } catch (Exception $e) {
                $res = array('status' => 0, 'message' => $e->getMessage());
            }

            $this->json_response($res);
        }

        $data = array(
            'record' => $this->getTrainingModel()->trainingGet($id),
            'rights' => $this->getTrainingModel()->getFolderRights($id),
            'folders' => $this->getTrainingModel()->folderGetList(),
            'employees' => $this->getEmployeeModel()->employeeGetActiveList($this->getUserID(), $this->getUserRole()),
            'employee_groups' => $this->employeeGroups,
        );

        // 3. Загрузка шаблона
        $this->load->view('form/training/edit_folder', $data);
    }

    public function add($idFolder = 0) {
        // 1. Проверка прав доступа
        $this->assertUserRight();

        // 2. Обработка данных формы
        if (!empty($_POST)) {
            try {
                $Parent = $this->input->post('Parent');
                $TrainingName = $this->input->post('TrainingName');
                $TrainingContent = $this->input->post('TrainingContent');
                $Employees = $this->input->post('Employees');

                if (!empty($Employees) && !in_array($this->getUserID(), $Employees)) {
                    $Employees[] = $this->getUserID(); // Добавление текущего пользователя к объекту прав
                }

                // проверяем наличие групп пользователей в Employees
                // если они там есть – назначаем права всем юзерам в группе
                if(!empty($Employees) && is_array($Employees)){
                    $Employees = $this->checkEmployeesGroups($Employees);
                }

                if (empty($TrainingName))
                    throw new Exception('Не указано имя статьи');

                $id = $this->getTrainingModel()->trainingInsert($TrainingName, $Parent, $this->getUserID(), false, $TrainingContent);
                $this->getTrainingModel()->trainingRightInsert($id, $Employees);

                $res = array('status' => 1, 'id' => $id);
            } catch (Exception $e) {
                $res = array('status' => 0, 'message' => $e->getMessage());
            }

            $this->json_response($res);
        }

        // 3. Загрузка шаблона
        $data = array(
            'Parent' => $idFolder,
            'bread' => $this->getTrainingModel()->breadGetList($idFolder),
            'folders' => $this->getTrainingModel()->folderGetList(),
            'employees' => $this->getEmployeeModel()->employeeGetActiveList($this->getUserID(), $this->getUserRole()),
            'employee_groups' => $this->employeeGroups,
        );

        $this->viewHeader($data);
        $this->view('form/training/add_training');
        $this->viewFooter([
            'isWysiwyg' => true,
            'js_array' => [
                'public/js/assol.training.article.js'
            ]
        ]);
    }

    public function edit($idFolder, $idRecord) {
        // 1. Проверка прав доступа
        $this->assertUserRight();

        // 2. Обработка данных формы
        if (!empty($_POST)) {
            try {
                $Parent = $this->input->post('Parent');
                $TrainingName = $this->input->post('TrainingName');
                $TrainingContent = $this->input->post('TrainingContent');
                $Employees = $this->input->post('Employees');

                if (!empty($Employees) && !in_array($this->getUserID(), $Employees)) {
                    $Employees[] = $this->getUserID(); // Добавление текущего пользователя к объекту прав
                }

                // проверяем наличие групп пользователей в Employees
                // если они там есть – назначаем права всем юзерам в группе
                if(!empty($Employees) && is_array($Employees)){
                    $Employees = $this->checkEmployeesGroups($Employees);
                }

                if (empty($TrainingName))
                    throw new Exception('Не указано имя статьи');

                $this->getTrainingModel()->trainingUpdate($idRecord, $TrainingName, $Parent, $TrainingContent);
                $this->getTrainingModel()->trainingRightUpdate($idRecord, $Employees, 0);

                $res = array('status' => 1, 'id' => $idRecord);
            } catch (Exception $e) {
                $res = array('status' => 0, 'message' => $e->getMessage());
            }

            $this->json_response($res);
        }

        // 3. Загрузка шаблона
        $data = array(
            'record' => $this->getTrainingModel()->trainingGet($idRecord),
            'bread' => $this->getTrainingModel()->breadGetList($idFolder),
            'Parent' => $idFolder,
            'rights' => $this->getTrainingModel()->getFolderRights($idRecord),
            'folders' => $this->getTrainingModel()->folderGetList(),
            'employees' => $this->getEmployeeModel()->employeeGetActiveList($this->getUserID(), $this->getUserRole()),
            'employee_groups' => $this->employeeGroups,
        );

        $this->viewHeader($data);
        $this->view('form/training/edit_training');
        $this->viewFooter([
            'isWysiwyg' => true,
            'js_array' => [
                'public/js/assol.training.article.js'
            ]
        ]);
    }

    public function show($idFolder, $idRecord) {
        $isFullAccess = IS_LOVE_STORY && $this->isDirector(); // Для директора LoveStory полный доступ

        // 1. Проверка прав доступа
        if (!$isFullAccess && !$this->getTrainingModel()->checkRights($idRecord, $this->getUserID())) {
            show_error('Доступ для пользователя закрыт', 403, 'Доступ запрещен');
        }

        $data = [
            'bread' => $this->getTrainingModel()->breadGetList($idFolder),
            'record' => $this->getTrainingModel()->trainingGet($idRecord),
            'Parent' => $idFolder
        ];

        $this->viewHeader($data);
        $this->view('form/training/show_training');
        $this->viewFooter();
    }

    public function remove() {
        try {
            // 1. Проверка прав доступа
            $this->assertUserRight();

            $id = $this->input->post('id');

            if (!isset($id))
                throw new RuntimeException("Не указан обязательный параметр");

            $this->getTrainingModel()->trainingDelete($id);

            $this->json_response(array("status" => 1));
        } catch (Exception $e) {
            $this->json_response(array('status' => 0, 'message' => $e->getMessage()));
        }
    }

    public function upload($parent) {
        $this->load->view('form/training/upload', ['parent' => $parent]);
    }

    public function server($parent) {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $data = $this->getTrainingModel()->fileGetList($parent);
                $records = [];

                foreach ($data as $value) {
                    // Пропуск папок
                    if ($value['IsFolder'] > 0)
                        continue;

                    $records[] = $value;
                }

                foreach ($records as $key => $value) {
                    // Пропуск файлов
                    if ($value['IsFolder'] > 0) continue;

                    $records[$key]['deleteType'] = 'DELETE';
                    $records[$key]['deleteUrl'] = base_url("training/delete_file/".$value['ID']);
                    $records[$key]['name'] = $value['Name'];
                    $records[$key]['size'] = '';
                }

                $this->json_response(array("status" => 1, 'files' => $records));
                break;
            case 'POST':
                // 2. Обработка данных формы
                if (!empty($_FILES)) {
                    try {
                        $file = $_FILES['files'];

                        if (!isset($file['error'][0]) || is_array($file['error'][0]))
                            throw new RuntimeException('Ошибка загрузки файла на сервер');

                        switch ($file['error'][0]) {
                            case UPLOAD_ERR_OK:
                                break;
                            case UPLOAD_ERR_NO_FILE:
                                throw new RuntimeException('Файл не загружен на сервер');
                            case UPLOAD_ERR_INI_SIZE:
                            case UPLOAD_ERR_FORM_SIZE:
                                throw new RuntimeException('Превышен размер файла');
                            default:
                                throw new RuntimeException('Неизвестная ошибка');
                        }

                        $id = $this->getTrainingModel()->fileInsert($file['name'][0], $parent, $this->getUserID(), false, $this->getFileContent($file['tmp_name'][0]));

                        $records = [];
                        $records[] = [
                            'deleteType' => 'DELETE',
                            'deleteUrl' => base_url("training/delete_file/$id"),
                            'name' => $file['name'][0],
                            'size' => $file['size'][0],
                        ];

                        $res = array('status' => 1, 'files' => $records);
                    } catch (Exception $e) {
                        $res = array('status' => 0, 'message' => $e->getMessage());
                    }

                    $this->json_response($res);
                }

                break;
        }
    }

    public function load($id) {
        try {
            // 1. Проверка прав доступа
            //$this->assertUserRight();

            if (!isset($id))
                throw new RuntimeException("Не указан обязательный параметр");

            $file = $this->getTrainingModel()->fileGet($id);

            if (empty($file))
                throw new RuntimeException("Не найден документ");

            $info = new SplFileInfo($file['Name']);
            $this->file_response(file_get_contents('./files/training/'.$file['ID']), $info->getExtension(), $file['Name']);
        } catch (Exception $e) {
            $this->json_response(array("status" => 0, "err" => $e->getMessage()));
        }
    }

    public function delete_file($id) {
        try {
            // 1. Проверка прав доступа
            $this->assertUserRight();

            if (!isset($id))
                throw new RuntimeException("Не указан обязательный параметр");

            $this->getTrainingModel()->fileDelete($id);

            $this->json_response(array("status" => 1));
        } catch (Exception $e) {
            $this->json_response(array('status' => 0, 'message' => $e->getMessage()));
        }
    }

    /**
     * проверяем массив на наличие групп пользователей (--10001, --10002)
     * если находим – получаем всех пользователей этих групп и добавляем в массив
     * @param array $Employees
     * @return array
     */
    public function checkEmployeesGroups($Employees)
    {
        $return = $Employees;
        $groups = array();
        foreach ($Employees as $k => $ID){
            if(strpos((string)$ID, '--') !== false){
                // это группа сотрудников
                $groups[] = str_replace('--', '', (string)$ID);
                unset($return[$k]);
            }
        }
        if(!empty($groups)){
            $groupsEmployees = $this->getEmployeeModel()->employeeGetFilterRoleList(0, $groups);
            if(!empty($groupsEmployees)){
                foreach ($groupsEmployees as $gEmployee){
                    if(!in_array($gEmployee['ID'], $return))
                        $return[] = $gEmployee['ID'];
                }
                unset($groupsEmployees);
            }
        }
        return $return;
    }
}
