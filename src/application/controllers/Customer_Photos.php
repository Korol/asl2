<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_Photos extends MY_Controller
{
    public function data()
    {
        try {
            $CustomerID = $this->input->post('id', true);
            if (!isset($CustomerID))
                throw new RuntimeException("Не указан обязательный параметр");

            $records = $this->getCustomerModel()->photosCustomerGetList($CustomerID);
            if(!empty($records)){
                foreach ($records as $rk => $record) {
                    $records[$rk]['pathFull'] = base_url('thumb/?src=/files/customer/photos/' . $record['ID'] . '.' . $record['ext']);
                    $records[$rk]['pathThumb'] = base_url('thumb/?src=/files/customer/photos/' . $record['ID'] . '.' . $record['ext'] . '&w=130&h=130&zc=1');
                }
            }

            $this->json_response(array("status" => 1, 'records' => $records));
        } catch (Exception $e) {
            $this->json_response(array('status' => 0, 'message' => $e->getMessage()));
        }
    }

    public function upload($idCustomer) {
        $this->load->view('form/customers/photos/upload', ['parent' => $idCustomer]);
    }

    public function server($CustomerID) {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $records = $this->getCustomerModel()->photosCustomerGetList($CustomerID);

                foreach ($records as $key => $value) {
                    $fileName = $value['ID'] . '.' . $value['ext'];

                    $records[$key]['deleteType'] = 'DELETE';
                    $records[$key]['deleteUrl'] = base_url('Customer_Photos/remove/' . $CustomerID . '/' . $value['ID']);
                    $records[$key]['name'] = $fileName;
                    $records[$key]['img'] = base_url('thumb?src=/files/customer/photos/'.$fileName.'&w=80');
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

                        $ext = $this->assertFileType($file['tmp_name'][0]);
                        $approved = ($this->isDirector()) ? 1 : 0;

                        $id = $this->getCustomerModel()->photosCustomerItemInsert(
                            $CustomerID,
                            $this->getFileContent($file['tmp_name'][0]),
                            $ext,
                            $approved,
                            $this->getUserID()
                        );
                        $this->getCustomerModel()->customerUpdateNote($CustomerID, $this->getUserID(), ['CustomerPhoto']);

                        $records = [];
                        $records[] = [
                            'deleteType' => 'DELETE',
                            'deleteUrl' => base_url('Customer_Photos/remove/' . $CustomerID . '/' . $id),
                            'name' => $file['name'][0],
                            'size' => $file['size'][0],
                            'img' => base_url('thumb?src=/files/customer/photos/' . $id . '.' . $ext . '&w=80'),
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

    public function remove($CustomerID, $idCustomerPhoto) {
        try {
            if (!isset($CustomerID, $idCustomerPhoto))
                throw new RuntimeException("Не указан обязательный параметр");

            $photo = $this->getCustomerModel()->photosGetItemForChat($idCustomerPhoto);
            if(
                !empty($photo['AuthorID']) &&
                ($this->getUserID() != $photo['AuthorID'])
            ){
                $this->getMessageModel()->chatMessageSend(
                    false,
                    $this->getUserID(),
                    $photo['AuthorID'],
                    'Фото вашей клиентки ' . $photo['SName'] . ' ' . mb_substr($photo['FName'], 0, 1, 'UTF-8') . '. отклонено');
            }

            $this->getCustomerModel()->photoCustomerDelete($idCustomerPhoto);
            $this->getCustomerModel()->customerUpdateNote($CustomerID, $this->getUserID(), ['CustomerPhotoRemove']);

            $this->json_response(array("status" => 1, 'index' => $idCustomerPhoto));
        } catch (Exception $e) {
            $this->json_response(array('status' => 0, 'message' => $e->getMessage()));
        }
    }

    protected function getFileTypes() {
        return array(
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif'
        );
    }

    public function load($idCustomer, $idQuestionPhoto) {
        try {
            if (!isset($idCustomer, $idQuestionPhoto))
                throw new RuntimeException("Не указан обязательный параметр");

            $record = $this->getCustomerModel()->photosGetItem($idQuestionPhoto);

            if (empty($record))
                throw new RuntimeException("Не найден документ");

            $ext = $record['ext'];
            $fileName = $record['ID'] . '.' . $ext;

            $this->file_response(file_get_contents('./files/customer/photos/'.$record['ID'].'.'.$ext), $ext, $fileName);
        } catch (Exception $e) {
            $this->json_response(array("status" => 0, "err" => $e->getMessage()));
        }
    }
}