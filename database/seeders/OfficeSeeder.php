<?php

namespace Database\Seeders;

use App\Models\Office;
use Illuminate\Database\Seeder;

class OfficeSeeder extends Seeder
{
    public function run(): void
    {
        $offices = [
            [
                'name' => 'Office of the SGOD Chief',
                'group' => 'SGOD',
                'allocation' => 13500.00,
                'prepared_by' => 'LORENZO O. CAPACIO, EdD',
                'prepared_by_designation' => 'Chief Education Supervisor',
                'reviewed_by' => 'LORENZO O. CAPACIO, EdD',
                'reviewed_by_designation' => 'Chief Education Supervisor',
                'approved_by' => 'LORENZO O. CAPACIO, EdD',
                'approved_by_designation' => 'Chief Education Supervisor, SGOD',
            ],
            [
                'name' => 'School Management, Monitoring and Evaluation',
                'group' => 'SGOD',
                'allocation' => 15000.00,
                'prepared_by' => 'MARY GLADYS J. DUBLAS',
                'prepared_by_designation' => 'Education Program Specialist II',
                'reviewed_by' => 'EDELINA M. EBORA',
                'reviewed_by_designation' => 'Senior Education Program Specialist',
                'approved_by' => 'LORENZO O. CAPACIO, EdD',
                'approved_by_designation' => 'Chief Education Supervisor, SGOD',
            ],
            [
                'name' => 'Social Mobilization and Networking Section',
                'group' => 'SGOD',
                'allocation' => 13500.00,
                'prepared_by' => 'RIO G. ARBUTANTE',
                'prepared_by_designation' => 'Education Program Specialist II',
                'reviewed_by' => 'MARSFIFTH M. MAMAWAG',
                'reviewed_by_designation' => 'Senior Education Program Specialist',
                'approved_by' => 'LORENZO O. CAPACIO, EdD',
                'approved_by_designation' => 'Chief Education Supervisor, SGOD',
            ],
            [
                'name' => 'Human Resource Development Section',
                'group' => 'SGOD',
                'allocation' => 13500.00,
                'prepared_by' => 'REX DACANAY',
                'prepared_by_designation' => 'Education Program Specialist II',
                'reviewed_by' => 'WOODROW WILSON B. MERIDA',
                'reviewed_by_designation' => 'Senior Education Program Specialist',
                'approved_by' => 'LORENZO O. CAPACIO, EdD',
                'approved_by_designation' => 'Chief Education Supervisor, SGOD',
            ],
            [
                'name' => 'Planning and Research Section',
                'group' => 'SGOD',
                'allocation' => 13500.00,
                'prepared_by' => 'NOVEM A. SESCON',
                'prepared_by_designation' => 'Education Program Specialist II',
                'reviewed_by' => 'RIA K. ALCUIZAR',
                'reviewed_by_designation' => 'Senior Education Program Specialist',
                'approved_by' => 'LORENZO O. CAPACIO, EdD',
                'approved_by_designation' => 'Chief Education Supervisor, SGOD',
            ],
            [
                'name' => 'Education Facilities Section',
                'group' => 'SGOD',
                'allocation' => 13500.00,
                'prepared_by' => 'ENGR. LESLIE T. FONTANILLA',
                'prepared_by_designation' => 'Engineer III',
                'reviewed_by' => 'ROSALIO P. ARANGCO',
                'reviewed_by_designation' => 'Education Program Supervisor',
                'approved_by' => 'LORENZO O. CAPACIO, EdD',
                'approved_by_designation' => 'Chief Education Supervisor, SGOD',
            ],
            [
                'name' => 'School Health and Nutrition Section',
                'group' => 'SGOD',
                'allocation' => 20250.00,
                'prepared_by' => 'KEZIAH FATIMA M. UN, RN',
                'prepared_by_designation' => 'Nurse II',
                'reviewed_by' => 'DR. PAUL REGIE MABELIN',
                'reviewed_by_designation' => 'Medical Officer IV',
                'approved_by' => 'LORENZO O. CAPACIO, EdD',
                'approved_by_designation' => 'Chief Education Supervisor, SGOD',
            ],
            [
                'name' => 'Disaster Risk Reduction and Management',
                'group' => 'SGOD',
                'allocation' => 13500.00,
                'prepared_by' => 'JIMDANDY S. LUCINE',
                'prepared_by_designation' => 'Project Development Officer II',
                'reviewed_by' => 'ROSALIO P. ARANGCO',
                'reviewed_by_designation' => 'Education Program Supervisor',
                'approved_by' => 'LORENZO O. CAPACIO, EdD',
                'approved_by_designation' => 'Chief Education Supervisor, SGOD',
            ],
            [
                'name' => 'Youth Formation',
                'group' => 'SGOD',
                'allocation' => 13500.00,
                'prepared_by' => 'KARL LOIS PAGARAN',
                'prepared_by_designation' => 'Project Development Officer I',
                'reviewed_by' => 'ROSALIO P. ARANGCO',
                'reviewed_by_designation' => 'Education Program Supervisor',
                'approved_by' => 'LORENZO O. CAPACIO, EdD',
                'approved_by_designation' => 'Chief Education Supervisor, SGOD',
            ],
            [
                'name' => 'SGOD - General Services',
                'group' => 'SGOD',
                'allocation' => 14250.00,
                'prepared_by' => 'JIMDANDY S. LUCINE',
                'prepared_by_designation' => 'Project Development Officer II',
                'reviewed_by' => 'ROSALIO P. ARANGCO',
                'reviewed_by_designation' => 'Education Program Supervisor',
                'approved_by' => 'LORENZO O. CAPACIO, EdD',
                'approved_by_designation' => 'Chief Education Supervisor',
            ],
            [
                'name' => 'Office of the SGOD EPS',
                'group' => 'SGOD',
                'allocation' => 13500.00,
                'prepared_by' => 'ROSALIO P. ARANGCO',
                'prepared_by_designation' => 'Education Program Supervisor',
                'reviewed_by' => 'ROSALIO P. ARANGCO',
                'reviewed_by_designation' => 'Education Program Supervisor',
                'approved_by' => 'LORENZO O. CAPACIO, EdD',
                'approved_by_designation' => 'Chief Education Supervisor',
            ],
        ];

        foreach ($offices as $office) {
            Office::firstOrCreate(['name' => $office['name']], $office);
        }
    }
}
