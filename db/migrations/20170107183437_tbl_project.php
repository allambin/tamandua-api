<?php

use Phinx\Migration\AbstractMigration;

class TblProject extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $project = $this->table('project');
        $project->addColumn('creator_id', 'integer')
                ->addColumn('code', 'string', array('limit' => 64))
                ->addColumn('title', 'string', array('limit' => 255))
                ->addColumn('description', 'text', array('null' => true))
                ->addColumn('created_at', 'timestamp')
                ->addColumn('updated_at', 'timestamp')
                ->addIndex(array('code'), array('unique' => true))
                ->create();
        
        $project->addForeignKey('creator_id', 'user', 'id', array('delete'=> 'NO_ACTION', 'update'=> 'NO_ACTION'))
              ->save();
    }
}
