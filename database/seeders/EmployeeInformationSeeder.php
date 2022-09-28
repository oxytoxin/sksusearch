<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeeInformationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'email' => 'christineabo@sksu.edu.ph',
                'password' => Hash::make('abo123'),
            ],
            [
                'email' => 'juliealbano@sksu.edu.ph',
                'password' => Hash::make('albano123'),
            ],
            [
                'email' => 'lynettepeniero@sksu.edu.ph',
                'password' => Hash::make('peniero123'),
            ],
            [
                'email' => 'helenespartero@sksu.edu.ph',
                'password' => Hash::make('espartero123'),
            ],
            [
                'email' => 'rodelyndalayap@sksu.edu.ph',
                'password' => Hash::make('dalayap123'),
            ],
            [
                'email' => 'sionybrunio@sksu.edu.ph',
                'password' => Hash::make('brunio123'),
            ],
            [
                'email' => 'rositarizaldo@sksu.edu.ph',
                'password' => Hash::make('rizaldo123'),
            ],
            [
                'email' => 'dennismeriales@sksu.edu.ph',
                'password' => Hash::make('meriales123'),
            ],
            [
                'email' => 'noraisayasin@sksu.edu.ph',
                'password' => Hash::make('yasin123'),
            ],
            [
                'email' => 'lanialcon@sksu.edu.ph',
                'password' => Hash::make('alcon123'),
            ],
            [
                'email' => 'rebeccasubillaga@sksu.edu.ph',
                'password' => Hash::make('subillaga123'),
            ],
            [
                'email' => 'susiedaza@sksu.edu.ph',
                'password' => Hash::make('daza123'),
            ],
            [
                'email' => 'leonciodulin@sksu.edu.ph',
                'password' => Hash::make('dulin123'),
            ],
            [
                'email' => 'benedictrabut@sksu.edu.ph',
                'password' => Hash::make('rabut123'),
            ],
            [
                'email' => 'kristinemaeampas@sksu.edu.ph',
                'password' => Hash::make('ampas123'),
            ],
            [
                'email' => 'alnequinoveba@sksu.edu.ph',
                'password' => Hash::make('quinoveba123'),
            ],
            [
                'email' => 'lilibethedano@sksu.edu.ph',
                'password' => Hash::make('edano123'),
            ],
            [
                'email' => 'vivenciocalixtro@sksu.edu.ph',
                'password' => Hash::make('calixtro123'),
            ],
            [
                'email' => 'jesherpalomaria@sksu.edu.ph',
                'password' => Hash::make('palomaria123'),
            ],
            [
                'email' => 'reyfuentebilla@sksu.edu.ph',
                'password' => Hash::make('fuentebilla123'),
            ],
            [
                'email' => 'annerilllorio@sksu.edu.ph',
                'password' => Hash::make('lorio123'),
            ],
            [
                'email' => 'maryjeanfalsario@sksu.edu.ph',
                'password' => Hash::make('falsario123'),
            ],
            [
                'email' => 'amiramaegumanoy@sksu.edu.ph',
                'password' => Hash::make('gumanoy123'),
            ],
            [
                'email' => 'hassanalabusama@sksu.edu.ph',
                'password' => Hash::make('abusama123'),
            ],
            [
                'email' => 'gracepedrola@sksu.edu.ph',
                'password' => Hash::make('pedrola123'),
            ],
            // [
            //     'email' => 'nestoralcon@sksu.edu.ph',
            //     'password' => Hash::make('alcon123'),
            // ],
            // [
            //     'email' => 'cyriljohndomingo@sksu.edu.ph',
            //     'password' => Hash::make('domingo123'),
            // ],
            // [
            //     'email' => 'mitosdelco@sksu.edu.ph',
            //     'password' => Hash::make('delco123'),
            // ],
            // [
            //     'email' => 'gemmaconstantino@sksu.edu.ph',
            //     'password' => Hash::make('constantino123'),
            // ],
            // [
            //     'email' => 'maritesjava@sksu.edu.ph',
            //     'password' => Hash::make('java123'),
            // ],
            // [
            //     'email' => 'violetapico@sksu.edu.ph',
            //     'password' => Hash::make('pico123'),
            // ],
            // [
            //     'email' => 'marissahitalia@sksu.edu.ph',
            //     'password' => Hash::make('hitalia123'),
            // ],
            // [
            //     'email' => 'lennieanncerdana@sksu.edu.ph',
            //     'password' => Hash::make('cerdana123'),
            // ],
            // [
            //     'email' => 'nancyespacio@sksu.edu.ph',
            //     'password' => Hash::make('espacio123'),
            // ],
            // [
            //     'email' => 'carmelacamilaurbano@sksu.edu.ph',
            //     'password' => Hash::make('urbano123'),
            // ],
            // [
            //     'email' => 'edwincortejo@sksu.edu.ph',
            //     'password' => Hash::make('cortejo123'),
            // ],
            // [
            //     'email' => 'majeanelleargonza@sksu.edu.ph',
            //     'password' => Hash::make('argonza123'),
            // ],
            // [
            //     'email' => 'jeannieromano@sksu.edu.ph',
            //     'password' => Hash::make('romano123'),
            // ],
            // [
            //     'email' => 'ceciliagener@sksu.edu.ph',
            //     'password' => Hash::make('gener123'),
            // ],
            // [
            //     'email' => 'randeberina@sksu.edu.ph',
            //     'password' => Hash::make('berina123'),
            // ],
            // [
            //     'email' => 'meilaflorpaclibar@sksu.edu.ph',
            //     'password' => Hash::make('paclibar123'),
            // ],
            // [
            //     'email' => 'eulogioapellido@sksu.edu.ph',
            //     'password' => Hash::make('apellido123'),
            // ],
            // [
            //     'email' => 'adonisbesa@sksu.edu.ph',
            //     'password' => Hash::make('besa123'),
            // ],
            // [
            //     'email' => 'desireelegaspi@sksu.edu.ph',
            //     'password' => Hash::make('legaspi123'),
            // ],
            // [
            //     'email' => 'reginalacamento@sksu.edu.ph',
            //     'password' => Hash::make('lacamento123'),
            // ],
            // [
            //     'email' => 'lovinacogollo@sksu.edu.ph',
            //     'password' => Hash::make('cogollo123'),
            // ],
            // [
            //     'email' => 'jaybadilla@sksu.edu.ph',
            //     'password' => Hash::make('badilla123'),
            // ],
            // [
            //     'email' => 'jeremyoriana@sksu.edu.ph',
            //     'password' => Hash::make('oriana123'),
            // ],
            // [
            //     'email' => 'rizzamaekusain@sksu.edu.ph',
            //     'password' => Hash::make('kusain123'),
            // ],
            // [
            //     'email' => 'reynaldodalayap@sksu.edu.ph',
            //     'password' => Hash::make('dalayap123'),
            // ],
            // [
            //     'email' => 'kayeannacaylar@sksu.edu.ph',
            //     'password' => Hash::make('acaylar123'),
            // ],
            // [
            //     'email' => 'mariloutorrecampo@sksu.edu.ph',
            //     'password' => Hash::make('torrecampo123'),
            // ],
            // [
            //     'email' => 'catherinelegaspi@sksu.edu.ph',
            //     'password' => Hash::make('legaspi123'),
            // ],
            // [
            //     'email' => 'lapuzmamalo@sksu.edu.ph',
            //     'password' => Hash::make('mamalo123'),
            // ],
            // [
            //     'email' => 'armiejanoto@sksu.edu.ph',
            //     'password' => Hash::make('janoto123'),
            // ],
            // [
            //     'email' => 'kimberlypantinople@sksu.edu.ph',
            //     'password' => Hash::make('pantinople123'),
            // ],
            // [
            //     'email' => 'nicolemagante@sksu.edu.ph',
            //     'password' => Hash::make('magante123'),
            // ],
            // [
            //     'email' => 'jasondavetemblique@sksu.edu.ph',
            //     'password' => Hash::make('temblique123'),
            // ],
            // [
            //     'email' => 'fretchiemaebedua@sksu.edu.ph',
            //     'password' => Hash::make('bedua123'),
            // ],
            // [
            //     'email' => 'johnreidelacruz@sksu.edu.ph',
            //     'password' => Hash::make('delacruz123'),
            // ],
            // [
            //     'email' => 'roselendador@sksu.edu.ph',
            //     'password' => Hash::make('dador123'),
            // ],
            // [
            //     'email' => 'estherlanceta@sksu.edu.ph',
            //     'password' => Hash::make('lanceta123'),
            // ],
            // [
            //     'email' => 'irishmaepasquin@sksu.edu.ph',
            //     'password' => Hash::make('pasquin123'),
            // ],
            // [
            //     'email' => 'rolandohechanova@sksu.edu.ph',
            //     'password' => Hash::make('hechanova123'),
            // ],
            // [
            //     'email' => 'tarhatausman@sksu.edu.ph',
            //     'password' => Hash::make('usman123'),
            // ],
            // [
            //     'email' => 'vivianlanceta@sksu.edu.ph',
            //     'password' => Hash::make('lanceta123'),
            // ],
            // [
            //     'email' => 'jacquelinbringuelo@sksu.edu.ph',
            //     'password' => Hash::make('bringuelo123'),
            // ],
            // [
            //     'email' => 'dennisabanales@sksu.edu.ph',
            //     'password' => Hash::make('abanales123'),
            // ],
            // [
            //     'email' => 'nasarudinabas@sksu.edu.ph',
            //     'password' => Hash::make('abas123'),
            // ],
            // [
            //     'email' => 'jeebabelito@sksu.edu.ph',
            //     'password' => Hash::make('abelito123'),
            // ],
            // [
            //     'email' => 'sharenjillabellar@sksu.edu.ph',
            //     'password' => Hash::make('abellar123'),
            // ],
            // [
            //     'email' => 'abrahamaccad@sksu.edu.ph',
            //     'password' => Hash::make('accad123'),
            // ],
            // [
            //     'email' => 'mildredaccad@sksu.edu.ph',
            //     'password' => Hash::make('accad123'),
            // ],
            // [
            //     'email' => 'hipolitoacuzar@sksu.edu.ph',
            //     'password' => Hash::make('acuzar123'),
            // ],
            // [
            //     'email' => 'norainaaguil@sksu.edu.ph',
            //     'password' => Hash::make('aguil123'),
            // ],
            // [
            //     'email' => 'crizjaleahmad@sksu.edu.ph',
            //     'password' => Hash::make('ahmad123'),
            // ],
            // [
            //     'email' => 'saidenakmad@sksu.edu.ph',
            //     'password' => Hash::make('akmad123'),
            // ],
            // [
            //     'email' => 'michellealbaran@sksu.edu.ph',
            //     'password' => Hash::make('albaran123'),
            // ],
            // [
            //     'email' => 'markanthonyalcantara@sksu.edu.ph',
            //     'password' => Hash::make('alcantara123'),
            // ],
            // [
            //     'email' => 'batutiali@sksu.edu.ph',
            //     'password' => Hash::make('ali123'),
            // ],
            // [
            //     'email' => 'marianoalimajen@sksu.edu.ph',
            //     'password' => Hash::make('alimajen123'),
            // ],
            // [
            //     'email' => 'merlyalimajen@sksu.edu.ph',
            //     'password' => Hash::make('alimajen123'),
            // ],
            // [
            //     'email' => 'gracelynaltaya@sksu.edu.ph',
            //     'password' => Hash::make('altaya123'),
            // ],
            // [
            //     'email' => 'cristobalambayon@sksu.edu.ph',
            //     'password' => Hash::make('ambayon123'),
            // ],
            // [
            //     'email' => 'maricelamit@sksu.edu.ph',
            //     'password' => Hash::make('amit123'),
            // ],
            // [
            //     'email' => 'aurelioampo@sksu.edu.ph',
            //     'password' => Hash::make('ampo123'),
            // ],
            // [
            //     'email' => 'keivenmarkampode@sksu.edu.ph',
            //     'password' => Hash::make('ampode123'),
            // ],
            // [
            //     'email' => 'roselynandamon@sksu.edu.ph',
            //     'password' => Hash::make('andamon123'),
            // ],
            // [
            //     'email' => 'almiraangkal@sksu.edu.ph',
            //     'password' => Hash::make('angkal123'),
            // ],
            // [
            //     'email' => 'cresencioantonio@sksu.edu.ph',
            //     'password' => Hash::make('antonio123'),
            // ],
            // [
            //     'email' => 'elbrenantonio@sksu.edu.ph',
            //     'password' => Hash::make('antonio123'),
            // ],
            // [
            //     'email' => 'henrisaaparis@sksu.edu.ph',
            //     'password' => Hash::make('aparis123'),
            // ],
            // [
            //     'email' => 'alexisapresto@sksu.edu.ph',
            //     'password' => Hash::make('apresto123'),
            // ],
            // [
            //     'email' => 'ziusapresto@sksu.edu.ph',
            //     'password' => Hash::make('apresto123'),
            // ],
            // [
            //     'email' => 'reynaldoaranego@sksu.edu.ph',
            //     'password' => Hash::make('aranego123'),
            // ],
            // [
            //     'email' => 'ramilarciosa@sksu.edu.ph',
            //     'password' => Hash::make('arciosa123'),
            // ],
            // [
            //     'email' => 'judemichaelarellano@sksu.edu.ph',
            //     'password' => Hash::make('arellano123'),
            // ],
            // [
            //     'email' => 'jaymarkarendain@sksu.edu.ph',
            //     'password' => Hash::make('arendain123'),
            // ],
            // [
            //     'email' => 'amyarmada@sksu.edu.ph',
            //     'password' => Hash::make('armada123'),
            // ],
            // [
            //     'email' => 'michaelarrivas@sksu.edu.ph',
            //     'password' => Hash::make('arrivas123'),
            // ],
            // [
            //     'email' => 'leeastrologo@sksu.edu.ph',
            //     'password' => Hash::make('astrologo123'),
            // ],
            // [
            //     'email' => 'judithasturias@sksu.edu.ph',
            //     'password' => Hash::make('asturias123'),
            // ],
            // [
            //     'email' => 'wilfredoatayan@sksu.edu.ph',
            //     'password' => Hash::make('atayan123'),
            // ],
            // [
            //     'email' => 'joselynbacera@sksu.edu.ph',
            //     'password' => Hash::make('bacera123'),
            // ],
            // [
            //     'email' => 'mailynebacongco@sksu.edu.ph',
            //     'password' => Hash::make('bacongco123'),
            // ],
            // [
            //     'email' => 'esneharabagundang@sksu.edu.ph',
            //     'password' => Hash::make('bagundang123'),
            // ],
            // [
            //     'email' => 'raulbalacuit@sksu.edu.ph',
            //     'password' => Hash::make('balacuit123'),
            // ],
            // [
            //     'email' => 'norenbangkulit@sksu.edu.ph',
            //     'password' => Hash::make('bangkulit123'),
            // ],
            // [
            //     'email' => 'marlonbangonon@sksu.edu.ph',
            //     'password' => Hash::make('bangonon123'),
            // ],
            // [
            //     'email' => 'nasserbantugan@sksu.edu.ph',
            //     'password' => Hash::make('bantugan123'),
            // ],
            // [
            //     'email' => 'noelbaraquia@sksu.edu.ph',
            //     'password' => Hash::make('baraquia123'),
            // ],
            // [
            //     'email' => 'rosaliebarroquillo@sksu.edu.ph',
            //     'password' => Hash::make('barroquillo123'),
            // ],
            // [
            //     'email' => 'elizabethbauzon@sksu.edu.ph',
            //     'password' => Hash::make('bauzon123'),
            // ],
            // [
            //     'email' => 'rnelbelgira@sksu.edu.ph',
            //     'password' => Hash::make('belgira123'),
            // ],
            // [
            //     'email' => 'michaeljohnbenavidez@sksu.edu.ph',
            //     'password' => Hash::make('benavides123'),
            // ],
            // [
            //     'email' => 'dayanarabesa@sksu.edu.ph',
            //     'password' => Hash::make('besa123'),
            // ],
            // [
            //     'email' => 'candellenbiadoma@sksu.edu.ph',
            //     'password' => Hash::make('biadoma123'),
            // ],
            // [
            //     'email' => 'antoniobibat@sksu.edu.ph',
            //     'password' => Hash::make('bibat123'),
            // ],
            // [
            //     'email' => 'frelinbinag@sksu.edu.ph',
            //     'password' => Hash::make('binag123'),
            // ],
            // [
            //     'email' => 'irenebinag@sksu.edu.ph',
            //     'password' => Hash::make('binag123'),
            // ],
            // [
            //     'email' => 'joelbinag@sksu.edu.ph',
            //     'password' => Hash::make('binag123'),
            // ],
            // [
            //     'email' => 'josevirsonbinag@sksu.edu.ph',
            //     'password' => Hash::make('binag123'),
            // ],
            // [
            //     'email' => 'christopherblasurca@sksu.edu.ph',
            //     'password' => Hash::make('blasurcha123'),
            // ],
            // [
            //     'email' => 'christianjohnboglosa@sksu.edu.ph',
            //     'password' => Hash::make('boglosa123'),
            // ],
            // [
            //     'email' => 'almiraboniel@sksu.edu.ph',
            //     'password' => Hash::make('boniel123'),
            // ],
            // [
            //     'email' => 'sandrabonrustro@sksu.edu.ph',
            //     'password' => Hash::make('bonrusto123'),
            // ],
            // [
            //     'email' => 'elmerbuenavides@sksu.edu.ph',
            //     'password' => Hash::make('buenavides123'),
            // ],
            // [
            //     'email' => 'jadebuenavides@sksu.edu.ph',
            //     'password' => Hash::make('buenavides123'),
            // ],
            // [
            //     'email' => 'polianbugador@sksu.edu.ph',
            //     'password' => Hash::make('bugador123'),
            // ],
            // [
            //     'email' => 'ivybulaloc@sksu.edu.ph',
            //     'password' => Hash::make('bulaloc123'),
            // ],
            // [
            //     'email' => 'wilbertcabanban@sksu.edu.ph',
            //     'password' => Hash::make('cabanban123'),
            // ],
            // [
            //     'email' => 'vincentlouiecabelin@sksu.edu.ph',
            //     'password' => Hash::make('cabelin123'),
            // ],
            // [
            //     'email' => 'allanjaycajandig@sksu.edu.ph',
            //     'password' => Hash::make('cajandig123'),
            // ],
            // [
            //     'email' => 'juanitacajandig@sksu.edu.ph',
            //     'password' => Hash::make('cajandig123'),
            // ],
            // [
            //     'email' => 'malyncalub@sksu.edu.ph',
            //     'password' => Hash::make('calub123'),
            // ],
            // [
            //     'email' => 'michaelkennedycamarao@sksu.edu.ph',
            //     'password' => Hash::make('camarao123'),
            // ],
            // [
            //     'email' => 'johnregnaircandado@sksu.edu.ph',
            //     'password' => Hash::make('candado123'),
            // ],
            // [
            //     'email' => 'jacquilinecandido@sksu.edu.ph',
            //     'password' => Hash::make('candido123'),
            // ],
            // [
            //     'email' => 'jovitacarigaba@sksu.edu.ph',
            //     'password' => Hash::make('carigaba123'),
            // ],
            // [
            //     'email' => 'maryjoycarnazo@sksu.edu.ph',
            //     'password' => Hash::make('carnazo123'),
            // ],
            // [
            //     'email' => 'rhodinacastillo@sksu.edu.ph',
            //     'password' => Hash::make('castillo123'),
            // ],
            // [
            //     'email' => 'romaamorcastromayor@sksu.edu.ph',
            //     'password' => Hash::make('castromayor123'),
            // ],
            // [
            //     'email' => 'lenmarcatajay@sksu.edu.ph',
            //     'password' => Hash::make('catajay123'),
            // ],
            // [
            //     'email' => 'rodelcatalan@sksu.edu.ph',
            //     'password' => Hash::make('catalan123'),
            // ],
            // [
            //     'email' => 'karencatane@sksu.edu.ph',
            //     'password' => Hash::make('catane123'),
            // ],
            // [
            //     'email' => 'arnoldcatipay@sksu.edu.ph',
            //     'password' => Hash::make('catipay123'),
            // ],
            // [
            //     'email' => 'kristinejoycatiwalaan@sksu.edu.ph',
            //     'password' => Hash::make('catiwalaan123'),
            // ],
            // [
            //     'email' => 'lanicejes@sksu.edu.ph',
            //     'password' => Hash::make('cejes123'),
            // ],
            // [
            //     'email' => 'arnelceleste@sksu.edu.ph',
            //     'password' => Hash::make('celeste123'),
            // ],
            // [
            //     'email' => 'erniecerado@sksu.edu.ph',
            //     'password' => Hash::make('cerado123'),
            // ],
            // [
            //     'email' => 'donaldcogo@sksu.edu.ph',
            //     'password' => Hash::make('cogo123'),
            // ],
            // [
            //     'email' => 'jovitacollado@sksu.edu.ph',
            //     'password' => Hash::make('collado123'),
            // ],
            // [
            //     'email' => 'luzvimindacolong@sksu.edu.ph',
            //     'password' => Hash::make('colong123'),
            // ],
            // [
            //     'email' => 'nievescomicho@sksu.edu.ph',
            //     'password' => Hash::make('comicho123'),
            // ],
            // [
            //     'email' => 'ellenconsomo@sksu.edu.ph',
            //     'password' => Hash::make('consomo123'),
            // ],
            // [
            //     'email' => 'rizalyncudera@sksu.edu.ph',
            //     'password' => Hash::make('cudera123'),
            // ],
            // [
            //     'email' => 'ginacuenca@sksu.edu.ph',
            //     'password' => Hash::make('cuenca123'),
            // ],
            // [
            //     'email' => 'leahdadoli@sksu.edu.ph',
            //     'password' => Hash::make('dadoli123'),
            // ],
            // [
            //     'email' => 'hannahdafielmoto@sksu.edu.ph',
            //     'password' => Hash::make('dafielmoto123'),
            // ],
            // [
            //     'email' => 'cheryldagaas@sksu.edu.ph',
            //     'password' => Hash::make('dagaas123'),
            // ],
            // [
            //     'email' => 'remigildodagamac@sksu.edu.ph',
            //     'password' => Hash::make('dagamac123'),
            // ],
            // [
            //     'email' => 'gerwindagum@sksu.edu.ph',
            //     'password' => Hash::make('dagum123'),
            // ],
            // [
            //     'email' => 'nuraisadalida@sksu.edu.ph',
            //     'password' => Hash::make('dalida123'),
            // ],
            // [
            //     'email' => 'johnernestdamandaman@sksu.edu.ph',
            //     'password' => Hash::make('damandaman123'),
            // ],
            // [
            //     'email' => 'tefannydaniel@sksu.edu.ph',
            //     'password' => Hash::make('daniel123'),
            // ],
            // [
            //     'email' => 'yolandadapitan@sksu.edu.ph',
            //     'password' => Hash::make('dapitan123'),
            // ],
            // [
            //     'email' => 'armandodardo@sksu.edu.ph',
            //     'password' => Hash::make('dardo123'),
            // ],
            // [
            //     'email' => 'renatodelacruz@sksu.edu.ph',
            //     'password' => Hash::make('delacruz123'),
            // ],
            // [
            //     'email' => 'randedechavez@sksu.edu.ph',
            //     'password' => Hash::make('dechavez123'),
            // ],
            // [
            //     'email' => 'aprilkrisdelacruz@sksu.edu.ph',
            //     'password' => Hash::make('delacruz123'),
            // ],
            // [
            //     'email' => 'crisjohnbryandela cruz@sksu.edu.ph',
            //     'password' => Hash::make('delacruz123'),
            // ],
            // [
            //     'email' => 'mandydelfin@sksu.edu.ph',
            //     'password' => Hash::make('delfin123'),
            // ],
            // [
            //     'email' => 'reynandemafeliz@sksu.edu.ph',
            //     'password' => Hash::make('demafeliz123'),
            // ],
            // [
            //     'email' => 'lodifeldeypalan@sksu.edu.ph',
            //     'password' => Hash::make('deypalan123'),
            // ],
            // [
            //     'email' => 'elviediaz@sksu.edu.ph',
            //     'password' => Hash::make('diaz123'),
            // ],
            // [
            //     'email' => 'evoradioneza@sksu.edu.ph',
            //     'password' => Hash::make('dioneza123'),
            // ],
            // [
            //     'email' => 'denmarkdizo@sksu.edu.ph',
            //     'password' => Hash::make('dizo123'),
            // ],
            // [
            //     'email' => 'kyrenedizon@sksu.edu.ph',
            //     'password' => Hash::make('dizon123'),
            // ],
            // [
            //     'email' => 'johndomondon@sksu.edu.ph',
            //     'password' => Hash::make('domondon123'),
            // ],
            // [
            //     'email' => 'velessa janedulin@sksu.edu.ph',
            //     'password' => Hash::make('dulin123'),
            // ],
            // [
            //     'email' => 'ludyduran@sksu.edu.ph',
            //     'password' => Hash::make('duran123'),
            // ],
            // [
            //     'email' => 'raymondedisan@sksu.edu.ph',
            //     'password' => Hash::make('edisan123'),
            // ],
            // [
            //     'email' => 'sallyedza@sksu.edu.ph',
            //     'password' => Hash::make('edza123'),
            // ],
            // [
            //     'email' => 'gloryjeaneiman@sksu.edu.ph',
            //     'password' => Hash::make('eiman123'),
            // ],
            // [
            //     'email' => 'reyejercito@sksu.edu.ph',
            //     'password' => Hash::make('ejercito123'),
            // ],
            // [
            //     'email' => 'gloriaenvidiado@sksu.edu.ph',
            //     'password' => Hash::make('envidiado123'),
            // ],
            // [
            //     'email' => 'dominicescano@sksu.edu.ph',
            //     'password' => Hash::make('escano123'),
            // ],
            // [
            //     'email' => 'lowellespinosa@sksu.edu.ph',
            //     'password' => Hash::make('espinosa123'),
            // ],
            // [
            //     'email' => 'sandraespinosa@sksu.edu.ph',
            //     'password' => Hash::make('espinosa123'),
            // ],
            // [
            //     'email' => 'nenitaesteban@sksu.edu.ph',
            //     'password' => Hash::make('esteban123'),
            // ],
            // [
            //     'email' => 'arnoldestrella@sksu.edu.ph',
            //     'password' => Hash::make('estrella123'),
            // ],
            // [
            //     'email' => 'joselynestrellan@sksu.edu.ph',
            //     'password' => Hash::make('estrellan123'),
            // ],
            // [
            //     'email' => 'elbertetrata@sksu.edu.ph',
            //     'password' => Hash::make('etrata123'),
            // ],
            // [
            //     'email' => 'ciriloevangelista@sksu.edu.ph',
            //     'password' => Hash::make('evangelista123'),
            // ],
            // [
            //     'email' => 'ivanroyevangelista@sksu.edu.ph',
            //     'password' => Hash::make('evangelista123'),
            // ],
            // [
            //     'email' => 'jaysonfalle@sksu.edu.ph',
            //     'password' => Hash::make('falle123'),
            // ],
            // [
            //     'email' => 'jenamaefatagani@sksu.edu.ph',
            //     'password' => Hash::make('fatagani123'),
            // ],
            // [
            //     'email' => 'divinafeliciano@sksu.edu.ph',
            //     'password' => Hash::make('feliciano123'),
            // ],
            // [
            //     'email' => 'marvinfermase@sksu.edu.ph',
            //     'password' => Hash::make('fermase123'),
            // ],
            // [
            //     'email' => 'anitaflores@sksu.edu.ph',
            //     'password' => Hash::make('flores123'),
            // ],
            // [
            //     'email' => 'efrenflores@sksu.edu.ph',
            //     'password' => Hash::make('flores123'),
            // ],
            // [
            //     'email' => 'roselynfloresca@sksu.edu.ph',
            //     'password' => Hash::make('floresca123'),
            // ],
            // [
            //     'email' => 'anniefrancisco@sksu.edu.ph',
            //     'password' => Hash::make('francisco123'),
            // ],
            // [
            //     'email' => 'josephinefreires@sksu.edu.ph',
            //     'password' => Hash::make('freires123'),
            // ],
            // [
            //     'email' => 'christinefuna@sksu.edu.ph',
            //     'password' => Hash::make('funa123'),
            // ],
            // [
            //     'email' => 'corazongabato@sksu.edu.ph',
            //     'password' => Hash::make('gabato123'),
            // ],
            // [
            //     'email' => 'maygallano@sksu.edu.ph',
            //     'password' => Hash::make('gallano123'),
            // ],
            // [
            //     'email' => 'marygracegallego@sksu.edu.ph',
            //     'password' => Hash::make('gallego123'),
            // ],
            // [
            //     'email' => 'alexesgallo@sksu.edu.ph',
            //     'password' => Hash::make('gallo123'),
            // ],
            // [
            //     'email' => 'marhodoragallo@sksu.edu.ph',
            //     'password' => Hash::make('gallo123'),
            // ],
            // [
            //     'email' => 'joylyngamiao@sksu.edu.ph',
            //     'password' => Hash::make('gamiao123'),
            // ],
            // [
            //     'email' => 'judithgenota@sksu.edu.ph',
            //     'password' => Hash::make('genota123'),
            // ],
            // [
            //     'email' => 'johnlarrygeonigo@sksu.edu.ph',
            //     'password' => Hash::make(''),
            // ],
            // [
            //     'email' => 'fatimagoleng@sksu.edu.ph',
            //     'password' => Hash::make('goleng123'),
            // ],
            // [
            //     'email' => 'olivegomez@sksu.edu.ph',
            //     'password' => Hash::make('gomez123'),
            // ],
            // [
            //     'email' => 'sarahjanegrande@sksu.edu.ph',
            //     'password' => Hash::make('grande123'),
            // ],
            // [
            //     'email' => 'jordanguiamadin@sksu.edu.ph',
            //     'password' => Hash::make('guiamadin123'),
            // ],
            // [
            //     'email' => 'josueguinsan@sksu.edu.ph',
            //     'password' => Hash::make('guinsan123'),
            // ],
            // [
            //     'email' => 'charissajoygumban@sksu.edu.ph',
            //     'password' => Hash::make('gumban123'),
            // ],
            // [
            //     'email' => 'rubyhechanova@sksu.edu.ph',
            //     'password' => Hash::make('hechanova123'),
            // ],
            // [
            //     'email' => 'virdenhechanova@sksu.edu.ph',
            //     'password' => Hash::make('hechanova123'),
            // ],
            // [
            //     'email' => 'samuelmorshilbero@sksu.edu.ph',
            //     'password' => Hash::make('hilbero123'),
            // ],
            // [
            //     'email' => 'laureenkayehuevas@sksu.edu.ph',
            //     'password' => Hash::make('huevas123'),
            // ],
            // [
            //     'email' => 'leoibot@sksu.edu.ph',
            //     'password' => Hash::make('ibot123'),
            // ],
            // [
            //     'email' => 'leonardoibot@sksu.edu.ph',
            //     'password' => Hash::make('ibot123'),
            // ],
            // [
            //     'email' => 'rosalieibot@sksu.edu.ph',
            //     'password' => Hash::make('ibot123'),
            // ],
            // [
            //     'email' => 'annielynignes@sksu.edu.ph',
            //     'password' => Hash::make('ignes123'),
            // ],
            // [
            //     'email' => 'gregorioilao@sksu.edu.ph',
            //     'password' => Hash::make('ilao123'),
            // ],
            // [
            //     'email' => 'evareafameinocente@sksu.edu.ph',
            //     'password' => Hash::make('inocente123'),
            // ],
            // [
            //     'email' => 'kristinemaeisales@sksu.edu.ph',
            //     'password' => Hash::make('isales123'),
            // ],
            // [
            //     'email' => 'nicolasjacinto@sksu.edu.ph',
            //     'password' => Hash::make('jacinto123'),
            // ],
            // [
            //     'email' => 'criseldajerez@sksu.edu.ph',
            //     'password' => Hash::make('jerez123'),
            // ],
            // [
            //     'email' => 'royjordan@sksu.edu.ph',
            //     'password' => Hash::make('jordan123'),
            // ],
            // [
            //     'email' => 'johnjuario@sksu.edu.ph',
            //     'password' => Hash::make('juario123'),
            // ],
            // [
            //     'email' => 'merrychrissekamad@sksu.edu.ph',
            //     'password' => Hash::make('kamad123'),
            // ],
            // [
            //     'email' => 'korriekasim@sksu.edu.ph',
            //     'password' => Hash::make('kasim123'),
            // ],
            // [
            //     'email' => 'rickkasim@sksu.edu.ph',
            //     'password' => Hash::make('kasim123'),
            // ],
            // [
            //     'email' => 'fahmiyakirab@sksu.edu.ph',
            //     'password' => Hash::make('kirab123'),
            // ],
            // [
            //     'email' => 'gisellelademora@sksu.edu.ph',
            //     'password' => Hash::make('lademora123'),
            // ],
            // [
            //     'email' => 'charmielagdamen@sksu.edu.ph',
            //     'password' => Hash::make('lagdamen123'),
            // ],
            // [
            //     'email' => 'joselagdamen@sksu.edu.ph',
            //     'password' => Hash::make('lagdamen123'),
            // ],
            // [
            //     'email' => 'jorgelaguda@sksu.edu.ph',
            //     'password' => Hash::make('laguda123'),
            // ],
            // [
            //     'email' => 'rommellagumen@sksu.edu.ph',
            //     'password' => Hash::make('lagumen123'),
            // ],
            // [
            //     'email' => 'meredylandero@sksu.edu.ph',
            //     'password' => Hash::make('landero123'),
            // ],
            // [
            //     'email' => 'victorinolaviste@sksu.edu.ph',
            //     'password' => Hash::make('laviste123'),
            // ],
            // [
            //     'email' => 'rubylegarde@sksu.edu.ph',
            //     'password' => Hash::make('legarde123'),
            // ],
            // [
            //     'email' => 'eduardolequigan@sksu.edu.ph',
            //     'password' => Hash::make('lequigan123'),
            // ],
            // [
            //     'email' => 'dexterleysa@sksu.edu.ph',
            //     'password' => Hash::make('leysa123'),
            // ],
            // [
            //     'email' => 'merlynleysa@sksu.edu.ph',
            //     'password' => Hash::make('leysa123'),
            // ],
            // [
            //     'email' => 'lovelynllanillo@sksu.edu.ph',
            //     'password' => Hash::make('llanillo123'),
            // ],
            // [
            //     'email' => 'maryjaneloquis@sksu.edu.ph',
            //     'password' => Hash::make('loquis123'),
            // ],
            // [
            //     'email' => 'susanlosanes@sksu.edu.ph',
            //     'password' => Hash::make('losanes123'),
            // ],
            // [
            //     'email' => 'erlindaluceno@sksu.edu.ph',
            //     'password' => Hash::make('luceno123'),
            // ],
            // [
            //     'email' => 'rizzalumangco@sksu.edu.ph',
            //     'password' => Hash::make('lumangco123'),
            // ],
            // [
            //     'email' => 'jenevievelumbuan@sksu.edu.ph',
            //     'password' => Hash::make('lumbuan123'),
            // ],
            // [
            //     'email' => 'joycellelumogdang@sksu.edu.ph',
            //     'password' => Hash::make('lumogdang123'),
            // ],
            // [
            //     'email' => 'manshurluna@sksu.edu.ph',
            //     'password' => Hash::make('luna123'),
            // ],
            // [
            //     'email' => 'toninaluna@sksu.edu.ph',
            //     'password' => Hash::make('luna123'),
            // ],
            // [
            //     'email' => 'merlindamacasayon@sksu.edu.ph',
            //     'password' => Hash::make('macasayon123'),
            // ],
            // [
            //     'email' => 'judithmachan@sksu.edu.ph',
            //     'password' => Hash::make('machan123'),
            // ],
            // [
            //     'email' => 'ivylynnmadriaga@sksu.edu.ph',
            //     'password' => Hash::make('madriaga123'),
            // ],
            // [
            //     'email' => 'alvinmagbanua@sksu.edu.ph',
            //     'password' => Hash::make('magbanua123'),
            // ],
            // [
            //     'email' => 'cherryloumagbanua@sksu.edu.ph',
            //     'password' => Hash::make('magbanua123'),
            // ],
            // [
            //     'email' => 'marylynnmagbanua@sksu.edu.ph',
            //     'password' => Hash::make('magbanua123'),
            // ],
            // [
            //     'email' => 'charliemaghanoy@sksu.edu.ph',
            //     'password' => Hash::make('maghanoy123'),
            // ],
            // [
            //     'email' => 'allanmaglantay@sksu.edu.ph',
            //     'password' => Hash::make('maglantay123'),
            // ],
            // [
            //     'email' => 'marygracemaglantay@sksu.edu.ph',
            //     'password' => Hash::make('maglantay123'),
            // ],
            // [
            //     'email' => 'dumamakalilay@sksu.edu.ph',
            //     'password' => Hash::make('makalilay 123'),
            // ],
            // [
            //     'email' => 'ameramalaco@sksu.edu.ph',
            //     'password' => Hash::make('malaco123'),
            // ],
            // [
            //     'email' => 'josephinemalinog@sksu.edu.ph',
            //     'password' => Hash::make('malinog123'),
            // ],
            // [
            //     'email' => 'norminamamalinta@sksu.edu.ph',
            //     'password' => Hash::make('mamalinta123'),
            // ],
            // [
            //     'email' => 'gracielaloumanaay@sksu.edu.ph',
            //     'password' => Hash::make('manaay123'),
            // ],
            // [
            //     'email' => 'reshneymanangan@sksu.edu.ph',
            //     'password' => Hash::make('manangan123'),
            // ],
            // [
            //     'email' => 'anesamangindra@sksu.edu.ph',
            //     'password' => Hash::make('mangindra123'),
            // ],
            // [
            //     'email' => 'annarosemarcelino@sksu.edu.ph',
            //     'password' => Hash::make('marcelino123'),
            // ],
            // [
            //     'email' => 'junitomarcelino@sksu.edu.ph',
            //     'password' => Hash::make('marcelino123'),
            // ],
            // [
            //     'email' => 'yoryncitamarfori@sksu.edu.ph',
            //     'password' => Hash::make('marfori123'),
            // ],
            // [
            //     'email' => 'rubileemariano@sksu.edu.ph',
            //     'password' => Hash::make('mariano123'),
            // ],
            // [
            //     'email' => 'vinamariemartin@sksu.edu.ph',
            //     'password' => Hash::make('martin123'),
            // ],
            // [
            //     'email' => 'richardmasla@sksu.edu.ph',
            //     'password' => Hash::make('masla123'),
            // ],
            // [
            //     'email' => 'adelaidamatilos@sksu.edu.ph',
            //     'password' => Hash::make('matilos123'),
            // ],
            // [
            //     'email' => 'randymayo@sksu.edu.ph',
            //     'password' => Hash::make('mayo123'),
            // ],
            // [
            //     'email' => 'leilajanemendoza@sksu.edu.ph',
            //     'password' => Hash::make('mendoza123'),
            // ],
            // [
            //     'email' => 'manolomercado@sksu.edu.ph',
            //     'password' => Hash::make('mercado123'),
            // ],
            // [
            //     'email' => 'leizelmeriales@sksu.edu.ph',
            //     'password' => Hash::make('meriales123'),
            // ],
            // [
            //     'email' => 'edralinmesias@sksu.edu.ph',
            //     'password' => Hash::make('mesias123'),
            // ],
            // [
            //     'email' => 'victoriamijares@sksu.edu.ph',
            //     'password' => Hash::make('mijares123'),
            // ],
            // [
            //     'email' => 'gracejoymillendez@sksu.edu.ph',
            //     'password' => Hash::make('millendez123'),
            // ],
            // [
            //     'email' => 'marygracemolina@sksu.edu.ph',
            //     'password' => Hash::make('molina123'),
            // ],
            // [
            //     'email' => 'ziljihmolina@sksu.edu.ph',
            //     'password' => Hash::make('molina123'),
            // ],
            // [
            //     'email' => 'emymorbo@sksu.edu.ph',
            //     'password' => Hash::make('morbo123'),
            // ],
            // [
            //     'email' => 'noramoya@sksu.edu.ph',
            //     'password' => Hash::make('moya123'),
            // ],
            // [
            //     'email' => 'amelitamoyet@sksu.edu.ph',
            //     'password' => Hash::make('moyet123'),
            // ],
            // [
            //     'email' => 'paternamurillo@sksu.edu.ph',
            //     'password' => Hash::make('murillo123'),
            // ],
            // [
            //     'email' => 'nathanielnaanep@sksu.edu.ph',
            //     'password' => Hash::make('naanep123'),
            // ],
            // [
            //     'email' => 'cesarnallos@sksu.edu.ph',
            //     'password' => Hash::make('nallos123'),
            // ],
            // [
            //     'email' => 'romeonarcilla@sksu.edu.ph',
            //     'password' => Hash::make('narcilla123'),
            // ],
            // [
            //     'email' => 'ramonitonazareno@sksu.edu.ph',
            //     'password' => Hash::make('nazareno123'),
            // ],
            // [
            //     'email' => 'leonardnecesito@sksu.edu.ph',
            //     'password' => Hash::make('necesito123'),
            // ],
            // [
            //     'email' => 'dexternecor@sksu.edu.ph',
            //     'password' => Hash::make('necor123'),
            // ],
            // [
            //     'email' => 'jaimeboyngag@sksu.edu.ph',
            //     'password' => Hash::make('ngag123'),
            // ],
            // [
            //     'email' => 'celiarosenota@sksu.edu.ph',
            //     'password' => Hash::make('nota123'),
            // ],
            // [
            //     'email' => 'adrianaobena@sksu.edu.ph',
            //     'password' => Hash::make('obena123'),
            // ],
            // [
            //     'email' => 'chonaomega@sksu.edu.ph',
            //     'password' => Hash::make('omega123'),
            // ],
            // [
            //     'email' => 'rosalindaona@sksu.edu.ph',
            //     'password' => Hash::make('ona123'),
            // ],
            // [
            //     'email' => 'paulryanonas@sksu.edu.ph',
            //     'password' => Hash::make('onas123'),
            // ],
            // [
            //     'email' => 'juareynondoy@sksu.edu.ph',
            //     'password' => Hash::make('ondoy123'),
            // ],
            // [
            //     'email' => 'markonia@sksu.edu.ph',
            //     'password' => Hash::make('onia123'),
            // ],
            // [
            //     'email' => 'ianmarkorcajada@sksu.edu.ph',
            //     'password' => Hash::make('orcajada123'),
            // ],
            // [
            //     'email' => 'charityoria@sksu.edu.ph',
            //     'password' => Hash::make('oria123'),
            // ],
            // [
            //     'email' => 'jecylleoriana@sksu.edu.ph',
            //     'password' => Hash::make('oriana123'),
            // ],
            // [
            //     'email' => 'jesusaortuoste@sksu.edu.ph',
            //     'password' => Hash::make('ortuoste123'),
            // ],
            // [
            //     'email' => 'romualdoortuoste@sksu.edu.ph',
            //     'password' => Hash::make('ortuoste123'),
            // ],
            // [
            //     'email' => 'allanreypaculanan@sksu.edu.ph',
            //     'password' => Hash::make('paculanan123'),
            // ],
            // [
            //     'email' => 'amypadernal@sksu.edu.ph',
            //     'password' => Hash::make('padernal123'),
            // ],
            // [
            //     'email' => 'merlepadilla@sksu.edu.ph',
            //     'password' => Hash::make('padilla123'),
            // ],
            // [
            //     'email' => 'artchiepadios@sksu.edu.ph',
            //     'password' => Hash::make('padios123'),
            // ],
            // [
            //     'email' => 'elmerpahm@sksu.edu.ph',
            //     'password' => Hash::make('pahm123'),
            // ],
            // [
            //     'email' => 'veronicapahm@sksu.edu.ph',
            //     'password' => Hash::make('pahm123'),
            // ],
            // [
            //     'email' => 'salvadorpal@sksu.edu.ph',
            //     'password' => Hash::make('pal123'),
            // ],
            // [
            //     'email' => 'lywelynpalanog@sksu.edu.ph',
            //     'password' => Hash::make('palanog123'),
            // ],
            // [
            //     'email' => 'rogeliopalanog@sksu.edu.ph',
            //     'password' => Hash::make('palanog123'),
            // ],
            // [
            //     'email' => 'jezylpalapos@sksu.edu.ph',
            //     'password' => Hash::make('palapos123'),
            // ],
            // [
            //     'email' => 'anjannetepallarcon@sksu.edu.ph',
            //     'password' => Hash::make('pallarcon123'),
            // ],
            // [
            //     'email' => 'cynthiapama@sksu.edu.ph',
            //     'password' => Hash::make('pama123'),
            // ],
            // [
            //     'email' => 'percilapanagdato@sksu.edu.ph',
            //     'password' => Hash::make('panagdato123'),
            // ],
            // [
            //     'email' => 'norhatapanday@sksu.edu.ph',
            //     'password' => Hash::make('panday123'),
            // ],
            // [
            //     'email' => 'irilpanes@sksu.edu.ph',
            //     'password' => Hash::make('panes123'),
            // ],
            // [
            //     'email' => 'badupanimbang@sksu.edu.ph',
            //     'password' => Hash::make('panimbang123'),
            // ],
            // [
            //     'email' => 'sunshineparaico@sksu.edu.ph',
            //     'password' => Hash::make('paraico123'),
            // ],
            // [
            //     'email' => 'emersonparcon@sksu.edu.ph',
            //     'password' => Hash::make('parcon123'),
            // ],
            // [
            //     'email' => 'marygracepasquin@sksu.edu.ph',
            //     'password' => Hash::make('pasquin123'),
            // ],
            // [
            //     'email' => 'dolorcitapauya@sksu.edu.ph',
            //     'password' => Hash::make('pauya123'),
            // ],
            // [
            //     'email' => 'cristelamariepelarco@sksu.edu.ph',
            //     'password' => Hash::make('pelarco123'),
            // ],
            // [
            //     'email' => 'marilouperez@sksu.edu.ph',
            //     'password' => Hash::make('perez123'),
            // ],
            // [
            //     'email' => 'jonalynperfecio@sksu.edu.ph',
            //     'password' => Hash::make('perfecio123'),
            // ],
            // [
            //     'email' => 'marygraceperocho@sksu.edu.ph',
            //     'password' => Hash::make('perocho123'),
            // ],
            // [
            //     'email' => 'richardpimentel@sksu.edu.ph',
            //     'password' => Hash::make('pimentel123'),
            // ],
            // [
            //     'email' => 'evapolo@sksu.edu.ph',
            //     'password' => Hash::make('polo123'),
            // ],
            // [
            //     'email' => 'joemariepono@sksu.edu.ph',
            //     'password' => Hash::make('pono123'),
            // ],
            // [
            //     'email' => 'eufemiaporque@sksu.edu.ph',
            //     'password' => Hash::make('porque123'),
            // ],
            // [
            //     'email' => 'edmarlynporras@sksu.edu.ph',
            //     'password' => Hash::make('porras123'),
            // ],
            // [
            //     'email' => 'adrianprotacio@sksu.edu.ph',
            //     'password' => Hash::make('protacio123'),
            // ],
            // [
            //     'email' => 'virginiapublico@sksu.edu.ph',
            //     'password' => Hash::make('publico123'),
            // ],
            // [
            //     'email' => 'cherylpueblo@sksu.edu.ph',
            //     'password' => Hash::make('pueblo123'),
            // ],
            // [
            //     'email' => 'edenquilla@sksu.edu.ph',
            //     'password' => Hash::make('quilla123'),
            // ],
            // [
            //     'email' => 'geraldinequillo@sksu.edu.ph',
            //     'password' => Hash::make('quillo123'),
            // ],
            // [
            //     'email' => 'janetrabut@sksu.edu.ph',
            //     'password' => Hash::make('rabut123'),
            // ],
            // [
            //     'email' => 'cyrusrael@sksu.edu.ph',
            //     'password' => Hash::make('rael123'),
            // ],
            // [
            //     'email' => 'avelinohermanramos@sksu.edu.ph',
            //     'password' => Hash::make('ramos123'),
            // ],
            // [
            //     'email' => 'brandorazon@sksu.edu.ph',
            //     'password' => Hash::make('razon123'),
            // ],
            // [
            //     'email' => 'maerebugio@sksu.edu.ph',
            //     'password' => Hash::make('rebugio123'),
            // ],
            // [
            //     'email' => 'alexremegio@sksu.edu.ph',
            //     'password' => Hash::make('remegio123'),
            // ],
            // [
            //     'email' => 'florlynmaeremegio @sksu.edu.ph',
            //     'password' => Hash::make('remegio123'),
            // ],
            // [
            //     'email' => 'bernardrendon@sksu.edu.ph',
            //     'password' => Hash::make('rendon123'),
            // ],
            // [
            //     'email' => 'andresreyes@sksu.edu.ph',
            //     'password' => Hash::make('reyes123'),
            // ],
            // [
            //     'email' => 'marianiereyes@sksu.edu.ph',
            //     'password' => Hash::make('reyes123'),
            // ],
            // [
            //     'email' => 'carlosrivera@sksu.edu.ph',
            //     'password' => Hash::make('rivera123'),
            // ],
            // [
            //     'email' => 'jonathanroque@sksu.edu.ph',
            //     'password' => Hash::make('roque123'),
            // ],
            // [
            //     'email' => 'cerilorubin@sksu.edu.ph',
            //     'password' => Hash::make('rubin123'),
            // ],
            // [
            //     'email' => 'maribethsalaban@sksu.edu.ph',
            //     'password' => Hash::make('salaban123'),
            // ],
            // [
            //     'email' => 'mickledansaladino@sksu.edu.ph',
            //     'password' => Hash::make('saladino123'),
            // ],
            // [
            //     'email' => 'carilynsalaniomartin@sksu.edu.ph',
            //     'password' => Hash::make('salaniomartin123'),
            // ],
            // [
            //     'email' => 'janmichaelsaldicaya@sksu.edu.ph',
            //     'password' => Hash::make('saldicaya123'),
            // ],
            // [
            //     'email' => 'fahadsalendab@sksu.edu.ph',
            //     'password' => Hash::make('salendab123'),
            // ],
            // [
            //     'email' => 'samuelsalvador@sksu.edu.ph',
            //     'password' => Hash::make('salvador123'),
            // ],
            // [
            //     'email' => 'aineegracesansano@sksu.edu.ph',
            //     'password' => Hash::make('sansano123'),
            // ],
            // [
            //     'email' => 'denafelsarana@sksu.edu.ph',
            //     'password' => Hash::make('sarana123'),
            // ],
            // [
            //     'email' => 'roselasazon@sksu.edu.ph',
            //     'password' => Hash::make('sazon123'),
            // ],
            // [
            //     'email' => 'joeselayro@sksu.edu.ph',
            //     'password' => Hash::make('selayro123'),
            // ],
            // [
            //     'email' => 'arnulfosolinap@sksu.edu.ph',
            //     'password' => Hash::make('solinap123'),
            // ],
            // [
            //     'email' => 'rodolfosolomon@sksu.edu.ph',
            //     'password' => Hash::make('solomon123'),
            // ],
            // [
            //     'email' => 'marilousombria@sksu.edu.ph',
            //     'password' => Hash::make('sombria123'),
            // ],
            // [
            //     'email' => 'jaysonsuhayon@sksu.edu.ph',
            //     'password' => Hash::make('suhayon123'),
            // ],
            // [
            //     'email' => 'hazelmaesulaiman@sksu.edu.ph',
            //     'password' => Hash::make('sulaiman123'),
            // ],
            // [
            //     'email' => 'shaidasumapal@sksu.edu.ph',
            //     'password' => Hash::make('sumapal123'),
            // ],
            // [
            //     'email' => 'ginarosetrexiannesy@sksu.edu.ph',
            //     'password' => Hash::make('sy123'),
            // ],
            // [
            //     'email' => 'maynectarcyrilltabares@sksu.edu.ph',
            //     'password' => Hash::make('tabares123'),
            // ],
            // [
            //     'email' => 'rosemarytabingo@sksu.edu.ph',
            //     'password' => Hash::make('tabingo123'),
            // ],
            // [
            //     'email' => 'kendatutago@sksu.edu.ph',
            //     'password' => Hash::make('tago123'),
            // ],
            // [
            //     'email' => 'karentalidong@sksu.edu.ph',
            //     'password' => Hash::make('talidong123'),
            // ],
            // [
            //     'email' => 'glenntalua@sksu.edu.ph',
            //     'password' => Hash::make('talua123'),
            // ],
            // [
            //     'email' => 'mohaidatamama@sksu.edu.ph',
            //     'password' => Hash::make('tamama123'),
            // ],
            // [
            //     'email' => 'doreentampus@sksu.edu.ph',
            //     'password' => Hash::make('tampus123'),
            // ],
            // [
            //     'email' => 'mayflortapot@sksu.edu.ph',
            //     'password' => Hash::make('tapot123'),
            // ],
            // [
            //     'email' => 'judytiana@sksu.edu.ph',
            //     'password' => Hash::make('tiana123'),
            // ],
            // [
            //     'email' => 'richardtoledo@sksu.edu.ph',
            //     'password' => Hash::make('toledo123'),
            // ],
            // [
            //     'email' => 'rosevinatutor@sksu.edu.ph',
            //     'password' => Hash::make('tutor123'),
            // ],
            // [
            //     'email' => 'ernestoumipig@sksu.edu.ph',
            //     'password' => Hash::make('umipig123'),
            // ],
            // [
            //     'email' => 'mohammadisausman@sksu.edu.ph',
            //     'password' => Hash::make('usman123'),
            // ],
            // [
            //     'email' => 'annierahusop@sksu.edu.ph',
            //     'password' => Hash::make('usop123'),
            // ],
            // [
            //     'email' => 'anamarievaldez@sksu.edu.ph',
            //     'password' => Hash::make('valdez123'),
            // ],
            // [
            //     'email' => 'dennisvaldez@sksu.edu.ph',
            //     'password' => Hash::make('valdez123'),
            // ],
            // [
            //     'email' => 'nevelasco@sksu.edu.ph',
            //     'password' => Hash::make('velasco123'),
            // ],
            // [
            //     'email' => 'cherryvanessaventura@sksu.edu.ph',
            //     'password' => Hash::make('ventura123'),
            // ],
            // [
            //     'email' => 'noelvillanueva@sksu.edu.ph',
            //     'password' => Hash::make('villanueva123'),
            // ],
            // [
            //     'email' => 'hubaidamamalinta@sksu.edu.ph',
            //     'password' => Hash::make('mamalinta123'),
            // ],
            // [
            //     'email' => 'johnrexnaceda@sksu.edu.ph',
            //     'password' => Hash::make('naceda123'),
            // ],
            // [
            //     'email' => 'geraldrebamonte@sksu.edu.ph',
            //     'password' => Hash::make('rebamonte123'),
            // ],
            // [
            //     'email' => 'joanmnieveras0613@gmail.com',
            //     'password' => Hash::make('nieveras123'),
            // ],
            // [
            //     'email' => 'gicawalo@gmail.com',
            //     'password' => Hash::make('gab123'),
            // ],
            // [
            //     'email' => 'sksuadmin@admin.com',
            //     'password' => Hash::make('superadmin123'),
            // ],
        ]);

        DB::table('employee_information')->insert([
            'full_name' => 'CHRISTINE P. ABO',
            'first_name' => 'CHRISTINE',
            'last_name' => 'ABO',
            'user_id' => 1,
            'role_id' => 2,
            'office_id' => 6,
            'position_id' => 13,
        ]);

        DB::table('employee_information')->insert([
            'full_name' => 'JULIE E. ALBANO',
            'first_name' => 'JULIE',
            'last_name' => 'ALBANO',
            'user_id' => 2,
            'role_id' => 2,
            'office_id' => 7,
            'position_id' => 13,
        ]);

        DB::table('employee_information')->insert([
            'full_name' => 'LYNETTE G. PENIERO',
            'first_name' => 'LYNETTE',
            'last_name' => 'PENIERO',
            'user_id' => 3,
            'role_id' => 2,
            'office_id' => 9,
            'position_id' => 14,
        ]);

        DB::table('employee_information')->insert([
            'full_name' => 'HELEN M. ESPARTERO',
            'first_name' => 'HELEN',
            'last_name' => 'ESPARTERO',
            'user_id' => 4,
            'role_id' => 2,
            'office_id' => 10,
            'position_id' => 12,
        ]);

        DB::table('employee_information')->insert([
            'full_name' => 'RODELYN M. DALAYAP',
            'first_name' => 'RODELYN',
            'last_name' => 'DALAYAP',
            'user_id' => 5,
            'role_id' => 2,
            'office_id' => 11,
            'position_id' => 12,
        ]);

        DB::table('employee_information')->insert([
            'full_name' => 'SIONY S. BRUNIO',
            'first_name' => 'SIONY',
            'last_name' => 'BRUNIO',
            'user_id' => 6,
            'role_id' => 2,
            'office_id' => 12,
            'position_id' => 12,
        ]);

        DB::table('employee_information')->insert([
            'full_name' => 'ROSITA T. RIZALDO',
            'first_name' => 'ROSITA',
            'last_name' => 'RIZALDO',
            'user_id' => 7,
            'role_id' => 2,
            'office_id' => 13,
            'position_id' => 12,
        ]);

        DB::table('employee_information')->insert([
            'full_name' => 'DENNIS M. MERIALES',
            'first_name' => 'DENNIS',
            'last_name' => 'MERIALES',
            'user_id' => 8,
            'role_id' => 2,
            'office_id' => 14,
            'position_id' => 12,
        ]);

        DB::table('employee_information')->insert([
            'full_name' => 'NORAISA K. YASIN',
            'first_name' => 'NORAISA',
            'last_name' => 'YASIN',
            'user_id' => 9,
            'role_id' => 2,
            'office_id' => 15,
            'position_id' => 12,
        ]);

        DB::table('employee_information')->insert([
            'full_name' => 'LANI B. ALCON',
            'first_name' => 'LANI',
            'last_name' => 'ALCON',
            'user_id' => 10,
            'role_id' => 2,
            'office_id' => 16,
            'position_id' => 12,
        ]);

        DB::table('employee_information')->insert([
            'full_name' => 'REBECCA D. SUBILLAGA',
            'first_name' => 'REBECCA',
            'last_name' => 'SUBILLAGA',
            'user_id' => 11,
            'role_id' => 2,
            'office_id' => 17,
            'position_id' => 12,
        ]);

        DB::table('employee_information')->insert([
            'full_name' => 'SUSIE D. DAZA',
            'first_name' => 'SUSIE',
            'last_name' => 'DAZA',
            'user_id' => 12,
            'role_id' => 2,
            'office_id' => 18,
            'position_id' => 12,
        ]);

        DB::table('employee_information')->insert([
            'full_name' => 'LEONCIO B. DULIN',
            'first_name' => 'LEONCIO',
            'last_name' => 'DULIN',
            'user_id' => 13,
            'role_id' => 2,
            'office_id' => 19,
            'position_id' => 12,
        ]);

        DB::table('employee_information')->insert([
            'full_name' => 'BENEDICT A. RABUT',
            'first_name' => 'BENEDICT',
            'last_name' => 'RABUT',
            'user_id' => 14,
            'role_id' => 2,
            'office_id' => 20,
            'position_id' => 12,
        ]);

        DB::table('employee_information')->insert([
            'full_name' => 'KRISTINE MAE H. AMPAS',
            'first_name' => 'KRISTINE MAE',
            'last_name' => 'AMPAS',
            'user_id' => 15,
            'role_id' => 2,
            'office_id' => 21,
            'position_id' => 15,
        ]);

        DB::table('employee_information')->insert([
            'full_name' => 'ALNE D. QUINOVEBA',
            'first_name' => 'ALNE',
            'last_name' => 'QUINOVEBA',
            'user_id' => 16,
            'role_id' => 2,
            'office_id' => 22,
            'position_id' => 12,
        ]);

        DB::table('employee_information')->insert([
            'full_name' => 'Lilibeth B. EDAÑO',
            'first_name' => 'Lilibeth',
            'last_name' => 'EDAÑO',
            'user_id' => 17,
            'role_id' => 2,
            'office_id' => 23,
            'position_id' => 12,
        ]);

        DB::table('employee_information')->insert([
            'full_name' => 'VIVENCIO L. CALIXTRO, JR.',
            'first_name' => 'VIVENCIO',
            'last_name' => 'CALIXTRO',
            'user_id' => 18,
            'role_id' => 2,
            'office_id' => 24,
            'position_id' => 12,
        ]);

        DB::table('employee_information')->insert([
            'full_name' => 'JESHER Y. PALOMARIA',
            'first_name' => 'JESHER',
            'last_name' => 'PALOMARIA',
            'user_id' => 19,
            'role_id' => 4,
            'office_id' => 25,
            'position_id' => 12,
        ]);

        DB::table('employee_information')->insert([
            'full_name' => 'REY S. FUENTEBILLA',
            'first_name' => 'REY',
            'last_name' => 'FUENTEBILLA',
            'user_id' => 20,
            'role_id' => 2,
            'office_id' => 26,
            'position_id' => 12,
        ]);

        DB::table('employee_information')->insert([
            'full_name' => 'ANNERILL R. LORIO',
            'first_name' => 'ANNERILL',
            'last_name' => 'LORIO',
            'user_id' => 21,
            'role_id' => 2,
            'office_id' => 27,
            'position_id' => 12,
        ]);

        DB::table('employee_information')->insert([
            'full_name' => 'MARY JEAN S. FALSARIO',
            'first_name' => 'MARY JEAN',
            'last_name' => 'FALSARIO',
            'user_id' => 22,
            'role_id' => 2,
            'office_id' => 28,
            'position_id' => 12,
        ]);

        DB::table('employee_information')->insert([
            'full_name' => 'AMIRA MAE C. GUMANOY',
            'first_name' => 'AMIRA MAE ',
            'last_name' => 'GUMANOY',
            'user_id' => 23,
            'role_id' => 2,
            'office_id' => 29,
            'position_id' => 12,
        ]);

        DB::table('employee_information')->insert([
            'full_name' => 'HASSANAL P. ABUSAMA',
            'first_name' => 'HASSANAL',
            'last_name' => 'ABUSAMA',
            'user_id' => 24,
            'role_id' => 2,
            'office_id' => 30,
            'position_id' => 12,
        ]);

        DB::table('employee_information')->insert([
            'full_name' => 'GRACE R. PEDROLA',
            'first_name' => 'GRACE',
            'last_name' => 'PEDROLA',
            'user_id' => 25,
            'role_id' => 2,
            'office_id' => 31,
            'position_id' => 12,
        ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ENGR. NESTOR ALCON',
        //     'first_name' => 'NESTOR',
        //     'last_name' => 'ALCON',
        //     'user_id' => 26,
        //     'role_id' => 2,
        //     'office_id' => 32,
        //     'position_id' => 12,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'CYRIL JOHN A. DOMINGO',
        //     'first_name' => 'CYRIL JOHN',
        //     'last_name' => 'DOMINGO',
        //     'user_id' => 27,
        //     'role_id' => 2,
        //     'office_id' => 55,
        //     'position_id' => 12,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MITOS D. DELCO',
        //     'first_name' => 'MITOS',
        //     'last_name' => 'DELCO',
        //     'user_id' => 28,
        //     'role_id' => 2,
        //     'office_id' => 33,
        //     'position_id' => 12,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'GEMMA CONSTANTINO',
        //     'first_name' => 'GEMMA',
        //     'last_name' => 'CONSTANTINO',
        //     'user_id' => 29,
        //     'role_id' => 2,
        //     'office_id' => 34,
        //     'position_id' => 16,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MARITES B. JAVA',
        //     'first_name' => 'MARITES',
        //     'last_name' => 'JAVA',
        //     'user_id' => 30,
        //     'role_id' => 2,
        //     'office_id' => 35,
        //     'position_id' => 12,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'VIOLETA T. PICO',
        //     'first_name' => 'VIOLETA',
        //     'last_name' => 'PICO',
        //     'user_id' => 31,
        //     'role_id' => 2,
        //     'office_id' => 56,
        //     'position_id' => 17,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MARISSA C. HITALIA',
        //     'first_name' => 'MARISSA',
        //     'last_name' => 'HITALIA',
        //     'user_id' => 32,
        //     'role_id' => 2,
        //     'office_id' => 36,
        //     'position_id' => 18,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ATTY. LENNIE ANN C. CERDANA',
        //     'first_name' => 'LENNIE ANN',
        //     'last_name' => 'CERDANA',
        //     'user_id' => 33,
        //     'role_id' => 2,
        //     'office_id' => 37,
        //     'position_id' => 18,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'NANCY D. ESPACIO',
        //     'first_name' => 'NANCY',
        //     'last_name' => 'ESPACIO',
        //     'user_id' => 34,
        //     'role_id' => 2,
        //     'office_id' => 38,
        //     'position_id' => 18,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'CARMELA CAMILA B. URBANO',
        //     'first_name' => 'CARMELA CAMILA',
        //     'last_name' => 'URBANO',
        //     'user_id' => 35,
        //     'role_id' => 2,
        //     'office_id' => 39,
        //     'position_id' => 18,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'EDWIN CORTEJO',
        //     'first_name' => 'EDWIN',
        //     'last_name' => 'CORTEJO',
        //     'user_id' => 36,
        //     'role_id' => 2,
        //     'office_id' => 40,
        //     'position_id' => 18,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MA. JEANELLE B. ARGONZA',
        //     'first_name' => 'MA. JEANELLE',
        //     'last_name' => 'ARGONZA',
        //     'user_id' => 37,
        //     'role_id' => 2,
        //     'office_id' => 41,
        //     'position_id' => 18,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JEANNIE A. ROMANO',
        //     'first_name' => 'JEANNIE',
        //     'last_name' => 'ROMANO',
        //     'user_id' => 38,
        //     'role_id' => 2,
        //     'office_id' => 42,
        //     'position_id' => 18,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'CECILIA E. GENER',
        //     'first_name' => 'CECILIA',
        //     'last_name' => 'GENER',
        //     'user_id' => 39,
        //     'role_id' => 2,
        //     'office_id' => 43,
        //     'position_id' => 18,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'RANDE T. BERINA',
        //     'first_name' => 'RANDE',
        //     'last_name' => 'BERINA',
        //     'user_id' => 40,
        //     'role_id' => 2,
        //     'office_id' => 44,
        //     'position_id' => 18,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MEILAFLOR A. PACLIBAR',
        //     'first_name' => 'MEILAFLOR',
        //     'last_name' => 'PACLIBAR',
        //     'user_id' => 41,
        //     'role_id' => 2,
        //     'office_id' => 45,
        //     'position_id' => 18,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'EULOGIO L. APELLIDO, JR.',
        //     'first_name' => 'EULOGIO',
        //     'last_name' => 'APELLIDO',
        //     'user_id' => 42,
        //     'role_id' => 2,
        //     'office_id' => 46,
        //     'position_id' => 19,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ADONIS S. BESA',
        //     'first_name' => 'ADONIS',
        //     'last_name' => 'BESA',
        //     'user_id' => 43,
        //     'role_id' => 2,
        //     'office_id' => 47,
        //     'position_id' => 19,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'DESIREE G. LEGASPI',
        //     'first_name' => 'DESIREE',
        //     'last_name' => 'LEGASPI',
        //     'user_id' => 44,
        //     'role_id' => 2,
        //     'office_id' => 5,
        //     'position_id' => 11,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'REGINA L. LACAMENTO',
        //     'first_name' => 'REGINA',
        //     'last_name' => 'LACAMENTO',
        //     'user_id' => 45,
        //     'role_id' => 2,
        //     'office_id' => 5,
        //     'position_id' => 11,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'LOVINA P. COGOLLO',
        //     'first_name' => 'LOVINA',
        //     'last_name' => 'COGOLLO',
        //     'user_id' => 46,
        //     'role_id' => 2,
        //     'office_id' => 49,
        //     'position_id' => 15,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JAY BADILLA',
        //     'first_name' => 'JAY',
        //     'last_name' => 'BADILLA',
        //     'user_id' => 47,
        //     'role_id' => 5,
        //     'office_id' => 2,
        //     'position_id' => 20,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JEREMY ORIANA',
        //     'first_name' => 'JEREMY',
        //     'last_name' => 'ORIANA',
        //     'user_id' => 48,
        //     'role_id' => 5,
        //     'office_id' => 2,
        //     'position_id' => 20,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'RIZA MAE D. KUSAIN',
        //     'first_name' => 'RIZA MAE',
        //     'last_name' => 'KUSAIN',
        //     'user_id' => 49,
        //     'role_id' => 5,
        //     'office_id' => 2,
        //     'position_id' => 20,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'REYNALDO H. DALAYAP JR.',
        //     'first_name' => 'REYNALDO',
        //     'last_name' => 'DALAYAP',
        //     'user_id' => 50,
        //     'role_id' => 5,
        //     'office_id' => 8,
        //     'position_id' => 13,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'KAYE ANN B. ACAYLAR',
        //     'first_name' => 'KAYE ANN',
        //     'last_name' => 'ACAYLAR',
        //     'user_id' => 51,
        //     'role_id' => 4,
        //     'office_id' => 3,
        //     'position_id' => 22,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MARILOU TORRECAMPO',
        //     'first_name' => 'MARILOU',
        //     'last_name' => 'TORRECAMPO',
        //     'user_id' => 52,
        //     'role_id' => 4,
        //     'office_id' => 3,
        //     'position_id' => 22,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'CATHERINE LEGASPI',
        //     'first_name' => 'CATHERINE',
        //     'last_name' => 'LEGASPI',
        //     'user_id' => 53,
        //     'role_id' => 4,
        //     'office_id' => 3,
        //     'position_id' => 22,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'LAPUZ MAMALO',
        //     'first_name' => 'LAPUZ',
        //     'last_name' => 'MAMALO',
        //     'user_id' => 54,
        //     'role_id' => 4,
        //     'office_id' => 3,
        //     'position_id' => 22,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ARMIE JANOTO',
        //     'first_name' => 'ARMIE',
        //     'last_name' => 'JANOTO',
        //     'user_id' => 55,
        //     'role_id' => 4,
        //     'office_id' => 3,
        //     'position_id' => 22,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'KIMBERLY PANTINOPLE',
        //     'first_name' => 'KIMBERLY',
        //     'last_name' => 'PANTINOPLE',
        //     'user_id' => 56,
        //     'role_id' => 4,
        //     'office_id' => 3,
        //     'position_id' => 22,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'NICOLE MAGANTE',
        //     'first_name' => 'NICOLE',
        //     'last_name' => 'MAGANTE',
        //     'user_id' => 57,
        //     'role_id' => 4,
        //     'office_id' => 3,
        //     'position_id' => 22,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JASON DAVE TEMBLIQUE',
        //     'first_name' => 'JASON DAVE',
        //     'last_name' => 'TEMBLIQUE',
        //     'user_id' => 58,
        //     'role_id' => 4,
        //     'office_id' => 3,
        //     'position_id' => 22,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'FRETCHIE MAE BEDUA',
        //     'first_name' => 'FRETCHIE MAE',
        //     'last_name' => 'BEDUA',
        //     'user_id' => 59,
        //     'role_id' => 4,
        //     'office_id' => 3,
        //     'position_id' => 22,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JOHN REI DELA CRUZ',
        //     'first_name' => 'JOHN REI ',
        //     'last_name' => ' DELA CRUZ',
        //     'user_id' => 60,
        //     'role_id' => 4,
        //     'office_id' => 3,
        //     'position_id' => 22,
        // ]);

        // //President's Office

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ROSELEN P. DADOR',
        //     'first_name' => 'ROSELEN',
        //     'last_name' => 'DADOR',
        //     'user_id' => 61,
        //     'role_id' => 2,
        //     'office_id' => 51,
        //     'position_id' => 23,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ESTHER S. LANCETA',
        //     'first_name' => 'ESTHER',
        //     'last_name' => 'LANCETA',
        //     'user_id' => 62,
        //     'role_id' => 2,
        //     'office_id' => 51,
        //     'position_id' => 23,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'IRISH MAE PASQUIN',
        //     'first_name' => 'IRISH MAE ',
        //     'last_name' => 'PASQUIN',
        //     'user_id' => 63,
        //     'role_id' => 2,
        //     'office_id' => 51,
        //     'position_id' => 23,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'SAMSON L. MOLAO',
        //     'first_name' => 'SAMSON',
        //     'last_name' => 'MOLAO',
        //     'user_id' => 64,
        //     'role_id' => 2,
        //     'office_id' => 51,
        //     'position_id' => 5,
        // ]);

        // //Cashier's Office

        // DB::table('employee_information')->insert([
        //     'full_name' => 'TARHATA K. USMAN',
        //     'first_name' => 'TARHATA',
        //     'last_name' => 'USMAN',
        //     'user_id' => 65,
        //     'role_id' => 2,
        //     'office_id' => 52,
        //     'position_id' => 21,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'VIVIAN R. LANCETA',
        //     'first_name' => 'VIVIAN',
        //     'last_name' => 'LANCETA',
        //     'user_id' => 66,
        //     'role_id' => 2,
        //     'office_id' => 52,
        //     'position_id' => 21,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JACQUELIN BRINGUELO',
        //     'first_name' => 'JACQUELIN',
        //     'last_name' => 'BRINGUELO',
        //     'user_id' => 67,
        //     'role_id' => 2,
        //     'office_id' => 52,
        //     'position_id' => 21,
        // ]);

        // //FACULTIES

        // DB::table('employee_information')->insert([
        //     'full_name' => 'DENNIS B. ABANALES',
        //     'first_name' => 'DENNIS',
        //     'last_name' => 'ABANALES',
        //     'user_id' => 68,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'NASARUDIN I. ABAS',
        //     'first_name' => 'NASARUDIN',
        //     'last_name' => 'ABAS',
        //     'user_id' => 69,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JEEB T. ABELITO',
        //     'first_name' => 'JEEB',
        //     'last_name' => 'ABELITO',
        //     'user_id' => 70,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'SHAREN JILL M. ABELLAR	',
        //     'first_name' => 'SHAREN JILL',
        //     'last_name' => 'ABELLAR',
        //     'user_id' => 71,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ABRAHAM S. ACCAD',
        //     'first_name' => 'ABRAHAM',
        //     'last_name' => 'ACCAD',
        //     'user_id' => 72,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MILDRED F. ACCAD',
        //     'first_name' => 'MILDRED',
        //     'last_name' => 'ACCAD',
        //     'user_id' => 73,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'HIPOLITO E. ACUZAR',
        //     'first_name' => 'HIPOLITO',
        //     'last_name' => 'ACUZAR',
        //     'user_id' => 74,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'NORAINA M. AGUIL',
        //     'first_name' => 'NORAINA',
        //     'last_name' => 'AGUIL',
        //     'user_id' => 75,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'CRIZJALE V. AHMAD',
        //     'first_name' => 'CRIZJALE',
        //     'last_name' => 'AHMAD',
        //     'user_id' => 76,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'SAIDEN P. AKMAD',
        //     'first_name' => 'SAIDEN',
        //     'last_name' => 'AKMAD',
        //     'user_id' => 77,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MICHELLE C. ALBARAN',
        //     'first_name' => 'MICHELLE',
        //     'last_name' => 'ALBARAN',
        //     'user_id' => 78,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MARK ANTHONY G. ALCANTARA',
        //     'first_name' => 'MARK ANTHONY',
        //     'last_name' => 'ALCANTARA',
        //     'user_id' => 79,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'BATUTI M. ALI',
        //     'first_name' => 'BATUTI',
        //     'last_name' => 'ALI',
        //     'user_id' => 80,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MARIANO S. ALIMAJEN JR.',
        //     'first_name' => 'MARIANO',
        //     'last_name' => 'ALIMAJEN',
        //     'user_id' => 81,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MERLY M. ALIMAJEN',
        //     'first_name' => 'MERLY',
        //     'last_name' => 'ALIMAJEN',
        //     'user_id' => 82,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'GRACELYN C. ALTAYA',
        //     'first_name' => 'GRACELYN',
        //     'last_name' => 'ALTAYA',
        //     'user_id' => 83,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'CRISTOBAL M. AMBAYON',
        //     'first_name' => 'CRISTOBAL',
        //     'last_name' => 'AMBAYON',
        //     'user_id' => 84,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MARICEL L. AMIT',
        //     'first_name' => 'MARICEL',
        //     'last_name' => 'AMIT',
        //     'user_id' => 85,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'AURELIO E. AMPO',
        //     'first_name' => 'AURELIO',
        //     'last_name' => 'AMPO',
        //     'user_id' => 86,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'KEIVEN MARK B. AMPODE',
        //     'first_name' => 'KEIVEN MARK',
        //     'last_name' => 'AMPODE',
        //     'user_id' => 87,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ROSELYN G. ANDAMON',
        //     'first_name' => 'ROSELYN',
        //     'last_name' => 'ANDAMON',
        //     'user_id' => 88,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ALMIRA C. ANGKAL',
        //     'first_name' => 'ALMIRA',
        //     'last_name' => 'ANGKAL',
        //     'user_id' => 89,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'CRESENCIO P. ANTONIO',
        //     'first_name' => 'CRESENCIO',
        //     'last_name' => 'ANTONIO',
        //     'user_id' => 90,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ELBREN O. ANTONIO',
        //     'first_name' => 'ELBREN',
        //     'last_name' => 'ANTONIO',
        //     'user_id' => 91,
        //     'role_id' => 2,
        //     'position_id' => 18,
        //     'office_id' => 43,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'HENRISA P. APARIS',
        //     'first_name' => 'HENRISA',
        //     'last_name' => 'APARIS',
        //     'user_id' => 92,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ALEXIS D. APRESTO',
        //     'first_name' => 'ALEXIS',
        //     'last_name' => 'APRESTO',
        //     'user_id' => 93,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ZIUS D. APRESTO',
        //     'first_name' => 'ZIUS',
        //     'last_name' => 'APRESTO',
        //     'user_id' => 94,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'REYNALDO B. ARANEGO',
        //     'first_name' => 'REYNALDO',
        //     'last_name' => 'ARANEGO',
        //     'user_id' => 95,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'RAMIL M. ARCIOSA',
        //     'first_name' => 'RAMIL',
        //     'last_name' => 'ARCIOSA',
        //     'user_id' => 96,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JUDE MICHAEL L. ARELLANO',
        //     'first_name' => 'JUDE MICHAEL',
        //     'last_name' => 'ARELLANO',
        //     'user_id' => 97,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JAY MARK F. ARENDAIN',
        //     'first_name' => 'JAY MARK',
        //     'last_name' => 'ARENDAIN',
        //     'user_id' => 98,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'AMY A. ARMADA',
        //     'first_name' => 'AMY',
        //     'last_name' => 'ARMADA',
        //     'user_id' => 99,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MICHAEL C. ARRIVAS',
        //     'first_name' => 'MICHAEL',
        //     'last_name' => 'ARRIVAS',
        //     'user_id' => 100,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'LEE B. ASTROLOGO',
        //     'first_name' => 'LEE',
        //     'last_name' => 'ASTROLOGO',
        //     'user_id' => 101,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JUDITH V. ASTURIAS',
        //     'first_name' => 'JUDITH',
        //     'last_name' => 'ASTURIAS',
        //     'user_id' => 102,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'WILFREDO M. ATAYAN',
        //     'first_name' => 'WILFREDO',
        //     'last_name' => 'ATAYAN',
        //     'user_id' => 103,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JOSELYN H. BACERA',
        //     'first_name' => 'JOSELYN',
        //     'last_name' => 'BACERA',
        //     'user_id' => 104,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MAILYNE V. BACONGCO',
        //     'first_name' => 'MAILYNE',
        //     'last_name' => 'BACONGCO',
        //     'user_id' => 105,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ESNEHARA P. BAGUNDANG',
        //     'first_name' => 'ESNEHARA',
        //     'last_name' => 'BAGUNDANG',
        //     'user_id' => 106,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'RAUL H. BALACUIT',
        //     'first_name' => 'RAUL',
        //     'last_name' => 'BALACUIT',
        //     'user_id' => 107,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'NOR-EN E. BANGKULIT',
        //     'first_name' => 'NOR-EN',
        //     'last_name' => 'BANGKULIT',
        //     'user_id' => 108,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MARLON D. BANGONON',
        //     'first_name' => 'MARLON',
        //     'last_name' => 'BANGONON',
        //     'user_id' => 109,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'NASSER M. BANTUGAN',
        //     'first_name' => 'NASSER',
        //     'last_name' => 'BANTUGAN',
        //     'user_id' => 110,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'NOEL H. BARAQUIA',
        //     'first_name' => 'NOEL',
        //     'last_name' => 'BARAQUIA',
        //     'user_id' => 111,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ROSALIE S. BARROQUILLO',
        //     'first_name' => 'ROSALIE',
        //     'last_name' => 'BARROQUILLO',
        //     'user_id' => 112,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ELIZABETH S. BAUZON',
        //     'first_name' => 'ELIZABETH',
        //     'last_name' => 'BAUZON',
        //     'user_id' => 113,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'R-NEL R. BELGIRA	',
        //     'first_name' => 'R-NEL',
        //     'last_name' => 'BELGIRA',
        //     'user_id' => 114,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MICHAEL JOHN D. BENAVIDEZ',
        //     'first_name' => 'MICHAEL JOHN',
        //     'last_name' => 'BENAVIDEZ',
        //     'user_id' => 115,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'DAYANARA P. BESA',
        //     'first_name' => 'DAYANARA',
        //     'last_name' => 'BESA',
        //     'user_id' => 116,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'CANDELLEN P. BIADOMA',
        //     'first_name' => 'CANDELLEN',
        //     'last_name' => 'BIADOMA',
        //     'user_id' => 117,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ANTONIO G. BIBAT JR.',
        //     'first_name' => 'ANTONIO',
        //     'last_name' => 'BIBAT',
        //     'user_id' => 118,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'FRELIN R. BINAG',
        //     'first_name' => 'FRELIN',
        //     'last_name' => 'BINAG',
        //     'user_id' => 119,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'IRENE A. BINAG',
        //     'first_name' => 'IRENE',
        //     'last_name' => 'BINAG',
        //     'user_id' => 120,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JOEL P. BINAG',
        //     'first_name' => 'JOEL',
        //     'last_name' => 'BINAG',
        //     'user_id' => 121,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JOSE VIRSON P. BINAG',
        //     'first_name' => 'JOSE VIRSON',
        //     'last_name' => 'BINAG',
        //     'user_id' => 122,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'CHRISTOPHER B. BLASURCA',
        //     'first_name' => 'CHRISTOPHER',
        //     'last_name' => 'BLASURCA',
        //     'user_id' => 123,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'CHRISTIAN JOHN T. BOGLOSA',
        //     'first_name' => 'CHRISTIAN JOHN',
        //     'last_name' => 'BOGLOSA',
        //     'user_id' => 124,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ALMIRA A. BONIEL',
        //     'first_name' => 'ALMIRA',
        //     'last_name' => 'BONIEL',
        //     'user_id' => 125,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'SANDRA B. BONRUSTRO',
        //     'first_name' => 'SANDRA',
        //     'last_name' => 'BONRUSTRO',
        //     'user_id' => 126,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ELMER C. BUENAVIDES',
        //     'first_name' => 'ELMER',
        //     'last_name' => 'BUENAVIDES',
        //     'user_id' => 127,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JADE C. BUENAVIDES',
        //     'first_name' => 'JADE',
        //     'last_name' => 'BUENAVIDES',
        //     'user_id' => 128,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'POL IAN M. BUGADOR',
        //     'first_name' => 'POL IAN',
        //     'last_name' => 'BUGADOR',
        //     'user_id' => 129,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'IVY P. BULALOC',
        //     'first_name' => 'IVY',
        //     'last_name' => 'BULALOC',
        //     'user_id' => 130,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'WILBERT A. CABANBAN',
        //     'first_name' => 'WILBERT',
        //     'last_name' => 'CABANBAN',
        //     'user_id' => 131,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'VINCENT LOUIE D. CABELIN',
        //     'first_name' => 'VINCENT LOUIE',
        //     'last_name' => 'CABELIN',
        //     'user_id' => 132,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ALLAN JAY S. CAJANDIG',
        //     'first_name' => 'ALLAN JAY',
        //     'last_name' => 'CAJANDIG',
        //     'user_id' => 133,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JUANITA S. CAJANDIG',
        //     'first_name' => 'JUANITA',
        //     'last_name' => 'CAJANDIG',
        //     'user_id' => 134,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MA LYN A. CALUB',
        //     'first_name' => 'MA LYN',
        //     'last_name' => 'CALUB',
        //     'user_id' => 135,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MICHAEL KENNEDY G. CAMARAO',
        //     'first_name' => 'MICHAEL KENNEDY',
        //     'last_name' => 'CAMARAO',
        //     'user_id' => 136,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JOHN REGNAIR M. CANDADO',
        //     'first_name' => 'JOHN REGNAIR',
        //     'last_name' => 'CANDADO',
        //     'user_id' => 137,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JACQUILINE B. CANDIDO',
        //     'first_name' => 'JACQUILINE',
        //     'last_name' => 'CANDIDO',
        //     'user_id' => 138,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JOVITA S. CARIGABA',
        //     'first_name' => 'JOVITA',
        //     'last_name' => 'CARIGABA',
        //     'user_id' => 139,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MARY JOY C. CARNAZO',
        //     'first_name' => 'MARY JOY',
        //     'last_name' => 'CARNAZO',
        //     'user_id' => 140,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'RHODINA C. CASTILLO',
        //     'first_name' => 'RHODINA',
        //     'last_name' => 'CASTILLO',
        //     'user_id' => 141,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ROMA AMOR M. CASTROMAYOR',
        //     'first_name' => 'ROMA AMOR',
        //     'last_name' => 'CASTROMAYOR',
        //     'user_id' => 142,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'LENMAR T. CATAJAY',
        //     'first_name' => 'LENMAR',
        //     'last_name' => 'CATAJAY',
        //     'user_id' => 143,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'RODEL A. CATALAN',
        //     'first_name' => 'RODEL',
        //     'last_name' => 'CATALAN',
        //     'user_id' => 144,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'KAREN L. CATANE',
        //     'first_name' => 'KAREN',
        //     'last_name' => 'CATANE',
        //     'user_id' => 145,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ARNOLD Z. CATIPAY',
        //     'first_name' => 'ARNOLD',
        //     'last_name' => 'CATIPAY',
        //     'user_id' => 146,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'KRISTINE JOY L. CATIWALAAN',
        //     'first_name' => 'KRISTINE JOY',
        //     'last_name' => 'CATIWALAAN',
        //     'user_id' => 147,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'LANI B. CEJES',
        //     'first_name' => 'LANI',
        //     'last_name' => 'CEJES',
        //     'user_id' => 148,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ARNEL Y. CELESTE',
        //     'first_name' => 'ARNEL',
        //     'last_name' => 'CELESTE',
        //     'user_id' => 149,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ERNIE C. CERADO',
        //     'first_name' => 'ERNIE',
        //     'last_name' => 'CERADO',
        //     'user_id' => 150,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'DONALD A. COGO',
        //     'first_name' => 'DONALD',
        //     'last_name' => 'COGO',
        //     'user_id' => 151,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JOVITA M. COLLADO',
        //     'first_name' => 'JOVITA',
        //     'last_name' => 'COLLADO',
        //     'user_id' => 152,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'LUZVIMINDA D. COLONG',
        //     'first_name' => 'LUZVIMINDA',
        //     'last_name' => 'COLONG',
        //     'user_id' => 153,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'NIEVES J. COMICHO',
        //     'first_name' => 'NIEVES',
        //     'last_name' => 'COMICHO',
        //     'user_id' => 154,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ELLEN L. CONSOMO',
        //     'first_name' => 'ELLEN',
        //     'last_name' => 'CONSOMO',
        //     'user_id' => 155,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'RIZALYN B. CUDERA',
        //     'first_name' => 'RIZALYN',
        //     'last_name' => 'CUDERA',
        //     'user_id' => 156,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'GINA S. CUENCA',
        //     'first_name' => 'GINA',
        //     'last_name' => 'CUENCA',
        //     'user_id' => 157,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'LEAH A. DADOLI',
        //     'first_name' => 'LEAH',
        //     'last_name' => 'DADOLI',
        //     'user_id' => 158,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'HANNAH L. DAFIELMOTO',
        //     'first_name' => 'HANNAH',
        //     'last_name' => 'DAFIELMOTO',
        //     'user_id' => 159,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'CHERYL P. DAGA AS',
        //     'first_name' => 'CHERYL',
        //     'last_name' => 'DAGA AS',
        //     'user_id' => 160,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'REMIGILDO S. DAGAMAC',
        //     'first_name' => 'REMIGILDO',
        //     'last_name' => 'DAGAMAC',
        //     'user_id' => 161,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'GERWIN G. DAGUM',
        //     'first_name' => 'GERWIN',
        //     'last_name' => 'DAGUM',
        //     'user_id' => 162,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'NURAISA S. DALIDA',
        //     'first_name' => 'NURAISA',
        //     'last_name' => 'DALIDA',
        //     'user_id' => 163,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JOHN ERNEST R. DAMANDAMAN',
        //     'first_name' => 'JOHN ERNEST',
        //     'last_name' => 'DAMANDAMAN',
        //     'user_id' => 164,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'TEFANNY V. DANIEL',
        //     'first_name' => 'TEFANNY',
        //     'last_name' => 'DANIEL',
        //     'user_id' => 165,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'YOLANDA C. DAPITAN',
        //     'first_name' => 'YOLANDA',
        //     'last_name' => 'DAPITAN',
        //     'user_id' => 166,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ARMANDO S. DARDO',
        //     'first_name' => 'ARMANDO',
        //     'last_name' => 'DARDO',
        //     'user_id' => 167,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'RENATO B. DE LA CRUZ',
        //     'first_name' => 'RENATO',
        //     'last_name' => ' DE LA CRUZ',
        //     'user_id' => 168,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'RANDE B. DECHAVEZ',
        //     'first_name' => 'RANDE',
        //     'last_name' => 'DECHAVEZ',
        //     'user_id' => 169,
        //     'role_id' => 2,
        //     'office_id' => null,
        //     'position_id' => 12,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'APRIL KRIS D. DELA CRUZ',
        //     'first_name' => 'APRIL KRIS',
        //     'last_name' => 'DELA CRUZ',
        //     'user_id' => 170,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'CRIS JOHN BRYAN C. DELA CRUZ',
        //     'first_name' => 'CRIS JOHN BRYAN',
        //     'last_name' => 'DELA CRUZ',
        //     'user_id' => 171,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MANDY A. DELFIN',
        //     'first_name' => 'MANDY',
        //     'last_name' => 'DELFIN',
        //     'user_id' => 172,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'REYNAN E. DEMAFELIZ',
        //     'first_name' => 'REYNAN',
        //     'last_name' => 'DEMAFELIZ',
        //     'user_id' => 173,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'LODIFEL C. DEYPALAN',
        //     'first_name' => 'LODIFEL',
        //     'last_name' => 'DEYPALAN',
        //     'user_id' => 174,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ELVIE V. DIAZ',
        //     'first_name' => 'ELVIE',
        //     'last_name' => 'DIAZ',
        //     'user_id' => 175,
        //     'role_id' => 2,
        //     'office_id' => null,
        //     'position_id' => 12,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'EVORA H. DIONEZA',
        //     'first_name' => 'EVORA',
        //     'last_name' => 'DIONEZA',
        //     'user_id' => 176,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'DENMARK N. DIZO',
        //     'first_name' => 'DENMARK',
        //     'last_name' => 'DIZO',
        //     'user_id' => 177,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'KYRENE L. DIZON',
        //     'first_name' => 'KYRENE',
        //     'last_name' => 'DIZON',
        //     'user_id' => 178,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JOHN C. DOMONDON',
        //     'first_name' => 'JOHN',
        //     'last_name' => 'DOMONDON',
        //     'user_id' => 179,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'VELESSA JANE B. DULIN',
        //     'first_name' => 'VELESSA JANE',
        //     'last_name' => 'DULIN',
        //     'user_id' => 180,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'LUDY R. DURAN',
        //     'first_name' => 'LUDY',
        //     'last_name' => 'DURAN',
        //     'user_id' => 181,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'RAYMOND E. EDISAN',
        //     'first_name' => 'RAYMOND',
        //     'last_name' => 'EDISAN',
        //     'user_id' => 182,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'SALLY J. EDZA',
        //     'first_name' => 'SALLY',
        //     'last_name' => 'EDZA',
        //     'user_id' => 183,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'GLORY JEAN B. EIMAN',
        //     'first_name' => 'GLORY JEAN',
        //     'last_name' => 'EIMAN',
        //     'user_id' => 184,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'REY R. EJERCITO',
        //     'first_name' => 'REY',
        //     'last_name' => 'EJERCITO',
        //     'user_id' => 185,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'GLORIA D. ENVIDIADO',
        //     'first_name' => 'GLORIA',
        //     'last_name' => 'ENVIDIADO',
        //     'user_id' => 186,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'DOMINIC A. ESCAÑO',
        //     'first_name' => 'DOMINIC',
        //     'last_name' => 'ESCAÑO',
        //     'user_id' => 187,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'LOWELL D. ESPINOSA',
        //     'first_name' => 'LOWELL',
        //     'last_name' => 'ESPINOSA',
        //     'user_id' => 188,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'SANDRA E. ESPINOSA',
        //     'first_name' => 'SANDRA',
        //     'last_name' => 'ESPINOSA',
        //     'user_id' => 189,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'NENITA V. ESTEBAN',
        //     'first_name' => 'NENITA',
        //     'last_name' => 'ESTEBAN',
        //     'user_id' => 190,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ARNOLD V. ESTRELLA',
        //     'first_name' => 'ARNOLD',
        //     'last_name' => 'ESTRELLA',
        //     'user_id' => 191,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JOSELYN M. ESTRELLAN',
        //     'first_name' => 'JOSELYN',
        //     'last_name' => 'ESTRELLAN',
        //     'user_id' => 192,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ELBERT C. ETRATA',
        //     'first_name' => 'ELBERT',
        //     'last_name' => 'ETRATA',
        //     'user_id' => 193,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'CIRILO L. EVANGELISTA',
        //     'first_name' => 'CIRILO',
        //     'last_name' => 'EVANGELISTA',
        //     'user_id' => 194,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'IVAN ROY S. EVANGELISTA',
        //     'first_name' => 'IVAN ROY',
        //     'last_name' => 'EVANGELISTA',
        //     'user_id' => 195,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JAYSON A. FALLE',
        //     'first_name' => 'JAYSON',
        //     'last_name' => 'FALLE',
        //     'user_id' => 196,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JENA MAE M. FATAGANI',
        //     'first_name' => 'JENA MAE',
        //     'last_name' => 'FATAGANI',
        //     'user_id' => 197,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'DIVINA T. FELICIANO',
        //     'first_name' => 'DIVINA',
        //     'last_name' => 'FELICIANO',
        //     'user_id' => 198,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MARVIN T. FERMASE',
        //     'first_name' => 'MARVIN',
        //     'last_name' => 'FERMASE',
        //     'user_id' => 199,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ANITA C. FLORES',
        //     'first_name' => 'ANITA',
        //     'last_name' => 'FLORES',
        //     'user_id' => 200,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'EFREN C. FLORES',
        //     'first_name' => 'EFREN',
        //     'last_name' => 'FLORES',
        //     'user_id' => 201,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ROSELYN A. FLORESCA',
        //     'first_name' => 'ROSELYN',
        //     'last_name' => 'FLORESCA',
        //     'user_id' => 202,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ANNIE D. FRANCISCO',
        //     'first_name' => 'ANNIE',
        //     'last_name' => 'FRANCISCO',
        //     'user_id' => 203,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JOSEPHINE F. FREIRES',
        //     'first_name' => 'JOSEPHINE',
        //     'last_name' => 'FREIRES',
        //     'user_id' => 204,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'CHRISTINE G. FUNA',
        //     'first_name' => 'CHRISTINE',
        //     'last_name' => 'FUNA',
        //     'user_id' => 205,
        //     'role_id' => 2,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'CORAZON N. GABATO',
        //     'first_name' => 'CORAZON',
        //     'last_name' => 'GABATO',
        //     'user_id' => 206,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MAY C. GALLANO',
        //     'first_name' => 'MAY',
        //     'last_name' => 'GALLANO',
        //     'user_id' => 207,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MARY GRACE O. GALLEGO',
        //     'first_name' => 'MARY GRACE',
        //     'last_name' => 'GALLEGO',
        //     'user_id' => 208,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ALEXES N. GALLO',
        //     'first_name' => 'ALEXES',
        //     'last_name' => 'GALLO',
        //     'user_id' => 209,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MA RHODORA R. GALLO',
        //     'first_name' => 'MA RHODORA',
        //     'last_name' => 'GALLO',
        //     'user_id' => 210,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JOYLYN S. GAMIAO',
        //     'first_name' => 'JOYLYN',
        //     'last_name' => 'GAMIAO',
        //     'user_id' => 211,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JUDITH C. GENOTA',
        //     'first_name' => 'JUDITH',
        //     'last_name' => 'GENOTA',
        //     'user_id' => 212,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JOHN LARRY G. GEONIGO',
        //     'first_name' => 'JOHN LARRY',
        //     'last_name' => 'GEONIGO',
        //     'user_id' => 213,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'FATIMA G. GOLENG',
        //     'first_name' => 'FATIMA',
        //     'last_name' => 'GOLENG',
        //     'user_id' => 214,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'OLIVE G. GOMEZ',
        //     'first_name' => 'OLIVE',
        //     'last_name' => 'GOMEZ',
        //     'user_id' => 215,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'SARAH JANE D. GRANDE',
        //     'first_name' => 'SARAH JANE',
        //     'last_name' => 'GRANDE',
        //     'user_id' => 216,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JORDAN M. GUIAMADIN',
        //     'first_name' => 'JORDAN',
        //     'last_name' => 'GUIAMADIN',
        //     'user_id' => 217,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JOSUE V. GUINSAN',
        //     'first_name' => 'JOSUE',
        //     'last_name' => 'GUINSAN',
        //     'user_id' => 218,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'CHARISSA JOY A. GUMBAN',
        //     'first_name' => 'CHARISSA JOY',
        //     'last_name' => 'GUMBAN',
        //     'user_id' => 219,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'RUBY S. HECHANOVA',
        //     'first_name' => 'RUBY',
        //     'last_name' => 'HECHANOVA',
        //     'user_id' => 220,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'VIRDEN S. HECHANOVA',
        //     'first_name' => 'VIRDEN',
        //     'last_name' => 'HECHANOVA',
        //     'user_id' => 221,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'SAMUEL MORS D. HILBERO',
        //     'first_name' => 'SAMUEL MORS',
        //     'last_name' => 'HILBERO',
        //     'user_id' => 222,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'LAUREEN KAYE C. HUEVAS',
        //     'first_name' => 'LAUREEN KAYE ',
        //     'last_name' => 'HUEVAS',
        //     'user_id' => 223,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'LEO C. IBOT',
        //     'first_name' => 'LEO',
        //     'last_name' => 'IBOT',
        //     'user_id' => 224,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'LEONARDO C. IBOT',
        //     'first_name' => 'LEONARDO',
        //     'last_name' => 'IBOT',
        //     'user_id' => 225,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ROSALIE S. IBOT',
        //     'first_name' => 'ROSALIE',
        //     'last_name' => 'IBOT',
        //     'user_id' => 226,
        //     'role_id' => 2,
        //     'position_id' => 25,
        //     'office_id' => 56,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ANNIELYN A. IGNES',
        //     'first_name' => 'ANNIELYN',
        //     'last_name' => 'IGNES',
        //     'user_id' => 227,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'GREGORIO C. ILAO',
        //     'first_name' => 'GREGORIO',
        //     'last_name' => 'ILAO',
        //     'user_id' => 228,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'EVA REA FAME V. INOCENTE',
        //     'first_name' => 'EVA REA FAME ',
        //     'last_name' => 'INOCENTE',
        //     'user_id' => 229,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'KRISTINE MAE L. ISALES',
        //     'first_name' => 'KRISTINE MAE',
        //     'last_name' => 'ISALES',
        //     'user_id' => 230,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'NICOLAS C. JACINTO',
        //     'first_name' => 'NICOLAS',
        //     'last_name' => 'JACINTO',
        //     'user_id' => 231,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'CRISELDA M. JEREZ',
        //     'first_name' => 'CRISELDA',
        //     'last_name' => 'JEREZ',
        //     'user_id' => 232,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ROY C. JORDAN',
        //     'first_name' => 'ROY',
        //     'last_name' => 'JORDAN',
        //     'user_id' => 233,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JOHN G. JUARIO',
        //     'first_name' => 'JOHN',
        //     'last_name' => 'JUARIO',
        //     'user_id' => 234,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MERRY CHRISSE C. KAMAD',
        //     'first_name' => 'MERRY CHRISSE',
        //     'last_name' => 'KAMAD',
        //     'user_id' => 235,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'KORRIE B. KASIM',
        //     'first_name' => 'KORRIE',
        //     'last_name' => 'KASIM',
        //     'user_id' => 236,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'RICK B. KASIM',
        //     'first_name' => 'RICK',
        //     'last_name' => 'KASIM',
        //     'user_id' => 237,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'FAHMIYA D. KIRAB',
        //     'first_name' => 'FAHMIYA',
        //     'last_name' => 'KIRAB',
        //     'user_id' => 238,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'GISELLE A. LADEMORA',
        //     'first_name' => 'GISELLE',
        //     'last_name' => 'LADEMORA',
        //     'user_id' => 239,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'CHARMIE A. LAGDAMEN',
        //     'first_name' => 'CHARMIE',
        //     'last_name' => 'LAGDAMEN',
        //     'user_id' => 240,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JOSE P. LAGDAMEN',
        //     'first_name' => 'JOSE',
        //     'last_name' => 'LAGDAMEN',
        //     'user_id' => 241,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JORGE L. LAGUDA',
        //     'first_name' => 'JORGE',
        //     'last_name' => 'LAGUDA',
        //     'user_id' => 242,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ROMMEL M. LAGUMEN',
        //     'first_name' => 'ROMMEL',
        //     'last_name' => 'LAGUMEN',
        //     'user_id' => 243,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MEREDY D. LANDERO',
        //     'first_name' => 'MEREDY',
        //     'last_name' => 'LANDERO',
        //     'user_id' => 244,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'VICTORINO M. LAVISTE',
        //     'first_name' => 'VICTORINO',
        //     'last_name' => 'LAVISTE',
        //     'user_id' => 245,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'RUBY M. LEGARDE',
        //     'first_name' => 'RUBY',
        //     'last_name' => 'LEGARDE',
        //     'user_id' => 246,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'EDUARDO S. LEQUIGAN',
        //     'first_name' => 'EDUARDO',
        //     'last_name' => 'LEQUIGAN',
        //     'user_id' => 247,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'DEXTER C. LEYSA',
        //     'first_name' => 'DEXTER',
        //     'last_name' => 'LEYSA',
        //     'user_id' => 248,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MERLYN P. LEYSA',
        //     'first_name' => 'MERLYN',
        //     'last_name' => 'LEYSA',
        //     'user_id' => 249,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'LOVELYN C. LLANILLO',
        //     'first_name' => 'LOVELYN',
        //     'last_name' => 'LLANILLO',
        //     'user_id' => 250,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MARY JANE D. LOQUIS',
        //     'first_name' => 'MARY JANE',
        //     'last_name' => 'LOQUIS',
        //     'user_id' => 251,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'SUSAN P. LOSAÑES',
        //     'first_name' => 'SUSAN',
        //     'last_name' => 'LOSAÑES',
        //     'user_id' => 252,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ERLINDA S. LUCEÑO',
        //     'first_name' => 'ERLINDA',
        //     'last_name' => 'LUCEÑO',
        //     'user_id' => 253,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'RIZZA G. LUMANGCO',
        //     'first_name' => 'RIZZA',
        //     'last_name' => 'LUMANGCO',
        //     'user_id' => 254,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JENEVIEVE D. LUMBU AN',
        //     'first_name' => 'JENEVIEVE',
        //     'last_name' => 'LUMBU AN',
        //     'user_id' => 255,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JOYCELLE C. LUMOGDANG',
        //     'first_name' => 'JOYCELLE',
        //     'last_name' => 'LUMOGDANG',
        //     'user_id' => 256,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MANSHUR A. LUNA',
        //     'first_name' => 'MANSHUR',
        //     'last_name' => 'LUNA',
        //     'user_id' => 257,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'TONINA M. LUNA',
        //     'first_name' => 'TONINA',
        //     'last_name' => 'LUNA',
        //     'user_id' => 258,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MERLINDA B. MACASAYON',
        //     'first_name' => 'MERLINDA',
        //     'last_name' => 'MACASAYON',
        //     'user_id' => 259,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JUDITH A. MACHAN',
        //     'first_name' => 'JUDITH',
        //     'last_name' => 'MACHAN',
        //     'user_id' => 260,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'IVY LYNN F. MADRIAGA',
        //     'first_name' => 'IVY LYNN',
        //     'last_name' => 'MADRIAGA',
        //     'user_id' => 261,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ALVIN E. MAGBANUA',
        //     'first_name' => 'ALVIN',
        //     'last_name' => 'MAGBANUA',
        //     'user_id' => 262,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'CHERRYLOU B. MAGBANUA',
        //     'first_name' => 'CHERRYLOU',
        //     'last_name' => 'MAGBANUA',
        //     'user_id' => 263,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MARY LYNN G. MAGBANUA',
        //     'first_name' => 'MARY LYNN',
        //     'last_name' => 'MAGBANUA',
        //     'user_id' => 264,
        //     'role_id' => 2,
        //     'office_id' => null,
        //     'position_id' => 12,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'CHARLIE J. MAGHANOY',
        //     'first_name' => 'CHARLIE',
        //     'last_name' => 'MAGHANOY',
        //     'user_id' => 265,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ALLAN A. MAGLANTAY',
        //     'first_name' => 'ALLAN',
        //     'last_name' => 'MAGLANTAY',
        //     'user_id' => 266,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MARY GRACE G. MAGLANTAY',
        //     'first_name' => 'MARY GRACE',
        //     'last_name' => 'MAGLANTAY',
        //     'user_id' => 267,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'DUMA J. MAKALILAY',
        //     'first_name' => 'DUMA',
        //     'last_name' => 'MAKALILAY',
        //     'user_id' => 268,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'AMERA C. MALACO',
        //     'first_name' => 'AMERA',
        //     'last_name' => 'MALACO',
        //     'user_id' => 269,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JOSEPHINE P. MALINOG',
        //     'first_name' => 'JOSEPHINE',
        //     'last_name' => 'MALINOG',
        //     'user_id' => 270,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'NORMINA A. MAMALINTA',
        //     'first_name' => 'NORMINA',
        //     'last_name' => 'MAMALINTA',
        //     'user_id' => 271,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'GRACIELA LOU F. MANA-AY',
        //     'first_name' => 'GRACIELA LOU ',
        //     'last_name' => 'MANA-AY',
        //     'user_id' => 272,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'RESHNEY F. MANANGAN',
        //     'first_name' => 'RESHNEY',
        //     'last_name' => 'MANANGAN',
        //     'user_id' => 273,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ANESA P. MANGINDRA',
        //     'first_name' => 'ANESA',
        //     'last_name' => 'MANGINDRA',
        //     'user_id' => 274,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ANNA ROSE T. MARCELINO',
        //     'first_name' => 'ANNA ROSE',
        //     'last_name' => 'MARCELINO',
        //     'user_id' => 275,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JUNITO P. MARCELINO',
        //     'first_name' => 'JUNITO',
        //     'last_name' => 'MARCELINO',
        //     'user_id' => 276,
        //     'role_id' => 2,
        //     'office_id' => null,
        //     'position_id' => 18,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'YORYNCITA C. MARFORI',
        //     'first_name' => 'YORYNCITA',
        //     'last_name' => 'MARFORI',
        //     'user_id' => 277,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'RUBILEE S. MARIANO',
        //     'first_name' => 'RUBILEE',
        //     'last_name' => 'MARIANO',
        //     'user_id' => 278,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'VINA MARIE A. MARTIN',
        //     'first_name' => 'VINA MARIE',
        //     'last_name' => 'MARTIN',
        //     'user_id' => 279,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'RICHARD K. MASLA',
        //     'first_name' => 'RICHARD',
        //     'last_name' => 'MASLA',
        //     'user_id' => 280,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ADELAIDA R. MATILOS',
        //     'first_name' => 'ADELAIDA',
        //     'last_name' => 'MATILOS',
        //     'user_id' => 281,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'RANDY E. MAYO',
        //     'first_name' => 'RANDY',
        //     'last_name' => 'MAYO',
        //     'user_id' => 282,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'LEILA JANE D. MENDOZA',
        //     'first_name' => 'LEILA JANE',
        //     'last_name' => 'MENDOZA',
        //     'user_id' => 283,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MANOLO B. MERCADO',
        //     'first_name' => 'MANOLO',
        //     'last_name' => 'MERCADO',
        //     'user_id' => 284,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'LEIZEL C. MERIALES',
        //     'first_name' => 'LEIZEL',
        //     'last_name' => 'MERIALES',
        //     'user_id' => 285,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'EDRALIN D. MESIAS',
        //     'first_name' => 'EDRALIN',
        //     'last_name' => 'MESIAS',
        //     'user_id' => 286,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'VICTORIA A. MIJARES',
        //     'first_name' => 'VICTORIA',
        //     'last_name' => 'MIJARES',
        //     'user_id' => 287,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'GRACE JOY T. MILLENDEZ',
        //     'first_name' => 'GRACE JOY ',
        //     'last_name' => 'MILLENDEZ',
        //     'user_id' => 288,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MARY GRACE S. MOLINA',
        //     'first_name' => 'MARY GRAC',
        //     'last_name' => 'MOLINA',
        //     'user_id' => 289,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ZILJIH S. MOLINA',
        //     'first_name' => 'ZILJIH',
        //     'last_name' => 'MOLINA',
        //     'user_id' => 290,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'EMY A. MORBO',
        //     'first_name' => 'EMY',
        //     'last_name' => 'MORBO',
        //     'user_id' => 291,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'NORA M. MOYA',
        //     'first_name' => 'NORA',
        //     'last_name' => 'MOYA',
        //     'user_id' => 292,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'AMELITA A. MOYET',
        //     'first_name' => 'AMELITA',
        //     'last_name' => 'MOYET',
        //     'user_id' => 293,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'PATERNA A. MURILLO',
        //     'first_name' => 'PATERNA',
        //     'last_name' => 'MURILLO',
        //     'user_id' => 294,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ENGR. NATHANIEL D. NAANEP',
        //     'first_name' => 'NATHANIEL',
        //     'last_name' => 'NAANEP',
        //     'user_id' => 295,
        //     'role_id' => 2,
        //     'office_id' => null,
        //     'position_id' => 12,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'CESAR J. NALLOS',
        //     'first_name' => 'CESAR',
        //     'last_name' => 'NALLOS',
        //     'user_id' => 296,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ROMEO C. NARCILLA',
        //     'first_name' => 'ROMEO',
        //     'last_name' => 'NARCILLA',
        //     'user_id' => 297,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'RAMONITO J. NAZARENO',
        //     'first_name' => 'RAMONITO',
        //     'last_name' => 'NAZARENO',
        //     'user_id' => 298,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'LEONARD R. NECESITO',
        //     'first_name' => 'LEONARD',
        //     'last_name' => 'NECESITO',
        //     'user_id' => 299,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'DEXTER C. NECOR',
        //     'first_name' => 'DEXTER',
        //     'last_name' => 'NECOR',
        //     'user_id' => 300,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JAIME BOY U. NGAG',
        //     'first_name' => 'JAIME BOY',
        //     'last_name' => 'NGAG',
        //     'user_id' => 301,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'CELIA ROSE J. NOTA',
        //     'first_name' => 'CELIA ROSE',
        //     'last_name' => 'NOTA',
        //     'user_id' => 302,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ADRIANA B. OBENA',
        //     'first_name' => 'ADRIANA',
        //     'last_name' => 'OBENA',
        //     'user_id' => 303,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'CHONA A. OMEGA',
        //     'first_name' => 'CHONA',
        //     'last_name' => 'OMEGA',
        //     'user_id' => 304,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ROSALINDA M. ONA',
        //     'first_name' => 'ROSALINDA ',
        //     'last_name' => 'ONA',
        //     'user_id' => 305,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'PAUL RYAN L. OÑAS',
        //     'first_name' => 'PAUL RYAN',
        //     'last_name' => 'OÑAS',
        //     'user_id' => 306,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JUAREYN L. ONDOY',
        //     'first_name' => 'JUAREYN',
        //     'last_name' => 'ONDOY',
        //     'user_id' => 307,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MARK F. ONIA',
        //     'first_name' => 'MARK',
        //     'last_name' => 'ONIA',
        //     'user_id' => 308,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'IAN MARK E. ORCAJADA',
        //     'first_name' => 'IAN MARK',
        //     'last_name' => 'ORCAJADA',
        //     'user_id' => 309,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'CHARITY L. ORIA',
        //     'first_name' => 'CHARITY',
        //     'last_name' => 'ORIA',
        //     'user_id' => 310,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JECYLLE R. ORIANA',
        //     'first_name' => 'JECYLLE',
        //     'last_name' => 'ORIANA',
        //     'user_id' => 311,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JESUSA D. ORTUOSTE',
        //     'first_name' => 'JESUSA',
        //     'last_name' => 'ORTUOSTE',
        //     'user_id' => 312,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ROMUALDO M. ORTUOSTE',
        //     'first_name' => 'ROMUALDO',
        //     'last_name' => 'ORTUOSTE',
        //     'user_id' => 313,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ALLAN REY M. PACULANAN',
        //     'first_name' => 'ALLAN REY',
        //     'last_name' => 'PACULANAN',
        //     'user_id' => 314,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'AMY M. PADERNAL',
        //     'first_name' => 'AMY',
        //     'last_name' => 'PADERNAL',
        //     'user_id' => 315,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MERLE C. PADILLA',
        //     'first_name' => 'MERLE',
        //     'last_name' => 'PADILLA',
        //     'user_id' => 316,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ARTCHIE P. PADIOS',
        //     'first_name' => 'ARTCHIE',
        //     'last_name' => 'PADIOS',
        //     'user_id' => 317,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ELMER T. PAHM',
        //     'first_name' => 'ELMER',
        //     'last_name' => 'PAHM',
        //     'user_id' => 318,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'VERONICA D. PAHM',
        //     'first_name' => 'VERONICA',
        //     'last_name' => 'PAHM',
        //     'user_id' => 319,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'SALVADOR A. PAL',
        //     'first_name' => 'SALVADOR',
        //     'last_name' => 'PAL',
        //     'user_id' => 320,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'LYWELYN M. PALANOG',
        //     'first_name' => 'LYWELYN',
        //     'last_name' => 'PALANOG',
        //     'user_id' => 321,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ROGELIO C. PALANOG',
        //     'first_name' => 'ROGELIO',
        //     'last_name' => 'PALANOG',
        //     'user_id' => 322,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JEZYL E. PALAPOS',
        //     'first_name' => 'JEZYL',
        //     'last_name' => 'PALAPOS',
        //     'user_id' => 323,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ANJANNETE C. PALLARCON',
        //     'first_name' => 'ANJANNETE',
        //     'last_name' => 'PALLARCON',
        //     'user_id' => 324,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'CYNTHIA L. PAMA',
        //     'first_name' => 'CYNTHIA',
        //     'last_name' => 'PAMA',
        //     'user_id' => 325,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'PERCILA M. PANAGDATO',
        //     'first_name' => 'PERCILA',
        //     'last_name' => 'PANAGDATO',
        //     'user_id' => 326,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'NORHATA T. PANDAY',
        //     'first_name' => 'NORHATA',
        //     'last_name' => 'PANDAY',
        //     'user_id' => 327,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'IRIL I. PANES',
        //     'first_name' => 'IRIL',
        //     'last_name' => 'PANES',
        //     'user_id' => 328,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'BADU M. PANIMBANG',
        //     'first_name' => 'BADU',
        //     'last_name' => 'PANIMBANG',
        //     'user_id' => 329,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'SUNSHINE M. PARAICO',
        //     'first_name' => 'SUNSHINE',
        //     'last_name' => 'PARAICO',
        //     'user_id' => 330,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'EMERSON G. PARCON',
        //     'first_name' => 'EMERSON',
        //     'last_name' => 'PARCON',
        //     'user_id' => 331,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MARY GRACE P. PASQUIN',
        //     'first_name' => 'MARY GRACE',
        //     'last_name' => 'PASQUIN',
        //     'user_id' => 332,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'DOLORCITA E. PAUYA',
        //     'first_name' => 'DOLORCITA',
        //     'last_name' => 'PAUYA',
        //     'user_id' => 333,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'CRISTELA MARIE M. PELARCO',
        //     'first_name' => 'CRISTELA MARIE',
        //     'last_name' => 'PELARCO',
        //     'user_id' => 334,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MARILOU U. PEREZ',
        //     'first_name' => 'MARILOU',
        //     'last_name' => 'PEREZ',
        //     'user_id' => 335,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JONALYN S. PERFECIO',
        //     'first_name' => 'JONALYN',
        //     'last_name' => 'PERFECIO',
        //     'user_id' => 336,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MARY GRACE L. PEROCHO',
        //     'first_name' => 'MARY GRACE',
        //     'last_name' => 'PEROCHO',
        //     'user_id' => 337,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'RICHARD H. PIMENTEL',
        //     'first_name' => 'RICHARD',
        //     'last_name' => 'PIMENTEL',
        //     'user_id' => 338,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'EVA C. POLO',
        //     'first_name' => 'EVA',
        //     'last_name' => 'POLO',
        //     'user_id' => 339,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JOEMARIE A. PONO',
        //     'first_name' => 'JOEMARIE',
        //     'last_name' => 'PONO',
        //     'user_id' => 340,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'EUFEMIA B. PORQUE',
        //     'first_name' => 'EUFEMIA',
        //     'last_name' => 'PORQUE',
        //     'user_id' => 341,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'EDMARLYN C. PORRAS',
        //     'first_name' => 'EDMARLYN',
        //     'last_name' => 'PORRAS',
        //     'user_id' => 342,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ADRIAN V. PROTACIO',
        //     'first_name' => 'ADRIAN',
        //     'last_name' => 'PROTACIO',
        //     'user_id' => 343,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'VIRGINIA E. PUBLICO',
        //     'first_name' => 'VIRGINIA',
        //     'last_name' => 'PUBLICO',
        //     'user_id' => 344,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'CHERYL T. PUEBLO',
        //     'first_name' => 'CHERYL',
        //     'last_name' => 'PUEBLO',
        //     'user_id' => 345,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'EDEN R. QUILLA',
        //     'first_name' => 'EDEN',
        //     'last_name' => 'QUILLA',
        //     'user_id' => 346,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'GERALDINE P. QUILLO',
        //     'first_name' => 'GERALDINE',
        //     'last_name' => 'QUILLO',
        //     'user_id' => 347,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JANET F. RABUT',
        //     'first_name' => 'JANET',
        //     'last_name' => 'RABUT',
        //     'user_id' => 348,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'CYRUS B. RAEL',
        //     'first_name' => 'CYRUS',
        //     'last_name' => 'RAEL',
        //     'user_id' => 349,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'AVELINO HERMAN B. RAMOS',
        //     'first_name' => 'AVELINO HERMAN',
        //     'last_name' => 'RAMOS',
        //     'user_id' => 350,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'BRANDO C. RAZON',
        //     'first_name' => 'BRANDO',
        //     'last_name' => 'RAZON',
        //     'user_id' => 351,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MAE D. REBUGIO',
        //     'first_name' => 'MAE',
        //     'last_name' => 'REBUGIO',
        //     'user_id' => 352,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ALEX N. REMEGIO',
        //     'first_name' => 'ALEX',
        //     'last_name' => 'REMEGIO',
        //     'user_id' => 353,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'FLORLYN MAE S. REMEGIO',
        //     'first_name' => 'FLORLYN MAE',
        //     'last_name' => 'REMEGIO',
        //     'user_id' => 354,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'BERNARD A. RENDON',
        //     'first_name' => 'BERNARD',
        //     'last_name' => 'RENDON',
        //     'user_id' => 355,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ANDRES T. REYES',
        //     'first_name' => 'ANDRES',
        //     'last_name' => 'REYES',
        //     'user_id' => 356,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MARIANIE L. REYES',
        //     'first_name' => 'MARIANIE',
        //     'last_name' => 'REYES',
        //     'user_id' => 357,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'CARLOS S. RIVERA',
        //     'first_name' => 'CARLOS',
        //     'last_name' => 'RIVERA',
        //     'user_id' => 358,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JONATHAN P. ROQUE',
        //     'first_name' => 'JONATHAN',
        //     'last_name' => 'ROQUE',
        //     'user_id' => 359,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'CERILO B. RUBIN',
        //     'first_name' => 'CERILO',
        //     'last_name' => 'RUBIN',
        //     'user_id' => 360,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MARIBETH P. SALABAN',
        //     'first_name' => 'MARIBETH',
        //     'last_name' => 'SALABAN',
        //     'user_id' => 361,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MICKLE DAN M. SALADINO',
        //     'first_name' => 'MICKLE DAN',
        //     'last_name' => 'SALADINO',
        //     'user_id' => 362,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'CARILYN S. SALANIO-MARTIN',
        //     'first_name' => 'CARILYN',
        //     'last_name' => 'SALANIO-MARTIN',
        //     'user_id' => 363,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JAN MICHAEL B. SALDICAYA',
        //     'first_name' => 'JAN MICHAEL',
        //     'last_name' => 'SALDICAYA',
        //     'user_id' => 364,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'FAHAD A. SALENDAB',
        //     'first_name' => 'FAHAD',
        //     'last_name' => 'SALENDAB',
        //     'user_id' => 365,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'SAMUEL R. SALVADOR',
        //     'first_name' => 'SAMUEL',
        //     'last_name' => 'SALVADOR',
        //     'user_id' => 366,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'AINEE GRACE S. SANSANO',
        //     'first_name' => 'AINEE GRACE',
        //     'last_name' => 'SANSANO',
        //     'user_id' => 367,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'DENAFEL C. SARAÑA',
        //     'first_name' => 'DENAFEL',
        //     'last_name' => 'SARAÑA',
        //     'user_id' => 368,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ROSELA O. SAZON',
        //     'first_name' => 'ROSELA',
        //     'last_name' => 'SAZON',
        //     'user_id' => 369,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JOE H. SELAYRO',
        //     'first_name' => 'JOE',
        //     'last_name' => 'SELAYRO',
        //     'user_id' => 370,
        //     'role_id' => 2,
        //     'position_id' => 16,
        //     'office_id' => 20,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ARNULFO C. SOLINAP',
        //     'first_name' => 'ARNULFO',
        //     'last_name' => 'SOLINAP',
        //     'user_id' => 371,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'RODOLFO B. SOLOMON',
        //     'first_name' => 'RODOLFO',
        //     'last_name' => 'SOLOMON',
        //     'user_id' => 372,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MARILOU P. SOMBRIA',
        //     'first_name' => 'MARILOU',
        //     'last_name' => 'SOMBRIA',
        //     'user_id' => 373,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JAYSON S. SUHAYON',
        //     'first_name' => 'JAYSON',
        //     'last_name' => 'SUHAYON',
        //     'user_id' => 374,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'HAZEL MAE B. SULAIMAN',
        //     'first_name' => 'HAZEL MAE',
        //     'last_name' => 'SULAIMAN',
        //     'user_id' => 375,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'SHAIDA B. SUMAPAL',
        //     'first_name' => 'SHAIDA',
        //     'last_name' => 'SUMAPAL',
        //     'user_id' => 376,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'GINA ROSE TREXIANNE S. SY',
        //     'first_name' => 'GINA ROSE TREXIANNE',
        //     'last_name' => 'SY',
        //     'user_id' => 377,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MAY NECTAR CYRILL L. TABARES',
        //     'first_name' => 'MAY NECTAR CYRILL',
        //     'last_name' => 'TABARES',
        //     'user_id' => 378,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ROSEMARY C. TABINGO',
        //     'first_name' => 'ROSEMARY',
        //     'last_name' => 'TABINGO',
        //     'user_id' => 379,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'KENDATU L. TAGO',
        //     'first_name' => 'KENDATU',
        //     'last_name' => 'TAGO',
        //     'user_id' => 380,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'KAREN B. TALIDONG',
        //     'first_name' => 'KAREN',
        //     'last_name' => 'TALIDONG',
        //     'user_id' => 381,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'GLENN S. TALUA',
        //     'first_name' => 'GLENN',
        //     'last_name' => 'TALUA',
        //     'user_id' => 382,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MOHAIDA A. TAMAMA',
        //     'first_name' => 'MOHAIDA',
        //     'last_name' => 'TAMAMA',
        //     'user_id' => 383,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'DOREEN B. TAMPUS',
        //     'first_name' => 'DOREEN',
        //     'last_name' => 'TAMPUS',
        //     'user_id' => 384,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MAY FLOR L. TAPOT',
        //     'first_name' => 'MAY FLOR',
        //     'last_name' => 'TAPOT',
        //     'user_id' => 385,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'JUDY E. TIAÑA',
        //     'first_name' => 'JUDY',
        //     'last_name' => 'TIAÑA',
        //     'user_id' => 386,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'RICHARD S. TOLEDO',
        //     'first_name' => 'RICHARD',
        //     'last_name' => 'TOLEDO',
        //     'user_id' => 387,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ROSE VIÑA E. TUTOR',
        //     'first_name' => 'ROSE VIÑA',
        //     'last_name' => 'TUTOR',
        //     'user_id' => 388,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ERNESTO L. UMIPIG',
        //     'first_name' => 'ERNESTO',
        //     'last_name' => 'UMIPIG',
        //     'user_id' => 389,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'MOHAMMAD ISA S. USMAN',
        //     'first_name' => 'MOHAMMAD ISA',
        //     'last_name' => 'USMAN',
        //     'user_id' => 390,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ANNIERAH M. USOP',
        //     'first_name' => 'ANNIERAH',
        //     'last_name' => 'USOP',
        //     'user_id' => 391,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'ANAMARIE G. VALDEZ',
        //     'first_name' => 'ANAMARIE',
        //     'last_name' => 'VALDEZ',
        //     'user_id' => 392,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'DENNIS M. VALDEZ',
        //     'first_name' => 'DENNIS',
        //     'last_name' => 'VALDEZ',
        //     'user_id' => 393,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'NE B. VELASCO',
        //     'first_name' => 'NE',
        //     'last_name' => 'VELASCO',
        //     'user_id' => 394,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'CHERRY VANESSA M. VENTURA',
        //     'first_name' => 'CHERRY VANESSA',
        //     'last_name' => 'VENTURA',
        //     'user_id' => 395,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'NOEL R. VILLANUEVA',
        //     'first_name' => 'NOEL',
        //     'last_name' => 'VILLANUEVA',
        //     'user_id' => 396,
        //     'role_id' => 3,
        //     'position_id' => 9,
        //     'office_id' => 50,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'HUBAIDA A. MAMALINTA',
        //     'first_name' => 'HUBAIDA',
        //     'last_name' => 'MAMALINTA',
        //     'user_id' => 397,
        //     'role_id' => 2,
        //     'office_id' => null,
        //     'position_id' => 12,
        // ]);

        // //Archiver

        // DB::table('employee_information')->insert([
        //     'full_name' => 'Johnrex T. Naceda',
        //     'first_name' => 'Johnrex',
        //     'last_name' => 'Naceda',
        //     'user_id' => 398,
        //     'role_id' => 7,
        //     'office_id' => 3,
        //     'position_id' => 24,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'Gerald B Rebamonte',
        //     'first_name' => 'Gerald',
        //     'last_name' => 'Rebamonte',
        //     'user_id' => 399,
        //     'role_id' => 7,
        //     'office_id' => 3,
        //     'position_id' => 24,
        // ]);

        // //Auditor

        // DB::table('employee_information')->insert([
        //     'full_name' => 'Joan M. Nieveras',
        //     'first_name' => 'Joan',
        //     'last_name' => 'Nieveras',
        //     'user_id' => 400,
        //     'role_id' => 7,
        //     'office_id' => 48,
        //     'position_id' => 7,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => 'Gabriel Jon Icawalo',
        //     'first_name' => 'Gabriel Jon',
        //     'last_name' => 'Icawalo',
        //     'user_id' => 401,
        //     'role_id' => 7,
        //     'office_id' => 3,
        //     'position_id' => 24,
        // ]);

        // DB::table('employee_information')->insert([
        //     'full_name' => null,
        //     'first_name' => null,
        //     'full_name' => 'Administrator',
        //     'user_id' => 402,
        //     'role_id' => 1,
        //     'office_id' => null,
        //     'position_id' => null,
        // ]);
    }
}
