<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Topic;
use App\Models\User;
use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppointmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tutor = User::where('role', 'tutor')->first();
        $topic = Topic::first();

        $appointment = new Appointment();
        $appointment->description = 'Die Nachhilfe findet online statt.';
        $appointment->price = 45.00;
        $appointment->tutor_id = $tutor->id;
        $appointment->topic_id = $topic->id;
        $appointment->proposed_time = new DateTime('2025-06-11 7:00:00');
        $appointment->save();

        $appointment = new Appointment();
        $appointment->description = 'Der perfekte Kurs für alle: Von Anfänger*in bis Expert*in';
        $appointment->price = 45.00;
        $appointment->tutor_id = $tutor->id;
        $appointment->topic_id = $topic->id;
        $appointment->proposed_time =  new DateTime('2025-06-11 16:00:00');
        $appointment->save();


        $student = User::where('role', 'student')->first();

        $appointment2 = new Appointment();
        $appointment2->description = 'Nachhilfespaß in der FH Hagenberg';
        $appointment2->price = 80.00;
        $appointment2->status = 'confirmed';
        $appointment2->tutor_id = $tutor->id;
        $appointment2->topic_id = $topic->id;
        $appointment2->student_id = $student->id;
        $appointment2->proposed_time = new DateTime('2025-06-11 10:00:00');
        $appointment2->save();

        $tutor2 = User::where('role', 'tutor')->orderBy('id', 'desc')->first();
        $student2 = User::where('role', 'student')->orderBy('id', 'desc')->first();
        $topic2 = Topic::orderBy('id', 'desc')->first();

        $appointment3 = new Appointment();
        $appointment3->description = 'Nachhilfespaß in der FH Hagenberg';
        $appointment3->price = 80.00;
        $appointment3->status = 'proposed_by_student';
        $appointment3->tutor_id = $tutor2->id;
        $appointment3->topic_id = $topic2->id;
        $appointment3->student_id = $student->id;
        $appointment3->proposed_time = new DateTime('2025-06-16 8:00:00');
        $appointment3->save();

        $appointment4 = new Appointment();
        $appointment4->description = 'Nachhilfespaß in der FH Hagenberg';
        $appointment4->price = 80.00;
        $appointment4->status = 'confirmed';
        $appointment4->tutor_id = $tutor2->id;
        $appointment4->topic_id = $topic2->id;
        $appointment4->student_id = $student2->id;
        $appointment4->proposed_time = new DateTime('2025-06-16 11:30:00');
        $appointment4->save();

        $appointment4 = new Appointment();
        $appointment4->description = 'Ich lern dir alles von 0 weg!';
        $appointment4->price = 80.00;
        $appointment4->status = 'open';
        $appointment4->tutor_id = $tutor2->id;
        $appointment4->topic_id = $topic2->id;
        $appointment4->proposed_time = new DateTime('2025-06-20 13:00:00');
        $appointment4->save();
    }
}
