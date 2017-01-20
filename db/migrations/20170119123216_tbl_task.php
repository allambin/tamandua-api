<?php

use Phinx\Migration\AbstractMigration;

class TblTask extends AbstractMigration
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
        $task = $this->table('task');
        $task->addColumn('project_id', 'integer')
                ->addColumn('creator_id', 'integer')
                ->addColumn('assigned_to_id', 'integer', array('null' => true))
                ->addColumn('title', 'string', array('limit' => 255))
                ->addColumn('description', 'text', array('null' => true))
                ->addColumn('status', 'enum', array('values' => array('new', 'assigned', 'pending', 'resolved', 'rejected'), 'default' => 'new'))
                ->addColumn('priority', 'enum', array('values' => array('low', 'normal', 'high', 'urgent'), 'default' => 'normal'))
                ->addColumn('type', 'enum', array('values' => array('task', 'issue', 'documentation'), 'default' => 'task'))
                ->addColumn('start_date', 'date', array('null' => true))
                ->addColumn('due_date', 'date', array('null' => true))
                ->addColumn('created_at', 'timestamp')
                ->addColumn('updated_at', 'timestamp')
                ->create();
        
        $task->addForeignKey('creator_id', 'user', 'id', array('delete'=> 'NO_ACTION', 'update'=> 'NO_ACTION'))
              ->save();
        $task->addForeignKey('assigned_to_id', 'user', 'id', array('delete'=> 'NO_ACTION', 'update'=> 'NO_ACTION'))
              ->save();
        $task->addForeignKey('project_id', 'project', 'id', array('delete'=> 'NO_ACTION', 'update'=> 'NO_ACTION'))
              ->save();
    }
}
