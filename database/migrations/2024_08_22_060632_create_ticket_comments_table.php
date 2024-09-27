<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_comments', function (Blueprint $table) {
            $table->id(); // Creates an auto-incrementing primary key column named 'id'
            $table->foreignId('ticket_id')->constrained('tickets'); // Creates a foreign key column 'ticket_id' referencing 'tickets' table
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade'); // Creates a foreign key column 'user_id' referencing 'users' table
            $table->foreignId('admin_id')->nullable()->constrained('admins'); // 
            $table->text('comment'); // Creates a 'comment' column for long text
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
        Schema::dropIfExists('ticket_comments');
    }
}
