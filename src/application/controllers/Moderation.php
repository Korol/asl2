<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Moderation extends MY_Controller
{
    public function index()
    {
        redirect(base_url('moderation/photos'));
    }

    public function photos()
    {
        if(!$this->isDirector()){
            redirect(base_url());
        }

        $data['customer_id'] = (!empty($_GET['customer_id'])) ? (int)$_GET['customer_id'] : 0;
        $data['author_id'] = (!empty($_GET['author_id'])) ? (int)$_GET['author_id'] : 0;

        if(!empty($data['customer_id'])){
            $data['photos'] = $this->getCustomerModel()->photosGetCustomerUnapprovedList($data['customer_id']);
        }
        elseif(!empty($data['author_id'])){
            $data['photos'] = $this->getCustomerModel()->photosGetAuthorUnapprovedList($data['author_id']);
        }
        else{
            $data['photos'] = $this->getCustomerModel()->photosUnapprovedGetList();
        }

        $data['customers'] = $this->getCustomerModel()->photosGetUnapprovedCustomersList();
        $data['authors'] = $this->getCustomerModel()->photosGetUnapprovedAuthorsList();

        if(!empty($data['photos'])){
            foreach ($data['photos'] as $pk => $records) {
                foreach ($records as $rk => $record){
                    $data['photos'][$pk][$rk]['pathFull'] = base_url('thumb/?src=/files/customer/photos/' . $record['ID'] . '.' . $record['ext']);
                    $data['photos'][$pk][$rk]['pathThumb'] = base_url('thumb/?src=/files/customer/photos/' . $record['ID'] . '.' . $record['ext'] . '&w=130&h=130&zc=1');
                }
            }
        }

        $this->viewHeader();
        $this->view('form/moderation/photos', $data);
        $this->viewFooter();
    }

    public function batchphotos()
    {
        try {
            $items = $this->input->post('items', true);
            $mode = $this->input->post('mode', true);
            if (!isset($items, $mode) || !in_array($mode, array('approve', 'remove')))
                throw new RuntimeException("Не указан обязательный параметр");

            $items = ltrim($items, '_');
            $items_ex = explode('_', $items);
            $message = '';

            if($mode === 'approve'){
                $result = $this->getCustomerModel()->photosBatchApprove($items_ex);
                if($result > 0){
                    $message = $result . ' фото успешно одобрено!';
                }
            }
            elseif($mode === 'remove'){
                $result = $this->getCustomerModel()->photosBatchRemove($items_ex);
                if($result > 0){
                    $this->sendMessagesToAuthors($items_ex);
                    $message = $result . ' фото успешно удалено!';
                }
            }
            else
                $result = 0;

            if($result <= 0){
                $items_ex = array(0,0,0); // fish
            }

            $this->json_response(array("status" => 1, 'records' => $items_ex, 'message' => $message));
        } catch (Exception $e) {
            $this->json_response(array('status' => 0, 'message' => $e->getMessage()));
        }
    }

    public function sendMessagesToAuthors($ids)
    {
        $photos = $this->getCustomerModel()->photosGetListByIDs($ids);
        if(!empty($photos)){
            $author_ids = array();
            foreach ($photos as $photo) {
                $cnt = (!empty($author_ids[$photo['AuthorID']]['count']))
                    ? ($author_ids[$photo['AuthorID']]['count'] + 1)
                    : 1;
                $author_ids[$photo['AuthorID']]['count'] = $cnt;
                if(empty($author_ids[$photo['AuthorID']]['customers'][$photo['CustomerID']])){
                    $author_ids[$photo['AuthorID']]['customers'][$photo['CustomerID']] = trim($photo['SName'])
                        . ' ' . mb_substr(trim($photo['FName']), 0, 1, 'UTF-8') . '.';
                }

            }
            if(!empty($author_ids)){
                foreach ($author_ids as $ak => $author_id) {
                    $messageCustomers = (count($author_id['customers']) > 1)
                        ? ' ваших клиенток '
                        : ' вашей клиентки ';
                    $message = ($author_id['count'] > 1)
                        ? $author_id['count'] . ' фото' . $messageCustomers
                            . implode(', ', array_values($author_id['customers'])) . ' отклонены'
                        : 'Фото' . $messageCustomers
                            . implode(', ', array_values($author_id['customers'])) . ' отклонено';
                    $this->getMessageModel()->chatMessageSend(
                        false,
                        $this->getUserID(),
                        $ak,
                        $message);
                }
            }
        }
    }

    public function countphotos()
    {
        echo $this->getCustomerModel()->photosUnapprovedGetCount();
    }

    /**
     * получаем комментарий к фото для редактирования
     */
    public function getphototext()
    {
        $response = array('status' => 0, 'message' => 'Нет данных для редактирования');
        $ID = $this->input->post('ID', true);
        if(empty($ID)){
            $this->json_response($response);
        }
        $photo = $this->getCustomerModel()->photosGetItem($ID);
        if(!empty($photo)){
            $cm = (!empty($photo['Comment'])) ? $photo['Comment'] : '';
            $response = array('status' => 1, 'message' => $cm);
        }
        $this->json_response($response);
    }

    /**
     * сохраняем новый комментарий к фото
     */
    public function savenewcomment()
    {
        $response = array('status' => 0, 'message' => 'Комментарий НЕ сохранён!');
        $ID = $this->input->post('ID', true);
        $Comment = $this->input->post('Comment', true);
        if(empty($ID)){
            $this->json_response($response);
        }
        $res = $this->getCustomerModel()->photosUpdateComment($ID, $Comment);
        if($res > 0) {
            $response = array(
                'status' => 1,
                'message' => 'Комментарий успешно сохранён!',
                'id' => $ID,
                'comment' => $Comment,
            );
        }
        $this->json_response($response);
    }
}