<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FinancialOrganism;

class FinancialOrganismsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FinancialOrganism::create(['code' => 1, 'description' => 'GENUINO']);
        FinancialOrganism::create(['code' => 3, 'description' => 'FONACIDE']);
        FinancialOrganism::create(['code' => 9, 'description' => 'ITAIPU']);
        FinancialOrganism::create(['code' => 10, 'description' => 'YACYRETA']);
        FinancialOrganism::create(['code' => 63, 'description' => 'CONACYT']);
        FinancialOrganism::create(['code' => 72, 'description' => 'FONDO NACIONAL PARA LA SALUD']);
        FinancialOrganism::create(['code' => 83, 'description' => 'COPARTICIPACION DE TRIBUTOS MSPBS - LEY N° 5538/15']);
        FinancialOrganism::create(['code' => 85, 'description' => 'COPARTICIPACION DE TRIBUTOS IVA BIENES ENSAMBLADOS - LEY N° 5819/17']);
        FinancialOrganism::create(['code' => 183, 'description' => 'AUTORIDAD REGULADORA RADIOLOGIA Y NUCLEAR (ARRN']);
        FinancialOrganism::create(['code' => 302, 'description' => 'COMUNIDAD ECONOMICA EUROPEA']);
        FinancialOrganism::create(['code' => 359, 'description' => 'MERCOSUR (FOCEM']);
        FinancialOrganism::create(['code' => 401, 'description' => 'BANCO INTERAMERICANO DE DESARROLLO (BID']);
        FinancialOrganism::create(['code' => 402, 'description' => 'BANCO INTERNACIONAL DE RECONSTRUCCIÓN Y FOMENTO (BIRF']);
        FinancialOrganism::create(['code' => 509, 'description' => 'REPUBLICA DE CHINA']);
        FinancialOrganism::create(['code' => 519, 'description' => 'OTROS GOBIERNOS EXTRANJEROS']);
        FinancialOrganism::create(['code' => 604, 'description' => 'AGENCIA ESPAÑOLA DE COOPERACIÓN INTERNACIONAL (AECI']);
        FinancialOrganism::create(['code' => 607, 'description' => 'AGENCIA ANDALUZA DE COOPERACION INTERNACIONAL PARA EL DESARROLLO (AACID)']);
        FinancialOrganism::create(['code' => 654, 'description' => 'INSTITUTO ALEMÁN DE CRÉDITO PARA LA RECONSTRUCCIÓN – KFW']);
        FinancialOrganism::create(['code' => 655, 'description' => 'BANCO DE COOPERACIÓN INTERNACIONAL DEL JAPÓN (JBIC']);
        FinancialOrganism::create(['code' => 659, 'description' => 'OTROS ENTIDADES FINANCIERAS INTERNACIONALES']);
        FinancialOrganism::create(['code' => 13, 'description' => 'BONOS']);
        FinancialOrganism::create(['code' => 4, 'description' => 'BONOS SOBERANOS']);
        FinancialOrganism::create(['code' => 86, 'description' => 'Coparticipación de Tributos ISC Bebidas Alcohólicas - Ley N° 6266/2018']);
        FinancialOrganism::create(['code' => 514, 'description' => 'Gobierno de Marruecos']);
        FinancialOrganism::create(['code' => 513, 'description' => 'Gobierno Español']);
        FinancialOrganism::create(['code' => 817, 'description' => 'FONDO DE EMERGENCIA SANITARIA']);
    }
}
