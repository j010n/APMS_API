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
			Schema::table('users', function (Blueprint $table) {
				$table->enum('sex', ['M', 'F', 'O'])->nullable()->after('email');
				$table->string('cpf')->unique()->nullable()->after('sex');
				$table->string('rg')->nullable()->after('cpf');
				$table->date('birthdate')->nullable()->after('rg');

				$table->string('phone')->nullable()->after('birthdate');
				$table->string('tel')->nullable()->after('phone');

				$table->string('country')->nullable()->after('tel');
				$table->string('state')->nullable()->after('country');
				$table->string('city')->nullable()->after('state');

				$table->boolean('affiliated')->default(false)->after('city');

				$table->softDeletes(); // cria deleted_at
			});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
			Schema::table('users', function (Blueprint $table) {
				 $table->dropColumn([
            'sex',
            'cpf',
            'rg',
            'birthdate',
            'phone',
            'tel',
            'country',
            'state',
            'city',
            'affiliated',
            'deleted_at',
        ]);
			});
    }
};
