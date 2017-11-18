<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sms_access extends MY_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->database();
    }
    
    /**
     * Функция проверки прав доступа
     */
    function assertUserRight() {
//        if (!$this->role['isDirector'])
        if($this->getUserID() != 1)
            show_error('Данный раздел доступен только для роли "Директор" с ID = 1', 403, 'Доступ запрещен');
    }

    public function index()
    {
        // 1. Проверка прав доступа
        $this->assertUserRight();
        // 2. Загрузка шаблона
        $content['employees'] = $this->db
            ->select('ID, SName, FName, MName, sms, IsBlocked')
            ->where(array(
                'IsDeleted' => 0,
            ))
            ->order_by('SName asc, FName asc')
            ->get('assol_employee')->result_array();

        $this->viewHeader(array('title' => 'Доступ по СМС'));
        $this->load->view('form/sms_tab', $content);
        $this->viewFooter();
    }

    public function sms_change()
    {
        // 1. Проверка прав доступа
        $this->assertUserRight();
        // 2. Сохранение данных
        $user_id = $this->input->post('user_id');
        $user_value = $this->input->post('user_value');
        if(!empty($user_id)){
            $this->db->update('assol_employee', array('sms' => (!empty($user_value))? 1 : 0), array('ID' => $user_id));
        }
        if($this->db->affected_rows() > 0)
            echo 1;
        else
            echo 0;
    }
} 