<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStudentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField($this->fields());
        $this->forge->addKey('id', true);
        $this->forge->createTable('students');
    }

    public function down()
    {
        $this->forge->dropTable('students');
    }

    private function fields() 
    {
        return
        [
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
            ],
            'email' => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
            ],
            'photo' => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
            ],
            'phone' => [ 
                'type'           => 'VARCHAR',
                'constraint'     => '20',
            ],
            'password' => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'cep' => [
                'type'           => 'VARCHAR',
                'constraint'     => '10',
            ],
            'uf' => [
                'type'           => 'VARCHAR',
                'constraint'     => '2',
            ],
            'city' => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
            ],
            'number' => [
                'type'           => 'VARCHAR',
                'constraint'     => '10',
            ],
            'address' => [ 
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'created_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
            ],
            'updated_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
            ],
        ];
    }
}
