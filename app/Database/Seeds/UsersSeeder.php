<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsersSeeder extends Seeder
{
   public function run()
{
    // Disable foreign key checks
    $this->db->query('SET FOREIGN_KEY_CHECKS=0');

    // Optional: Truncate the table
    $this->db->table('users')->truncate();

    $data = [
        [
            'name'     => 'John Michael Castillo',
            'email'    => 'jmykel1342@gmail.com',
            'password' => password_hash('password123', PASSWORD_DEFAULT),
            'role'     => 'admin',
        ],
        [
            'name'     => 'Zaidie Adlaon',
            'email'    => 'zai@gmail.com',
            'password' => password_hash('maria2025', PASSWORD_DEFAULT),
            'role'     => 'student',
        ],
    ];

    // Insert data
    $this->db->table('users')->insertBatch($data);

    // Re-enable foreign key checks
    $this->db->query('SET FOREIGN_KEY_CHECKS=1');
}
}