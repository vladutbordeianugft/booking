<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddTripTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        
        // create the table
        $table = $this->table('Trip',['id' => true, 'primary_key' => 'id']);
        $table->addColumn('slug', 'string', ['limit' => 255])
              ->addColumn('title', 'string', ['limit' => 255])
              ->addColumn('description', 'string', ['limit' => 50])
              ->addColumn('start_date', 'datetime')
              ->addColumn('end_date', 'datetime')
              ->addColumn('location', 'string', ['limit' => 255])
              ->addColumn('price', 'decimal',['default'=>0,'precision'=>10,'scale'=>2])
              ->create();
              

    }
}
