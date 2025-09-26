<?php

namespace Database\Seeders;

use App\Models\Staff;
use Illuminate\Database\Seeder;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $phoneNumber = 8111111111;

        $staffs = [
            [
                "name" => "Manager",
                "phone" => "0" . $phoneNumber++,
                "position" => "manager",
                "image" => "image/profile.jpg",
                "active" => true,
            ],
            [
                "name" => "Cashier-01",
                "phone" => "0" . $phoneNumber++,
                "position" => "cashier",
                "image" => "image/profile.jpg",
                "active" => true,
            ],
            [
                "name" => "Cashier-02",
                "phone" => "0" . $phoneNumber++,
                "position" => "cashier",
                "image" => "image/profile.jpg",
                "active" => true,
            ],
            [
                "name" => "Chef-01",
                "phone" => "0" . $phoneNumber++,
                "position" => "chef",
                "image" => "image/profile.jpg",
                "active" => true,
            ],
            [
                "name" => "Chef-02",
                "phone" => "0" . $phoneNumber++,
                "position" => "chef",
                "image" => "image/profile.jpg",
                "active" => true,
            ],
            [
                "name" => "Chef-03",
                "phone" => "0" . $phoneNumber++,
                "position" => "chef",
                "image" => "image/profile.jpg",
                "active" => true,
            ],
            [
                "name" => "Chef-04",
                "phone" => "0" . $phoneNumber++,
                "position" => "chef",
                "image" => "image/profile.jpg",
                "active" => true,
            ],
            [
                "name" => "Chef-05",
                "phone" => "0" . $phoneNumber++,
                "position" => "chef",
                "image" => "image/profile.jpg",
                "active" => true,
            ],
            [
                "name" => "Chef-06",
                "phone" => "0" . $phoneNumber++,
                "position" => "chef",
                "image" => "image/profile.jpg",
                "active" => true,
            ],
            [
                "name" => "Waiter-01",
                "phone" => "0" . $phoneNumber++,
                "position" => "waiter",
                "image" => "image/profile.jpg",
                "active" => true,
            ],
            [
                "name" => "Waiter-02",
                "phone" => "0" . $phoneNumber++,
                "position" => "waiter",
                "image" => "image/profile.jpg",
                "active" => true,
            ],
        ];

        foreach ($staffs as $staff) {
            Staff::create($staff);
        }
    }
}
