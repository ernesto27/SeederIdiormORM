<?php

use Phinx\Migration\AbstractMigration;

class CreatePostTable extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $this->table('posts')
             ->addColumn('title', 'string')
             ->addColumn('body', 'text')
             ->addColumn('user_id', 'integer')
             ->save();

    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('posts');
    }
}
