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

            if($mode === 'approve')
                $result = $this->getCustomerModel()->photosBatchApprove($items_ex);
            elseif($mode === 'remove')
                $result = $this->getCustomerModel()->photosBatchRemove($items_ex);
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
}