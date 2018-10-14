<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEnableCcColToInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('innov_invoices')) {
            Schema::table('innov_invoices', function(Blueprint $table){
                $table->boolean('enable_cc')->after('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('innov_invoices')) {
            Schema::table('innov_invoices', function (Blueprint $table) {
                $table->dropColumn('enable_cc');
            });
        }
    }
}
