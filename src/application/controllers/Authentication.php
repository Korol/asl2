<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authentication extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('employee_model');

        $this->load->helper('url');
        $this->load->library('session');
    }

    public function login() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        $data = array(
            "employees" => $this->employee_model->employeeGetList(),
            "role_d" => array(
                "10001" => "Директор",
                "10002" => "Секретарь",
                "10003" => "Переводчик",
                "10004" => "Сотрудник"
            )
        );

        // 2. Обработка данных формы
        if (!empty($_POST)) {
            try {
                if (empty($username))
                    throw new Exception('Не указан логин!');

                if (empty($password))
                    throw new Exception('Не указан пароль!');

                $res = $this->employee_model->userAuthorization($username, $password);

                if (!empty($res['errorMessage']))
                    throw new Exception($res['errorMessage']);

                $this->session->set_userdata(
                    array(
                        'logged_system' => TRUE,
                        'username' => $username,
                        'IS_LOVE_STORY' => ($this->input->post('site') == 1),
                        'user' => array(
                            'ID' => $res['record']['ID'],
                            'role' => $res['record']['role'],
                            'Avatar' => $res['record']['Avatar'],
                            'SName' => $res['record']['SName'],
                            'FName' => $res['record']['FName']
                        )
                    )
                );
                redirect(base_url(), 'refresh');
            } catch (Exception $e) {
                $data['errorMessage'] = $e->getMessage();
            }
        }

        $this->load->view('form/login', $data);
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect(base_url('login'), 'refresh');
    }

    /**
     * Логин с СМС авторизацией
     */
    public function login_test(){
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        $data = array(
            "employees" => $this->employee_model->employeeGetList(),
            "role_d" => array(
                "10001" => "Директор",
                "10002" => "Секретарь",
                "10003" => "Переводчик",
                "10004" => "Сотрудник"
            )
        );

        // 2. Обработка данных формы
        if (!empty($_POST)) {
            try {
                if (empty($username))
                    throw new Exception('Не указан логин!');

                if (empty($password))
                    throw new Exception('Не указан пароль!');

                $res = $this->employee_model->userAuthorization($username, $password);

                if (!empty($res['errorMessage']))
                    throw new Exception($res['errorMessage']);

                // сотрудник авторизован по связке логин/пароль – отправляем СМС с кодом
                // и выводим страницу с формой для ввода кода из СМС
                // данные пользователя сохраняем в сессию
                $logged_system = (!empty($res['record']['sms'])) ? false : true; // 1 - вход через СМС, 0 - вход без СМС
                $this->session->set_userdata(
                    array(
                        'logged_system' => $logged_system, // установим в true после СМС авторизации
                        'username' => $username,
                        'IS_LOVE_STORY' => ($this->input->post('site') == 1),
                        'user' => array(
                            'ID' => $res['record']['ID'],
                            'role' => $res['record']['role'],
                            'Avatar' => $res['record']['Avatar'],
                            'SName' => $res['record']['SName'],
                            'FName' => $res['record']['FName']
                        )
                    )
                );

                if(empty($res['record']['sms'])){
                    redirect(base_url(), 'refresh');
                }
                else{
                    // отправка СМС
                    $this->send_sms($res['record']['ID']);
                    $this->load->view('form/login_sms', array('employee_id' => $res['record']['ID']));
                }

            } catch (Exception $e) {
                $data['errorMessage'] = $e->getMessage();
            }
        }

        $this->load->view('form/login_test', $data);
    }

    /**
     * Отправка СМС с кодом авторизации
     * @param $employee_id
     */
    public function send_sms($employee_id)
    {
        // получаем телефон сотрудника
        $recipient_phone = $this->get_phone($employee_id);
        if(empty($recipient_phone)){
            $data['errorMessage'] = 'Телефон сотрудника (ID: ' . $employee_id . ') не найден!';
            $this->load->view('form/login', $data);
        }

        // получаем код
        $sms_code = $this->generate_code(7);
        // сохраняем информацию об СМС-сеансе в БД
        $this->db->insert('assol_employee_sms', array(
            'employee_id' => $employee_id,
            'employee_phone' => $recipient_phone,
            'sms_code' => $sms_code,
            'send_ts' => date('Y-m-d H:i:s'),
        ));
        $employee_sms_id = $this->db->insert_id();
        // готовим и отправляем СМС
        $text = htmlspecialchars('Ваш код авторизации: ' . $sms_code);
        $description = htmlspecialchars('Ассоль');
        $start_time = 'AUTO'; // отправить немедленно или ставим дату и время  в формате YYYY-MM-DD HH:MM:SS
        $end_time = 'AUTO'; // автоматически рассчитать системой или ставим дату и время  в формате YYYY-MM-DD HH:MM:SS
        $rate = 1; // скорость отправки сообщений (1 = 1 смс минута). Одиночные СМС сообщения отправляются всегда с максимальной скоростью.
        $lifetime = 4; // срок жизни сообщения 4 часа
        $recipient = $recipient_phone; // формат 380631234567
        // Assol
        $user = '380993176402'; // тут ваш логин в международном формате без знака +. Пример: 380501234567
        $password = 'Assol300117'; // Ваш пароль

        $myXML   = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
        $myXML  .= "<request>"."\n";
        $myXML  .= "<operation>SENDSMS</operation>"."\n";
        $myXML  .= '        <message start_time="'.$start_time.'" end_time="'.$end_time.'" lifetime="'.$lifetime.'" rate="'.$rate.'" desc="'.$description.'">'."\n";
        $myXML  .= "        <body>".$text."</body>"."\n";
        $myXML  .= "        <recipient>".$recipient."</recipient>"."\n";
        $myXML  .=  "</message>"."\n";
        $myXML  .= "</request>";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERPWD , $user.':'.$password);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, 'http://sms-fly.com/api/api.noai.php');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml", "Accept: text/xml"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $myXML);
        $response = curl_exec($ch);
        curl_close($ch);
        // сохраняем ответ СМС-сервера в БД
        $this->db->update('assol_employee_sms',
            array(
                'response' => $response,
            ),
            array(
                'id' => $employee_sms_id,
            )
        );
    }

    /**
     * Получаем телефон сотрудника для отправки СМС, приводим номер в нужный формат
     * @param $employee_id
     * @return string
     */
    public function get_phone($employee_id)
    {
        $phone = '';
        $phones = $this->employee_model->phoneGetList($employee_id);
        if(!empty($phones[0]['Phone'])){
            $num_arr = array();
            preg_match_all('#\d#', $phones[0]['Phone'], $num_arr); // только цифры
            if(!empty($num_arr)){
                $phone = implode('', $num_arr[0]);
            }
            if(!empty($phone)){
                $phone = '38' . substr($phone, -10); // отрезаем «чистый» номер, добавляем международный код
            }
        }
        return $phone;
    }

    /**
     * Генерация кода для СМС
     * @param int $len
     * @param bool $letters
     * @param bool $upper
     * @param bool $other
     * @return string
     */
    public function generate_code($len = 6, $letters = false, $upper = false, $other = false)
    {
        $chars = "0123456789";
        if($letters){
            $chars .= "abcdefghijklmnpqrstuvwxyz";
        }
        if($upper){
            $chars .= "ABCDEFGHIJKLMNPQRSTUVWXYZ";
        }
        if($other){
            $chars .= "-_!@#$%&[]{}?|";
        }
        $suggestion = substr(str_shuffle($chars), 0, ($len-1));
        return $suggestion;
    }

    /**
     * Обработка формы ввода кода из СМС, завершение авторизации сотрудника (или нет)
     */
    public function login_sms()
    {
        $sms_code = $this->input->post('sms_code', true);
        $employee_id = (int)$this->input->post('employee_id');
        $max_attempts = 3; // количество попыток ввода кода из СМС
        if(empty($employee_id)) redirect(base_url('login')); // от прямого вызова

        // проверяем наличие кода в БД
        $res = $this->db
            ->where('employee_id', $employee_id)
            ->where('sms_code', $sms_code)
            ->where('used', 0)
            ->where("`send_ts` >= (NOW() - INTERVAL 30 MINUTE)")
            ->order_by('id desc')
            ->limit(1)
            ->get('assol_employee_sms')->row_array();
        if(!empty($res)){
            // отмечаем, что код использован
            $this->db->update('assol_employee_sms', array('used' => 1), array('id' => $res['id']));
            // ставим флаг авторизации в сессии
            $this->session->set_userdata('logged_system', true);
            // направляем на Главную страницу
            redirect(base_url(), 'refresh');
        }
        else{
            $login_attempts = (int)$this->session->userdata('login_attempts');
            $login_attempts++;
            // попытки для ввода кода из СМС
            if($login_attempts < $max_attempts){
                // фиксируем попытку в сессии
                $this->session->set_userdata('login_attempts', $login_attempts);
                // повторно показываем страницу с формой для ввода кода из СМС
                $plural = array(
                    1 => 'ка',
                    2 => 'ки',
                    3 => 'ки',
                );
                $attempts_left = $max_attempts - $login_attempts;
                $this->load->view('form/login_sms', array(
                    'employee_id' => $employee_id,
                    'errorMessage' => 'Вы ввели неверный СМС код! Повторите попытку. У вас осталось <strong>' . $attempts_left . '</strong> попыт' . $plural[$attempts_left] . '!',
                ));
            }
            else{
                // удаляем данные об авторизации из сессии
                $this->session->unset_userdata(array('logged_system', 'username', 'IS_LOVE_STORY', 'user', 'login_attempts'));
                // обратно на страницу авторизации
                redirect(base_url('login'), 'location');
            }
        }
    }

}