<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Модель для работы с услугами
 */
class Service_model extends MY_Model {

    private $table_service_western =
        "CREATE TABLE IF NOT EXISTS `assol_service_western` (
            `ID` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Уникальный номер записи',
            `EmployeeID` INT(11) NOT NULL COMMENT 'Уникальный номер сотрудника',
            `Date` DATE NOT NULL COMMENT 'Дата',
            `Girl` VARCHAR(512) NOT NULL COMMENT 'Девушка',
            `Men` VARCHAR(512) NOT NULL COMMENT 'Мужчина',
            `SiteID` INT(11) NOT NULL COMMENT 'Уникальный номер сайта',
            `Sum` VARCHAR(128) NOT NULL COMMENT 'Сумма',
            `Code` VARCHAR(64) NOT NULL COMMENT 'Код',
            `IsSend` TINYINT(1) DEFAULT 0 COMMENT 'Флаг отсылки / % Кли-ки',
            `IsPer` TINYINT(1) DEFAULT 0 COMMENT '% Пер-ка',
            `IsDone` TINYINT(1) DEFAULT 0 COMMENT 'Флаг выполнения',
            PRIMARY KEY (`ID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT='Услуги - вестерны';";

    private $table_service_meeting =
        "CREATE TABLE IF NOT EXISTS `assol_service_meeting` (
            `ID` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Уникальный номер записи',
            `EmployeeID` INT(11) NOT NULL COMMENT 'Уникальный номер сотрудника',
            `DateIn` DATE NOT NULL COMMENT 'Дата приезда',
            `DateOut` DATE NOT NULL COMMENT 'Дата отъезда',
            `Girl` VARCHAR(512) NOT NULL COMMENT 'Девушка',
            `Men` VARCHAR(512) NOT NULL COMMENT 'Мужчина',
            `SiteID` INT(11) NOT NULL COMMENT 'Уникальный номер сайта',
            `UserTranslateID` INT(11) NULL COMMENT 'Уникальный номер переводчика - для Love story',
            `UserTranslateOrganizer` VARCHAR(512) NOT NULL COMMENT 'Переводчик организатор - для Love story',
            `UserTranslateDuring` VARCHAR(512) NOT NULL COMMENT 'Переводчик во время встречи - для Love story',
            `UserTranslate` VARCHAR(512) NOT NULL COMMENT 'Переводчик - для Assol',
            `City` VARCHAR(512) NOT NULL COMMENT 'Город',
            `Transfer` VARCHAR(512) NOT NULL COMMENT 'Трансфер',
            `Housing` VARCHAR(512) NOT NULL COMMENT 'Жилье',
            `Translate` VARCHAR(512) NOT NULL COMMENT 'Перевод',
            `IsDone` TINYINT(1) DEFAULT 0 COMMENT 'Флаг выполнения',
            `Photo` VARCHAR(255) DEFAULT NULL,
            `Comment` TEXT,
            PRIMARY KEY (`ID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT='Услуги - встречи';";

    private $table_service_delivery =
        "CREATE TABLE IF NOT EXISTS `assol_service_delivery` (
            `ID` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Уникальный номер записи',
            `EmployeeID` INT(11) NOT NULL COMMENT 'Уникальный номер сотрудника',
            `Date` DATE NOT NULL COMMENT 'Дата',
            `Girl` VARCHAR(512) NOT NULL COMMENT 'Девушка',
            `Men` VARCHAR(512) NOT NULL COMMENT 'Мужчина',
            `SiteID` INT(11) NOT NULL COMMENT 'Уникальный номер сайта',
            `UserTranslateID` INT(11) NULL COMMENT 'Уникальный номер переводчика - для Love story',
            `Delivery` VARCHAR(512) NOT NULL COMMENT 'Доставка',
            `Gratitude` VARCHAR(512) NOT NULL COMMENT 'Благодарность',
            `IsDone` TINYINT(1) DEFAULT 0 COMMENT 'Флаг выполнения',
            `Photo` VARCHAR(255) DEFAULT NULL,
            `Comment` TEXT,
            PRIMARY KEY (`ID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT='Услуги - доставки';";

    private $table_service_contact =
        "CREATE TABLE `assol_service_contact` (
          `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `Date` date DEFAULT NULL,
          `SiteID` int(11) DEFAULT NULL,
          `EmployeeID` int(11) DEFAULT NULL,
          `Men` varchar(255) DEFAULT NULL,
          `CustomerID` int(11) DEFAULT NULL,
          `Description` text,
          `Added` datetime DEFAULT NULL,
          `AuthorID` int(11) DEFAULT NULL,
          PRIMARY KEY (`ID`),
          KEY `SiteID` (`SiteID`),
          KEY `EmployeeID` (`EmployeeID`),
          KEY `CustomerID` (`CustomerID`),
          KEY `AuthorID` (`AuthorID`),
          KEY `Date` (`Date`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

    /**
     * Инициализация таблицы
     */
    public function initDataBase() {
        $this->db()->query($this->table_service_western);
        $this->db()->query($this->table_service_meeting);
        $this->db()->query($this->table_service_delivery);
        $this->db()->query($this->table_service_contact);
    }

    public function dropTables() {
        $this->load->dbforge();

        $this->dbforge->drop_table(self::TABLE_SERVICE_WESTERN_NAME, TRUE);
        $this->dbforge->drop_table(self::TABLE_SERVICE_MEETING_NAME, TRUE);
        $this->dbforge->drop_table(self::TABLE_SERVICE_DELIVERY_NAME, TRUE);
        $this->dbforge->drop_table(self::TABLE_SERVICE_CONTACT_NAME, TRUE);
    }

    /**
     * Получить количество непрочитанных услуг
     */
    public function getCountUnreadService($isAdmin, $userTranslateID) {
        $count = 0;

        if (IS_LOVE_STORY) {
            if ($isAdmin) {
                $count += $this->db()
                    ->from(self::TABLE_SERVICE_WESTERN_NAME)
                    ->where('IsDone', 0)
                    ->count_all_results();
                $count += $this->db()
                    ->from(self::TABLE_SERVICE_MEETING_NAME)
                    ->where('IsDone', 0)
                    ->count_all_results();
                $count += $this->db()
                    ->from(self::TABLE_SERVICE_DELIVERY_NAME)
                    ->where('IsDone', 0)
                    ->count_all_results();
            } else {
                $count += $this->db()
                    ->from(self::TABLE_SERVICE_MEETING_NAME)
                    ->where('UserTranslateID', $userTranslateID)
                    ->where('IsDone', 0)
                    ->count_all_results();

                $count += $this->db()
                    ->from(self::TABLE_SERVICE_DELIVERY_NAME)
                    ->where('UserTranslateID', $userTranslateID)
                    ->where('IsDone', 0)
                    ->count_all_results();
            }
        } else {
            if ($isAdmin) {
                $count += $this->db()
                    ->from(self::TABLE_SERVICE_WESTERN_NAME)
                    ->where('IsDone', 0)
                    ->count_all_results();
                $count += $this->db()
                    ->from(self::TABLE_SERVICE_MEETING_NAME)
                    ->where('IsDone', 0)
                    ->count_all_results();
                $count += $this->db()
                    ->from(self::TABLE_SERVICE_DELIVERY_NAME)
                    ->where('IsDone', 0)
                    ->count_all_results();
            } else {
                $count += $this->db()
                    ->from(self::TABLE_SERVICE_DELIVERY_NAME)
                    ->where('UserTranslateID', $userTranslateID)
                    ->where('IsDone', 0)
                    ->count_all_results();
            }
        }

        return $count;
    }

    /**
     * Поиск услуги "Вестерн"
     *
     * @param int    $idEmployee ID сотрудника
     * @param string $start      начало периода
     * @param string $end        окончание периода
     * @param string $isDirector флаг директора. Для директора выбираются все не выполненные услуги, если не указаны временные интервалы
     *
     * @return array список услуг
     */
    public function westernGetList($idEmployee, $start, $end, $isDirector) {
        if (!empty($idEmployee))
            $this->db()->where('EmployeeID', $idEmployee);

        $this->db()->group_start();

        if (!empty($start)) {
            if (!empty($end)) {
                $this->db()->where("`Date` BETWEEN '$start' AND '$end'", NULL, FALSE);
            } else {
                $this->db()->where('Date', $start);
            }
        } else {
            if (IS_LOVE_STORY) {
                $this->db()
                    ->group_start()
                        ->where('IsDone', 0)
                        ->or_where("`Date` = DATE_FORMAT(NOW(),'%Y-%m-%d')", NULL, FALSE)
                    ->group_end();
            } else {
                if ($isDirector) {
                    $this->db()->where('IsDone', 0);
                } else {
                    $this->db()->where("`Date` = DATE_FORMAT(NOW(),'%Y-%m-%d')", NULL, FALSE);
                }
            }
        }

        $this->db()->group_end();

        return $this->db()->get(self::TABLE_SERVICE_WESTERN_NAME)->result_array();
    }

    /**
     * Полученить указанный вестерн
     *
     * @param string $id ID записи
     */
    public function westernGet($id) {
        return $this->db()->get_where(self::TABLE_SERVICE_WESTERN_NAME, ['ID' => $id])->row_array();
    }

    /**
     * Добавление нового вестерна
     *
     * @param int       $employeeID ID сотрудника
     * @param string    $date       дата
     * @param string    $girl       девушка
     * @param string    $men        мужчина
     * @param int       $site       ID сайта
     * @param float     $sum        сумма
     * @param string    $code       код
     * @param int       $isSend     флаг "выслали / % Кли-ки"
     * @param int       $isPer      флаг "% Пер-ка"
     *
     * @return int ID записи
     */
    public function westernInsert($employeeID, $date, $girl, $men, $site, $sum, $code, $isSend, $isPer) {
        $data = array(
            'EmployeeID' => $employeeID,
            'Date' => $date,
            'Girl' => $girl,
            'Men' => $men,
            'SiteID' => $site,
            'Sum' => $sum,
            'Code' => $code,
            'IsSend' => $isSend,
            'IsPer' => empty($isPer) ? 0 : $isPer
        );
        $this->db()->insert(self::TABLE_SERVICE_WESTERN_NAME, $data);

        return $this->db()->insert_id();
    }

    /**
     * Обновление вестерна
     *
     * @param int       $id         ID записи
     * @param string    $date       дата
     * @param string    $girl       девушка
     * @param string    $men        мужчина
     * @param int       $site       ID сайта
     * @param float     $sum        сумма
     * @param string    $code       код
     * @param int       $isSend     флаг "выслали / % Кли-ки"
     * @param int       $isPer      флаг "% Пер-ка"
     */
    public function westernUpdate($id, $date, $girl, $men, $site, $sum, $code, $isSend, $isPer) {
        $data = array(
            'Date' => $date,
            'Girl' => $girl,
            'Men' => $men,
            'SiteID' => $site,
            'Sum' => $sum,
            'Code' => $code,
            'IsSend' => $isSend,
            'IsPer' => empty($isPer) ? 0 : $isPer,
        );

        $this->db()->where('ID', $id);
        $this->db()->update(self::TABLE_SERVICE_WESTERN_NAME, $data);
    }

    public function westernDone($id) {
        $this->db()->where('ID', $id);
        $this->db()->update(self::TABLE_SERVICE_WESTERN_NAME, array('IsDone' => 1));
    }

    public function westernSend($id, $isSend) {
        $this->db()->where('ID', $id);
        $this->db()->update(self::TABLE_SERVICE_WESTERN_NAME, array('IsSend' => $isSend));
    }

    public function westernPer($id, $isPer) {
        $this->db()->where('ID', $id);
        $this->db()->update(self::TABLE_SERVICE_WESTERN_NAME, array('IsPer' => $isPer));
    }

    /**
     * Поиск услуги "Встречи"
     *
     * @param int    $idEmployee ID сотрудника
     * @param string $start      начало периода
     * @param string $end        окончание периода
     * @param string $isDirector флаг директора. Для директора выбираются все не выполненные услуги, если не указаны временные интервалы
     *
     * @return array список услуг
     */
    public function meetingGetList($idEmployee, $start, $end, $isDirector) {
        if (!empty($idEmployee)) {
            $this->db()->where('EmployeeID', $idEmployee);
        }

        $this->db()->group_start();

        if (!empty($start)) {
            if (!empty($end)) {
                // Выбираем полное покрытие интервала + вхождение $start и $end в любой интервал
                $this->db()->where("(`DateIn` >= '$start' AND `DateOut` <= '$end') OR ('$start' BETWEEN `DateIn` AND `DateOut`) OR ('$end' BETWEEN `DateIn` AND `DateOut`)", NULL, FALSE);
            } else {
                $this->db()->where("'$start' BETWEEN `DateIn` AND `DateOut`", NULL, FALSE);
            }
        } else {
//            $this->db()
//                ->group_start()
//                    ->where('IsDone', 0)
//                    ->or_where("NOW() BETWEEN `DateIn` AND `DateOut`", NULL, FALSE)
//                ->group_end();

            if (IS_LOVE_STORY) {
                $this->db()
                    ->group_start()
                        ->where('IsDone', 0)
                        ->or_where("CURDATE() BETWEEN `DateIn` AND `DateOut`", NULL, FALSE)
                    ->group_end();
            } else {
                if ($isDirector) {
                    $this->db()->where('IsDone', 0);
                } else {
                    $this->db()->where("CURDATE() BETWEEN `DateIn` AND `DateOut`", NULL, FALSE);
                }
            }
        }

        $this->db()->group_end();

        return $this->db()->get(self::TABLE_SERVICE_MEETING_NAME)->result_array();
    }

    /**
     * Полученить указанную встречу
     *
     * @param string $id ID записи
     */
    public function meetingGet($id) {
        return $this->db()->get_where(self::TABLE_SERVICE_MEETING_NAME, ['ID' => $id])->row_array();
    }

    /**
     * Добавление новой встречи
     *
     * @param int       $employeeID             ID сотрудника
     * @param string    $dateIn                 дата приезда
     * @param string    $dateOut                дата отъезда
     * @param string    $girl                   девушка
     * @param string    $men                    мужчина
     * @param int       $site                   ID сайта
     * @param string    $userTranslate          переводчик
     * @param string    $city                   город
     * @param string    $transfer               трансфер
     * @param string    $housing                жилье
     * @param string    $translate              перевод
     * @param string    $userTranslateOrganizer переводчик - организатор
     * @param string    $userTranslateDuring    переводчик во время встречи
     *
     * @return int ID записи
     */
    public function meetingInsert($employeeID, $dateIn, $dateOut, $girl, $men, $site, $userTranslate, $city,
                                  $transfer, $housing, $translate, $userTranslateOrganizer, $userTranslateDuring) {
        $data = array(
            'EmployeeID' => $employeeID,
            'DateIn' => $dateIn,
            'DateOut' => $dateOut,
            'Girl' => $girl,
            'Men' => $men,
            'SiteID' => $site,
            IS_LOVE_STORY ? 'UserTranslateID' : 'UserTranslate' => $userTranslate,
            'City' => $city,
            'Transfer' => $transfer,
            'Housing' => $housing,
            'Translate' => $translate
        );
        if (IS_LOVE_STORY) {
            $data['UserTranslateOrganizer'] = $userTranslateOrganizer;
            $data['UserTranslateDuring'] = $userTranslateDuring;
        }

        $this->db()->insert(self::TABLE_SERVICE_MEETING_NAME, $data);

        return $this->db()->insert_id();
    }

    /**
     * Обновление встречи
     *
     * @param int       $id                     ID записи
     * @param string    $dateIn                 дата приезда
     * @param string    $dateOut                дата отъезда
     * @param string    $girl                   девушка
     * @param string    $men                    мужчина
     * @param int       $site                   ID сайта
     * @param string    $userTranslate          переводчик
     * @param string    $city                   город
     * @param string    $transfer               трансфер
     * @param string    $housing                жилье
     * @param string    $translate              перевод
     * @param string    $userTranslateOrganizer переводчик - организатор
     * @param string    $userTranslateDuring    переводчик во время встречи
     */
    public function meetingUpdate($id, $dateIn, $dateOut, $girl, $men, $site, $userTranslate, $city, $transfer,
                                  $housing, $translate, $userTranslateOrganizer, $userTranslateDuring) {
        $data = array(
            'DateIn' => $dateIn,
            'DateOut' => $dateOut,
            'Girl' => $girl,
            'Men' => $men,
            'SiteID' => $site,
            IS_LOVE_STORY ? 'UserTranslateID' : 'UserTranslate' => $userTranslate,
            'City' => $city,
            'Transfer' => $transfer,
            'Housing' => $housing,
            'Translate' => $translate
        );

        if (IS_LOVE_STORY) {
            $data['UserTranslateOrganizer'] = $userTranslateOrganizer;
            $data['UserTranslateDuring'] = $userTranslateDuring;
        }

        $this->db()->where('ID', $id);
        $this->db()->update(self::TABLE_SERVICE_MEETING_NAME, $data);
    }

    public function meetingDone($id) {
        $this->db()->where('ID', $id);
        $this->db()->update(self::TABLE_SERVICE_MEETING_NAME, array('IsDone' => 1));
    }

    /**
     * Поиск услуги "Доставка"
     *
     * @param int    $idEmployee ID сотрудника
     * @param string $start      начало периода
     * @param string $end        окончание периода
     * @param string $isDirector флаг директора. Для директора выбираются все не выполненные услуги, если не указаны временные интервалы
     *
     * @return array список услуг
     */
    public function deliveryGetList($idEmployee, $start, $end, $isDirector) {
        if (!empty($idEmployee)) {
            $this->db()
                ->group_start()
                    ->where('EmployeeID', $idEmployee)
                    ->or_where('UserTranslateID', $idEmployee)
                ->group_end();
        }

        $this->db()->group_start();

        if (!empty($start)) {
            if (!empty($end)) {
                $this->db()->where("`Date` BETWEEN '$start' AND '$end'", NULL, FALSE);
            } else {
                $this->db()->where('Date', $start);
            }
        } else {
            $this->db()
                ->group_start()
                    ->where('IsDone', 0)
                    ->or_where("`Date` = DATE_FORMAT(NOW(),'%Y-%m-%d')", NULL, FALSE)
                ->group_end();
        }

        $this->db()->group_end();

        return $this->db()->get(self::TABLE_SERVICE_DELIVERY_NAME)->result_array();
    }

    /**
     * Полученить указанную доставку
     *
     * @param string $id ID записи
     */
    public function deliveryGet($id) {
        return $this->db()->get_where(self::TABLE_SERVICE_DELIVERY_NAME, ['ID' => $id])->row_array();
    }

    /**
     * Добавление новой доставки
     *
     * @param int       $employeeID     ID сотрудника
     * @param string    $date           дата
     * @param string    $girl           девушка
     * @param string    $men            мужчина
     * @param int       $site           ID сайта
     * @param string    $userTranslate  переводчик
     * @param string    $delivery       доставка
     * @param string    $gratitude      благодарность
     *
     * @return int ID записи
     */
    public function deliveryInsert($employeeID, $date, $girl, $men, $site, $userTranslate, $delivery, $gratitude) {
        $data = array(
            'EmployeeID' => $employeeID,
            'Date' => $date,
            'Girl' => $girl,
            'Men' => $men,
            'SiteID' => $site,
            'UserTranslateID' => $userTranslate,
            'Delivery' => $delivery,
            'Gratitude' => $gratitude
        );
        $this->db()->insert(self::TABLE_SERVICE_DELIVERY_NAME, $data);

        return $this->db()->insert_id();
    }

    /**
     * Обновление доставки
     *
     * @param int       $id             ID записи
     * @param string    $date           дата
     * @param string    $girl           девушка
     * @param string    $men            мужчина
     * @param int       $site           ID сайта
     * @param string    $userTranslate  переводчик
     * @param string    $delivery       доставка
     * @param string    $gratitude      благодарность
     */
    public function deliveryUpdate($id, $date, $girl, $men, $site, $userTranslate, $delivery, $gratitude) {
        $data = array(
            'Date' => $date,
            'Girl' => $girl,
            'Men' => $men,
            'SiteID' => $site,
            'UserTranslateID' => $userTranslate,
            'Delivery' => $delivery,
            'Gratitude' => $gratitude
        );

        $this->db()->where('ID', $id);
        $this->db()->update(self::TABLE_SERVICE_DELIVERY_NAME, $data);
    }

    public function deliveryDone($id) {
        $this->db()->where('ID', $id);
        $this->db()->update(self::TABLE_SERVICE_DELIVERY_NAME, array('IsDone' => 1));
    }

    /**
     * Удаление услуги
     * @param int $id           ID записи
     * @param string $type      тип услуги (вестерн, доставка, встреча)
     */
    public function serviceDelete($id, $type)
    {
        $types = array(
            'western' => self::TABLE_SERVICE_WESTERN_NAME,
            'meeting' => self::TABLE_SERVICE_MEETING_NAME,
            'delivery' => self::TABLE_SERVICE_DELIVERY_NAME,
        );

        if(!empty($id) && in_array($type, array_keys($types))){
            $this->db()->delete($types[$type], array('ID' => $id));
        }
    }

    /**
     * Поиск услуги по фамилии клиентки и типу (Вестерн | Встреча | Доставка)
     * @param $SName
     * @param $type
     * @return array|object
     */
    public function findServiceByCustomerSName($SName, $type)
    {
        $types = array(
            'western' => array(
                'table' => self::TABLE_SERVICE_WESTERN_NAME,
                'select' => '',
                'sort' => array('field' => 'ID', 'type' => 'desc'),
            ),
            'meeting' => array(
                'table' => self::TABLE_SERVICE_MEETING_NAME,
                'select' => '',
                'sort' => array('field' => 'DateIn', 'type' => 'desc'),
            ),
            'delivery' => array(
                'table' => self::TABLE_SERVICE_DELIVERY_NAME,
                'select' => ', e2.SName AS TSName, e2.FName AS TFName, e2.MName AS TMName',
                'sort' => array('field' => 'Date', 'type' => 'desc'),
            ),
        );

        if(!empty($SName) && in_array($type, array_keys($types))){
            $this->db()->select($types[$type]['table'] . '.*, e.SName AS ESName, e.FName AS EFName, e.MName AS EMName' . $types[$type]['select']);
            $this->db()->join(self::TABLE_EMPLOYEE_NAME . ' AS e', 'e.ID = ' . $types[$type]['table'] . '.EmployeeID');
            if($type == 'delivery'){
                $this->db()->join(self::TABLE_EMPLOYEE_NAME . ' AS e2', 'e2.ID = ' . $types[$type]['table'] . '.UserTranslateID');
            }
            $this->db()->where('IsDone', 1);
            $this->db()->like('Girl', $SName, 'after');
            $this->db()->order_by($types[$type]['sort']['field'], $types[$type]['sort']['type']);
            $res = $this->db()->get($types[$type]['table'])->result_array();//var_dump($this->db()->last_query());
        }

        return (!empty($res)) ? $res : array();
    }

    public function updateServiceAdditional($sid, $type, $data)
    {
        $types = array(
            'western' => self::TABLE_SERVICE_WESTERN_NAME,
            'meeting' => self::TABLE_SERVICE_MEETING_NAME,
            'delivery' => self::TABLE_SERVICE_DELIVERY_NAME,
        );

        if(!empty($sid) && in_array($type, array_keys($types)) && !empty($data)){
            $this->db()->update($types[$type], $data, array('ID' => $sid));
        }
    }

    public function serviceGet($id, $type)
    {
        $types = array(
            'western' => self::TABLE_SERVICE_WESTERN_NAME,
            'meeting' => self::TABLE_SERVICE_MEETING_NAME,
            'delivery' => self::TABLE_SERVICE_DELIVERY_NAME,
        );

        if(!empty($id) && in_array($type, array_keys($types))){
            $res = $this->db()->get_where($types[$type], array('ID' => $id))->row_array();
        }
        return (!empty($res)) ? $res : array();
    }

    /**
     * @param string $date      - сегодняшняя дата Y-m-d
     * @param string $userField - поле, в котором указано ФИО
     * @param string $SName     - фамилия
     * @return array
     */
    public function getMeetingByDateAndSName($date, $userField, $SName)
    {
        $return = array();
        if(!empty($date)){
            $return = $this->db()
                ->where("`IsDone` = 0
                AND ('" . $date . "' BETWEEN `DateIn` AND `DateOut`)
                AND (`" . $userField . "` LIKE '%" . $SName . "%')", null, false)
                ->get(self::TABLE_SERVICE_MEETING_NAME)->result_array();
        }
        return (!empty($return)) ? $return : array();
    }

    /**
     * @param string $date - дата Y-m-d
     * @return array
     */
    public function getMeetingByDate($date)
    {
        $return = array();
        if(!empty($date)){
            $return = $this->db()
                ->where("`IsDone` = 0 AND ('" . $date . "' BETWEEN `DateIn` AND `DateOut`)", null, false)
                ->get(self::TABLE_SERVICE_MEETING_NAME)->result_array();
        }
        return (!empty($return)) ? $return : array();
    }

    /*  КОНТАКТЫ */

    /**
     * Добавление нового контакта
     *
     * @param int       $authorID       ID добавляющего контакт
     * @param string    $date           дата
     * @param int       $site           ID сайта
     * @param int       $employeeID     ID переводчика
     * @param string    $men            имя мужчины
     * @param string    $customerID     ID девушки
     * @param string    $description    описание
     *
     * @return int ID записи
     */
    public function contactInsert($authorID, $date, $site, $employeeID, $men, $customerID, $description) {
        $data = array(
            'Date' => $date,
            'SiteID' => $site,
            'EmployeeID' => $employeeID,
            'Men' => $men,
            'CustomerID' => $customerID,
            'Description' => $description,
            'Added' => date('Y-m-d H:i:s'),
            'AuthorID' => $authorID
        );
        $this->db()->insert(self::TABLE_SERVICE_CONTACT_NAME, $data);

        return $this->db()->insert_id();
    }

    /**
     * Обновление контакта
     *
     * @param int       $id             ID редактируемого контакта
     * @param string    $date           дата
     * @param int       $site           ID сайта
     * @param int       $employeeID     ID переводчика
     * @param string    $men            имя мужчины
     * @param string    $customerID     ID девушки
     * @param string    $description    описание
     */
    public function contactUpdate($id, $date, $site, $employeeID, $men, $customerID, $description) {
        $data = array(
            'Date' => $date,
            'SiteID' => $site,
            'EmployeeID' => $employeeID,
            'Men' => $men,
            'CustomerID' => $customerID,
            'Description' => $description,
        );

        $this->db()->where('ID', $id);
        $this->db()->update(self::TABLE_SERVICE_CONTACT_NAME, $data);
    }

    /**
     * Полный список запросов контактов
     *
     * @return mixed
     */
    public function getFullContactsList()
    {
        return $this->db()
            ->select("sc.*, s.Name as 'SiteName', 
                    e.SName as 'TSName', e.FName  as 'TFName', 
                    c.SName as 'CSName', c.FName as 'CFName'")
            ->from(self::TABLE_SERVICE_CONTACT_NAME . ' as sc')
            ->join(self::TABLE_SITE_NAME . ' as s', 's.ID = sc.SiteID')
            ->join(self::TABLE_EMPLOYEE_NAME . ' as e', 'e.ID = sc.EmployeeID')
            ->join(self::TABLE_CUSTOMER_NAME . ' as c', 'c.ID = sc.CustomerID')
            ->where('sc.IsDone', 0)
            ->order_by('CSName ASC')
            ->get()->result_array();
    }

    /**
     * Список контактов Клиентки
     *
     * @param int $CustomerID
     * @return mixed
     */
    public function getCustomerContactsList($CustomerID)
    {
        return $this->db()
            ->select("sc.*, s.Name as 'SiteName', 
                    e.SName as 'TSName', e.FName  as 'TFName'")
            ->from(self::TABLE_SERVICE_CONTACT_NAME . ' as sc')
            ->join(self::TABLE_SITE_NAME . ' as s', 's.ID = sc.SiteID')
            ->join(self::TABLE_EMPLOYEE_NAME . ' as e', 'e.ID = sc.EmployeeID')
            ->where('sc.CustomerID', $CustomerID)
            ->where('sc.IsDone', 1)
            ->order_by('sc.ID DESC')
            ->get()->result_array();
    }

    /**
     * Поиск Клиентки по фамилии
     *
     * @param string $name
     * @return mixed
     */
    public function findCustomerByName($name)
    {
        return $this->db()
            ->like('SName', $name)
            ->limit(1)
            ->get(self::TABLE_CUSTOMER_NAME)->row_array();
    }

    /**
     * Получаем определённый контакт для редактирования
     *
     * @param int $id
     * @return mixed
     */
    public function contactGet($id)
    {
        return $this->db()
            ->select("sc.*, s.Name as 'SiteName', 
                    e.SName as 'TSName', e.FName  as 'TFName', 
                    c.SName as 'CSName', c.FName as 'CFName'")
            ->from(self::TABLE_SERVICE_CONTACT_NAME . ' as sc')
            ->join(self::TABLE_SITE_NAME . ' as s', 's.ID = sc.SiteID')
            ->join(self::TABLE_EMPLOYEE_NAME . ' as e', 'e.ID = sc.EmployeeID')
            ->join(self::TABLE_CUSTOMER_NAME . ' as c', 'c.ID = sc.CustomerID')
            ->where('sc.ID', $id)
            ->get()->row_array();
    }

    /**
     * Удаление контакта
     *
     * @param int $id
     * @return mixed
     */
    public function contactDelete($id)
    {
        return $this->db()->delete(self::TABLE_SERVICE_CONTACT_NAME, array('ID' => $id));
    }

    /**
     * отметка о выполнении контакта
     * @param $id
     * @return int
     */
    public function contactDone($id)
    {
        $this->db()->update(
            self::TABLE_SERVICE_CONTACT_NAME,
            array('IsDone' => 1),
            array('ID' => $id)
        );
        return $this->db()->affected_rows();
    }
}