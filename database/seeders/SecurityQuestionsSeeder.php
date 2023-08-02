<?php

namespace Database\Seeders;

use App\Models\Security\Question;
use Illuminate\Database\Seeder;

class SecurityQuestionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $questions = [
            "What is your mother's maiden name?",
            "In which city were you born?",
            "What is the name of your favorite childhood pet?",
            "What is the name of your favorite childhood friend?",
            "Which street did you grow up on?"
        ];

        foreach ($questions as $question) {
            Question::updateOrCreate([
                'question' => $question,
                'is_approved' => true,
                'is_edited' => false
            ]);
        }
    }
}
