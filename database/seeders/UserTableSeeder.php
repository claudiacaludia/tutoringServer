<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new User;
        $user->name = "Melina Murr";
        $user->email = "murr@gmail.com";
        $user->password = bcrypt('secret');
        $user->role = 'tutor';
        $user->education = 'Master of Science in Hagenberg - Software Engineering';
        $user->contact_info="Telefonnummer: +43 660 8097876";
        $user->save();

        $user2 = new User;
        $user2->name = "Simon Stelzer";
        $user2->email = "simon@gmail.com";
        $user2->password = bcrypt('secret');
        $user2->role = 'tutor';
        $user2->education = 'HTL Leonding, 10 Jahre Erfahrung';
        $user2->contact_info="Telefonnummer: +43 660 8011890";
        $user2->save();

        $user3 = new User;
        $user3->name = "Lisa Lustig";
        $user3->email = "lustig@gmail.com";
        $user3->password = bcrypt('secret');
        $user3->role = 'student';
        $user3->education = 'KWM Studentin';
        $user3->contact_info="Telefonnummer: +43 680 8881890";
        $user3->save();

        $user4 = new User;
        $user4->name = "Mario Müde";
        $user4->email = "muede@gmail.com";
        $user4->password = bcrypt('secret');
        $user4->role = 'student';
        $user4->education = 'Student';
        $user4->contact_info="Email: martin@gmail.com";
        $user4->save();
    }
}
