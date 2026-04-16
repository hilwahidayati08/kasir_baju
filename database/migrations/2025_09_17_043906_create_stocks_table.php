    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        /**
         * Run the migrations.
         */
        public function up(): void
        {
            Schema::create('stocks', function (Blueprint $table) {
                $table->increments('stock_id');
                $table->integer('stock');
                $table->unsignedInteger('variant_id')->nullable();
                $table->unsignedInteger('branch_id')->nullable();
                $table->foreign('branch_id')->references('branch_id')->on('branch')->onDelete('cascade');
                $table->foreign('variant_id')->references('variant_id')->on('variants')->onDelete('restrict');
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('stocks');
        }
    };
