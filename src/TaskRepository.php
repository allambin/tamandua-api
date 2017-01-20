<?php

namespace Inextends\Tamandua;

use Inextends\Tamandua\Models\Task;
use Inextends\Tamandua\Models\Project;
use Inextends\Tamandua\Models\User;

class TaskRepository
{
    /**
     * 
     * @param string $title
     * @param string $projectCode
     * @param User $user
     * @param array $extra
     * @return boolean
     * @throws APIException
     */
    public function create($title, $projectCode, User $user, $extra = array())
    {
        try {
            $project = Project::where('code', $projectCode)->firstOrFail();
        } catch (\Exception $e) {
            throw new APIException(400401);
        }
        
        ErrorHandler::checkFieldsValidity(array('status', 'priority', 'type'), $extra);
        
        $startDate = null;
        $dueDate = null;
        $assignedToId = null;
        if(!empty($extra['start_date'])) {
            ErrorHandler::checkDateValidity($extra['start_date']);
            $startDate = $extra['start_date'];
        }
        if(!empty($extra['due_date'])) {
            ErrorHandler::checkDateValidity($extra['due_date']);
            $dueDate = $extra['due_date'];
        }
        if(!empty($extra['start_date']) && !empty($extra['due_date'])) {
            ErrorHandler::checkDatesTimeline($extra['start_date'], $extra['due_date']);
        }
        
        if(!empty($extra['assigned_to_id'])) {
            try {
                $assignee = User::where('id', $extra['assigned_to_id'])->firstOrFail();
                $assignedToId = $extra['assigned_to_id'];
            } catch (\Exception $e) {
                throw new APIException(400401);
            }
        }

        $task = new Task();
        $task->title = $title;
        $task->description = !empty($extra['description']) ? $extra['description'] : '';
        $task->status = !empty($extra['status']) ? $extra['status'] : 'new';
        $task->priority = !empty($extra['priority']) ? $extra['priority'] : 'normal';
        $task->type = !empty($extra['type']) ? $extra['type'] : 'task';
        $task->start_date = $startDate;
        $task->due_date = $dueDate;
        $task->assigned_to_id = $assignedToId;
        $task->creator_id = $user->id;
        $task->project_id = $project->id;
        try {
            $task->save();
        } catch (\Illuminate\Database\QueryException $e) {
            throw new APIException(400998, $e->errorInfo[2]);
        }
        
        return true;
    }

}
