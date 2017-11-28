<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Модель для работы со звонками
 */
class Calls_model extends MY_Model {

    private $table = "CREATE TABLE `assol_calls` (
      `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `CustomerID` int(11) DEFAULT NULL,
      `CustomerName` varchar(255) DEFAULT NULL,
      `EmployeeID` int(11) DEFAULT NULL,
      `EmployeeName` varchar(255) DEFAULT NULL,
      `CreatedDate` date DEFAULT NULL,
      `CreatedTS` datetime DEFAULT NULL,
      `Comment` text,
      PRIMARY KEY (`ID`),
      KEY `CustomerID` (`CustomerID`),
      KEY `EmployeeID` (`EmployeeID`),
      KEY `CreatedDate` (`CreatedDate`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

    /**
     * Инициализация таблицы
     */
    public function initDataBase() {
        $this->db()->query($this->table);
    }

    public function dropTables() {
        $this->load->dbforge();

        $this->dbforge->drop_table(self::TABLE_CALENDAR_EVENT_NAME, TRUE);
    }

    /**
     * сохраняем новый звонок
     * @param $data
     * @return mixed
     */
    public function saveCall($data)
    {
        $this->db()->insert(
            self::TABLE_CALLS,
            $data
        );
        return ($this->db()->affected_rows() > 0)
            ? $this->db()->insert_id()
            : 0;
    }

    /**
     * список звонков за указанный месяц указанного года
     * @param $year
     * @param $month
     * @return mixed
     */
    public function getCalls($year, $month)
    {
        return $this->db()
            ->where("DATE_FORMAT(`CreatedDate`, '%Y-%m') = '" . $year . "-" . $month . "'", null, false)
            ->order_by('CreatedTS DESC')
            ->get(self::TABLE_CALLS)->result_array();
    }

    /**
     * удаляем звонок
     * @param $id
     * @return mixed
     */
    public function removeCall($id)
    {
        $this->db()->delete(
            self::TABLE_CALLS,
            array(
                'ID' => $id,
            )
        );
        return $this->db()->affected_rows();
    }

}