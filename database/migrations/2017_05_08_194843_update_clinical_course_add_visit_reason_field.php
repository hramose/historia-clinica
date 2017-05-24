<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateClinicalCourseAddVisitReasonField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clinical_course', function (Blueprint $table) {
            $table->string('visit_reason')->nullable()->after('review_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clinical_course', function (Blueprint $table) {
            $table->dropColumn('visit_reason');
        });
    }
}
