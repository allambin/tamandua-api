<?php

use Phinx\Migration\AbstractMigration;

class TblAuthToken extends AbstractMigration
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
        $token = $this->table('auth_token');
        $token->addColumn('user_id', 'integer')
              ->addColumn('token', 'string', array('limit' => 255))
              ->addColumn('valid_until', 'datetime')
              ->addColumn('created_at', 'timestamp', array('default' => 'CURRENT_TIMESTAMP'))
              ->addIndex(array('token'), array('unique' => true))
              ->create();
        
        $token->addForeignKey('user_id', 'user', 'id', array('delete'=> 'CASCADE', 'update'=> 'NO_ACTION'))
              ->save();
    }
}
