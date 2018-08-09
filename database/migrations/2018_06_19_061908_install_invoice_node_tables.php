<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InstallInvoiceNodeTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('innov_invoices', function(Blueprint $table){
            $table->increments('id');
            $table->string('ref', 30);
            $table->string('client_name', 100);
            $table->string('business_name', 150)->nullable();
            $table->string('business_number', 50)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('address', 255);
            $table->boolean('is_taxable')->default(false);
            $table->double('tax_rate')->default(0.0);
            $table->string('template', 150)->default('default');
            $table->dateTime('issued_at')->nullable();
            $table->string('status', 50)->default('new');

            $table->softDeletes();
            $table->timestamps();

        });

        Schema::create('innov_bill_entries', function(Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('invoice_id');
            $table->integer('position');
            $table->string('description', 255);
            $table->double('charge')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('invoice_id')->references('id')->on('innov_invoices');
        });

        Schema::create('innov_payments', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('invoice_id');
            $table->string('internal_status', 50);
            $table->text('raw_data');
            $table->string('gateway', 25);

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('invoice_id')->references('id')->on('innov_invoices');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('innov_invoices');
        Schema::dropIfExists('innov_bill_entries');
    }
}
