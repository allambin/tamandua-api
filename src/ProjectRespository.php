<?php

namespace Inextends\Tamandua;

use Inextends\Tamandua\Models\Project;

class ProjectRespository
{
    /**
     * 
     * @return array
     */
    public function getAll() {
        $projects = array();
        
        foreach (Project::with('creator')->select('id', 'code', 'title', 'description', 'creator_id')->cursor() as $project) {
            $project->creator;
            unset($project->deleted_at);
            unset($project->creator_id);
            array_push($projects, $project);
        }
        
        return $projects;
    }
    
    /**
     * 
     * @param int $id
     */
    public function get($id) {
        try {
            $project = Project::with('creator')
                       ->select('id', 'code', 'title', 'description', 'creator_id')
                       ->where('id', $id)
                       ->firstOrFail();
        } catch (\Exception $e) {
            throw new APIException(400401);
        }
        
        unset($project->creator_id);
        
        return $project;
    }
    
    /**
     * 
     * @param string $code
     * @param string $title
     * @param \Inextends\Tamandua\Models\User $user
     * @param array $extra
     * @return boolean
     * @throws APIException
     */
    public function create($code, $title, Models\User $user, $extra = array()) {
        ErrorHandler::checkFieldValidity('code', $code);
        ErrorHandler::checkFieldUnicity('code', $code);
        
        $project = new Project();
        $project->code = $code;
        $project->title = $title;
        $project->description = !empty($extra['description']) ? $extra['description'] : '';
        $project->creator_id = $user->id;
        try {
            $project->save();
        } catch (\Illuminate\Database\QueryException $e) {
            throw new APIException(400998, $e->errorInfo[2]);
        }
        
        return true;
    }
    
    /**
     * 
     * @param int $id
     * @param \Inextends\Tamandua\Models\User $user
     * @param string $extra
     * @return boolean
     * @throws APIException
     */
    public function update($id, Models\User $user, $extra = array()) {
        try {
            $project = Project::where('id', $id)->firstOrFail();
        } catch (\Exception $e) {
            throw new APIException(400401);
        }
        
        ErrorHandler::checkIsCreator($project, $user);
        
        if(isset($extra['code']) && $extra['code'] !== $project->code) {
            ErrorHandler::checkFieldValidity('code', $extra['code']);
            ErrorHandler::checkFieldUnicity('code', $extra['code']);
            $project->code = $extra['code'];
        }
        if(isset($extra['title']) && !empty($extra['title'])) {
            $project->title = $extra['title'];
        }
        if(isset($extra['description'])) {
            $project->description = $extra['description'];
        }
        
        try {
            $project->save();
        } catch (\Illuminate\Database\QueryException $e) {
            throw new APIException(400998, $e->errorInfo[2]);
        }
        
        return true;
    }
    
    /**
     * 
     * @param int $id
     * @param \Inextends\Tamandua\Models\User $user
     * @return boolean
     * @throws APIException
     */
    public function delete($id, Models\User $user) {
        try {
            $project = Project::where('id', $id)->firstOrFail();
        } catch (\Exception $e) {
            throw new APIException(400401);
        }
        
        ErrorHandler::checkIsCreator($project, $user);
        
        try {
            $project->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            throw new APIException(400998, $e->errorInfo[2]);
        }
        
        return true;
    }
}
