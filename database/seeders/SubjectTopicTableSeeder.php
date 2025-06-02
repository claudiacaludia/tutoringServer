<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectTopicTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subject1 = new Subject;
        $subject1->name = 'Webentwicklung';
        $subject1->description = 'Grundlagen und moderne Webtechnologien wie HTML, CSS, JavaScript.';
        $subject1->save();

        $topic1 = new Topic;
        $topic1->name = 'Vue';
        $topic1->description = 'Grundlagen von dem Framework Vue.js';

        $topic2 = new Topic;
        $topic2->name = 'React';
        $topic2->description = 'Grundlagen von dem Framework React.js';

        $subject1->topics()->saveMany([$topic1, $topic2]);

        $subject2 = new Subject;
        $subject2->name = 'Datenbanken';
        $subject2->description = 'Relationale Datenbanken, SQL und Datenmodellierung.';
        $subject2->save();
        $topic3 = new Topic;
        $topic3->name = 'SQL Basics';
        $topic3->description = "Tabellen erstellen und löschen";
        $subject2->topics()->save($topic3);

    }
}
