<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_history', function (Blueprint $table) {
            $table->id(); // Creates an auto-incrementing primary key column named 'id'
            $table->foreignId('ticket_id')->constrained('tickets'); // Creates a foreign key column 'ticket_id' referencing 'tickets' table
            $table->foreignId('changed_by')->constrained('users'); // Creates a foreign key column 'changed_by' referencing 'users' table
            $table->text('change_description'); // Creates a 'change_description' column for storing text
            $table->timestamps(); // Creates 'created_at' and 'updated_at' timestamp columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ticket_history');
    }
}
