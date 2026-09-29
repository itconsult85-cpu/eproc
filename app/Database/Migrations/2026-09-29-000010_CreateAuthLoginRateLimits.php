<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAuthLoginRateLimits extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'ip_address' => ['type' => 'VARCHAR', 'constraint' => 45],
            'failed_attempts' => ['type' => 'SMALLINT', 'unsigned' => true, 'default' => 0],
            'locked_until' => ['type' => 'DATETIME', 'null' => true],
            'last_attempt_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('ip_address', true);
        $this->forge->addKey('locked_until');
        $this->forge->createTable('auth_login_rate_limits', true);
    }

    public function down()
    {
        $this->forge->dropTable('auth_login_rate_limits', true);
    }
}
