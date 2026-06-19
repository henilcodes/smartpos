<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SessionSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->where('email', 'admin@smartpos.local')->first();
        $staff = User::query()->where('email', 'staff@smartpos.local')->first();

        if (! $admin && ! $staff) {
            return;
        }

        $sessions = [];

        if ($admin) {
            $sessions[] = [
                'id' => 'seed-session-admin-demo',
                'user_id' => $admin->id,
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Demo) SmartPOS Admin Session',
                'payload' => base64_encode(serialize([])),
                'last_activity' => now()->subMinutes(10)->timestamp,
            ];
        }

        if ($staff) {
            $sessions[] = [
                'id' => 'seed-session-staff-demo',
                'user_id' => $staff->id,
                'ip_address' => '192.168.1.42',
                'user_agent' => 'Mozilla/5.0 (Demo) SmartPOS Staff Session',
                'payload' => base64_encode(serialize([])),
                'last_activity' => now()->subHours(2)->timestamp,
            ];

            $sessions[] = [
                'id' => 'seed-session-staff-expired',
                'user_id' => $staff->id,
                'ip_address' => '192.168.1.50',
                'user_agent' => 'Mozilla/5.0 (Demo) SmartPOS Mobile Session',
                'payload' => base64_encode(serialize([])),
                'last_activity' => now()->subDays(3)->timestamp,
            ];
        }

        foreach ($sessions as $session) {
            DB::table('sessions')->updateOrInsert(
                ['id' => $session['id']],
                $session,
            );
        }
    }
}
