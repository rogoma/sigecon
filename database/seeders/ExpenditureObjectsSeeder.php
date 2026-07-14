<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExpenditureObject;

class ExpenditureObjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ExpenditureObject::create([
            'code' => '100',
            'level' => 1,
            'description' => 'SERVICIOS PERSONALES',
            'alias' => '',
        ]);
        ExpenditureObject::create([
            'code' => '110',
            'level' => 2,
            'description' => 'REMUNERACIONES BASICAS',
            'alias' => '',
            'superior_expenditure_object_id' => 1
        ]);
        ExpenditureObject::create([
            'code' => '111',
            'level' => 3,
            'description' => 'Sueldos',
            'alias' => '',
            'superior_expenditure_object_id' => 2
        ]);
        ExpenditureObject::create([
            'code' => '112',
            'level' => 3,
            'description' => 'Dietas',
            'alias' => '',
            'superior_expenditure_object_id' => 2
        ]);
        ExpenditureObject::create([
            'code' => '113',
            'level' => 3,
            'description' => 'Gastos de representación',
            'alias' => '',
            'superior_expenditure_object_id' => 2
        ]);
        ExpenditureObject::create([
            'code' => '114',
            'level' => 3,
            'description' => 'Aguinaldo',
            'alias' => '',
            'superior_expenditure_object_id' => 2
        ]);
        ExpenditureObject::create([
            'code' => '120',
            'level' => 2,
            'description' => 'REMUNERACIONES TEMPORALES',
            'alias' => '',
            'superior_expenditure_object_id' => 1
        ]);
        ExpenditureObject::create([
            'code' => '122',
            'level' => 3,
            'description' => 'Gastos de residencia',
            'alias' => '',
            'superior_expenditure_object_id' => 7
        ]);
        ExpenditureObject::create([
            'code' => '123',
            'level' => 3,
            'description' => 'Remuneración extraordinaria',
            'alias' => '',
            'superior_expenditure_object_id' => 7
        ]);
        ExpenditureObject::create([
            'code' => '125',
            'level' => 3,
            'description' => 'Remuneración adicional',
            'alias' => '',
            'superior_expenditure_object_id' => 7
        ]);
        ExpenditureObject::create([
            'code' => '130',
            'level' => 2,
            'description' => 'ASIGNACIONES COMPLEMENTARIAS',
            'alias' => '',
            'superior_expenditure_object_id' => 1
        ]);
        ExpenditureObject::create([
            'code' => '131',
            'level' => 3,
            'description' => 'Subsidio familiar',
            'alias' => '',
            'superior_expenditure_object_id' => 11
        ]);
        ExpenditureObject::create([
            'code' => '132',
            'level' => 3,
            'description' => 'Escalafón docente',
            'alias' => '',
            'superior_expenditure_object_id' => 11
        ]);
        ExpenditureObject::create([
            'code' => '133',
            'level' => 3,
            'description' => 'Bonificaciones y gratificaciones',
            'alias' => '',
            'superior_expenditure_object_id' => 11
        ]);
        ExpenditureObject::create([
            'code' => '134',
            'level' => 3,
            'description' => 'Aporte jubilatorio del empleador',
            'alias' => '',
            'superior_expenditure_object_id' => 11
        ]);
        ExpenditureObject::create([
            'code' => '135',
            'level' => 3,
            'description' => 'Bonificaciones por ventas',
            'alias' => '',
            'superior_expenditure_object_id' => 11
        ]);
        ExpenditureObject::create([
            'code' => '137',
            'level' => 3,
            'description' => 'Gratificaciones por servicios especiales',
            'alias' => 'GRATIFICACIONES',
            'superior_expenditure_object_id' => 11
        ]);
        ExpenditureObject::create([
            'code' => '138',
            'level' => 3,
            'description' => 'Unidad básica alimentaria',
            'alias' => '',
            'superior_expenditure_object_id' => 11
        ]);
        ExpenditureObject::create([
            'code' => '139',
            'level' => 3,
            'description' => 'Escalafón Diplomático y Administrativo',
            'alias' => '',
            'superior_expenditure_object_id' => 11
        ]);
        ExpenditureObject::create([
            'code' => '140',
            'level' => 2,
            'description' => 'PERSONAL CONTRATADO',
            'alias' => 'PERSONAL CONTRATADO',
            'superior_expenditure_object_id' => 1
        ]);
        ExpenditureObject::create([
            'code' => '141',
            'level' => 3,
            'description' => 'Contratación de personal técnico',
            'alias' => 'CONTRATACION PERSONAL TECNICO',
            'superior_expenditure_object_id' => 20
        ]);
        ExpenditureObject::create([
            'code' => '142',
            'level' => 3,
            'description' => 'Contratación de personal de salud',
            'alias' => 'CONTRATACION DE PERSONAL DE SALUD',
            'superior_expenditure_object_id' => 20
        ]);
        ExpenditureObject::create([
            'code' => '143',
            'level' => 3,
            'description' => 'Contratación ocasional del personal docente',
            'alias' => 'CONTRATACION OCASIONAL DE PERSONAL DOCENTE',
            'superior_expenditure_object_id' => 20
        ]);
        ExpenditureObject::create([
            'code' => '144',
            'level' => 3,
            'description' => 'Jornales',
            'alias' => 'JORNALES',
            'superior_expenditure_object_id' => 20
        ]);
        ExpenditureObject::create([
            'code' => '145',
            'level' => 3,
            'description' => 'Honorarios profesionales',
            'alias' => 'CONSULTORIA',
            'superior_expenditure_object_id' => 20
        ]);
        ExpenditureObject::create([
            'code' => '160',
            'level' => 2,
            'description' => 'REMUNERACIONES POR SERVICIOS EN EL EXTERIOR',
            'alias' => '',
            'superior_expenditure_object_id' => 1
        ]);
        ExpenditureObject::create([
            'code' => '161',
            'level' => 3,
            'description' => 'Sueldos',
            'alias' => '',
            'superior_expenditure_object_id' => 26
        ]);
        ExpenditureObject::create([
            'code' => '162',
            'level' => 3,
            'description' => 'Gastos de representación',
            'alias' => '',
            'superior_expenditure_object_id' => 26
        ]);
        ExpenditureObject::create([
            'code' => '163',
            'level' => 3,
            'description' => 'Aguinaldo',
            'alias' => '',
            'superior_expenditure_object_id' => 26
        ]);
        ExpenditureObject::create([
            'code' => '180',
            'level' => 2,
            'description' => 'FONDO DE RESERVAS ESPECIALES',
            'alias' => '',
            'superior_expenditure_object_id' => 1
        ]);
        ExpenditureObject::create([
            'code' => '182',
            'level' => 3,
            'description' => 'Fondo de compensación social y reinserción laboral',
            'alias' => '',
            'superior_expenditure_object_id' => 30
        ]);
        ExpenditureObject::create([
            'code' => '183',
            'level' => 3,
            'description' => 'Fondo de recategorización salarial por méritos',
            'alias' => '',
            'superior_expenditure_object_id' => 30
        ]);
        ExpenditureObject::create([
            'code' => '185',
            'level' => 3,
            'description' => 'Fondo para crecimiento vegetativo',
            'alias' => '',
            'superior_expenditure_object_id' => 30
        ]);
        ExpenditureObject::create([
            'code' => '190',
            'level' => 2,
            'description' => 'OTROS GASTOS DEL PERSONAL',
            'alias' => '',
            'superior_expenditure_object_id' => 1
        ]);
        ExpenditureObject::create([
            'code' => '191',
            'level' => 3,
            'description' => 'Subsidio para la salud',
            'alias' => '',
            'superior_expenditure_object_id' => 34
        ]);
        ExpenditureObject::create([
            'code' => '192',
            'level' => 3,
            'description' => 'Seguro de vida',
            'alias' => '',
            'superior_expenditure_object_id' => 34
        ]);
        ExpenditureObject::create([
            'code' => '199',
            'level' => 3,
            'description' => 'Otros gastos del personal.',
            'alias' => '',
            'superior_expenditure_object_id' => 34
        ]);
        ExpenditureObject::create([
            'code' => '200',
            'level' => 1,
            'description' => 'SERVICIOS NO PERSONALES',
            'alias' => '',
        ]);
        ExpenditureObject::create([
            'code' => '210',
            'level' => 2,
            'description' => 'SERVICIOS BASICOS',
            'alias' => 'SERVICIOS BASICOS',
            'superior_expenditure_object_id' => 38
        ]);
        ExpenditureObject::create([
            'code' => '211',
            'level' => 3,
            'description' => 'Energía eléctrica',
            'alias' => '',
            'superior_expenditure_object_id' => 39
        ]);
        ExpenditureObject::create([
            'code' => '212',
            'level' => 3,
            'description' => 'Agua',
            'alias' => '',
            'superior_expenditure_object_id' => 39
        ]);
        ExpenditureObject::create([
            'code' => '214',
            'level' => 3,
            'description' => 'Teléfonos, telefax y otros servicios de telecomunicaciones',
            'alias' => '',
            'superior_expenditure_object_id' => 39
        ]);
        ExpenditureObject::create([
            'code' => '215',
            'level' => 3,
            'description' => 'Correos y otros servicios postales',
            'alias' => '',
            'superior_expenditure_object_id' => 39
        ]);
        ExpenditureObject::create([
            'code' => '219',
            'level' => 3,
            'description' => 'Servicios básicos varios',
            'alias' => '',
            'superior_expenditure_object_id' => 39
        ]);
        ExpenditureObject::create([
            'code' => '220',
            'level' => 2,
            'description' => 'TRANSPORTE Y ALMACENAJE',
            'alias' => 'TRANSPORTE Y ALMACENAJE',
            'superior_expenditure_object_id' => 38
        ]);
        ExpenditureObject::create([
            'code' => '221',
            'level' => 3,
            'description' => 'Transporte',
            'alias' => '',
            'superior_expenditure_object_id' => 45
        ]);
        ExpenditureObject::create([
            'code' => '222',
            'level' => 3,
            'description' => 'Almacenaje',
            'alias' => '',
            'superior_expenditure_object_id' => 45
        ]);
        ExpenditureObject::create([
            'code' => '223',
            'level' => 3,
            'description' => 'Transporte de personas',
            'alias' => '',
            'superior_expenditure_object_id' => 45
        ]);
        ExpenditureObject::create([
            'code' => '229',
            'level' => 3,
            'description' => 'Transporte y almacenaje varios',
            'alias' => '',
            'superior_expenditure_object_id' => 45
        ]);
        ExpenditureObject::create([
            'code' => '230',
            'level' => 2,
            'description' => 'PASAJES Y VIATICOS',
            'alias' => 'VIÁTICOS',
            'superior_expenditure_object_id' => 38
        ]);
        ExpenditureObject::create([
            'code' => '231',
            'level' => 3,
            'description' => 'Pasajes',
            'alias' => 'PASAJES',
            'superior_expenditure_object_id' => 50
        ]);
        ExpenditureObject::create([
            'code' => '232',
            'level' => 3,
            'description' => 'Viáticos y movilidad',
            'alias' => 'VIATICOS',
            'superior_expenditure_object_id' => 50
        ]);
        ExpenditureObject::create([
            'code' => '233',
            'level' => 3,
            'description' => 'Gastos de traslado',
            'alias' => '',
            'superior_expenditure_object_id' => 50
        ]);
        ExpenditureObject::create([
            'code' => '239',
            'level' => 3,
            'description' => 'Pasajes y viáticos varios',
            'alias' => '',
            'superior_expenditure_object_id' => 50
        ]);
        ExpenditureObject::create([
            'code' => '240',
            'level' => 2,
            'description' => 'GASTOS POR SERVICIOS DE ASEO, MANTENIMIENTO Y REPARACIONES',
            'alias' => 'MANTENIMIENTOS',
            'superior_expenditure_object_id' => 38
        ]);
        ExpenditureObject::create([
            'code' => '241',
            'level' => 3,
            'description' => 'Mantenimiento y reparaciones menores de vías de comunicación',
            'alias' => '',
            'superior_expenditure_object_id' => 55
        ]);
        ExpenditureObject::create([
            'code' => '242',
            'level' => 3,
            'description' => 'Mantenimiento y reparaciones menores de edificios y locales',
            'alias' => '',
            'superior_expenditure_object_id' => 55
        ]);
        ExpenditureObject::create([
            'code' => '243',
            'level' => 3,
            'description' => 'Mantenimientos y reparaciones menores de maquinarias, equipos y muebles de oficina',
            'alias' => '',
            'superior_expenditure_object_id' => 55
        ]);
        ExpenditureObject::create([
            'code' => '244',
            'level' => 3,
            'description' => 'Mantenimientos y reparaciones menores de vehículos',
            'alias' => '',
            'superior_expenditure_object_id' => 55
        ]);
        ExpenditureObject::create([
            'code' => '245',
            'level' => 3,
            'description' => 'Servicios de limpieza, aseo y fumigación',
            'alias' => '',
            'superior_expenditure_object_id' => 55
        ]);
        ExpenditureObject::create([
            'code' => '246',
            'level' => 3,
            'description' => 'Mantenimientos y reparaciones menores de instalaciones',
            'alias' => '',
            'superior_expenditure_object_id' => 55
        ]);
        ExpenditureObject::create([
            'code' => '247',
            'level' => 3,
            'description' => 'Mantenimientos y reparaciones menores de obras',
            'alias' => '',
            'superior_expenditure_object_id' => 55
        ]);
        ExpenditureObject::create([
            'code' => '248',
            'level' => 3,
            'description' => 'Otros mantenimientos y reparaciones menores',
            'alias' => '',
            'superior_expenditure_object_id' => 55
        ]);
        ExpenditureObject::create([
            'code' => '249',
            'level' => 3,
            'description' => 'Servicios de aseo, mantenimiento y reparaciones menores varios',
            'alias' => '',
            'superior_expenditure_object_id' => 55
        ]);
        ExpenditureObject::create([
            'code' => '250',
            'level' => 2,
            'description' => 'ALQUILERES Y DERECHOS',
            'alias' => '',
            'superior_expenditure_object_id' => 38
        ]);
        ExpenditureObject::create([
            'code' => '251',
            'level' => 3,
            'description' => 'Alquiler de edificios y locales',
            'alias' => '',
            'superior_expenditure_object_id' => 65
        ]);
        ExpenditureObject::create([
            'code' => '252',
            'level' => 3,
            'description' => 'Alquiler de maquinarias y equipos',
            'alias' => '',
            'superior_expenditure_object_id' => 65
        ]);
        ExpenditureObject::create([
            'code' => '253',
            'level' => 3,
            'description' => 'Derechos de bienes intangibles',
            'alias' => '',
            'superior_expenditure_object_id' => 65
        ]);
        ExpenditureObject::create([
            'code' => '254',
            'level' => 3,
            'description' => 'Alquiler de equipos de computación',
            'alias' => '',
            'superior_expenditure_object_id' => 65
        ]);
        ExpenditureObject::create([
            'code' => '255',
            'level' => 3,
            'description' => 'Alquiler de fotocopiadoras',
            'alias' => '',
            'superior_expenditure_object_id' => 65
        ]);
        ExpenditureObject::create([
            'code' => '256',
            'level' => 3,
            'description' => 'Arrendamiento de tierras y terrenos',
            'alias' => '',
            'superior_expenditure_object_id' => 65
        ]);
        ExpenditureObject::create([
            'code' => '257',
            'level' => 3,
            'description' => 'Alquiler de viviendas',
            'alias' => '',
            'superior_expenditure_object_id' => 65
        ]);
        ExpenditureObject::create([
            'code' => '258',
            'level' => 3,
            'description' => 'Alquileres y derechos de sistema leasing',
            'alias' => '',
            'superior_expenditure_object_id' => 65
        ]);
        ExpenditureObject::create([
            'code' => '259',
            'level' => 3,
            'description' => 'Alquileres y derechos varios',
            'alias' => '',
            'superior_expenditure_object_id' => 65
        ]);
        ExpenditureObject::create([
            'code' => '260',
            'level' => 2,
            'description' => 'SERVICIOS TECNICOS Y PROFESIONALES',
            'alias' => 'SERVICIOS TÉCNICOS Y PROFESIONALES',
            'superior_expenditure_object_id' => 38
        ]);
        ExpenditureObject::create([
            'code' => '261',
            'level' => 3,
            'description' => 'De informática y sistemas computarizados',
            'alias' => 'SERVICIOS TÉCNICOS DE INFORMATICA',
            'superior_expenditure_object_id' => 75
        ]);
        ExpenditureObject::create([
            'code' => '262',
            'level' => 3,
            'description' => 'Imprenta, publicaciones y reproducciones',
            'alias' => 'SERVICIOS TECNICOS Y PROFESIONALES IMPRESIONES',
            'superior_expenditure_object_id' => 75
        ]);
        ExpenditureObject::create([
            'code' => '263',
            'level' => 3,
            'description' => 'Servicios bancarios',
            'alias' => '',
            'superior_expenditure_object_id' => 75
        ]);
        ExpenditureObject::create([
            'code' => '264',
            'level' => 3,
            'description' => 'Primas y gastos de seguros',
            'alias' => '',
            'superior_expenditure_object_id' => 75
        ]);
        ExpenditureObject::create([
            'code' => '265',
            'level' => 3,
            'description' => 'Publicidad y propaganda',
            'alias' => 'PUBLICIDAD',
            'superior_expenditure_object_id' => 75
        ]);
        ExpenditureObject::create([
            'code' => '266',
            'level' => 3,
            'description' => 'Consultorías, asesorías e investigaciones',
            'alias' => 'SERVICIOS PROFESIONALES CONSULTORIAS',
            'superior_expenditure_object_id' => 75
        ]);
        ExpenditureObject::create([
            'code' => '267',
            'level' => 3,
            'description' => 'Promociones y exposiciones',
            'alias' => '',
            'superior_expenditure_object_id' => 75
        ]);
        ExpenditureObject::create([
            'code' => '268',
            'level' => 3,
            'description' => 'Servicios de comunicaciones',
            'alias' => 'COMUNICACIONES',
            'superior_expenditure_object_id' => 75
        ]);
        ExpenditureObject::create([
            'code' => '269',
            'level' => 3,
            'description' => 'Servicios técnicos y profesionales varios',
            'alias' => '',
            'superior_expenditure_object_id' => 75
        ]);
        ExpenditureObject::create([
            'code' => '270',
            'level' => 2,
            'description' => 'SERVICIO SOCIAL',
            'alias' => '',
            'superior_expenditure_object_id' => 38
        ]);
        ExpenditureObject::create([
            'code' => '279',
            'level' => 3,
            'description' => 'Servicio social',
            'alias' => '',
            'superior_expenditure_object_id' => 85
        ]);
        ExpenditureObject::create([
            'code' => '280',
            'level' => 2,
            'description' => 'OTROS SERVICIOS EN GENERAL',
            'alias' => 'CEREMONIAL',
            'superior_expenditure_object_id' => 38
        ]);
        ExpenditureObject::create([
            'code' => '281',
            'level' => 3,
            'description' => 'Servicios de ceremonial',
            'alias' => '',
            'superior_expenditure_object_id' => 87
        ]);
        ExpenditureObject::create([
            'code' => '282',
            'level' => 3,
            'description' => 'Servicios de vigilancia',
            'alias' => '',
            'superior_expenditure_object_id' => 87
        ]);
        ExpenditureObject::create([
            'code' => '283',
            'level' => 3,
            'description' => 'Gastos de peculio',
            'alias' => '',
            'superior_expenditure_object_id' => 87
        ]);
        ExpenditureObject::create([
            'code' => '284',
            'level' => 3,
            'description' => 'Servicios gastronómicos',
            'alias' => '',
            'superior_expenditure_object_id' => 87
        ]);
        ExpenditureObject::create([
            'code' => '288',
            'level' => 3,
            'description' => 'Servicios en general',
            'alias' => '',
            'superior_expenditure_object_id' => 87
        ]);
        ExpenditureObject::create([
            'code' => '289',
            'level' => 3,
            'description' => 'Otros servicios varios',
            'alias' => '',
            'superior_expenditure_object_id' => 87
        ]);
        ExpenditureObject::create([
            'code' => '290',
            'level' => 2,
            'description' => 'SERVICIOS DE CAPACITACION Y ADIESTRAMIENTO',
            'alias' => 'SERVICIOS DE CAPACITACION',
            'superior_expenditure_object_id' => 38
        ]);
        ExpenditureObject::create([
            'code' => '291',
            'level' => 3,
            'description' => 'Capacitación del personal del Estado',
            'alias' => '',
            'superior_expenditure_object_id' => 94
        ]);
        ExpenditureObject::create([
            'code' => '292',
            'level' => 3,
            'description' => 'Capacitación y formación laboral',
            'alias' => '',
            'superior_expenditure_object_id' => 94
        ]);
        ExpenditureObject::create([
            'code' => '293',
            'level' => 3,
            'description' => 'Capacitación especializada',
            'alias' => '',
            'superior_expenditure_object_id' => 94
        ]);
        ExpenditureObject::create([
            'code' => '299',
            'level' => 3,
            'description' => 'Capacitación y adiestramiento varios',
            'alias' => '',
            'superior_expenditure_object_id' => 94
        ]);
        ExpenditureObject::create([
            'code' => '300',
            'level' => 1,
            'description' => 'BIENES DE CONSUMO E INSUMOS',
            'alias' => '',
        ]);
        ExpenditureObject::create([
            'code' => '310',
            'level' => 2,
            'description' => 'PRODUCTOS ALIMENTICIOS',
            'alias' => '',
            'superior_expenditure_object_id' => 99
        ]);
        ExpenditureObject::create([
            'code' => '311',
            'level' => 3,
            'description' => 'Alimentos para personas',
            'alias' => '',
            'superior_expenditure_object_id' => 100
        ]);
        ExpenditureObject::create([
            'code' => '312',
            'level' => 3,
            'description' => 'Alimentos para animales',
            'alias' => '',
            'superior_expenditure_object_id' => 100
        ]);
        ExpenditureObject::create([
            'code' => '319',
            'level' => 3,
            'description' => 'Productos alimenticios varios',
            'alias' => '',
            'superior_expenditure_object_id' => 100
        ]);
        ExpenditureObject::create([
            'code' => '320',
            'level' => 2,
            'description' => 'TEXTILES Y VESTUARIOS',
            'alias' => '',
            'superior_expenditure_object_id' => 99
        ]);
        ExpenditureObject::create([
            'code' => '321',
            'level' => 3,
            'description' => 'Hilados y telas',
            'alias' => '',
            'superior_expenditure_object_id' => 104
        ]);
        ExpenditureObject::create([
            'code' => '322',
            'level' => 3,
            'description' => 'Prendas de vestir',
            'alias' => '',
            'superior_expenditure_object_id' => 104
        ]);
        ExpenditureObject::create([
            'code' => '323',
            'level' => 3,
            'description' => 'Confecciones textiles',
            'alias' => '',
            'superior_expenditure_object_id' => 104
        ]);
        ExpenditureObject::create([
            'code' => '324',
            'level' => 3,
            'description' => 'Calzados',
            'alias' => '',
            'superior_expenditure_object_id' => 104
        ]);
        ExpenditureObject::create([
            'code' => '325',
            'level' => 3,
            'description' => 'Cueros, cauchos y gomas',
            'alias' => '',
            'superior_expenditure_object_id' => 104
        ]);
        ExpenditureObject::create([
            'code' => '329',
            'level' => 3,
            'description' => 'Textiles y confecciones varios',
            'alias' => '',
            'superior_expenditure_object_id' => 104
        ]);
        ExpenditureObject::create([
            'code' => '330',
            'level' => 2,
            'description' => 'PRODUCTOS DE PAPEL, CARTON E IMPRESOS',
            'alias' => 'PRODUCTOS DE PAPEL, CARTON E IMPRESOS',
            'superior_expenditure_object_id' => 99
        ]);
        ExpenditureObject::create([
            'code' => '331',
            'level' => 3,
            'description' => 'Papel de escritorio y cartón',
            'alias' => '',
            'superior_expenditure_object_id' => 111
        ]);
        ExpenditureObject::create([
            'code' => '332',
            'level' => 3,
            'description' => 'Papel para computación',
            'alias' => '',
            'superior_expenditure_object_id' => 111
        ]);
        ExpenditureObject::create([
            'code' => '333',
            'level' => 3,
            'description' => 'Productos e impresiones de artes gráficas',
            'alias' => '',
            'superior_expenditure_object_id' => 111
        ]);
        ExpenditureObject::create([
            'code' => '334',
            'level' => 3,
            'description' => 'Productos de papel y cartón',
            'alias' => '',
            'superior_expenditure_object_id' => 111
        ]);
        ExpenditureObject::create([
            'code' => '335',
            'level' => 3,
            'description' => 'Libros, revistas y periódicos',
            'alias' => '',
            'superior_expenditure_object_id' => 111
        ]);
        ExpenditureObject::create([
            'code' => '336',
            'level' => 3,
            'description' => 'Textos de enseńanza',
            'alias' => '',
            'superior_expenditure_object_id' => 111
        ]);
        ExpenditureObject::create([
            'code' => '339',
            'level' => 3,
            'description' => 'Productos de papel, cartón e impresos varios',
            'alias' => '',
            'superior_expenditure_object_id' => 111
        ]);
        ExpenditureObject::create([
            'code' => '340',
            'level' => 2,
            'description' => 'BIENES DE CONSUMO DE OFICINAS E INSUMOS',
            'alias' => 'BIENES DE CONSUMO E INSUMOS',
            'superior_expenditure_object_id' => 99
        ]);
        ExpenditureObject::create([
            'code' => '341',
            'level' => 3,
            'description' => 'Elementos de limpieza',
            'alias' => '',
            'superior_expenditure_object_id' => 119
        ]);
        ExpenditureObject::create([
            'code' => '342',
            'level' => 3,
            'description' => 'Útiles de escritorio, oficina y enseńanza',
            'alias' => '',
            'superior_expenditure_object_id' => 119
        ]);
        ExpenditureObject::create([
            'code' => '343',
            'level' => 3,
            'description' => 'Útiles y materiales eléctricos',
            'alias' => '',
            'superior_expenditure_object_id' => 119
        ]);
        ExpenditureObject::create([
            'code' => '344',
            'level' => 3,
            'description' => 'Utensilios de cocina y comedor',
            'alias' => '',
            'superior_expenditure_object_id' => 119
        ]);
        ExpenditureObject::create([
            'code' => '345',
            'level' => 3,
            'description' => 'Productos de vidrio, loza y porcelana',
            'alias' => '',
            'superior_expenditure_object_id' => 119
        ]);
        ExpenditureObject::create([
            'code' => '346',
            'level' => 3,
            'description' => 'Repuestos y accesorios menores',
            'alias' => '',
            'superior_expenditure_object_id' => 119
        ]);
        ExpenditureObject::create([
            'code' => '347',
            'level' => 3,
            'description' => 'Elementos y útiles diversos',
            'alias' => '',
            'superior_expenditure_object_id' => 119
        ]);
        ExpenditureObject::create([
            'code' => '349',
            'level' => 3,
            'description' => 'Bienes de consumo varios',
            'alias' => '',
            'superior_expenditure_object_id' => 119
        ]);
        ExpenditureObject::create([
            'code' => '350',
            'level' => 2,
            'description' => 'PRODUCTOS E INSTRUMENTALES QUIMICOS Y MEDICINALES',
            'alias' => '',
            'superior_expenditure_object_id' => 99
        ]);
        ExpenditureObject::create([
            'code' => '351',
            'level' => 3,
            'description' => 'Compuestos químicos',
            'alias' => '',
            'superior_expenditure_object_id' => 128
        ]);
        ExpenditureObject::create([
            'code' => '352',
            'level' => 3,
            'description' => 'Productos farmacéuticos y medicinales',
            'alias' => '',
            'superior_expenditure_object_id' => 128
        ]);
        ExpenditureObject::create([
            'code' => '353',
            'level' => 3,
            'description' => 'Abonos y fertilizantes',
            'alias' => '',
            'superior_expenditure_object_id' => 128
        ]);
        ExpenditureObject::create([
            'code' => '354',
            'level' => 3,
            'description' => 'Insecticidas, fumigantes y otros',
            'alias' => '',
            'superior_expenditure_object_id' => 128
        ]);
        ExpenditureObject::create([
            'code' => '355',
            'level' => 3,
            'description' => 'Tintas, pinturas y colorantes',
            'alias' => '',
            'superior_expenditure_object_id' => 128
        ]);
        ExpenditureObject::create([
            'code' => '356',
            'level' => 3,
            'description' => 'Productos específicos veterinarios',
            'alias' => '',
            'superior_expenditure_object_id' => 128
        ]);
        ExpenditureObject::create([
            'code' => '357',
            'level' => 3,
            'description' => 'Productos de material plástico',
            'alias' => '',
            'superior_expenditure_object_id' => 128
        ]);
        ExpenditureObject::create([
            'code' => '358',
            'level' => 3,
            'description' => 'Útiles y materiales médico-quirúrgicos y de laboratorio',
            'alias' => '',
            'superior_expenditure_object_id' => 128
        ]);
        ExpenditureObject::create([
            'code' => '359',
            'level' => 3,
            'description' => 'Productos e instrumentales químicos y medicinales varios',
            'alias' => '',
            'superior_expenditure_object_id' => 128
        ]);
        ExpenditureObject::create([
            'code' => '360',
            'level' => 2,
            'description' => 'COMBUSTIBLES Y LUBRICANTES',
            'alias' => 'COMBUSTIBLES Y LUBRICANTES',
            'superior_expenditure_object_id' => 99
        ]);
        ExpenditureObject::create([
            'code' => '361',
            'level' => 3,
            'description' => 'Combustibles',
            'alias' => '',
            'superior_expenditure_object_id' => 138
        ]);
        ExpenditureObject::create([
            'code' => '362',
            'level' => 3,
            'description' => 'Lubricantes',
            'alias' => '',
            'superior_expenditure_object_id' => 138
        ]);
        ExpenditureObject::create([
            'code' => '369',
            'level' => 3,
            'description' => 'Combustibles y lubricantes varios',
            'alias' => '',
            'superior_expenditure_object_id' => 138
        ]);
        ExpenditureObject::create([
            'code' => '390',
            'level' => 2,
            'description' => 'OTROS BIENES DE CONSUMO',
            'alias' => 'CUBIERTAS',
            'superior_expenditure_object_id' => 99
        ]);
        ExpenditureObject::create([
            'code' => '391',
            'level' => 3,
            'description' => 'Artículos de caucho',
            'alias' => '',
            'superior_expenditure_object_id' => 142
        ]);
        ExpenditureObject::create([
            'code' => '392',
            'level' => 3,
            'description' => 'Cubiertas y cámaras de aire',
            'alias' => '',
            'superior_expenditure_object_id' => 142
        ]);
        ExpenditureObject::create([
            'code' => '393',
            'level' => 3,
            'description' => 'Estructuras metálicas acabadas',
            'alias' => '',
            'superior_expenditure_object_id' => 142
        ]);
        ExpenditureObject::create([
            'code' => '394',
            'level' => 3,
            'description' => 'Herramientas menores',
            'alias' => '',
            'superior_expenditure_object_id' => 142
        ]);
        ExpenditureObject::create([
            'code' => '395',
            'level' => 3,
            'description' => 'Materiales para seguridad y adiestramiento',
            'alias' => '',
            'superior_expenditure_object_id' => 142
        ]);
        ExpenditureObject::create([
            'code' => '396',
            'level' => 3,
            'description' => 'Artículos de plásticos',
            'alias' => '',
            'superior_expenditure_object_id' => 142
        ]);
        ExpenditureObject::create([
            'code' => '397',
            'level' => 3,
            'description' => 'Productos e insumos metálicos',
            'alias' => '',
            'superior_expenditure_object_id' => 142
        ]);
        ExpenditureObject::create([
            'code' => '398',
            'level' => 3,
            'description' => 'Productos e insumos no metálicos',
            'alias' => '',
            'superior_expenditure_object_id' => 142
        ]);
        ExpenditureObject::create([
            'code' => '399',
            'level' => 3,
            'description' => 'Bienes de consumo varios',
            'alias' => '',
            'superior_expenditure_object_id' => 142
        ]);
        ExpenditureObject::create([
            'code' => '400',
            'level' => 1,
            'description' => 'BIENES DE CAMBIO',
            'alias' => '',
        ]);
        ExpenditureObject::create([
            'code' => '410',
            'level' => 2,
            'description' => 'BIENES E INSUMOS DEL SECTOR AGROPECUARIO Y FORESTAL',
            'alias' => '',
            'superior_expenditure_object_id' => 152
        ]);
        ExpenditureObject::create([
            'code' => '411',
            'level' => 3,
            'description' => 'Animales y productos pecuarios',
            'alias' => '',
            'superior_expenditure_object_id' => 153
        ]);
        ExpenditureObject::create([
            'code' => '412',
            'level' => 3,
            'description' => 'Productos agroforestales',
            'alias' => '',
            'superior_expenditure_object_id' => 153
        ]);
        ExpenditureObject::create([
            'code' => '413',
            'level' => 3,
            'description' => 'Madera, corcho y sus manufacturas',
            'alias' => '',
            'superior_expenditure_object_id' => 153
        ]);
        ExpenditureObject::create([
            'code' => '414',
            'level' => 3,
            'description' => 'Productos o insumos agropecuarios',
            'alias' => '',
            'superior_expenditure_object_id' => 153
        ]);
        ExpenditureObject::create([
            'code' => '419',
            'level' => 3,
            'description' => 'Insumos agropecuarios y forestales varios',
            'alias' => '',
            'superior_expenditure_object_id' => 153
        ]);
        ExpenditureObject::create([
            'code' => '420',
            'level' => 2,
            'description' => 'MINERALES',
            'alias' => '',
            'superior_expenditure_object_id' => 152
        ]);
        ExpenditureObject::create([
            'code' => '421',
            'level' => 3,
            'description' => 'Petróleo crudo y gas natural',
            'alias' => '',
            'superior_expenditure_object_id' => 159
        ]);
        ExpenditureObject::create([
            'code' => '422',
            'level' => 3,
            'description' => 'Piedra, arcilla, cerámica, arena y sus productos',
            'alias' => '',
            'superior_expenditure_object_id' => 159
        ]);
        ExpenditureObject::create([
            'code' => '423',
            'level' => 3,
            'description' => 'Minerales metalíferos',
            'alias' => '',
            'superior_expenditure_object_id' => 159
        ]);
        ExpenditureObject::create([
            'code' => '424',
            'level' => 3,
            'description' => 'Carbón mineral',
            'alias' => '',
            'superior_expenditure_object_id' => 159
        ]);
        ExpenditureObject::create([
            'code' => '425',
            'level' => 3,
            'description' => 'Cemento, cal, asbesto, yeso y sus productos',
            'alias' => '',
            'superior_expenditure_object_id' => 159
        ]);
        ExpenditureObject::create([
            'code' => '426',
            'level' => 3,
            'description' => 'Productos ferrosos',
            'alias' => '',
            'superior_expenditure_object_id' => 159
        ]);
        ExpenditureObject::create([
            'code' => '427',
            'level' => 3,
            'description' => 'Productos no ferrosos',
            'alias' => '',
            'superior_expenditure_object_id' => 159
        ]);
        ExpenditureObject::create([
            'code' => '429',
            'level' => 3,
            'description' => 'Minerales varios',
            'alias' => '',
            'superior_expenditure_object_id' => 159
        ]);
        ExpenditureObject::create([
            'code' => '430',
            'level' => 2,
            'description' => 'INSUMOS INDUSTRIALES',
            'alias' => '',
            'superior_expenditure_object_id' => 152
        ]);
        ExpenditureObject::create([
            'code' => '439',
            'level' => 3,
            'description' => 'Insumos industriales',
            'alias' => '',
            'superior_expenditure_object_id' => 168
        ]);
        ExpenditureObject::create([
            'code' => '440',
            'level' => 2,
            'description' => 'ENERGIA Y COMBUSTIBLES',
            'alias' => '',
            'superior_expenditure_object_id' => 152
        ]);
        ExpenditureObject::create([
            'code' => '441',
            'level' => 3,
            'description' => 'Energía',
            'alias' => '',
            'superior_expenditure_object_id' => 170
        ]);
        ExpenditureObject::create([
            'code' => '442',
            'level' => 3,
            'description' => 'Combustibles',
            'alias' => '',
            'superior_expenditure_object_id' => 170
        ]);
        ExpenditureObject::create([
            'code' => '443',
            'level' => 3,
            'description' => 'Lubricantes',
            'alias' => '',
            'superior_expenditure_object_id' => 170
        ]);
        ExpenditureObject::create([
            'code' => '449',
            'level' => 3,
            'description' => 'Energía y combustibles varios',
            'alias' => '',
            'superior_expenditure_object_id' => 170
        ]);
        ExpenditureObject::create([
            'code' => '450',
            'level' => 2,
            'description' => 'TIERRAS, TERRENOS Y EDIFICACIONES',
            'alias' => '',
            'superior_expenditure_object_id' => 152
        ]);
        ExpenditureObject::create([
            'code' => '451',
            'level' => 3,
            'description' => 'Tierras, terrenos y edificaciones',
            'alias' => '',
            'superior_expenditure_object_id' => 175
        ]);
        ExpenditureObject::create([
            'code' => '459',
            'level' => 3,
            'description' => 'Tierras, terrenos y edificaciones varias',
            'alias' => '',
            'superior_expenditure_object_id' => 175
        ]);
        ExpenditureObject::create([
            'code' => '490',
            'level' => 2,
            'description' => 'OTRAS MATERIAS PRIMAS Y PRODUCTOS SEMIELABORADOS',
            'alias' => '',
            'superior_expenditure_object_id' => 152
        ]);
        ExpenditureObject::create([
            'code' => '491',
            'level' => 3,
            'description' => 'Especies timbradas y valores',
            'alias' => '',
            'superior_expenditure_object_id' => 178
        ]);
        ExpenditureObject::create([
            'code' => '492',
            'level' => 3,
            'description' => 'Insumos químicos y de laboratorios industriales',
            'alias' => '',
            'superior_expenditure_object_id' => 178
        ]);
        ExpenditureObject::create([
            'code' => '499',
            'level' => 3,
            'description' => 'Materias primas y productos semielaborados varios',
            'alias' => '',
            'superior_expenditure_object_id' => 178
        ]);
        ExpenditureObject::create([
            'code' => '500',
            'level' => 1,
            'description' => 'INVERSION FISICA',
            'alias' => '',
        ]);
        ExpenditureObject::create([
            'code' => '510',
            'level' => 2,
            'description' => 'ADQUISICION DE INMUEBLES',
            'alias' => '',
            'superior_expenditure_object_id' => 182
        ]);
        ExpenditureObject::create([
            'code' => '511',
            'level' => 3,
            'description' => 'Tierras y terrenos',
            'alias' => '',
            'superior_expenditure_object_id' => 183
        ]);
        ExpenditureObject::create([
            'code' => '512',
            'level' => 3,
            'description' => 'Adquisiciones de edificios e instalaciones',
            'alias' => '',
            'superior_expenditure_object_id' => 183
        ]);
        ExpenditureObject::create([
            'code' => '519',
            'level' => 3,
            'description' => 'Adquisiciones de inmuebles varios',
            'alias' => '',
            'superior_expenditure_object_id' => 183
        ]);
        ExpenditureObject::create([
            'code' => '520',
            'level' => 2,
            'description' => 'CONSTRUCCIONES',
            'alias' => 'CONSTRUCCIONES DE OBRAS DE USO PUBLICO',
            'superior_expenditure_object_id' => 182
        ]);
        ExpenditureObject::create([
            'code' => '521',
            'level' => 3,
            'description' => 'Construcciones de obras de uso público',
            'alias' => 'CONSTRUCCIONES DE OBRAS DE USO PUBLICO',
            'superior_expenditure_object_id' => 187
        ]);
        ExpenditureObject::create([
            'code' => '522',
            'level' => 3,
            'description' => 'Construcciones de obras de uso institucional',
            'alias' => '',
            'superior_expenditure_object_id' => 187
        ]);
        ExpenditureObject::create([
            'code' => '523',
            'level' => 3,
            'description' => 'Construcciones de obras militares',
            'alias' => '',
            'superior_expenditure_object_id' => 187
        ]);
        ExpenditureObject::create([
            'code' => '524',
            'level' => 3,
            'description' => 'Construcciones de obras para uso privado',
            'alias' => '',
            'superior_expenditure_object_id' => 187
        ]);
        ExpenditureObject::create([
            'code' => '525',
            'level' => 3,
            'description' => 'Construcciones de obras de infraestructuras',
            'alias' => '',
            'superior_expenditure_object_id' => 187
        ]);
        ExpenditureObject::create([
            'code' => '526',
            'level' => 3,
            'description' => 'Otras obras e instalaciones de infraestructuras',
            'alias' => '',
            'superior_expenditure_object_id' => 187
        ]);
        ExpenditureObject::create([
            'code' => '528',
            'level' => 3,
            'description' => 'Escalamiento de Costos',
            'alias' => '',
            'superior_expenditure_object_id' => 187
        ]);
        ExpenditureObject::create([
            'code' => '529',
            'level' => 3,
            'description' => 'Construcciones varias',
            'alias' => '',
            'superior_expenditure_object_id' => 187
        ]);
        ExpenditureObject::create([
            'code' => '530',
            'level' => 2,
            'description' => 'ADQUISICIONES DE MAQUINARIAS, EQUIPOS Y HERRAMIENTAS MAYORES',
            'alias' => '',
            'superior_expenditure_object_id' => 182
        ]);
        ExpenditureObject::create([
            'code' => '531',
            'level' => 3,
            'description' => 'Maquinarias y equipos de la construcción',
            'alias' => '',
            'superior_expenditure_object_id' => 196
        ]);
        ExpenditureObject::create([
            'code' => '532',
            'level' => 3,
            'description' => 'Maquinarias y equipos agropecuarios e industriales',
            'alias' => '',
            'superior_expenditure_object_id' => 196
        ]);
        ExpenditureObject::create([
            'code' => '533',
            'level' => 3,
            'description' => 'Maquinarias y equipos industriales',
            'alias' => '',
            'superior_expenditure_object_id' => 196
        ]);
        ExpenditureObject::create([
            'code' => '534',
            'level' => 3,
            'description' => 'Equipos educativos y recreacionales',
            'alias' => '',
            'superior_expenditure_object_id' => 196
        ]);
        ExpenditureObject::create([
            'code' => '535',
            'level' => 3,
            'description' => 'Equipos de salud y de laboratorio',
            'alias' => '',
            'superior_expenditure_object_id' => 196
        ]);
        ExpenditureObject::create([
            'code' => '536',
            'level' => 3,
            'description' => 'Equipos de comunicaciones y seńalamientos',
            'alias' => '',
            'superior_expenditure_object_id' => 196
        ]);
        ExpenditureObject::create([
            'code' => '537',
            'level' => 3,
            'description' => 'Equipo de transporte',
            'alias' => '',
            'superior_expenditure_object_id' => 196
        ]);
        ExpenditureObject::create([
            'code' => '538',
            'level' => 3,
            'description' => 'Herramientas, aparatos e instrumentos en general',
            'alias' => '',
            'superior_expenditure_object_id' => 196
        ]);
        ExpenditureObject::create([
            'code' => '539',
            'level' => 3,
            'description' => 'Maquinarias, equipos y herramientas mayores varias',
            'alias' => '',
            'superior_expenditure_object_id' => 196
        ]);
        ExpenditureObject::create([
            'code' => '540',
            'level' => 2,
            'description' => 'ADQUISICIONES DE EQUIPOS DE OFICINA Y COMPUTACION',
            'alias' => 'ADQUISICIONES',
            'superior_expenditure_object_id' => 182
        ]);
        ExpenditureObject::create([
            'code' => '541',
            'level' => 3,
            'description' => 'Adquisiciones de muebles y enseres',
            'alias' => '',
            'superior_expenditure_object_id' => 206
        ]);
        ExpenditureObject::create([
            'code' => '542',
            'level' => 3,
            'description' => 'Adquisiciones de equipos de oficina',
            'alias' => '',
            'superior_expenditure_object_id' => 206
        ]);
        ExpenditureObject::create([
            'code' => '543',
            'level' => 3,
            'description' => 'Adquisiciones de equipos de computación',
            'alias' => '',
            'superior_expenditure_object_id' => 206
        ]);
        ExpenditureObject::create([
            'code' => '544',
            'level' => 3,
            'description' => 'Adquisiciones de equipos de imprenta',
            'alias' => '',
            'superior_expenditure_object_id' => 206
        ]);
        ExpenditureObject::create([
            'code' => '549',
            'level' => 3,
            'description' => 'Adquisiciones de equipos de oficina y computación varias',
            'alias' => '',
            'superior_expenditure_object_id' => 206
        ]);
        ExpenditureObject::create([
            'code' => '550',
            'level' => 2,
            'description' => 'ADQUISICIONES DE EQUIPOS MILITARES Y DE SEGURIDAD',
            'alias' => '',
            'superior_expenditure_object_id' => 182
        ]);
        ExpenditureObject::create([
            'code' => '551',
            'level' => 3,
            'description' => 'Equipos militares y de seguridad',
            'alias' => '',
            'superior_expenditure_object_id' => 212
        ]);
        ExpenditureObject::create([
            'code' => '552',
            'level' => 3,
            'description' => 'Equipos de seguridad institucional',
            'alias' => '',
            'superior_expenditure_object_id' => 212
        ]);
        ExpenditureObject::create([
            'code' => '559',
            'level' => 3,
            'description' => 'Equipos militares y de seguridad varios',
            'alias' => '',
            'superior_expenditure_object_id' => 212
        ]);
        ExpenditureObject::create([
            'code' => '560',
            'level' => 2,
            'description' => 'ADQUISICION DE SEMOVIENTES',
            'alias' => '',
            'superior_expenditure_object_id' => 182
        ]);
        ExpenditureObject::create([
            'code' => '569',
            'level' => 3,
            'description' => 'Adquisiciones de semovientes',
            'alias' => '',
            'superior_expenditure_object_id' => 216
        ]);
        ExpenditureObject::create([
            'code' => '570',
            'level' => 2,
            'description' => 'ADQUISICIONES DE ACTIVOS INTANGIBLES',
            'alias' => 'ADQUISICIONES DE ACTIVOS INTANGIBLES',
            'superior_expenditure_object_id' => 182
        ]);
        ExpenditureObject::create([
            'code' => '579',
            'level' => 3,
            'description' => 'Activos intangibles',
            'alias' => '',
            'superior_expenditure_object_id' => 218
        ]);
        ExpenditureObject::create([
            'code' => '580',
            'level' => 2,
            'description' => 'ESTUDIOS Y PROYECTOS DE INVERSION',
            'alias' => '',
            'superior_expenditure_object_id' => 182
        ]);
        ExpenditureObject::create([
            'code' => '589',
            'level' => 3,
            'description' => 'Estudios y proyectos de inversión varios',
            'alias' => '',
            'superior_expenditure_object_id' => 220
        ]);
        ExpenditureObject::create([
            'code' => '590',
            'level' => 2,
            'description' => 'OTROS GASTOS DE INVERSION Y REPARACIONES MAYORES',
            'alias' => '',
            'superior_expenditure_object_id' => 182
        ]);
        ExpenditureObject::create([
            'code' => '591',
            'level' => 3,
            'description' => 'Inversión en recursos naturales al sector público',
            'alias' => '',
            'superior_expenditure_object_id' => 222
        ]);
        ExpenditureObject::create([
            'code' => '592',
            'level' => 3,
            'description' => 'Inversión en recursos naturales al sector privado',
            'alias' => '',
            'superior_expenditure_object_id' => 222
        ]);
        ExpenditureObject::create([
            'code' => '593',
            'level' => 3,
            'description' => 'Otras inversiones',
            'alias' => '',
            'superior_expenditure_object_id' => 222
        ]);
        ExpenditureObject::create([
            'code' => '594',
            'level' => 3,
            'description' => 'Indemnizaciones por inmuebles',
            'alias' => '',
            'superior_expenditure_object_id' => 222
        ]);
        ExpenditureObject::create([
            'code' => '595',
            'level' => 3,
            'description' => 'Reparaciones mayores de inmuebles',
            'alias' => '',
            'superior_expenditure_object_id' => 222
        ]);
        ExpenditureObject::create([
            'code' => '596',
            'level' => 3,
            'description' => 'Reparaciones mayores de equipos',
            'alias' => '',
            'superior_expenditure_object_id' => 222
        ]);
        ExpenditureObject::create([
            'code' => '597',
            'level' => 3,
            'description' => 'Reparaciones mayores de máquinas',
            'alias' => '',
            'superior_expenditure_object_id' => 222
        ]);
        ExpenditureObject::create([
            'code' => '598',
            'level' => 3,
            'description' => 'Reparaciones mayores de herramientas y otros',
            'alias' => '',
            'superior_expenditure_object_id' => 222
        ]);
        ExpenditureObject::create([
            'code' => '599',
            'level' => 3,
            'description' => 'Otras reparaciones mayores',
            'alias' => '',
            'superior_expenditure_object_id' => 222
        ]);
        ExpenditureObject::create([
            'code' => '600',
            'level' => 1,
            'description' => 'INVERSION FINANCIERA',
            'alias' => '',
        ]);
        ExpenditureObject::create([
            'code' => '610',
            'level' => 2,
            'description' => 'ACCIONES Y PARTICIPACIONES DE CAPITAL',
            'alias' => '',
            'superior_expenditure_object_id' => 232
        ]);
        ExpenditureObject::create([
            'code' => '611',
            'level' => 3,
            'description' => 'Aportes de capital en entidades nacionales',
            'alias' => '',
            'superior_expenditure_object_id' => 233
        ]);
        ExpenditureObject::create([
            'code' => '612',
            'level' => 3,
            'description' => 'Aportes de capital en entidades binacionales',
            'alias' => '',
            'superior_expenditure_object_id' => 233
        ]);
        ExpenditureObject::create([
            'code' => '613',
            'level' => 3,
            'description' => 'Aportes de capital en organismos internacionales',
            'alias' => '',
            'superior_expenditure_object_id' => 233
        ]);
        ExpenditureObject::create([
            'code' => '619',
            'level' => 3,
            'description' => 'Acciones y participación de capital varios',
            'alias' => '',
            'superior_expenditure_object_id' => 233
        ]);
        ExpenditureObject::create([
            'code' => '620',
            'level' => 2,
            'description' => 'PRESTAMOS A ENTIDADES DEL SECTOR PÚBLICO',
            'alias' => '',
            'superior_expenditure_object_id' => 232
        ]);
        ExpenditureObject::create([
            'code' => '621',
            'level' => 3,
            'description' => 'Préstamos varios a entidades del sector público',
            'alias' => '',
            'superior_expenditure_object_id' => 238
        ]);
        ExpenditureObject::create([
            'code' => '629',
            'level' => 3,
            'description' => 'Préstamos a entidades del sector público varios',
            'alias' => '',
            'superior_expenditure_object_id' => 238
        ]);
        ExpenditureObject::create([
            'code' => '630',
            'level' => 2,
            'description' => 'PRESTAMOS AL SECTOR PRIVADO',
            'alias' => '',
            'superior_expenditure_object_id' => 232
        ]);
        ExpenditureObject::create([
            'code' => '631',
            'level' => 3,
            'description' => 'Préstamos a familias',
            'alias' => '',
            'superior_expenditure_object_id' => 241
        ]);
        ExpenditureObject::create([
            'code' => '632',
            'level' => 3,
            'description' => 'Préstamos a empresas privadas',
            'alias' => '',
            'superior_expenditure_object_id' => 241
        ]);
        ExpenditureObject::create([
            'code' => '633',
            'level' => 3,
            'description' => 'Garantía para préstamos',
            'alias' => '',
            'superior_expenditure_object_id' => 241
        ]);
        ExpenditureObject::create([
            'code' => '634',
            'level' => 3,
            'description' => 'Contrato Fiduciario',
            'alias' => '',
            'superior_expenditure_object_id' => 241
        ]);
        ExpenditureObject::create([
            'code' => '639',
            'level' => 3,
            'description' => 'Préstamos al sector privado varios',
            'alias' => '',
            'superior_expenditure_object_id' => 241
        ]);
        ExpenditureObject::create([
            'code' => '640',
            'level' => 2,
            'description' => 'ADQUISICIONES DE TITULOS Y VALORES',
            'alias' => '',
            'superior_expenditure_object_id' => 232
        ]);
        ExpenditureObject::create([
            'code' => '641',
            'level' => 3,
            'description' => 'Títulos y valores varios',
            'alias' => '',
            'superior_expenditure_object_id' => 247
        ]);
        ExpenditureObject::create([
            'code' => '649',
            'level' => 3,
            'description' => 'Adquisiciones de títulos y valores varios',
            'alias' => '',
            'superior_expenditure_object_id' => 247
        ]);
        ExpenditureObject::create([
            'code' => '650',
            'level' => 2,
            'description' => 'DEPOSITOS A PLAZO FIJO',
            'alias' => '',
            'superior_expenditure_object_id' => 232
        ]);
        ExpenditureObject::create([
            'code' => '651',
            'level' => 3,
            'description' => 'Depósitos a plazo fijo',
            'alias' => '',
            'superior_expenditure_object_id' => 250
        ]);
        ExpenditureObject::create([
            'code' => '659',
            'level' => 3,
            'description' => 'Depósitos a plazo fijo varios',
            'alias' => '',
            'superior_expenditure_object_id' => 250
        ]);
        ExpenditureObject::create([
            'code' => '660',
            'level' => 2,
            'description' => 'PRESTAMOS A INSTITUCIONES FINANCIERAS INTERMEDIARIAS',
            'alias' => '',
            'superior_expenditure_object_id' => 232
        ]);
        ExpenditureObject::create([
            'code' => '661',
            'level' => 3,
            'description' => 'Préstamos a instituciones financieras intermediarias',
            'alias' => '',
            'superior_expenditure_object_id' => 253
        ]);
        ExpenditureObject::create([
            'code' => '669',
            'level' => 3,
            'description' => 'Préstamos a instituciones financieras intermediarias varios',
            'alias' => '',
            'superior_expenditure_object_id' => 253
        ]);
        ExpenditureObject::create([
            'code' => '700',
            'level' => 1,
            'description' => 'SERVICIO DE LA DEUDA PÚBLICA',
            'alias' => '',
        ]);
        ExpenditureObject::create([
            'code' => '710',
            'level' => 2,
            'description' => 'INTERESES DE LA DEUDA PÚBLICA INTERNA',
            'alias' => '',
            'superior_expenditure_object_id' => 256
        ]);
        ExpenditureObject::create([
            'code' => '711',
            'level' => 3,
            'description' => 'Intereses de la deuda con el sector público financiero',
            'alias' => '',
            'superior_expenditure_object_id' => 257
        ]);
        ExpenditureObject::create([
            'code' => '712',
            'level' => 3,
            'description' => 'Intereses de la deuda con el sector público no financiero',
            'alias' => '',
            'superior_expenditure_object_id' => 257
        ]);
        ExpenditureObject::create([
            'code' => '713',
            'level' => 3,
            'description' => 'Intereses de la deuda con el sector privado',
            'alias' => '',
            'superior_expenditure_object_id' => 257
        ]);
        ExpenditureObject::create([
            'code' => '714',
            'level' => 3,
            'description' => 'Intereses del crédito interno de proveedores',
            'alias' => '',
            'superior_expenditure_object_id' => 257
        ]);
        ExpenditureObject::create([
            'code' => '715',
            'level' => 3,
            'description' => 'Intereses por deuda bonificada',
            'alias' => '',
            'superior_expenditure_object_id' => 257
        ]);
        ExpenditureObject::create([
            'code' => '719',
            'level' => 3,
            'description' => 'Intereses deuda pública interna varios',
            'alias' => '',
            'superior_expenditure_object_id' => 257
        ]);
        ExpenditureObject::create([
            'code' => '720',
            'level' => 2,
            'description' => 'INTERESES DE LA DEUDA PÚBLICA EXTERNA',
            'alias' => '',
            'superior_expenditure_object_id' => 256
        ]);
        ExpenditureObject::create([
            'code' => '721',
            'level' => 3,
            'description' => 'Intereses de la deuda con organismos multilaterales',
            'alias' => '',
            'superior_expenditure_object_id' => 264
        ]);
        ExpenditureObject::create([
            'code' => '722',
            'level' => 3,
            'description' => 'Intereses de la deuda con gobiernos extranjeros y sus agencias financieras',
            'alias' => '',
            'superior_expenditure_object_id' => 264
        ]);
        ExpenditureObject::create([
            'code' => '723',
            'level' => 3,
            'description' => 'Intereses de la deuda con entes financieros privados del exterior',
            'alias' => '',
            'superior_expenditure_object_id' => 264
        ]);
        ExpenditureObject::create([
            'code' => '724',
            'level' => 3,
            'description' => 'Intereses de la deuda con proveedores del exterior',
            'alias' => '',
            'superior_expenditure_object_id' => 264
        ]);
        ExpenditureObject::create([
            'code' => '725',
            'level' => 3,
            'description' => 'Intereses por deuda externa bonificada',
            'alias' => '',
            'superior_expenditure_object_id' => 264
        ]);
        ExpenditureObject::create([
            'code' => '729',
            'level' => 3,
            'description' => 'Intereses de la deuda pública externa varios',
            'alias' => '',
            'superior_expenditure_object_id' => 264
        ]);
        ExpenditureObject::create([
            'code' => '730',
            'level' => 2,
            'description' => 'AMORTIZACIONES DE LA DEUDA PÚBLICA INTERNA',
            'alias' => '',
            'superior_expenditure_object_id' => 256
        ]);
        ExpenditureObject::create([
            'code' => '731',
            'level' => 3,
            'description' => 'Amortización de la deuda con el sector público financiero',
            'alias' => '',
            'superior_expenditure_object_id' => 271
        ]);
        ExpenditureObject::create([
            'code' => '732',
            'level' => 3,
            'description' => 'Amortización de la deuda con el sector público no financiero',
            'alias' => '',
            'superior_expenditure_object_id' => 271
        ]);
        ExpenditureObject::create([
            'code' => '733',
            'level' => 3,
            'description' => 'Amortización de la deuda con el sector privado',
            'alias' => '',
            'superior_expenditure_object_id' => 271
        ]);
        ExpenditureObject::create([
            'code' => '734',
            'level' => 3,
            'description' => 'Amortización del crédito interno de proveedores',
            'alias' => '',
            'superior_expenditure_object_id' => 271
        ]);
        ExpenditureObject::create([
            'code' => '735',
            'level' => 3,
            'description' => 'Amortización por deuda bonificada',
            'alias' => '',
            'superior_expenditure_object_id' => 271
        ]);
        ExpenditureObject::create([
            'code' => '739',
            'level' => 3,
            'description' => 'Amortizaciones de la deuda pública interna varias',
            'alias' => '',
            'superior_expenditure_object_id' => 271
        ]);
        ExpenditureObject::create([
            'code' => '740',
            'level' => 2,
            'description' => 'AMORTIZACIONES DE LA DEUDA PÚBLICA EXTERNA',
            'alias' => '',
            'superior_expenditure_object_id' => 256
        ]);
        ExpenditureObject::create([
            'code' => '741',
            'level' => 3,
            'description' => 'Amortización de la deuda con organismos multilaterales',
            'alias' => '',
            'superior_expenditure_object_id' => 278
        ]);
        ExpenditureObject::create([
            'code' => '742',
            'level' => 3,
            'description' => 'Amortización de la deuda con gobiernos extranjeros y sus agencias financieras',
            'alias' => '',
            'superior_expenditure_object_id' => 278
        ]);
        ExpenditureObject::create([
            'code' => '743',
            'level' => 3,
            'description' => 'Amortización de la deuda con entes financieros privados del exterior',
            'alias' => '',
            'superior_expenditure_object_id' => 278
        ]);
        ExpenditureObject::create([
            'code' => '744',
            'level' => 3,
            'description' => 'Amortización de la deuda con proveedores del exterior',
            'alias' => '',
            'superior_expenditure_object_id' => 278
        ]);
        ExpenditureObject::create([
            'code' => '745',
            'level' => 3,
            'description' => 'Amortización por deuda externa bonificada',
            'alias' => '',
            'superior_expenditure_object_id' => 278
        ]);
        ExpenditureObject::create([
            'code' => '749',
            'level' => 3,
            'description' => 'Amortizaciones de la deuda pública externa varias',
            'alias' => '',
            'superior_expenditure_object_id' => 278
        ]);
        ExpenditureObject::create([
            'code' => '750',
            'level' => 2,
            'description' => 'COMISIONES',
            'alias' => '',
            'superior_expenditure_object_id' => 256
        ]);
        ExpenditureObject::create([
            'code' => '751',
            'level' => 3,
            'description' => 'Comisiones y otros gastos de la deuda interna',
            'alias' => '',
            'superior_expenditure_object_id' => 285
        ]);
        ExpenditureObject::create([
            'code' => '752',
            'level' => 3,
            'description' => 'Comisiones y otros gastos de la deuda externa',
            'alias' => '',
            'superior_expenditure_object_id' => 285
        ]);
        ExpenditureObject::create([
            'code' => '753',
            'level' => 3,
            'description' => 'Comisiones y otros gastos de la deuda interna bonificada',
            'alias' => '',
            'superior_expenditure_object_id' => 285
        ]);
        ExpenditureObject::create([
            'code' => '754',
            'level' => 3,
            'description' => 'Comisiones y otros gastos de la deuda externa bonificada',
            'alias' => '',
            'superior_expenditure_object_id' => 285
        ]);
        ExpenditureObject::create([
            'code' => '759',
            'level' => 3,
            'description' => 'Comisiones varias',
            'alias' => '',
            'superior_expenditure_object_id' => 285
        ]);
        ExpenditureObject::create([
            'code' => '760',
            'level' => 2,
            'description' => 'OTROS GASTOS DEL SERVICIO DE LA DEUDA PUBLICA',
            'alias' => '',
            'superior_expenditure_object_id' => 256
        ]);
        ExpenditureObject::create([
            'code' => '761',
            'level' => 3,
            'description' => 'Amortizaciones de la deuda pública externa',
            'alias' => '',
            'superior_expenditure_object_id' => 291
        ]);
        ExpenditureObject::create([
            'code' => '762',
            'level' => 3,
            'description' => 'Amortizaciones de la deuda pública externa bonificada',
            'alias' => '',
            'superior_expenditure_object_id' => 291
        ]);
        ExpenditureObject::create([
            'code' => '763',
            'level' => 3,
            'description' => 'Intereses de la deuda pública externa',
            'alias' => '',
            'superior_expenditure_object_id' => 291
        ]);
        ExpenditureObject::create([
            'code' => '764',
            'level' => 3,
            'description' => 'Intereses de la deuda pública externa bonificada',
            'alias' => '',
            'superior_expenditure_object_id' => 291
        ]);
        ExpenditureObject::create([
            'code' => '765',
            'level' => 3,
            'description' => 'Comisiones y otros gastos de la deuda pública externa',
            'alias' => '',
            'superior_expenditure_object_id' => 291
        ]);
        ExpenditureObject::create([
            'code' => '766',
            'level' => 3,
            'description' => 'Comisiones y otros gastos de la deuda pública externa bonificada',
            'alias' => '',
            'superior_expenditure_object_id' => 291
        ]);
        ExpenditureObject::create([
            'code' => '769',
            'level' => 3,
            'description' => 'Otros gastos del servicio de la deuda pública varios',
            'alias' => '',
            'superior_expenditure_object_id' => 291
        ]);
        ExpenditureObject::create([
            'code' => '800',
            'level' => 1,
            'description' => 'TRANSFERENCIAS',
            'alias' => '',
        ]);
        ExpenditureObject::create([
            'code' => '810',
            'level' => 2,
            'description' => 'TRANSFERENCIAS CONSOLIDABLES CORRIENTES AL SECTOR PÚBLICO',
            'alias' => '',
            'superior_expenditure_object_id' => 299
        ]);
        ExpenditureObject::create([
            'code' => '811',
            'level' => 3,
            'description' => 'Transferencias consolidables de la administración central a entidades descentralizadas',
            'alias' => '',
            'superior_expenditure_object_id' => 300
        ]);
        ExpenditureObject::create([
            'code' => '812',
            'level' => 3,
            'description' => 'Transferencias consolidables de las entidades descentralizadas a la administración central',
            'alias' => '',
            'superior_expenditure_object_id' => 300
        ]);
        ExpenditureObject::create([
            'code' => '813',
            'level' => 3,
            'description' => 'Transferencias consolidables por coparticipación IVA',
            'alias' => '',
            'superior_expenditure_object_id' => 300
        ]);
        ExpenditureObject::create([
            'code' => '814',
            'level' => 3,
            'description' => 'Transferencias consolidables por coparticipación juegos de azar',
            'alias' => '',
            'superior_expenditure_object_id' => 300
        ]);
        ExpenditureObject::create([
            'code' => '815',
            'level' => 3,
            'description' => 'Transferencias consolidables por coparticipación royalties',
            'alias' => '',
            'superior_expenditure_object_id' => 300
        ]);
        ExpenditureObject::create([
            'code' => '816',
            'level' => 3,
            'description' => 'Transferencias consolidables entre entidades descentralizadas',
            'alias' => '',
            'superior_expenditure_object_id' => 300
        ]);
        ExpenditureObject::create([
            'code' => '817',
            'level' => 3,
            'description' => 'Transferencias consolidables entre organismos de la administración central',
            'alias' => '',
            'superior_expenditure_object_id' => 300
        ]);
        ExpenditureObject::create([
            'code' => '819',
            'level' => 3,
            'description' => 'Otras transferencias consolidables corrientes',
            'alias' => '',
            'superior_expenditure_object_id' => 300
        ]);
        ExpenditureObject::create([
            'code' => '820',
            'level' => 2,
            'description' => 'TRANSFERENCIAS A JUBILADOS Y PENSIONADOS',
            'alias' => '',
            'superior_expenditure_object_id' => 299
        ]);
        ExpenditureObject::create([
            'code' => '821',
            'level' => 3,
            'description' => 'Jubilaciones y pensiones funcionarios y empleados del sector público y privado',
            'alias' => '',
            'superior_expenditure_object_id' => 309
        ]);
        ExpenditureObject::create([
            'code' => '822',
            'level' => 3,
            'description' => 'Jubilaciones y pensiones magistrados judiciales',
            'alias' => '',
            'superior_expenditure_object_id' => 309
        ]);
        ExpenditureObject::create([
            'code' => '823',
            'level' => 3,
            'description' => 'Jubilaciones y pensiones magisterio nacional',
            'alias' => '',
            'superior_expenditure_object_id' => 309
        ]);
        ExpenditureObject::create([
            'code' => '824',
            'level' => 3,
            'description' => 'Jubilaciones y pensiones docentes universitarios',
            'alias' => '',
            'superior_expenditure_object_id' => 309
        ]);
        ExpenditureObject::create([
            'code' => '825',
            'level' => 3,
            'description' => 'Jubilaciones y pensiones fuerzas armadas',
            'alias' => '',
            'superior_expenditure_object_id' => 309
        ]);
        ExpenditureObject::create([
            'code' => '826',
            'level' => 3,
            'description' => 'Jubilaciones y pensiones fuerzas policiales',
            'alias' => '',
            'superior_expenditure_object_id' => 309
        ]);
        ExpenditureObject::create([
            'code' => '827',
            'level' => 3,
            'description' => 'Pensiones graciables',
            'alias' => '',
            'superior_expenditure_object_id' => 309
        ]);
        ExpenditureObject::create([
            'code' => '828',
            'level' => 3,
            'description' => 'Pensiones varias',
            'alias' => '',
            'superior_expenditure_object_id' => 309
        ]);
        ExpenditureObject::create([
            'code' => '829',
            'level' => 3,
            'description' => 'Otras transferencias a jubilados y pensionados',
            'alias' => '',
            'superior_expenditure_object_id' => 309
        ]);
        ExpenditureObject::create([
            'code' => '830',
            'level' => 2,
            'description' => 'OTRAS TRANSFERENCIAS CORRIENTES AL SECTOR PÚBLICO O PRIVADO',
            'alias' => '',
            'superior_expenditure_object_id' => 299
        ]);
        ExpenditureObject::create([
            'code' => '831',
            'level' => 3,
            'description' => 'Aportes a entidades con fines sociales y al fondo nacional de emergencia.',
            'alias' => '',
            'superior_expenditure_object_id' => 319
        ]);
        ExpenditureObject::create([
            'code' => '832',
            'level' => 3,
            'description' => 'Aportes de la tesorería general',
            'alias' => '',
            'superior_expenditure_object_id' => 319
        ]);
        ExpenditureObject::create([
            'code' => '833',
            'level' => 3,
            'description' => 'Transferencias a municipalidades',
            'alias' => '',
            'superior_expenditure_object_id' => 319
        ]);
        ExpenditureObject::create([
            'code' => '834',
            'level' => 3,
            'description' => 'Otras transferencias al sector público y a organismos regionales.',
            'alias' => '',
            'superior_expenditure_object_id' => 319
        ]);
        ExpenditureObject::create([
            'code' => '835',
            'level' => 3,
            'description' => 'Otros aportes de la tesorería general',
            'alias' => '',
            'superior_expenditure_object_id' => 319
        ]);
        ExpenditureObject::create([
            'code' => '836',
            'level' => 3,
            'description' => 'Transferencias a organizaciones municipales',
            'alias' => '',
            'superior_expenditure_object_id' => 319
        ]);
        ExpenditureObject::create([
            'code' => '837',
            'level' => 3,
            'description' => 'Transferencias a entidades de seguridad social',
            'alias' => '',
            'superior_expenditure_object_id' => 319
        ]);
        ExpenditureObject::create([
            'code' => '838',
            'level' => 3,
            'description' => 'Transferencias por subsidio de tarifa social a la ANDE – Ley N° / “QUE AMPLIA LA TARIFA SOCIAL DE ENERGIA ELECTRICA”',
            'alias' => '',
            'superior_expenditure_object_id' => 319
        ]);
        ExpenditureObject::create([
            'code' => '839',
            'level' => 3,
            'description' => 'Otras transferencias corrientes al sector público o privado varias',
            'alias' => '',
            'superior_expenditure_object_id' => 319
        ]);
        ExpenditureObject::create([
            'code' => '840',
            'level' => 2,
            'description' => 'TRANSFERENCIAS CORRIENTES AL SECTOR PRIVADO',
            'alias' => '',
            'superior_expenditure_object_id' => 299
        ]);
        ExpenditureObject::create([
            'code' => '841',
            'level' => 3,
            'description' => 'Becas',
            'alias' => '',
            'superior_expenditure_object_id' => 329
        ]);
        ExpenditureObject::create([
            'code' => '842',
            'level' => 3,
            'description' => 'Aportes a entidades educativas e instituciones sin fines de lucro',
            'alias' => '',
            'superior_expenditure_object_id' => 329
        ]);
        ExpenditureObject::create([
            'code' => '843',
            'level' => 3,
            'description' => 'Aportes a los partidos políticos',
            'alias' => '',
            'superior_expenditure_object_id' => 329
        ]);
        ExpenditureObject::create([
            'code' => '844',
            'level' => 3,
            'description' => 'Subsidios a los partidos políticos',
            'alias' => '',
            'superior_expenditure_object_id' => 329
        ]);
        ExpenditureObject::create([
            'code' => '845',
            'level' => 3,
            'description' => 'Indemnizaciones',
            'alias' => '',
            'superior_expenditure_object_id' => 329
        ]);
        ExpenditureObject::create([
            'code' => '846',
            'level' => 3,
            'description' => 'Subsidios y asistencia social a personas y familias del sector privado',
            'alias' => '',
            'superior_expenditure_object_id' => 329
        ]);
        ExpenditureObject::create([
            'code' => '847',
            'level' => 3,
            'description' => 'Aportes de programas de inversión pública',
            'alias' => 'APORTES PARA CAPACITACION',
            'superior_expenditure_object_id' => 329
        ]);
        ExpenditureObject::create([
            'code' => '848',
            'level' => 3,
            'description' => 'Transferencias para complemento nutricional en las escuelas públicas',
            'alias' => '',
            'superior_expenditure_object_id' => 329
        ]);
        ExpenditureObject::create([
            'code' => '849',
            'level' => 3,
            'description' => 'Otras transferencias corrientes',
            'alias' => '',
            'superior_expenditure_object_id' => 329
        ]);
        ExpenditureObject::create([
            'code' => '850',
            'level' => 2,
            'description' => 'TRANSFERENCIAS CORRIENTES AL SECTOR EXTERNO',
            'alias' => '',
            'superior_expenditure_object_id' => 299
        ]);
        ExpenditureObject::create([
            'code' => '851',
            'level' => 3,
            'description' => 'Transferencias corrientes al sector externo',
            'alias' => '',
            'superior_expenditure_object_id' => 339
        ]);
        ExpenditureObject::create([
            'code' => '859',
            'level' => 3,
            'description' => 'Transferencias corrientes al sector externo varias',
            'alias' => '',
            'superior_expenditure_object_id' => 339
        ]);
        ExpenditureObject::create([
            'code' => '860',
            'level' => 2,
            'description' => 'TRANSFERENCIAS CONSOLIDABLES DE CAPITAL AL SECTOR PUBLICO',
            'alias' => '',
            'superior_expenditure_object_id' => 299
        ]);
        ExpenditureObject::create([
            'code' => '861',
            'level' => 3,
            'description' => 'Transferencias consolidables de la administración central a entidades descentralizadas',
            'alias' => '',
            'superior_expenditure_object_id' => 342
        ]);
        ExpenditureObject::create([
            'code' => '862',
            'level' => 3,
            'description' => 'Transferencias consolidables de las entidades descentralizadas a la administración central',
            'alias' => '',
            'superior_expenditure_object_id' => 342
        ]);
        ExpenditureObject::create([
            'code' => '863',
            'level' => 3,
            'description' => 'Transferencias consolidables por coparticipación IVA',
            'alias' => '',
            'superior_expenditure_object_id' => 342
        ]);
        ExpenditureObject::create([
            'code' => '864',
            'level' => 3,
            'description' => 'Transferencias consolidables por coparticipación juegos de azar',
            'alias' => '',
            'superior_expenditure_object_id' => 342
        ]);
        ExpenditureObject::create([
            'code' => '865',
            'level' => 3,
            'description' => 'Transferencias consolidables por coparticipación royalties',
            'alias' => '',
            'superior_expenditure_object_id' => 342
        ]);
        ExpenditureObject::create([
            'code' => '866',
            'level' => 3,
            'description' => 'Transferencias consolidables entre entidades descentralizadas',
            'alias' => '',
            'superior_expenditure_object_id' => 342
        ]);
        ExpenditureObject::create([
            'code' => '867',
            'level' => 3,
            'description' => 'Transferencias consolidables entre organismos de la administración central',
            'alias' => '',
            'superior_expenditure_object_id' => 342
        ]);
        ExpenditureObject::create([
            'code' => '869',
            'level' => 3,
            'description' => 'Otras transferencias consolidables de capital',
            'alias' => '',
            'superior_expenditure_object_id' => 342
        ]);
        ExpenditureObject::create([
            'code' => '870',
            'level' => 2,
            'description' => 'TRANSFERENCIAS DE CAPITAL AL SECTOR PRIVADO',
            'alias' => '',
            'superior_expenditure_object_id' => 299
        ]);
        ExpenditureObject::create([
            'code' => '871',
            'level' => 3,
            'description' => 'Transferencias de capital al sector privado',
            'alias' => '',
            'superior_expenditure_object_id' => 351
        ]);
        ExpenditureObject::create([
            'code' => '872',
            'level' => 3,
            'description' => 'Transferencias del fondo de servicios universales',
            'alias' => '',
            'superior_expenditure_object_id' => 351
        ]);
        ExpenditureObject::create([
            'code' => '873',
            'level' => 3,
            'description' => 'Transferencias del programa Apoyo de Certificado Agronómico',
            'alias' => '',
            'superior_expenditure_object_id' => 351
        ]);
        ExpenditureObject::create([
            'code' => '879',
            'level' => 3,
            'description' => 'Transferencias de capital al sector privado varias',
            'alias' => '',
            'superior_expenditure_object_id' => 351
        ]);
        ExpenditureObject::create([
            'code' => '880',
            'level' => 2,
            'description' => 'TRANSFERENCIAS DE CAPITAL AL SECTOR EXTERNO',
            'alias' => '',
            'superior_expenditure_object_id' => 299
        ]);
        ExpenditureObject::create([
            'code' => '881',
            'level' => 3,
            'description' => 'Transferencias de capital al sector externo',
            'alias' => '',
            'superior_expenditure_object_id' => 356
        ]);
        ExpenditureObject::create([
            'code' => '889',
            'level' => 3,
            'description' => 'Transferencias de capital al sector externo varias',
            'alias' => '',
            'superior_expenditure_object_id' => 356
        ]);
        ExpenditureObject::create([
            'code' => '890',
            'level' => 2,
            'description' => 'OTRAS TRANSFERENCIAS DE CAPITAL AL SECTOR PÚBLICO O PRIVADO',
            'alias' => '',
            'superior_expenditure_object_id' => 299
        ]);
        ExpenditureObject::create([
            'code' => '891',
            'level' => 3,
            'description' => 'Transferencias de capital al Banco Central del Paraguay',
            'alias' => '',
            'superior_expenditure_object_id' => 359
        ]);
        ExpenditureObject::create([
            'code' => '892',
            'level' => 3,
            'description' => 'Aportes de la tesorería general',
            'alias' => '',
            'superior_expenditure_object_id' => 359
        ]);
        ExpenditureObject::create([
            'code' => '893',
            'level' => 3,
            'description' => 'Transferencias a municipalidades',
            'alias' => '',
            'superior_expenditure_object_id' => 359
        ]);
        ExpenditureObject::create([
            'code' => '894',
            'level' => 3,
            'description' => 'Otras transferencias al sector público',
            'alias' => 'Otras transferencias al sector público',
            'superior_expenditure_object_id' => 359
        ]);
        ExpenditureObject::create([
            'code' => '895',
            'level' => 3,
            'description' => 'Otros aportes de la tesorería general',
            'alias' => '',
            'superior_expenditure_object_id' => 359
        ]);
        ExpenditureObject::create([
            'code' => '896',
            'level' => 3,
            'description' => 'Transferencias a organizaciones municipales',
            'alias' => '',
            'superior_expenditure_object_id' => 359
        ]);
        ExpenditureObject::create([
            'code' => '897',
            'level' => 3,
            'description' => 'Transferencias de capital al fondo de jubilaciones y pensiones para miembros del Poder Legislativo',
            'alias' => '',
            'superior_expenditure_object_id' => 359
        ]);
        ExpenditureObject::create([
            'code' => '898',
            'level' => 3,
            'description' => 'Transferencias de capital del Banco Central del Paraguay al fondo de garantía de desarrollo (FGD)',
            'alias' => '',
            'superior_expenditure_object_id' => 359
        ]);
        ExpenditureObject::create([
            'code' => '899',
            'level' => 3,
            'description' => 'Otras transferencias de capital al sector público o privado varias',
            'alias' => '',
            'superior_expenditure_object_id' => 359
        ]);
        ExpenditureObject::create([
            'code' => '900',
            'level' => 1,
            'description' => 'OTROS GASTOS',
            'alias' => '',
        ]);
        ExpenditureObject::create([
            'code' => '910',
            'level' => 2,
            'description' => 'PAGO DE IMPUESTOS, TASAS, GASTOS JUDICIALES Y OTROS',
            'alias' => '',
            'superior_expenditure_object_id' => 369
        ]);
        ExpenditureObject::create([
            'code' => '911',
            'level' => 3,
            'description' => 'Impuestos directos',
            'alias' => '',
            'superior_expenditure_object_id' => 370
        ]);
        ExpenditureObject::create([
            'code' => '912',
            'level' => 3,
            'description' => 'Impuestos indirectos',
            'alias' => '',
            'superior_expenditure_object_id' => 370
        ]);
        ExpenditureObject::create([
            'code' => '913',
            'level' => 3,
            'description' => 'Tasas y contribuciones',
            'alias' => '',
            'superior_expenditure_object_id' => 370
        ]);
        ExpenditureObject::create([
            'code' => '914',
            'level' => 3,
            'description' => 'Multas y recargos',
            'alias' => '',
            'superior_expenditure_object_id' => 370
        ]);
        ExpenditureObject::create([
            'code' => '915',
            'level' => 3,
            'description' => 'Gastos judiciales',
            'alias' => '',
            'superior_expenditure_object_id' => 370
        ]);
        ExpenditureObject::create([
            'code' => '916',
            'level' => 3,
            'description' => 'Estudio de histocompatibilidad (HLA) e inmunegenética (ADN)',
            'alias' => '',
            'superior_expenditure_object_id' => 370
        ]);
        ExpenditureObject::create([
            'code' => '917',
            'level' => 3,
            'description' => 'Adquisiciones simuladas',
            'alias' => '',
            'superior_expenditure_object_id' => 370
        ]);
        ExpenditureObject::create([
            'code' => '919',
            'level' => 3,
            'description' => 'Impuestos, tasas y gastos judiciales varios',
            'alias' => '',
            'superior_expenditure_object_id' => 370
        ]);
        ExpenditureObject::create([
            'code' => '920',
            'level' => 2,
            'description' => 'DEVOLUCION DE IMPUESTOS Y OTROS INGRESOS NO TRIBUTARIOS',
            'alias' => '',
            'superior_expenditure_object_id' => 369
        ]);
        ExpenditureObject::create([
            'code' => '921',
            'level' => 3,
            'description' => 'Devoluciones de impuestos, tasas y contribuciones',
            'alias' => '',
            'superior_expenditure_object_id' => 379
        ]);
        ExpenditureObject::create([
            'code' => '922',
            'level' => 3,
            'description' => 'Devolución de ingresos no tributarios',
            'alias' => '',
            'superior_expenditure_object_id' => 379
        ]);
        ExpenditureObject::create([
            'code' => '923',
            'level' => 3,
            'description' => 'Devolución de aranceles',
            'alias' => '',
            'superior_expenditure_object_id' => 379
        ]);
        ExpenditureObject::create([
            'code' => '925',
            'level' => 3,
            'description' => 'Devolución de depósitos y garantías',
            'alias' => '',
            'superior_expenditure_object_id' => 379
        ]);
        ExpenditureObject::create([
            'code' => '929',
            'level' => 3,
            'description' => 'Devoluciones varias',
            'alias' => '',
            'superior_expenditure_object_id' => 379
        ]);
        ExpenditureObject::create([
            'code' => '930',
            'level' => 2,
            'description' => 'INTERESES DE LAS ENTIDADES FINANCIERAS PÚBLICAS',
            'alias' => '',
            'superior_expenditure_object_id' => 369
        ]);
        ExpenditureObject::create([
            'code' => '939',
            'level' => 3,
            'description' => 'Intereses de las entidades financieras públicas varios',
            'alias' => '',
            'superior_expenditure_object_id' => 385
        ]);
        ExpenditureObject::create([
            'code' => '940',
            'level' => 2,
            'description' => 'DESCUENTOS POR VENTAS',
            'alias' => '',
            'superior_expenditure_object_id' => 369
        ]);
        ExpenditureObject::create([
            'code' => '949',
            'level' => 3,
            'description' => 'Descuentos varios',
            'alias' => '',
            'superior_expenditure_object_id' => 387
        ]);
        ExpenditureObject::create([
            'code' => '950',
            'level' => 2,
            'description' => 'RESERVAS TECNICAS Y CAMBIARIAS',
            'alias' => '',
            'superior_expenditure_object_id' => 369
        ]);
        ExpenditureObject::create([
            'code' => '951',
            'level' => 3,
            'description' => 'Previsión para diferencia de cambio',
            'alias' => '',
            'superior_expenditure_object_id' => 389
        ]);
        ExpenditureObject::create([
            'code' => '959',
            'level' => 3,
            'description' => 'Reservas técnicas varias',
            'alias' => '',
            'superior_expenditure_object_id' => 389
        ]);
        ExpenditureObject::create([
            'code' => '960',
            'level' => 2,
            'description' => 'DEUDAS PENDIENTES DE PAGO DE GASTOS CORRIENTES DE EJERCICIOS ANTERIORES',
            'alias' => '',
            'superior_expenditure_object_id' => 369
        ]);
        ExpenditureObject::create([
            'code' => '961',
            'level' => 3,
            'description' => 'Servicios personales',
            'alias' => '',
            'superior_expenditure_object_id' => 392
        ]);
        ExpenditureObject::create([
            'code' => '962',
            'level' => 3,
            'description' => 'Servicios no personales',
            'alias' => '',
            'superior_expenditure_object_id' => 392
        ]);
        ExpenditureObject::create([
            'code' => '963',
            'level' => 3,
            'description' => 'Bienes de Consumo e Insumos',
            'alias' => '',
            'superior_expenditure_object_id' => 392
        ]);
        ExpenditureObject::create([
            'code' => '964',
            'level' => 3,
            'description' => 'Servicios de la deuda pública',
            'alias' => '',
            'superior_expenditure_object_id' => 392
        ]);
        ExpenditureObject::create([
            'code' => '965',
            'level' => 3,
            'description' => 'Transferencias',
            'alias' => '',
            'superior_expenditure_object_id' => 392
        ]);
        ExpenditureObject::create([
            'code' => '969',
            'level' => 3,
            'description' => 'Otros gastos',
            'alias' => '',
            'superior_expenditure_object_id' => 392
        ]);
        ExpenditureObject::create([
            'code' => '970',
            'level' => 2,
            'description' => 'GASTOS RESERVADOS',
            'alias' => '',
            'superior_expenditure_object_id' => 369
        ]);
        ExpenditureObject::create([
            'code' => '979',
            'level' => 3,
            'description' => 'Gastos reservados',
            'alias' => '',
            'superior_expenditure_object_id' => 399
        ]);
        ExpenditureObject::create([
            'code' => '980',
            'level' => 2,
            'description' => 'DEUDAS PENDIENTES DE PAGO DE GASTOS DE CAPITAL DE EJERCICIOS ANTERIORES',
            'alias' => '',
            'superior_expenditure_object_id' => 369
        ]);
        ExpenditureObject::create([
            'code' => '981',
            'level' => 3,
            'description' => 'Servicios personales',
            'alias' => '',
            'superior_expenditure_object_id' => 401
        ]);
        ExpenditureObject::create([
            'code' => '982',
            'level' => 3,
            'description' => 'Servicios no personales',
            'alias' => '',
            'superior_expenditure_object_id' => 401
        ]);
        ExpenditureObject::create([
            'code' => '983',
            'level' => 3,
            'description' => 'Bienes de consumo e insumos',
            'alias' => '',
            'superior_expenditure_object_id' => 401
        ]);
        ExpenditureObject::create([
            'code' => '984',
            'level' => 3,
            'description' => 'Bienes de cambio',
            'alias' => '',
            'superior_expenditure_object_id' => 401
        ]);
        ExpenditureObject::create([
            'code' => '985',
            'level' => 3,
            'description' => 'Inversión física',
            'alias' => '',
            'superior_expenditure_object_id' => 401
        ]);
        ExpenditureObject::create([
            'code' => '986',
            'level' => 3,
            'description' => 'Inversión financiera',
            'alias' => '',
            'superior_expenditure_object_id' => 401
        ]);
        ExpenditureObject::create([
            'code' => '987',
            'level' => 3,
            'description' => 'Servicios de la deuda pública',
            'alias' => '',
            'superior_expenditure_object_id' => 401
        ]);
        ExpenditureObject::create([
            'code' => '988',
            'level' => 3,
            'description' => 'Transferencias',
            'alias' => '',
            'superior_expenditure_object_id' => 401
        ]);
        ExpenditureObject::create([
            'code' => '989',
            'level' => 3,
            'description' => 'Otros gastos',
            'alias' => '',
            'superior_expenditure_object_id' => 401
        ]);
        ExpenditureObject::create([
            'code' => '874',
            'level' => 3,
            'description' => 'Aportes y subsidios a entidades educativas e instituciones privadas sin fines de lucro',
            'alias' => 'TRANSFERENCIAS (INFRA/BIBLIO/MOBIL/PROYECTO)',
            'superior_expenditure_object_id' => 351
        ]);
        ExpenditureObject::create([
            'code' => '852',
            'level' => 3,
            'description' => 'Transferencias corrientes a Entidades del Sector Privado, Académico y/o Público del Exterior',
            'alias' => 'Transferencias corrientes a Entidades del Sector Privado, Académico y/o Público del Exterior',
            'superior_expenditure_object_id' => 339
        ]);
    }
}
