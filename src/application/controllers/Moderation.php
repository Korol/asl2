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

        $photos = $this->getCustomerModel()->photosUnapprovedGetList();
        if(!empty($photos)){
            foreach ($photos as $pk => $records) {
                foreach ($records as $rk => $record){
                    $photos[$pk][$rk]['pathFull'] = base_url('thumb/?src=/files/customer/photos/' . $record['ID'] . '.' . $record['ext']);
                    $photos[$pk][$rk]['pathThumb'] = base_url('thumb/?src=/files/customer/photos/' . $record['ID'] . '.' . $record['ext'] . '&w=130&h=130&zc=1');
                }
            }
        }

        $this->viewHeader();
        $this->view('form/moderation/photos', array('photos' => $photos));
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

            if($mode === 'approve'){
                $result = $this->getCustomerModel()->photosBatchApprove($items_ex);
            }
            elseif($mode === 'remove'){
                $this->sendMessagesToAuthors($items_ex);
                $result = $this->getCustomerModel()->photosBatchRemove($items_ex);
            }
            else
                $result = 0;

            if($result <= 0){
                $items_ex = array(0,0,0); // fish
            }

            $this->json_response(array("status" => 1, 'records' => $items_ex));
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
}