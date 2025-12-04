public function up(): void
{
    Schema::table('bookings', function (Blueprint $table) {
        $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->onDelete('set null');
    });
}

public function down(): void
{
    Schema::table('bookings', function (Blueprint $table) {
        $table->dropForeignKeyConstraints();
        $table->dropColumn('user_id');
    });
}
