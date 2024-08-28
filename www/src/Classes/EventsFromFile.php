<?php

namespace App\Classes;

use App\Classes\Interfaces\DbInterface;
use App\Classes\Interfaces\EventsFetchInterface;
use App\Exceptions\FileNotFoundException;
use PDO;

class EventsFromFile implements EventsFetchInterface
{

    private \PDO $pdo;

    public function __construct(DbInterface $dbSource)
    {
        $this->pdo = $dbSource->connection;
    }

    public function fetch($json_file)
    {
        // Check for json file 
        if (!file_exists($json_file))
            throw new FileNotFoundException();
        // Decode JSON data
        $data = json_decode(file_get_contents($json_file), true);


        // Iterate through Events and insert into database
        foreach ($data as $event) {
           $employeeId=   $this->insertIntoEmployee($event);
           $eventId=   $this->insertIntoEvents($event);
           $this->insertIntoEmployeeEvents($employeeId,$eventId);
          
        }
    }



    protected function insertIntoEvents($event): int
    {
        // check for the event is new or not 
        $stmt = $this->pdo->prepare('SELECT id,name  FROM events WHERE name = :name ');
        $stmt->bindParam(":name", $event['event_name'], PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $event_id = $row['id'] ?? 0;


        if (!$row) {
            //insert into Events 
            $stmt = $this->pdo->prepare("INSERT INTO events ( name , date , fee) VALUES ( :name , :date , :fee )");
            $stmt->bindParam(":name", $event['event_name'], PDO::PARAM_STR);
            $stmt->bindParam(":date", $event['event_date'], PDO::PARAM_STR);
            $stmt->bindParam(":fee", $event['participation_fee'], PDO::PARAM_STR);
            $stmt->execute();
            $event_id =  $this->pdo->lastInsertId();
        }
        return $event_id;
    }
    protected function insertIntoEmployee($event): int
    {
        $stmt = $this->pdo->prepare('SELECT * FROM employees WHERE email=:email');
        $stmt->bindParam(":email", $event['employee_mail'], PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $employee_id  = $row['id'] ?? 0;
       
        if (!$row) {
            //insert into employee 
            $stmt = $this->pdo->prepare("INSERT INTO employees (name, email) VALUES (:name, :email)");
            $stmt->bindParam(":name", $event['employee_name'], PDO::PARAM_STR);
            $stmt->bindParam(":email", $event['employee_mail'], PDO::PARAM_STR);
            $stmt->execute();
            $employee_id =  $this->pdo->lastInsertId();
        }
        return $employee_id;
    }

    protected function insertIntoEmployeeEvents($employeeID ,$eventID):void{
        $stmt = $this->pdo->prepare('SELECT employee_id,event_id FROM employees_events WHERE employee_id=:employee_id AND event_id=:event_id');
        $stmt->bindParam(":employee_id", $employeeID, PDO::PARAM_STR);
        $stmt->bindParam(":event_id", $eventID, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            //insert into employee 
            $stmt = $this->pdo->prepare("INSERT INTO employees_events ( employee_id , event_id ) VALUES ( :naemployee_id , :event_id  )");
            $stmt->bindParam(":naemployee_id", $employeeID, PDO::PARAM_STR);
            $stmt->bindParam(":event_id", $eventID, PDO::PARAM_STR);
            $stmt->execute();
        }
    }
}
