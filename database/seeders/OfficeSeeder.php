<?php

namespace Database\Seeders;

use App\Models\Office;
use Illuminate\Database\Seeder;

class OfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Office::create([
            'name' => 'Administration',
            'code' => 'acc-adm',
            'campus_id' => '1',
        ]); //1
        Office::create([
            'name' => 'Budget Office',
            'code' => 'acc-budg',
            'campus_id' => '1',
            'admin_user_id' => '49',
            'head_id' => '47',
        ]); //2
        Office::create([
            'name' => 'Accounting Office',
            'code' => 'acc-acto',
            'campus_id' => '1',
            'admin_user_id' => '19',
            'head_id' => '60',
        ]); //3
        Office::create([
            'name' => 'Information and Communications Tech',
            'code' => 'acc-ict',
            'campus_id' => '1',
        ]); //4
        Office::create([
            'name' => 'Internal Control Unit',
            'code' => 'acc-icu',
            'campus_id' => '1',
            'admin_user_id' => '45',
            'head_id' => '44',
        ]); //5
        Office::create([
            'name' => 'Academic Affairs',
            'code' => 'acc-acaf',
            'admin_user_id' => '1',
            'campus_id' => '1',
        ]); //6
        Office::create([
            'name' => 'Research Development and Extension Services',
            'code' => 'acc-rdes',
            'admin_user_id' => '2',
            'campus_id' => '1',
        ]); //7
        Office::create([
            'name' => 'Finance, Administration and Resource Generation',
            'code' => 'acc-rdes',
            'admin_user_id' => '50',
            'campus_id' => '1',
        ]); //8

        Office::create([
            'name' => 'Student Admission and Record Office',
            'code' => 'acc-saro',
            'admin_user_id' => '3',
            'campus_id' => '1',
        ]); //9

        Office::create([
            'name' => 'Instruction',
            'code' => 'acc-ins',
            'admin_user_id' => '4',
            'campus_id' => '1',
        ]); //10

        Office::create([
            'name' => 'Research Development',
            'code' => 'acc-rd',
            'admin_user_id' => '5',
            'campus_id' => '1',
        ]); //11

        Office::create([
            'name' => 'Extension Services',
            'code' => 'acc-es',
            'admin_user_id' => '6',
            'campus_id' => '1',
        ]); //12

        Office::create([
            'name' => 'Resource Generation',
            'code' => 'acc-es',
            'admin_user_id' => '7',
            'campus_id' => '1',
        ]); //13

        Office::create([
            'name' => 'Planning and Development',
            'code' => 'acc-pad',
            'admin_user_id' => '8',
            'campus_id' => '1',
        ]); //14

        Office::create([
            'name' => 'Human Resource Management and Development',
            'code' => 'acc-hrms',
            'admin_user_id' => '9',
            'campus_id' => '1',
        ]); //15

        Office::create([
            'name' => 'Quality Management System and Assurance',
            'code' => 'acc-qmsa',
            'admin_user_id' => '10',
            'campus_id' => '1',
        ]); //16

        Office::create([
            'name' => 'Public Relations and Information Office',
            'code' => 'acc-prio',
            'admin_user_id' => '11',
            'campus_id' => '1',
        ]); //17

        Office::create([
            'name' => 'GAD',
            'code' => 'acc-gad',
            'admin_user_id' => '12',
            'campus_id' => '1',
        ]); //18

        Office::create([
            'name' => 'Security Services',
            'code' => 'acc-ss',
            'admin_user_id' => '13',
            'campus_id' => '1',
        ]); //19

        Office::create([
            'name' => 'uICT Office',
            'code' => 'acc-uict',
            'admin_user_id' => '14',
            'head_id' => '370',
            'campus_id' => '1',
        ]); //20

        Office::create([
            'name' => 'MIS Office',
            'code' => 'acc-mis',
            'admin_user_id' => '15',
            'campus_id' => '1',
        ]); //21

        Office::create([
            'name' => 'Library Services and Museum',
            'code' => 'acc-lsm',
            'admin_user_id' => '16',
            'campus_id' => '1',
        ]); //22

        Office::create([
            'name' => 'Socio-cultural Affairs',
            'code' => 'acc-sca',
            'admin_user_id' => '17',
            'campus_id' => '1',
        ]); //23

        Office::create([
            'name' => 'Sports & Amusement Center',
            'code' => 'acc-sac',
            'admin_user_id' => '18',
            'campus_id' => '1',
        ]); //24

        Office::create([
            'name' => 'Finance Services',
            'code' => 'acc-fin',
            'admin_user_id' => '19',
            'campus_id' => '1',
        ]); //25

        Office::create([
            'name' => 'NSTP',
            'code' => 'acc-nstp',
            'campus_id' => '1',
        ]); //26

        Office::create([
            'name' => 'Guidance & Testing Center',
            'code' => 'acc-gtc',
            'admin_user_id' => '21',
            'campus_id' => '1',
        ]); //27

        Office::create([
            'name' => 'Alumni Relations',
            'code' => 'acc-ar',
            'admin_user_id' => '22',
            'campus_id' => '1',
        ]); //28

        Office::create([
            'name' => 'Instructional Materials Development Center',
            'code' => 'acc-imdc',
            'admin_user_id' => '23',
            'campus_id' => '1',
        ]); //29

        Office::create([
            'name' => 'Student Affairs & Services',
            'code' => 'acc-safs',
            'admin_user_id' => '24',
            'campus_id' => '1',
        ]); //30

        Office::create([
            'name' => 'Health Services',
            'code' => 'acc-hs',
            'admin_user_id' => '25',
            'campus_id' => '1',
        ]); //31

        Office::create([
            'name' => 'General Services and Motorpool',
            'code' => 'acc-gsm',
            'admin_user_id' => '26',
            'campus_id' => '1',
        ]); //32

        Office::create([
            'name' => 'Climate Change & DRRMC',
            'code' => 'acc-ccdrrmc',
            'admin_user_id' => '28',
            'campus_id' => '1',
        ]); //33

        Office::create([
            'name' => 'DRRMC',
            'code' => 'acc-drrmc',
            'admin_user_id' => '29',
            'campus_id' => '1',
        ]); //34

        Office::create([
            'name' => 'Board Review and Coaching',
            'code' => 'acc-brc',
            'admin_user_id' => '30',
            'campus_id' => '1',
        ]); //35

        Office::create([
            'name' => 'Graduate School',
            'code' => 'acc-gs',
            'admin_user_id' => '32',
            'campus_id' => '1',
        ]); //36

        Office::create([
            'name' => 'College of Law',
            'code' => 'acc-law',
            'admin_user_id' => '33',
            'campus_id' => '1',
        ]); //37

        Office::create([
            'name' => 'College of Teacher Education',
            'code' => 'acc-ted',
            'admin_user_id' => '34',
            'campus_id' => '1',
        ]); //38

        Office::create([
            'name' => 'College of Health Sciences',
            'code' => 'acc-hs',
            'admin_user_id' => '35',
            'campus_id' => '1',
        ]); //39

        Office::create([
            'name' => 'College of Criminal Justice Education',
            'code' => 'acc-cje',
            'admin_user_id' => '36',
            'campus_id' => '1',
        ]); //40

        Office::create([
            'name' => 'College of Business Administration & Hotel Management',
            'code' => 'acc-bahm',
            'admin_user_id' => '37',
            'campus_id' => '1',
        ]); //41

        Office::create([
            'name' => 'College of Arts and Sciences',
            'code' => 'acc-aas',
            'admin_user_id' => '38',
            'campus_id' => '1',
        ]); //42

        Office::create([
            'name' => 'College of Computer Studies',
            'code' => 'acc-cs',
            'admin_user_id' => '91',
            'campus_id' => '1',
        ]); //43

        Office::create([
            'name' => 'College of Industrial Technology',
            'code' => 'acc-it',
            'admin_user_id' => '40',
            'campus_id' => '1',
        ]); //44

        Office::create([
            'name' => 'College of Engineering',
            'code' => 'acc-eng',
            'admin_user_id' => '41',
            'campus_id' => '1',
        ]); //45

        Office::create([
            'name' => 'University Accreditation',
            'code' => 'acc-uacc',
            'admin_user_id' => '42',
            'campus_id' => '1',
        ]); //46

        Office::create([
            'name' => 'Science Laboratory High School',
            'code' => 'acc-slhs',
            'admin_user_id' => '43',
            'campus_id' => '1',
        ]); //47

        Office::create([
            'name' => 'Internal Audit',
            'code' => 'acc-ia',
            'campus_id' => '1',
        ]); //48

        Office::create([
            'name' => 'Supply Office',
            'code' => 'acc-so',
            'admin_user_id' => '46',
            'campus_id' => '1',
        ]); //49

        Office::create([
            'name' => 'Faculty',
            'code' => 'acc-fac',
            'campus_id' => '1',
        ]); //50

        Office::create([
            'name' => 'President\'s Office',
            'code' => 'acc-presoff',
            'campus_id' => '1',
            'admin_user_id' => '64',
            'head_id' => '63',
        ]); //51

        Office::create([
            'name' => 'Cashier\'s Office',
            'code' => 'acc-cashoff',
            'campus_id' => '1',
            'admin_user_id' => '65',
            'head_id' => '66',
        ]); //52

        Office::create([
            'name' => 'College of Fisheries',
            'code' => 'kal-fish',
            'campus_id' => '4',
            'admin_user_id' => '169',
            // 'head_id'=>'66',
        ]); //53

        Office::create([
            'name' => 'Agri-Aqua Center',
            'code' => 'kal-aac',
            'campus_id' => '4',
            'admin_user_id' => '169',
            // 'head_id'=>'66',
        ]); //54

        Office::create([
            'name' => 'Regional Communal Food Processing Center',
            'code' => 'acc-rcfpc',
            'campus_id' => '1',
            'admin_user_id' => '27',
        ]); //55

        Office::create([
            'name' => 'Registrar',
            'code' => 'acc-reg',
            'campus_id' => '1',
            'admin_user_id' => '31',
        ]); //56

        Office::create([
            'name' => 'Registrar',
            'code' => 'isu-reg',
            'campus_id' => '3',
            'admin_user_id' => '226',
        ]); //57
    }
}
