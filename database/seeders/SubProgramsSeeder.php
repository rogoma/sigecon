<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubProgram;

class SubProgramsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SubProgram::create(['description' => 'GESTION ADMINISTRATIVA P/ EL FUNCIONAMIENTO INSTITUCIONAL',	
            'program_id' => 1,	
            'activity_code' => 1,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 2,
        ]);

        SubProgram::create([
            'description' => 'RECURSOS FINANCIEROS TRANSFERIDOS A CONSEJOS DE SALUD',	
            'program_id' => 1,	
            'activity_code' => 2,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 20,
        ]);

        SubProgram::create([
            'description' => 'CONSTRUCCIÓN DEL HOSPITAL NACIONAL DE CORONEL OVIEDO Snip 649',	
            'program_id' => 1,	
            'activity_code' => 4,	
            'proyecto' => '649',
            'program_measurement_unit_id' => 10,
        ]);

        SubProgram::create([
            'description' => 'CONSTRUCCIÓN GRAN HOSPITAL GENERAL DE BARRIO OBRERO',	
            'program_id' => 1,	
            'activity_code' => 5,	
            'proyecto' => '765',
            'program_measurement_unit_id' => 10,
        ]);

        SubProgram::create([
            'description' => 'MEJORAMIENTO FORT. Y APOYO INTEGRAL A LOS SERV DE SALUD PUB.',	
            'program_id' => 1,	
            'activity_code' => 6,	
            'proyecto' => '773',
            'program_measurement_unit_id' => 1,
        ]);

        SubProgram::create([
            'description' => 'ACCIONES PARA EL APOYO A LOS SERV. DE SALUD',	
            'program_id' => 1,	
            'activity_code' => 7,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 1,
        ]);

        SubProgram::create([
            'description' => 'SP PROGRAMA DE DESARROLLO INFANTIL TEMPRANO (DIT)',	
            'program_id' => 1,	
            'activity_code' => 8,	
            'proyecto' => '151',
            'program_measurement_unit_id' => 1,
        ]);

        SubProgram::create([
            'description' => 'CONTROL DE LA ZOONOSIS',	
            'program_id' => 1,	
            'activity_code' => 9,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 1,
        ]);

        SubProgram::create([
            'description' => 'ENFERMED. TRANSMITIDAS POR VECTORES REDUCIDAS Y CONTROLADAS',	
            'program_id' => 1,	
            'activity_code' => 10,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 1,
        ]);

        SubProgram::create([
            'description' => 'CURSOS Y CAPACITACIONES EN EL AREA DE SALUD',	
            'program_id' => 1,	
            'activity_code' => 11,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 6,
        ]);

        SubProgram::create([
            'description' => 'INVESTIGACIÓN, EDUC. Y BIOTECNOLOGÍA APLICADAS A LA SALUD',	
            'program_id' => 1,	
            'activity_code' => 12,	
            'proyecto' => '153',
            'program_measurement_unit_id' => 1,
        ]);

        SubProgram::create([
            'description' => 'ACCIONES PARA LA ATENCIÓN INTEGRAL DEL VIH-SIDA',	
            'program_id' => 1,	
            'activity_code' => 13,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 18,
        ]);

        SubProgram::create([
            'description' => 'VIGILANCIA DE SALUD Y RIESGOS ASOCIADOS A SUS DETERMINANTES',	
            'program_id' => 1,	
            'activity_code' => 14,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 1,
        ]);

        SubProgram::create([
            'description' => 'VIGILANCIA DE ENFERMEDADES NO TRANSMISIBLES',	
            'program_id' => 1,	
            'activity_code' => 15,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 1,
        ]);

        SubProgram::create([
            'description' => 'ATENCION INTEGRAL A PACIENTES CON ENFERM. DE LA DIABETES',	
            'program_id' => 1,	
            'activity_code' => 16,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 1,
        ]);

        SubProgram::create([
            'description' => 'GENERACIÓN DE INFORM. DE CALID. S/ LA SITUAC. DE SALUD DEL TERRITORIO',	
            'program_id' => 1,	
            'activity_code' => 17,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 12,
        ]);

        SubProgram::create([
            'description' => 'INSPECCIONES Y HABILITACIONES A ESTABLECIMIENTOS SANITARIOS',	
            'program_id' => 1,	
            'activity_code' => 18,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 11,
        ]);

        SubProgram::create([
            'description' => 'REGISTROS NUEVOS Y RENOVACIÓN DE PRODUCTOS SANITARIOS',	
            'program_id' => 1,	
            'activity_code' => 19,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 17,
        ]);

        SubProgram::create([
            'description' => 'CATEG. Y ACRED. ENTID. PRESTAD. DE SERV. DE SALUD NIVEL NAC.',	
            'program_id' => 1,	
            'activity_code' => 20,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 1,
        ]);

        SubProgram::create([
            'description' => 'REGULAR Y FISCALIZAR LA UTILIZACIÓN DE SANGRE HUMANA Y DERIV.',	
            'program_id' => 1,	
            'activity_code' => 21,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 1,
        ]);

        SubProgram::create([
            'description' => 'MEJORAMIENTO DE LA CALIDAD DE ATENCIÓN DE LOS ESTAB. MSPBS',	
            'program_id' => 1,	
            'activity_code' => 22,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 10,
        ]);

        SubProgram::create([
            'description' => 'CONSTRUCCIÓN FORTALECIMIENTO DE ATENCIÓN PRIMARIA EN SALUD',	
            'program_id' => 1,	
            'activity_code' => 23,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 16,
        ]);

        SubProgram::create([
            'description' => 'AMPLIACIÓN DE COBERTURA Y MEJORAMIENTO DE SALUD',	
            'program_id' => 1,	
            'activity_code' => 24,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 10,
        ]);

        SubProgram::create([
            'description' => 'AMPLIACIÓN DE COBERTURA Y MEJORAMIENTO DE SALUD',	
            'program_id' => 1,	
            'activity_code' => 25,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 10,
        ]);

        SubProgram::create([
            'description' => 'SERVICIOS DE ATENCIÓN PRIMARIA DE LA SALUD',	
            'program_id' => 2,	
            'activity_code' => 1,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'SERVICIOS DE ATENC. INTEGRAL. A LA POBLACION DE CONCEPCION',	
            'program_id' => 3,	
            'activity_code' => 1,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'SERV. DE ATEN. INTEGRAL A LA POBLACIÓN DE SAN PEDRO',	
            'program_id' => 3,	
            'activity_code' => 2,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'SERV. DE ATEN. INTEG. A LA POBLACION DE CORDILLERA',	
            'program_id' => 3,	
            'activity_code' => 3,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'SERV. DE ATEN.INT. A POBLACIÓN DE GUÁIRA',	
            'program_id' => 3,	
            'activity_code' => 4,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'SERV. DE ATEN. INT. A POBLACIÓN DE CAAGUAZÚ',	
            'program_id' => 3,	
            'activity_code' => 5,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'SERV. DE ATEN. INT. A POBLACIÓN DE CAAZAPÁ',	
            'program_id' => 3,	
            'activity_code' => 6,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'SERV. DE ATEN. INT. A POBLACIÓN DE ITAPÚA',	
            'program_id' => 3,	
            'activity_code' => 7,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'SERV. DE ATEN. INT. A POBLACIÓN DE MISIONES',	
            'program_id' => 3,	
            'activity_code' => 8,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'SERV. DE ATEN. INT. A POBLACIÓN DE PARAGUARÍ',	
            'program_id' => 3,	
            'activity_code' => 9,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'SERV. DE ATEN. INT. A POBLACIÓN DE ALTO PARANÁ',	
            'program_id' => 3,	
            'activity_code' => 10,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'SERV. DE ATEN. INT. A POBLACIÓN DE CENTRAL',	
            'program_id' => 3,	
            'activity_code' => 11,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'SERV. DE ATEN. INT. A POBLACIÓN DE ÑEEMBUCU',	
            'program_id' => 3,	
            'activity_code' => 12,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'SERV. DE ATEN. INT. A POBLACIÓN DE AMAMBAY',	
            'program_id' => 3,	
            'activity_code' => 13,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'SERV. DE ATEN. INT. A POBLACIÓN DE CANINDEYÚ',	
            'program_id' => 3,	
            'activity_code' => 14,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'SERV. DE ATEN. INT. A POBLACIÓN DE PDTE. HAYES',	
            'program_id' => 3,	
            'activity_code' => 15,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'SERV. DE ATEN. INT. A POBLACIÓN DE BOQUERÓN',	
            'program_id' => 3,	
            'activity_code' => 16,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'SERV. DE ATEN. INT. A POBLACIÓN DE ALTO PARAGUAY',	
            'program_id' => 3,	
            'activity_code' => 17,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'SERV. DE ATEN. INT. A POBLACIÓN DE LA CAPITAL',	
            'program_id' => 3,	
            'activity_code' => 18,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'SERV. DE ATEN. INT. A POBLACIÓN DE VILLETA',	
            'program_id' => 3,	
            'activity_code' => 19,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'SERV. DE ATEN. INT. A POBLACIÓN EN H. BARRIO OBRERO',	
            'program_id' => 3,	
            'activity_code' => 20,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'SERV. DE ATEN. INT. A POBLACIÓN EN H. DE ITÁ',	
            'program_id' => 3,	
            'activity_code' => 21,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'SERV. DE ATEN. INT. A POBLACIÓN EN EL H.D. ITAUGUA',	
            'program_id' => 3,	
            'activity_code' => 22,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'SERV. DE ATEN. INT. A POBLACIÓN EN EL H. DE LUQUE',	
            'program_id' => 3,	
            'activity_code' => 23,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'SERV. DE ATEN. INT. A POBLACIÓN EN H. DE ÑEMBY',	
            'program_id' => 3,	
            'activity_code' => 24,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'SERV. DE ATEN. INT. A POBLACIÓN EN EL H. SAN LORENZO',	
            'program_id' => 3,	
            'activity_code' => 25,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'SERV. DE ATEN. INT. A POBLACIÓN EN EL H. DE VILLA ELISA',	
            'program_id' => 3,	
            'activity_code' => 26,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'SERV. DE ATENCIÓN INTEGRAL A LA POB. EN EL H. PEDIATRICO',	
            'program_id' => 3,	
            'activity_code' => 27,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'SERV. DE ATEN.INT. A POBLAC. EN EL HOSP. NACIONAL',	
            'program_id' => 3,	
            'activity_code' => 28,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'SERV. DE ATEN. INT. A POBLACIÓN EN EL H. STA. ROSA DEL AGUARAY',	
            'program_id' => 3,	
            'activity_code' => 29,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'SERV. DE ATEN. INT. A POBLACIÓN EN EL H. LAMBARÉ',	
            'program_id' => 3,	
            'activity_code' => 30,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'SERV. DE ATEN. INT. A POBLACIÓN EN EL H.FDO DE LA MORA',	
            'program_id' => 3,	
            'activity_code' => 31,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'SERV. DE ATENC. INT. A POBLACIÓN EN EL H. LIMPIO',	
            'program_id' => 3,	
            'activity_code' => 32,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'SERV. DE ATEN. INT. A POBLACIÓN EN EL H. LOMA PYTA',	
            'program_id' => 3,	
            'activity_code' => 33,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'SERV. DE ATEN. INT. A POBLACIÓN EN EL H. CAPIATÁ',	
            'program_id' => 3,	
            'activity_code' => 34,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'SERV. DE ATEN. INT. A POBLACIÓN EN EL H.M.R.A.',	
            'program_id' => 3,	
            'activity_code' => 35,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'SERV. DE ATEN. EN EL INST. NAC. DE ABLACIÓN Y TRASP.',	
            'program_id' => 3,	
            'activity_code' => 36,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'ATEN. INT. A POBLACIÓN H. INDÍGENA SAN ROQUE G. SC.',	
            'program_id' => 3,	
            'activity_code' => 37,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'ATEN. DE AFECCIONES CARDIOVAS. H. SAN JORGE',	
            'program_id' => 3,	
            'activity_code' => 38,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'ATENCIÓN A POBLAC. PROG. NAC. PREV. CARDIOVASCULAR',	
            'program_id' => 3,	
            'activity_code' => 39,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'ATENCION INT. EN SALUD RESPIRATORIA - INERAM',	
            'program_id' => 3,	
            'activity_code' => 40,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'ATENCION DE POBLAC. CON ENFERM. INFECCIOSAS - IMT',	
            'program_id' => 3,	
            'activity_code' => 41,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'ATENCION A LA POBLACIÓN CON ENFERMEDADES ONCOLOGICAS - INCAN',	
            'program_id' => 3,	
            'activity_code' => 42,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'ATENC. DE POBLAC. C/ ENFERM. ONCOLOG. DE CUELLO UTERINO',	
            'program_id' => 3,	
            'activity_code' => 43,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'ATENC. INT. DEL QUEMADO - CENQUER',	
            'program_id' => 3,	
            'activity_code' => 44,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'ATEN. INT. EN EL CENT. NAC. DE CONTROL DE ADICCIONES',	
            'program_id' => 3,	
            'activity_code' => 45,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'ATENCIÓN A PACIENTES RENALES INST. NAC. DE NEFROLOGIA',	
            'program_id' => 3,	
            'activity_code' => 46,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'ATENCIÓN A LAS URGENCIAS MÉDICO, QUIRURGICAS - H . TRAUMA',	
            'program_id' => 3,	
            'activity_code' => 47,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'SERVICIO DE PROVISIÓN DE LECHE MATERNA - BANCO DE LECHE',	
            'program_id' => 3,	
            'activity_code' => 48,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 1,
        ]);

        SubProgram::create([
            'description' => 'ATENCIÓN A LA POBLACIÓN CON ENFERMEDAD MENTAL - H. PSIQUIÁTRICO',	
            'program_id' => 3,	
            'activity_code' => 49,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'ATENCIÓN INTEGRAL A LA POBLACIÓN MAT. INF. SAN PABLO',	
            'program_id' => 3,	
            'activity_code' => 50,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'ATENCIÓN INTEGRAL A POBLACIÓN MAT. INF. CRUZ ROJA PYA.',	
            'program_id' => 3,	
            'activity_code' => 51,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'ATENCIÓN INTEGRAL A POBLACIÓN MAT. INF. STMA. TRINIDAD',	
            'program_id' => 3,	
            'activity_code' => 52,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'CAPACITACIONES, FORMACIÓN E INVESTIGACIÓN EN EL IMT',	
            'program_id' => 3,	
            'activity_code' => 53,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 5,
        ]);

        SubProgram::create([
            'description' => 'CAPACITACIONES, FORMACIÓN E INVESTIGACIÓN EN EL H. NACIONAL',	
            'program_id' => 3,	
            'activity_code' => 54,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 5,
        ]);

        SubProgram::create([
            'description' => 'CAPACITACIÓN, FORMACIÓN E INVESTIGACIÓN EN EL H. PEDIATRICO',	
            'program_id' => 3,	
            'activity_code' => 55,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 5,
        ]);

        SubProgram::create([
            'description' => 'CAPACITACIÓN, FORMACIÓN E INVESTIGACION EN EL INERAM',	
            'program_id' => 3,	
            'activity_code' => 56,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 5,
        ]);

        SubProgram::create([
            'description' => 'CAPACITACIÓN, FORMACIÓN E INVESTIGACIÓN EN EL CENQUER',	
            'program_id' => 3,	
            'activity_code' => 57,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 5,
        ]);

        SubProgram::create([
            'description' => 'OBTENCIÓN, PRODUCCIÓN Y SUMINISTROS DE SANGRE',	
            'program_id' => 4,	
            'activity_code' => 1,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 1,
        ]);

        SubProgram::create([
            'description' => 'HABILITACIÓN Y CONTROL DE LABORATORIOS DE ANÁLISIS CLÍNICOS',	
            'program_id' => 4,	
            'activity_code' => 2,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 11,
        ]);

        SubProgram::create([
            'description' => 'SERVICIOS LABORATORIALES',	
            'program_id' => 4,	
            'activity_code' => 3,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 7,
        ]);

        SubProgram::create([
            'description' => 'GESTIONES INTEGRADAS PARA LA PROVISIÓN DE BIOLÓGICOS',	
            'program_id' => 4,	
            'activity_code' => 4,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 8,
        ]);

        SubProgram::create([
            'description' => 'INTERVENCIONES EN CASOS EMERG. EXTRAHOSPITALARIAS',	
            'program_id' => 4,	
            'activity_code' => 5,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 14,
        ]);

        SubProgram::create([
            'description' => 'PROV. DE MEDICAMENTOS, INSUMOS, INST. Y EQUIPOS',	
            'program_id' => 4,	
            'activity_code' => 6,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 1,
        ]);

        SubProgram::create([
            'description' => 'PROMOCIÓN Y TRATAMIENTO DE FIBRÓSIS QUÍSTICA Y RETARDO MENTAL',	
            'program_id' => 5,	
            'activity_code' => 1,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 18,
        ]);

        SubProgram::create([
            'description' => 'MEDICAMENTOS PARA ENFERMEDADES LISOSOMALES',	
            'program_id' => 5,	
            'activity_code' => 2,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 15,
        ]);

        SubProgram::create([
            'description' => 'ATENCIÓN INTEGRAL A LA POB. CON ENFERMEDADES OCULARES',	
            'program_id' => 5,	
            'activity_code' => 3,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'ATENCIÓN INTEGRAL DE POBLACIÓN CON ENFERMEDADES BUCALES',	
            'program_id' => 5,	
            'activity_code' => 4,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'ENTREGA DE INSUMOS PARA PERSONAS CON OSTOMÍA',	
            'program_id' => 5,	
            'activity_code' => 5,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 13,
        ]);

        SubProgram::create([
            'description' => 'ASISTENCIA A LA POBLACIÓN (ANATOMÍA Y SALUD MENTAL)',	
            'program_id' => 5,	
            'activity_code' => 6,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 3,
        ]);

        SubProgram::create([
            'description' => 'CAPACITACIONES DE RR.HH. DEL MSPYBS.',	
            'program_id' => 5,	
            'activity_code' => 7,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 5,
        ]);

        SubProgram::create([
            'description' => 'ENTREGA DE MEDICAMENTOS, KIT DE PARTO Y ANTICONCEPTIVOS',	
            'program_id' => 5,	
            'activity_code' => 8,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 13,
        ]);

        SubProgram::create([
            'description' => 'VIGILANCIA ALIMENTARIA NUTRICIONAL',	
            'program_id' => 6,	
            'activity_code' => 1,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 1,
        ]);

        SubProgram::create([
            'description' => 'SENSIBILIZACIÓN EN TEMAS DE ALIMENTACIÓN Y NUTRICIÓN',	
            'program_id' => 6,	
            'activity_code' => 2,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 1,
        ]);

        SubProgram::create([
            'description' => 'ASISTENCIA ALIMENTARIA NUTRICIONAL',	
            'program_id' => 6,	
            'activity_code' => 3,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 3,
        ]);

        SubProgram::create([
            'description' => 'ATENCIÓN INTEGRAL A LA POBLACIÓN ADULTA MAYOR',	
            'program_id' => 7,	
            'activity_code' => 1,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'ATENCIÓN INTEGRAL A LA POBLACIÓN EN SITUACIÓN DE RIESGO',	
            'program_id' => 7,	
            'activity_code' => 2,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 4,
        ]);

        SubProgram::create([
            'description' => 'REGISTRO Y ACREDITACIÓN DE ENTIDADES SIN FINES DE LUCRO',	
            'program_id' => 7,	
            'activity_code' => 3,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 17,
        ]);

        SubProgram::create([
            'description' => 'GESTIÓN ADMINISTRATIVA',	
            'program_id' => 8,	
            'activity_code' => 1,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 1,
        ]);

        SubProgram::create([
            'description' => 'CONSTRUCCIÓN SEMB. OPORT. - 480 SISTEMA DE AGUA Y SANEAM. Snip 308',	
            'program_id' => 8,	
            'activity_code' => 3,	
            'proyecto' => '308',
            'program_measurement_unit_id' => 19,
        ]);

        SubProgram::create([
            'description' => 'SP ABAST. DE AGUA. POT. Y SAN. BAS. PEQ. COM. RURAL. E INDI. (FOCEM) Snip 50',	
            'program_id' => 8,	
            'activity_code' => 4,	
            'proyecto' => '50',
            'program_measurement_unit_id' => 19,
        ]);

        SubProgram::create([
            'description' => 'CONSTRUCCIÓN SIST. AGUA POT. Y SAN. PEQ. CIUD. COM. RURAL. E INDIG. Snip 555',	
            'program_id' => 8,	
            'activity_code' => 6,	
            'proyecto' => '555',
            'program_measurement_unit_id' => 19,
        ]);

        SubProgram::create([
            'description' => 'ACCIONES DE VIGILANCIA DE CALIDAD AMBIENTAL',	
            'program_id' => 8,	
            'activity_code' => 7,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 18,
        ]);

        SubProgram::create([
            'description' => 'ATENCION INTEGRAL ANTE EMERGENCIA SANITARIA',	
            'program_id' => 9,	
            'activity_code' => 1,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 2,
        ]);

        SubProgram::create([
            'description' => 'EQUIPAMIENTO EQUIP. CONTENC Y APOYO A LOS SERV. DE SALUD',	
            'program_id' => 9,	
            'activity_code' => 2,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 9,
        ]);

        SubProgram::create([
            'description' => 'TRANSFERENCIAS CONSOLIDABLES',	
            'program_id' => 10,	
            'activity_code' => 1,	
            'proyecto' => '0',
            'program_measurement_unit_id' => 2,
        ]);

    }
}
