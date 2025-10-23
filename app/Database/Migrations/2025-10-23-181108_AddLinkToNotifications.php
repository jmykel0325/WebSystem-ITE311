<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLinkToNotifications extends Migration
{
    public function up()
    {
        $this->forge->addColumn('notifications', [
            'link' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'message',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('notifications', 'link');
    }
}
