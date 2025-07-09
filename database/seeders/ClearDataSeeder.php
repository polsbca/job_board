<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ClearDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    /**
     * Run the database seeds.
     * 
     * This seeder clears all data from the database in the correct order
     * to avoid foreign key constraint violations.
     */
    public function run(): void
    {
        // Disable foreign key checks
        if (config('database.default') !== 'sqlite') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        }

        $this->command->info('Clearing existing data...');
        
        // Clear data in reverse order of dependencies
        $this->clearTable('personal_access_tokens');
        $this->clearTable('applications');
        $this->clearTable('job_listings');
        $this->clearTable('users');
        
        // Re-enable foreign key checks
        if (config('database.default') !== 'sqlite') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
        
        $this->command->info('Database cleared successfully!');
    }
    
    /**
     * Clear all records from the specified table.
     *
     * @param string $tableName
     * @return void
     */
    private function clearTable(string $tableName): void
    {
        if (Schema::hasTable($tableName)) {
            $this->command->info("Clearing {$tableName}...");
            
            // Use truncate for better performance, but only if the table exists
            if (config('database.default') === 'sqlite') {
                // SQLite doesn't support TRUNCATE with foreign key constraints
                DB::table($tableName)->delete();
            } else {
                DB::table($tableName)->truncate();
            }
            
            // Reset auto-increment counter for SQLite
            if (config('database.default') === 'sqlite') {
                DB::statement('UPDATE sqlite_sequence SET seq = 0 WHERE name = ?', [$tableName]);
            }
        } else {
            $this->command->warn("Table {$tableName} does not exist.");
        }
    }
}
