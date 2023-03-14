<?php

// /***MigrationDescription***/

namespace /***MigrationNamespace***/;

use VirX\Qaton\Migration;

class /***MigrationClassName***/ extends Migration
{
    public function up()
    {
        $this->db->table('????????????????????')->alter([
            'name' => [
                'type' => 'string',
                'null' => false,
                'default' => 'Untitled'
            ],
            // ...
        ], ['timestamps' => true]);
    }

    public function down()
    {
        exit('Reverting altered migrations not supported yet');
    }
}
