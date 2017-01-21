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
        
        $this->checkForErrors($extra);

        $task = new Task();
        $task = $this->fill($task, array_merge($extra, ['title' => $title]));
        $task->creator_id = $user->id;
        $task->project_id = $project->id;
        try {
            $task->save();
        } catch (\Illuminate\Database\QueryException $e) {
            throw new APIException(400998, $e->errorInfo[2]);
        }
        
        return true;
    }
    
    /**
     * 
     * @param int $id
     * @param User $user
     * @param array $extra
     */
    public function update($id, User $user, $extra = array())
    {
        try {
            $task = Task::where('id', $id)->firstOrFail();
        } catch (\Exception $e) {
            throw new APIException(400401);
        }
        
        if(!empty($extra['project_code'])) {
            $project = Project::where('code', $extra['project_code'])->first();
            if($project == null || $project->id != $task->project_id) {
                throw new APIException(400502);
            }
        }

        ErrorHandler::checkIsCreator($task, $user);
        $this->checkForErrors($extra);
        
        $task = $this->fill($task, array_merge($task->toArray(), $extra));
        try {
            $task->save();
        } catch (\Illuminate\Database\QueryException $e) {
            throw new APIException(400998, $e->errorInfo[2]);
        }
        
        return true;
    }
    
    /**
     * 
     * @param array $extra
     * @return boolean
     * @throws APIException
     */
    private function checkForErrors($extra)
    {
        ErrorHandler::checkFieldsValidity(array('status', 'priority', 'type'), $extra);
        
        if (!empty($extra['start_date'])) {
            ErrorHandler::checkDateValidity($extra['start_date']);
        }
        if (!empty($extra['due_date'])) {
            ErrorHandler::checkDateValidity($extra['due_date']);
        }
        if (!empty($extra['start_date']) && !empty($extra['due_date'])) {
            ErrorHandler::checkDatesTimeline($extra['start_date'], $extra['due_date']);
        }

        if (!empty($extra['assigned_to_id'])) {
            try {
                User::where('id', $extra['assigned_to_id'])->firstOrFail();
            } catch (\Exception $e) {
                throw new APIException(400401);
            }
        } else {
            if(isset($extra['status']) && $extra['status'] == 'assigned') {
                throw new APIException(400501);
            }
        }
        
        return true;
    }
    
    /**
     * 
     * @param Task $task
     * @param array $extra
     * @return Task
     */
    private function fill($task, $extra)
    {
        $task->title = $extra['title'];
        $task->description = !empty($extra['description']) ? $extra['description'] : '';
        $task->status = !empty($extra['status']) ? $extra['status'] : 'new';
        $task->priority = !empty($extra['priority']) ? $extra['priority'] : 'normal';
        $task->type = !empty($extra['type']) ? $extra['type'] : 'task';
        $task->start_date = !empty($extra['start_date']) ? $extra['start_date'] : null;
        $task->due_date = !empty($extra['due_date']) ? $extra['due_date'] : null;
        $task->assigned_to_id = !empty($extra['assigned_to_id']) ? $extra['assigned_to_id'] : null;
        
        return $task;
    }

}
