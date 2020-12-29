<?php

// /***MigrationDescription***/

namespace /***MigrationNamespace***/;

use VirX\Qaton\Migration;

class /***MigrationClassName***/ extends Migration
{
    public function up()
    {
        $this->db->table('/***MigrationTableName***/')->create([
            'name' => [
                'type' => 'string',
                'null' => false,
                'default' => 'Untitled'
            ],
            // ...
        ]);
    }

    public function down()
    {
        $this->db->table('/***MigrationTableName***/')->drop();
    }
}
