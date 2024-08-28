<?php

namespace App\Controllers;

use App\Classes\View;
use App\Classes\MysqlConnector;
use App\Models\Event;

class HomeController
{
    protected Event $events;

    public function __construct()
    {
        $mysqlConnection = MysqlConnector::getInstance();

        $this->events = new Event($mysqlConnection);
    }

    public function index()
    {
        // Handle filtering and display
        $employee_name = $_GET['employee_name'] ?? '';
        $event_name = $_GET['event_name'] ?? '';
        $event_date = $_GET['event_date'] ?? '';
        $offset= $_GET['offset'] ?? 0;
        $events = $this->events->getEvents($employee_name, $event_name, $event_date,$offset);

        // load Events and pass it to View 
        return (new View('Event.index', ['events' => $events, ]))->render();
    }
}
