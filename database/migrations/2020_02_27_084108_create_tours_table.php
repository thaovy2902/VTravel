<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateToursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tours', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->string('name');
            $table->string('slug');
            $table->string('image');
            $table->json('gallery')->nullable();
            $table->string('depart');
            $table->text('description');
            $table->string('from_place_name');
            $table->json('to_place_code');
            $table->string('to_place_name');
            $table->string('transport');
            $table->tinyInteger('number_days');
            $table->tinyInteger('number_persons');
            $table->double('price_default', 10, 2);
            $table->double('price_children', 10, 2);
            $table->double('price_baby', 10, 2);
            $table->text('note')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('category_id')->unsigned();
            $table->timestamps();
        });

        // DB::statement("ALTER TABLE tours ADD COLUMN searchtext TSVECTOR");
        // DB::statement("UPDATE tours SET searchtext = to_tsvector('english', code || '' || name || '' || slug)");
        // DB::statement("CREATE INDEX searchtext_tours_gin ON tours USING GIN(searchtext)");
        // DB::statement("CREATE TRIGGER ts_searchtext BEFORE INSERT OR UPDATE ON tours FOR EACH ROW EXECUTE PROCEDURE tsvector_update_trigger('searchtext', 'pg_catalog.english', 'code', 'name', 'slug')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // DB::statement("DROP TRIGGER IF EXISTS tsvector_update_trigger ON tours");
        // DB::statement("DROP INDEX IF EXISTS searchtext_tours_gin");
        // DB::statement("ALTER TABLE tours DROP COLUMN searchtext");
        Schema::dropIfExists('tours');
    }
}
