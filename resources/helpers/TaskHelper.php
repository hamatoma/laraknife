<?php
namespace App\Helpers;

class TaskHelper
{
    public static function contextHelper(string $task, string $parameter): string
    {
        $rc = '';
        switch ($task) {
            case 'page-index-icon':
                $rc = "<a href=\"/task-create?page_id=$parameter\"><i class=\"bi bi-list-task text-primary\"></i></a>";
                break;
            default:
                break;
        }
        return $rc;
    }
    public function buildButtonTask()
    {

    }
}