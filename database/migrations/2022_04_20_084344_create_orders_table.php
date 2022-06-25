<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone_number');
            $table->string('customer_address');
            $table->date('date_depart');
            $table->tinyInteger('quantity_people')->default(0);
            $table->tinyInteger('quantity_children')->default(0);
            $table->tinyInteger('quantity_baby')->default(0);
            $table->string('discount_code')->nullable();
            $table->string('discount_percent')->nullable();
            $table->double('total', 10, 2);
            $table->double('discount', 10, 2);
            $table->double('total_amount', 10, 2);
            $table->text('note')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->boolean('is_paid')->default(false);
            $table->string('reason_cancel')->nullable();
            $table->integer('payment_id')->unsigned()->nullable();
            $table->integer('tour_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('tour_id')->references('id')->on('tours')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
        DB::statement("ALTER TABLE orders ADD COLUMN searchtext TSVECTOR");
        DB::statement("UPDATE orders SET searchtext = to_tsvector('english', code)");
        DB::statement("CREATE INDEX searchtext_orders_gin ON orders USING GIN(searchtext)");
        DB::statement("CREATE TRIGGER ts_searchtext BEFORE INSERT OR UPDATE ON orders FOR EACH ROW EXECUTE PROCEDURE tsvector_update_trigger('searchtext', 'pg_catalog.english', 'code')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP TRIGGER IF EXISTS tsvector_update_trigger ON orders");
        DB::statement("DROP INDEX IF EXISTS searchtext_orders_gin");
        DB::statement("ALTER TABLE orders DROP COLUMN searchtext");
        Schema::dropIfExists('orders');
    }
}
