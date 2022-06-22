<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('avatar')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('role_id')->unsigned();
            $table->timestamps();
        });
        DB::statement("ALTER TABLE users ADD COLUMN searchtext TSVECTOR");
        DB::statement("UPDATE users SET searchtext = to_tsvector('english', name || '' || email)");
        DB::statement("CREATE INDEX searchtext_users_gin ON users USING GIN(searchtext)");
        DB::statement("CREATE TRIGGER ts_searchtext BEFORE INSERT OR UPDATE ON users FOR EACH ROW EXECUTE PROCEDURE tsvector_update_trigger('searchtext', 'pg_catalog.english', 'name', 'email')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP TRIGGER IF EXISTS tsvector_update_trigger ON users");
        DB::statement("DROP INDEX IF EXISTS searchtext_users_gin");
        DB::statement("ALTER TABLE users DROP COLUMN searchtext");
        Schema::dropIfExists('users');
    }
}
