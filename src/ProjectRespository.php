<?php

namespace Inextends\Tamandua;

use Inextends\Tamandua\Models\Project;

class ProjectRespository
{
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
}
