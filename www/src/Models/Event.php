<?php

namespace App\Models;

use App\Classes\Interfaces\DbInterface;
use PDO;

class Event
{
    private PDO $pdo;
    public function __construct(DbInterface $dbSource)
    {
        $this->pdo = $dbSource->connection;
        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }


    public function getEvents($employee_name = '', $event_name='', $event_date='',$offset=0)
    {
        $params = [];
        $whereClause = [];
        $limit=20 ;
        $sql = "SELECT employees.name AS employee_name, events.name AS event_name, events.date AS event_date , events.id as id ,events.fee as event_fee
        FROM employees
        
        INNER JOIN employees_events ON employees.id = employees_events.employee_id
        INNER JOIN events ON events.id = employees_events.event_id  ";
        if (!empty($employee_name)) {
            $whereClause[] = "employees.name LIKE ?";
            $params[] = "%$employee_name%";
        }

        if (!empty($event_name)) {
            $whereClause[] = "events.name LIKE ?";
            $params[] = "%$event_name%";
        }

        if (!empty($event_date)) {
            $whereClause[] = "events.date = ?";
            $params[] = $event_date;
        }

        if (!empty($whereClause)) {
            $sql .= " WHERE " . implode(" AND ", $whereClause);
        }
        $sql .= " LIMIT $limit OFFSET $offset ";

     
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getEmployees()
    {
        $stmt = $this->pdo->prepare('SELECT id,name,email FROM employees ');
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getParticipation() {}
}
