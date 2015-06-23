<?php

use Phinx\Migration\AbstractMigration;

class CreateBasicStructure extends AbstractMigration
{
    public function change()
    {
        $this->table('task')
            ->addColumn('title', 'string')
            ->addColumn('description', 'string')
            ->addColumn('url', 'string')
            ->create();

        $this->table('activity')
            ->addColumn('title', 'string')
            ->create();

        $this->table('activity_log')
            ->addColumn('task_id', 'integer')
            ->addColumn('activity_id', 'integer')
            ->addColumn('time_start', 'integer')
            ->addColumn('time_end', 'integer')
            ->create();
    }
}
