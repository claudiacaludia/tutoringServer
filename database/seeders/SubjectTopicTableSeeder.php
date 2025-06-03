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
        $subject1->name = 'Web Development';
        $subject1->description = 'Basics and modern web technologies such as HTML, CSS, JavaScript.';
        $subject1->save();

        $topic1 = new Topic;
        $topic1->name = 'Vue';
        $topic1->description = 'Vue Basics and best practices.';

        $topic2 = new Topic;
        $topic2->name = 'React';
        $topic2->description = 'Everything one should know about react.';

        $subject1->topics()->saveMany([$topic1, $topic2]);

        $subject2 = new Subject;
        $subject2->name = 'Java Development';
        $subject2->description = 'Learn coding java';
        $subject2->save();
        $topic3 = new Topic;
        $topic3->name = 'Java Basics';
        $topic3->description = "All of the basics.";
        $subject2->topics()->save($topic3);

    }
}
