<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAuthenticationTables extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'username' => ['type' => 'VARCHAR', 'constraint' => 80],
            'email' => ['type' => 'VARCHAR', 'constraint' => 160],
            'full_name' => ['type' => 'VARCHAR', 'constraint' => 160],
            'password_hash' => ['type' => 'VARCHAR', 'constraint' => 255],
            'role' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'user'],
            'avatar_path' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'failed_login_attempts' => ['type' => 'SMALLINT', 'constraint' => 5, 'default' => 0],
            'locked_until' => ['type' => 'DATETIME', 'null' => true],
            'last_login_at' => ['type' => 'DATETIME', 'null' => true],
            'last_login_ip' => ['type' => 'VARCHAR', 'constraint' => 45, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('username');
        $this->forge->addUniqueKey('email');
        $this->forge->createTable('users', true);

        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'permission_key' => ['type' => 'VARCHAR', 'constraint' => 100],
            'label' => ['type' => 'VARCHAR', 'constraint' => 160],
            'group_name' => ['type' => 'VARCHAR', 'constraint' => 80],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('permission_key');
        $this->forge->createTable('permissions', true);

        $this->forge->addField([
            'user_id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
            'permission_id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
        ]);
        $this->forge->addKey(['user_id', 'permission_id'], true);
        $this->forge->createTable('user_permissions', true);

        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => true],
            'username' => ['type' => 'VARCHAR', 'constraint' => 80, 'null' => true],
            'event' => ['type' => 'VARCHAR', 'constraint' => 80],
            'ip_address' => ['type' => 'VARCHAR', 'constraint' => 45, 'null' => true],
            'user_agent' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            'details' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addKey('event');
        $this->forge->createTable('auth_audit_logs', true);

        $permissions = [
            ['dashboard.view', 'Lihat dashboard', 'Dashboard'],
            ['companies.view', 'Lihat perusahaan', 'Perusahaan'], ['companies.create', 'Tambah perusahaan', 'Perusahaan'], ['companies.edit', 'Edit perusahaan', 'Perusahaan'], ['companies.delete', 'Hapus perusahaan', 'Perusahaan'],
            ['products.view', 'Lihat produk', 'Produk'], ['products.create', 'Tambah produk', 'Produk'], ['products.edit', 'Edit produk', 'Produk'], ['products.delete', 'Hapus produk', 'Produk'],
            ['quotations.view', 'Lihat penawaran', 'Penawaran'], ['quotations.create', 'Tambah penawaran', 'Penawaran'], ['quotations.edit', 'Edit penawaran', 'Penawaran'], ['quotations.status', 'Ubah status penawaran', 'Penawaran'], ['quotations.delete', 'Hapus penawaran', 'Penawaran'], ['quotations.export', 'Ekspor penawaran', 'Penawaran'],
            ['settings.quotation', 'Kelola setting quotation', 'Konfigurasi'], ['users.manage', 'Kelola pengguna dan akses', 'Konfigurasi'],
        ];
        $now = date('Y-m-d H:i:s');
        foreach ($permissions as $permission) {
            $this->db->table('permissions')->insert(['permission_key' => $permission[0], 'label' => $permission[1], 'group_name' => $permission[2], 'created_at' => $now]);
        }
    }

    public function down()
    {
        $this->forge->dropTable('auth_audit_logs', true);
        $this->forge->dropTable('user_permissions', true);
        $this->forge->dropTable('permissions', true);
        $this->forge->dropTable('users', true);
    }
}
