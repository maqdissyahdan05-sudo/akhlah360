<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\User;
use App\Models\Department;
use App\Models\Employee;
use App\Models\AssessmentPeriod;
use App\Models\Assignment;
use App\Models\Assessment;
use App\Models\AssessmentResult;
use App\Models\AssessmentIndicator;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles
        $roles = [
            ['role_name' => 'HR Administrator', 'role_slug' => 'admin_hr'],
            ['role_name' => 'Management', 'role_slug' => 'manajemen'],
            ['role_name' => 'Superior', 'role_slug' => 'atasan'],
            ['role_name' => 'Employee', 'role_slug' => 'karyawan'],
        ];
        foreach ($roles as $role) {
            Role::firstOrCreate(['role_slug' => $role['role_slug']], $role);
        }

        // 2. Core Values
        $this->call([AkhlaqValuesSeeder::class]);
        $indicators = AssessmentIndicator::all();

        // 3. Departments
        $depts = ['Human Resources', 'Information Technology', 'Operations', 'Finance', 'Marketing'];
        $createdDepts = [];
        foreach ($depts as $dept) {
            $createdDepts[] = Department::firstOrCreate(['department_name' => $dept])->department_id;
        }

        // 4. Employees & Users (Dummy Data Generator)
        $defaultPassword = Hash::make('password123');
        $employees = [];
        $users = [];
        
        // Setup base accounts
        $adminRole = Role::where('role_slug', 'admin_hr')->first()->role_id;
        $manajemenRole = Role::where('role_slug', 'manajemen')->first()->role_id;
        $atasanRole = Role::where('role_slug', 'atasan')->first()->role_id;
        $karyawanRole = Role::where('role_slug', 'karyawan')->first()->role_id;

        // Key Names for Roles
        $names = [
            'Leona Debora Peter', 'Maqdis Syahdan', 'Cindy Joselyn Lim', 'Arsha Satria Widyawan', 'Rina Marlina',
            'Agus Pratama', 'Ahmad Hidayat', 'Putri Ramadhani', 'Reza Pahlevi', 'Nadia Safitri',
            'Eko Prasetyo', 'Maya Indah', 'Hendra Gunawan', 'Fitriani', 'Bambang Pamungkas',
            'Ayu Ningtyas', 'Rizky Firmansyah', 'Dian Sastro', 'Surya Saputra', 'Sri Wahyuni'
        ];

        // Create 20 Dummy Employees
        for ($i = 0; $i < 20; $i++) {
            $isManager = ($i + 1) % 5 === 0; // Every 5th person is a manager
            
            $supervisorId = null;
            if ($i === 3) { // Arsha
                $supervisorId = 2; // Maqdis is ID 2 (index 1)
            } elseif (!$isManager && $i > 4) {
                $supervisorId = Employee::where('employee_id', '<', $i + 1)->inRandomOrder()->first()?->employee_id;
            }

            $emp = Employee::create([
                'employee_number' => 'EMP-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'employee_name' => $names[$i],
                'department_id' => $createdDepts[$i % 5], // Distribute departments evenly
                'supervisor_id' => $supervisorId,
            ]);
            $employees[] = $emp;

            // Generate user account for them
            if ($i === 0) { // Force first to be Admin HR
                User::create(['username' => 'adminhr', 'role_id' => $adminRole, 'employee_id' => $emp->employee_id, 'email' => 'admin@akhlak.local', 'password' => $defaultPassword]);
            } elseif ($i === 1) { // Force second to be Manajemen (Maqdis)
                User::create(['username' => 'maqdis', 'role_id' => $manajemenRole, 'employee_id' => $emp->employee_id, 'email' => 'maqdis@akhlak.local', 'password' => $defaultPassword]);
            } elseif ($i === 2) { // Cindy (Superior)
                User::create(['username' => 'cindy', 'role_id' => $atasanRole, 'employee_id' => $emp->employee_id, 'email' => 'cindy@akhlak.local', 'password' => $defaultPassword]);
            } elseif ($i === 3) { // Arsha (Karyawan)
                User::create(['username' => 'arsha', 'role_id' => $karyawanRole, 'employee_id' => $emp->employee_id, 'email' => 'arsha@akhlak.local', 'password' => $defaultPassword]);
            } elseif ($isManager) {
                $username = 'manager' . ($i + 1);
                User::create(['username' => $username, 'role_id' => $atasanRole, 'employee_id' => $emp->employee_id, 'email' => $username . '@akhlak.local', 'password' => $defaultPassword]);
            } else {
                $username = 'staff' . ($i + 1);
                User::create(['username' => $username, 'role_id' => $karyawanRole, 'employee_id' => $emp->employee_id, 'email' => $username . '@akhlak.local', 'password' => $defaultPassword]);
            }
        }

        // 5. Assessment Period (Closed & Active)
        $closedPeriod = AssessmentPeriod::create([
            'period_name' => 'Assessment Q1 2026',
            'start_date' => Carbon::now()->subMonths(3)->startOfMonth(),
            'end_date' => Carbon::now()->subMonths(3)->endOfMonth(),
            'status' => 'closed'
        ]);

        $activePeriod = AssessmentPeriod::create([
            'period_name' => 'Assessment Q2 2026',
            'start_date' => Carbon::now()->startOfMonth(),
            'end_date' => Carbon::now()->endOfMonth(),
            'status' => 'active'
        ]);

        // 6. Generate Dummy Assessments for Closed Period (All Completed)
        foreach ($employees as $ratee) {
            $raters = collect($employees)->random(3); // Pick 3 random raters for everyone
            foreach ($raters as $rater) {
                $relType = $rater->employee_id === $ratee->employee_id ? 'self' : 'peer';
                $assignment = Assignment::create([
                    'period_id' => $closedPeriod->period_id,
                    'rater_id' => $rater->employee_id,
                    'ratee_id' => $ratee->employee_id,
                    'relationship_type' => $relType,
                    'is_completed' => true,
                    'completed_at' => Carbon::now()->subMonths(2),
                ]);

                $totalScore = 0;
                foreach ($indicators as $ind) {
                    $score = rand(3, 5); // Random score between 3 and 5
                    Assessment::create(['assignment_id' => $assignment->assignment_id, 'indicator_id' => $ind->indicator_id, 'score' => $score]);
                    $totalScore += $score;
                }
            }
            
            // Generate dummy result
            AssessmentResult::create([
                'period_id' => $closedPeriod->period_id,
                'employee_id' => $ratee->employee_id,
                'self_score' => rand(35, 50) / 10,
                'peer_score' => rand(35, 50) / 10,
                'superior_score' => rand(35, 50) / 10,
                'subordinate_score' => null,
                'final_score' => rand(38, 48) / 10,
            ]);
        }

        // 7. Generate Pending Assignments for Active Period
        foreach ($employees as $ratee) {
            // 7.1 Self Assessment
            Assignment::create([
                'period_id' => $activePeriod->period_id,
                'rater_id' => $ratee->employee_id,
                'ratee_id' => $ratee->employee_id,
                'relationship_type' => 'self',
                'is_completed' => false,
            ]);
            
            // 7.2 Superior Assessment (If ratee has a supervisor, the supervisor rates them)
            if ($ratee->supervisor_id) {
                Assignment::create([
                    'period_id' => $activePeriod->period_id,
                    'rater_id' => $ratee->supervisor_id,
                    'ratee_id' => $ratee->employee_id,
                    'relationship_type' => 'superior',
                    'is_completed' => false,
                ]);
            }

            // 7.3 Subordinate Assessment (If ratee manages others, subordinates rate the ratee)
            $subordinates = collect($employees)->where('supervisor_id', $ratee->employee_id);
            foreach ($subordinates as $sub) {
                Assignment::create([
                    'period_id' => $activePeriod->period_id,
                    'rater_id' => $sub->employee_id,
                    'ratee_id' => $ratee->employee_id,
                    'relationship_type' => 'subordinate',
                    'is_completed' => false,
                ]);
            }

            // 7.4 Peer Assessment (Assign up to 2 peers from the same department)
            $peers = collect($employees)
                ->where('department_id', $ratee->department_id)
                ->where('employee_id', '!=', $ratee->employee_id)
                ->take(2);
                
            foreach ($peers as $peer) {
                Assignment::create([
                    'period_id' => $activePeriod->period_id,
                    'rater_id' => $peer->employee_id,
                    'ratee_id' => $ratee->employee_id,
                    'relationship_type' => 'peer',
                    'is_completed' => false,
                ]);
            }
        }
    }
}
