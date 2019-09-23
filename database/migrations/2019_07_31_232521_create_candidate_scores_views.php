<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCandidateScoresViews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        DB::statement('CREATE VIEW rt_candidate_scores AS  SELECT `olevel` , candidate_id ,  (SUM((CASE WHEN  grade = "A1" THEN 8 ELSE 0 END) +
            (CASE WHEN grade = "B2" THEN 7 ELSE 0 END) +
                    (CASE WHEN grade = "B3" THEN 6 ELSE 0 END) +
                    (CASE WHEN grade = "C4" THEN 5 ELSE 0 END) +
                    (CASE WHEN grade = "C5" THEN 4 ELSE 0 END) +
                    (CASE WHEN grade = "C6" THEN 3 ELSE 0 END) +
                    (CASE WHEN  grade  = "D7" THEN 2 ELSE 0 END) +
                    (CASE WHEN  grade  = "E8" THEN 1 ELSE 0 END) +
                    (CASE WHEN  grade  = "F9" THEN 0 ELSE 0 END) )/7)  as total
                    FROM rt_candidate_olevels
                    inner  join rt_candidate_olevel_results
                        on rt_candidate_olevels.uuid = rt_candidate_olevel_results.olevel  group by olevel ,  candidate_id  order by olevel ,  candidate_id');
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW candidate_scores");
    }
}
