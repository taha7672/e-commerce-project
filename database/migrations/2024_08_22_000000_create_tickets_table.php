<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id(); 
            $table->string('subject', 255); // Creates a 'subject' column with a maximum length of 255 characters
            $table->text('description'); // Creates a 'description' column for long text
            $table->enum('status', ['sent', 'in_progress', 'answered', 'resolved']); // Creates a 'status' column with specified enum values
            $table->integer('priority'); // Creates a 'priority' column
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            // Creates a foreign key column 'user_id' referencing 'users' table
            $table->foreignId('assigned_to')->nullable()->constrained('admins')->onDelete('set null'); // Creates a foreign key column 'assigned_to' referencing 'users' table, nullable

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
        Schema::dropIfExists('tickets');
    }
}
