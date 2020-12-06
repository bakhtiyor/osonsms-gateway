// 'database/migrations/create_posts_table.php.stub'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOsonsmsLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('osonsms_log', function (Blueprint $table) {
            $table->id();
            $table->string('login', 255);
            $table->string('sender_name', 11);
            $table->string('message', 2500);
            $table->string('phonenumber', 13);
            $table->string('server_response', 2500)->nullable();
            $table->string('msgid', 20)->nullable();
            $table->tinyInteger('is_sent')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('osonsms_log');
    }
}
