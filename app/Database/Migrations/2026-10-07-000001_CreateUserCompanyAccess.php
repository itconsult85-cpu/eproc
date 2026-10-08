<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserCompanyAccess extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'user_id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
            'company_id' => ['type' => 'INT', 'unsigned' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey(['user_id', 'company_id'], true);
        $this->forge->addKey('company_id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('company_id', 'companies', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_company_access', true);

        $this->db->query(
            "INSERT IGNORE INTO user_company_access (user_id, company_id, created_at)
             SELECT u.id, c.id, ?
             FROM users u CROSS JOIN companies c
             WHERE u.role IN ('superadmin', 'admin')",
            [date('Y-m-d H:i:s')]
        );
    }

    public function down()
    {
        $this->forge->dropTable('user_company_access', true);
    }
}
