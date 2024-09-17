<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\FundClusterWFP;
use App\Models\MfoFee;
use Illuminate\Database\Seeder;

class MfoFeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FundClusterWFP::create([
            'name' => '164FF/MF',
        ]);

        $fees = [
            ['m_f_o_s_id' => 1, 'fund_cluster_w_f_p_s_id' => 3,'name' => 'IGP ACCESS - Dormitories'],
            ['m_f_o_s_id' => 1, 'fund_cluster_w_f_p_s_id' => 3,'name' => 'IGP ACCESS - Toga Rentals'],
            ['m_f_o_s_id' => 1, 'fund_cluster_w_f_p_s_id' => 3,'name' => 'IGP ACCESS - Facility Rentals'],
            ['m_f_o_s_id' => 1, 'fund_cluster_w_f_p_s_id' => 3,'name' => 'IGP ACCESS - Vehicle Rentals'],
            ['m_f_o_s_id' => 1, 'fund_cluster_w_f_p_s_id' => 3,'name' => 'IGP ACCESS - Utility Fees'],
            ['m_f_o_s_id' => 1, 'fund_cluster_w_f_p_s_id' => 3,'name' => 'IGP ACCESS - Birthing Clinic'],
            ['m_f_o_s_id' => 1, 'fund_cluster_w_f_p_s_id' => 3,'name' => 'IGP ACCESS - Barber Shop'],
            ['m_f_o_s_id' => 1, 'fund_cluster_w_f_p_s_id' => 3,'name' => 'IGP ACCESS - Library Fines and Penalties'],
            ['m_f_o_s_id' => 1, 'fund_cluster_w_f_p_s_id' => 3,'name' => 'IGP ACCESS - UPP'],
            ['m_f_o_s_id' => 1, 'fund_cluster_w_f_p_s_id' => 3,'name' => 'IGP ACCESS - REGISTRAR'],
            ['m_f_o_s_id' => 1, 'fund_cluster_w_f_p_s_id' => 3,'name' => 'IGP ACCESS - Coffee Cupping Facility'],
            ['m_f_o_s_id' => 1, 'fund_cluster_w_f_p_s_id' => 3,'name' => 'IGP ACCESS - Anthurium'],
            ['m_f_o_s_id' => 1, 'fund_cluster_w_f_p_s_id' => 3,'name' => 'IGP ACCESS - Programming Extension'],
            ['m_f_o_s_id' => 1, 'fund_cluster_w_f_p_s_id' => 3,'name' => 'IGP ACCESS - IGS Training Fee '],
            ['m_f_o_s_id' => 1, 'fund_cluster_w_f_p_s_id' => 3,'name' => 'IGP ACCESS - ID Sling (Consignment)'],
            ['m_f_o_s_id' => 1, 'fund_cluster_w_f_p_s_id' => 3,'name' => 'IGP BAGUMBAYAN - African Palm Oil'],
            ['m_f_o_s_id' => 1, 'fund_cluster_w_f_p_s_id' => 3,'name' => 'IGP ISULAN - BIMON Curls'],
            ['m_f_o_s_id' => 1, 'fund_cluster_w_f_p_s_id' => 3,'name' => 'IGP ISULAN - QR Contact Tracing Lincensing System'],
            ['m_f_o_s_id' => 1, 'fund_cluster_w_f_p_s_id' => 3,'name' => 'IGP ISULAN - Space/Stall Rental'],
            ['m_f_o_s_id' => 1, 'fund_cluster_w_f_p_s_id' => 3,'name' => 'IGP LUTAYAN - Rice Seed Production'],
            ['m_f_o_s_id' => 1, 'fund_cluster_w_f_p_s_id' => 3,'name' => 'IGP LUTAYAN - Land Rental'],
            ['m_f_o_s_id' => 1, 'fund_cluster_w_f_p_s_id' => 3,'name' => 'IGP LUTAYAN - Mushroom Production'],
            ['m_f_o_s_id' => 1, 'fund_cluster_w_f_p_s_id' => 3,'name' => 'IGP LUTAYAN - Cattle'],
            ['m_f_o_s_id' => 1, 'fund_cluster_w_f_p_s_id' => 3,'name' => 'IGP LUTAYAN - Goat'],
            ['m_f_o_s_id' => 1, 'fund_cluster_w_f_p_s_id' => 3,'name' => 'IGP LUTAYAN - Vermicast'],
            ['m_f_o_s_id' => 1, 'fund_cluster_w_f_p_s_id' => 3,'name' => 'IGP LUTAYAN - African Palm Oil'],
            ['m_f_o_s_id' => 1, 'fund_cluster_w_f_p_s_id' => 3,'name' => 'IGP KALAMANSIG - Guestelle'],
            ['m_f_o_s_id' => 1, 'fund_cluster_w_f_p_s_id' => 3,'name' => 'IGP TACURONG - Liliputian Hotel'],
            ['m_f_o_s_id' => 1, 'fund_cluster_w_f_p_s_id' => 3,'name' => 'IGP ACCESS - ID Sling (Consignment)'],
            ['m_f_o_s_id' => 1, 'fund_cluster_w_f_p_s_id' => 3,'name' => 'IGP PALIMBANG - Vegegreen'],
            //164T
            ['m_f_o_s_id' => 4, 'fund_cluster_w_f_p_s_id' => 4,'name' => 'RESEARCH'],
            ['m_f_o_s_id' => 5, 'fund_cluster_w_f_p_s_id' => 4,'name' => 'EXTENSION'],
            ['m_f_o_s_id' => 5, 'fund_cluster_w_f_p_s_id' => 4,'name' => 'RADYO KATILINGBAN'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 4,'name' => 'INSTRUCTION - Student Development'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 4,'name' => 'INSTRUCTION - Facilities Development'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 4,'name' => 'INSTRUCTION - Curriculum Development'],
            ['m_f_o_s_id' => 1, 'fund_cluster_w_f_p_s_id' => 4,'name' => 'MANDATORY RESERVE'],
            ['m_f_o_s_id' => 1, 'fund_cluster_w_f_p_s_id' => 4,'name' => 'ADMIN SHARE'],
            ['m_f_o_s_id' => 1, 'fund_cluster_w_f_p_s_id' => 4,'name' => 'PRODUCTION'],
            ['m_f_o_s_id' => 1, 'fund_cluster_w_f_p_s_id' => 4,'name' => 'FACILITIES DEVELOPMENT'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 4,'name' => 'FACULTY AND STAFF DEVELOPMENT'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 4,'name' => 'CURRICULUM DEVELOPMENT'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 4,'name' => 'TUITION'],
            ['m_f_o_s_id' => 3, 'fund_cluster_w_f_p_s_id' => 4,'name' => 'TUITION'],
            //164FF/OSF
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 5,'name' => 'NSTP FEE'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 5,'name' => 'SCUAA'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 5,'name' => 'ATHLETIC FEE'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 5,'name' => 'COMPUTER FEE'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 5,'name' => 'DEV FEE_USG'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 5,'name' => 'DEV FEE_ENACTUS'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 5,'name' => 'DEV FEE_SBO'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 5,'name' => 'DEV FEE_PUBLICATION'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 5,'name' => 'DEV FEE_ENHANCEMENT'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 5,'name' => 'GUIDANCE FEE'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 5,'name' => 'HANDBOOK FEES'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 5,'name' => 'LABORATORY FEE'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 5,'name' => 'LIBRARY FEE'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 5,'name' => 'MEDICAL & DENTAL FEE'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 5,'name' => 'INSURANCE FEE'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 5,'name' => 'REGISTRATION FEE'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 5,'name' => 'SCHOOL ID'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 5,'name' => 'TEST PAPER'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 5,'name' => 'TEST BOOKLET'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 5,'name' => 'GRADUATION FEE'],
            //164FF/MF
            ['m_f_o_s_id' => 1, 'fund_cluster_w_f_p_s_id' => 6,'name' => 'REGISTRATION INCOME'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 6,'name' => 'TESTING FEE'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 6,'name' => 'COMPETENCY ENHANCEMENT PROGRAM (CEP)'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 6,'name' => 'LICENSURE EXAMINATION FOR TEACHERS (LET)'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 6,'name' => 'RELATED LEARNING EXPERIENCE (RLE)'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 6,'name' => 'ROTC'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 6,'name' => 'DEFENSE'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 6,'name' => 'FS/INTERNSHIP'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 6,'name' => 'YEARBOOK'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 6,'name' => 'LIBRARY FEE'],
            ['m_f_o_s_id' => 3, 'fund_cluster_w_f_p_s_id' => 6,'name' => 'LIBRARY FEE'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 6,'name' => 'REGISTRATION FEE'],
            ['m_f_o_s_id' => 3, 'fund_cluster_w_f_p_s_id' => 6,'name' => 'REGISTRATION FEE'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 6,'name' => 'REQUESTED SUBJECT'],
            ['m_f_o_s_id' => 3, 'fund_cluster_w_f_p_s_id' => 6,'name' => 'REQUESTED SUBJECT'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 6,'name' => 'TESTPAPER'],
            ['m_f_o_s_id' => 3, 'fund_cluster_w_f_p_s_id' => 6,'name' => 'TESTPAPER'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 6,'name' => 'TESTING FEE'],
            ['m_f_o_s_id' => 3, 'fund_cluster_w_f_p_s_id' => 6,'name' => 'TESTING FEE'],
            ['m_f_o_s_id' => 3, 'fund_cluster_w_f_p_s_id' => 6,'name' => 'ADMISSION FEE'],
            ['m_f_o_s_id' => 3, 'fund_cluster_w_f_p_s_id' => 6,'name' => 'DEFENSE'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 6,'name' => 'DEV FEE_ENHANCEMENT'],
            ['m_f_o_s_id' => 3, 'fund_cluster_w_f_p_s_id' => 6,'name' => 'DEV FEE_ENHANCEMENT'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 6,'name' => 'GUIDANCE FEE'],
            ['m_f_o_s_id' => 3, 'fund_cluster_w_f_p_s_id' => 6,'name' => 'GUIDANCE FEE'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 6,'name' => 'SBO PROJECT'],
            ['m_f_o_s_id' => 3, 'fund_cluster_w_f_p_s_id' => 6,'name' => 'SBO PROJECT'],
            ['m_f_o_s_id' => 3, 'fund_cluster_w_f_p_s_id' => 6,'name' => 'JOURNAL FEE'],
            ['m_f_o_s_id' => 3, 'fund_cluster_w_f_p_s_id' => 6,'name' => 'DEV FEE_SBO'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 6,'name' => 'DEV FEE_PUBLICATION'],
            ['m_f_o_s_id' => 3, 'fund_cluster_w_f_p_s_id' => 6,'name' => 'DEV FEE_PUBLICATION'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 6,'name' => 'CULTURAL FEE'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 6,'name' => 'MISCELLANEOUS FEE'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 6,'name' => 'ATHLECTIC FEE'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 6,'name' => 'COMPUTER FEE'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 6,'name' => 'SCOUTING'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 6,'name' => 'CLUBS & ORGANIZATION'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 6,'name' => 'SSC'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 6,'name' => 'RED CROSS'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 6,'name' => 'ACTIVITY FEE'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 6,'name' => 'LHS RESEARCH'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 6,'name' => 'UTILITY FEE'],
            ['m_f_o_s_id' => 2, 'fund_cluster_w_f_p_s_id' => 6,'name' => 'LHS INSURANCE'],
        ];


        foreach ($fees as $item) {
            MfoFee::create($item);
        }
    }
}
