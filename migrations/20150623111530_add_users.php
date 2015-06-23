<?php

use Phinx\Migration\AbstractMigration;

class AddUsers extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     */
    public function change()
    {
        $this->table('user')
            ->addColumn('email', 'string')
            ->addIndex('email', ['unique' => true])
            ->create();

        $this->table('activity')
            ->addColumn('user_id', 'integer')
            ->addIndex('user_id')
            ->save();

        $this->table('task')
            ->addColumn('user_id', 'integer')
            ->addIndex('user_id')
            ->save();

        $this->table('activity_log')
            ->addColumn('user_id', 'integer')
            ->addIndex('user_id')
            ->addIndex('task_id')
            ->addIndex('activity_id')
            ->save();
    }
}
