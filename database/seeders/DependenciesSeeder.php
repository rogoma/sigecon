<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dependency;

class DependenciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Dependency::create([
            'description' => 'Ministro',
            'dependency_type_id' => 1,
        ]);
        Dependency::create([
            'description' => 'Dirección General de Administración y Finanzas',
            'dependency_type_id' => 3,
            'superior_dependency_id' => 1
        ]);
        Dependency::create([
            'description' => 'Asesoría Jurídica',
            'dependency_type_id' => 8,
            'superior_dependency_id' => 2
        ]);
        Dependency::create([
            'description' => 'Coordinación General',
            'dependency_type_id' => 7,
            'superior_dependency_id' => 2
        ]);
        Dependency::create([
            'description' => 'Unidad de Logística Internacional',
            'dependency_type_id' => 9,
            'superior_dependency_id' => 2
        ]);
        Dependency::create([
            'description' => 'Unidad de Gestión de Personal RSG N° 064/15',
            'dependency_type_id' => 9,
            'superior_dependency_id' => 2
        ]);
        Dependency::create([
            'description' => 'Unidad de Control Técnico y Administrativo',
            'dependency_type_id' => 9,
            'superior_dependency_id' => 2
        ]);
        Dependency::create([
            'description' => 'Unidad de Gestión de Documental RSG N° 219/13',
            'dependency_type_id' => 9,
            'superior_dependency_id' => 2
        ]);
        Dependency::create([
            'description' => 'Sub Unidades de Administración Financiera (SUAF´s)',
            'dependency_type_id' => 10,
            'superior_dependency_id' => 2
        ]);
        Dependency::create([
            'description' => 'Dirección de Presupuesto',
            'dependency_type_id' => 4,
            'superior_dependency_id' => 2
        ]);
        Dependency::create([
            'description' => 'Dirección Financiera RSG N° 62/13',
            'dependency_type_id' => 4,
            'superior_dependency_id' => 2
        ]);
        Dependency::create([
            'description' => 'Dirección de Contabilidad RSG N° 533/16',
            'dependency_type_id' => 4,
            'superior_dependency_id' => 2
        ]);
        Dependency::create([
            'description' => 'Dirección de Recursos Físicos R. DGAF N° 01/13',
            'dependency_type_id' => 4,
            'superior_dependency_id' => 2
        ]);
        Dependency::create([
            'description' => 'Dirección Administrativa RSG N° 533/16',
            'dependency_type_id' => 4,
            'superior_dependency_id' => 2
        ]);
        Dependency::create([
            'description' => 'Dirección de Gestión y Monitoreo de Procesos Administrativos RSG N° 533/18',
            'dependency_type_id' => 4,
            'superior_dependency_id' => 2
        ]);
        Dependency::create([
            'description' => 'UOC N° 01 - Dirección Operativa de Contrataciones (DOC) - Nivel Central',
            'dependency_type_id' => 4,
            'uoc_number' => 1,
            'superior_dependency_id' => 2
        ]);
        Dependency::create([
            'description' => 'Coordinación General',
            'dependency_type_id' => 7,
            'superior_dependency_id' => 10
        ]);
        Dependency::create([
            'description' => 'Dpto de Programación Presupuestaria',
            'dependency_type_id' => 5,
            'superior_dependency_id' => 10
        ]);
        Dependency::create([
            'description' => 'Dpto. de Control y Evaluación Presupuestaria',
            'dependency_type_id' => 5,
            'superior_dependency_id' => 10
        ]);
        Dependency::create([
            'description' => 'Dpto. de Ejecución Presupuestaria',
            'dependency_type_id' => 5,
            'superior_dependency_id' => 10
        ]);
        Dependency::create([
            'description' => 'Secretaría',
            'dependency_type_id' => 13,
            'superior_dependency_id' => 11
        ]);
        Dependency::create([
            'description' => 'Unidad de Operaciones Financieras',
            'dependency_type_id' => 9,
            'superior_dependency_id' => 11
        ]);
        Dependency::create([
            'description' => 'Dpto. de Control de Pago a Proveedores',
            'dependency_type_id' => 5,
            'superior_dependency_id' => 11
        ]);
        Dependency::create([
            'description' => 'Dpto. de Giraduría de Gastos',
            'dependency_type_id' => 5,
            'superior_dependency_id' => 11
        ]);
        Dependency::create([
            'description' => 'Dpto. Giraduría de Sueldos',
            'dependency_type_id' => 5,
            'superior_dependency_id' => 11
        ]);
        Dependency::create([
            'description' => 'Dpto. Control de Cuentas',
            'dependency_type_id' => 5,
            'superior_dependency_id' => 11
        ]);
        Dependency::create([
            'description' => 'Dpto. de Ingresos',
            'dependency_type_id' => 5,
            'superior_dependency_id' => 11
        ]);
        Dependency::create([
            'description' => 'Dpto. de Fondo Rotatorio',
            'dependency_type_id' => 5,
            'superior_dependency_id' => 11
        ]);
        Dependency::create([
            'description' => 'Dpto. de Programación y Evaluación Financiera',
            'dependency_type_id' => 5,
            'superior_dependency_id' => 11
        ]);
        Dependency::create([
            'description' => 'Secretaría',
            'dependency_type_id' => 13,
            'superior_dependency_id' => 12
        ]);
        Dependency::create([
            'description' => 'Unidad de Apoyo Técnico',
            'dependency_type_id' => 9,
            'superior_dependency_id' => 12
        ]);
        Dependency::create([
            'description' => 'Coordinación Contable',
            'dependency_type_id' => 7,
            'superior_dependency_id' => 12
        ]);
        Dependency::create([
            'description' => 'Dpto. de Registración Contable',
            'dependency_type_id' => 5,
            'superior_dependency_id' => 12
        ]);
        Dependency::create([
            'description' => 'Dpto. de Rendición de Cuentas',
            'dependency_type_id' => 5,
            'superior_dependency_id' => 12
        ]);
        Dependency::create([
            'description' => 'Dpto. de Análisis y Consolidación Contable',
            'dependency_type_id' => 5,
            'superior_dependency_id' => 12
        ]);
        Dependency::create([
            'description' => 'Dpto.Patrimonial del Activo Fijo',
            'dependency_type_id' => 5,
            'superior_dependency_id' => 12
        ]);
        Dependency::create([
            'description' => 'Secretaría',
            'dependency_type_id' => 13,
            'superior_dependency_id' => 13
        ]);
        Dependency::create([
            'description' => 'Unidad de Apoyo Administrativo',
            'dependency_type_id' => 9,
            'superior_dependency_id' => 13
        ]);
        Dependency::create([
            'description' => 'Coordinación Técnica',
            'dependency_type_id' => 7,
            'superior_dependency_id' => 13
        ]);
        Dependency::create([
            'description' => 'Gestión Documental',
            'dependency_type_id' => 14,
            'superior_dependency_id' => 13
        ]);
        Dependency::create([
            'description' => 'Dpto. de Diseños y Proyectos',
            'dependency_type_id' => 5,
            'superior_dependency_id' => 13
        ]);
        Dependency::create([
            'description' => 'Dpto. de Fiscalización de Obras',
            'dependency_type_id' => 5,
            'superior_dependency_id' => 13
        ]);
        Dependency::create([
            'description' => 'Dpto. de Gestiones, Permisos y Bienes Culturales',
            'dependency_type_id' => 5,
            'superior_dependency_id' => 13
        ]);
        Dependency::create([
            'description' => 'Secretaría',
            'dependency_type_id' => 13,
            'superior_dependency_id' => 14
        ]);
        Dependency::create([
            'description' => 'Almacén del Nivel Central',
            'dependency_type_id' => 15,
            'superior_dependency_id' => 14
        ]);
        Dependency::create([
            'description' => 'Unidad de Monitoreo y Control de Servicios de Transporte y Combustible',
            'dependency_type_id' => 9,
            'superior_dependency_id' => 14
        ]);
        Dependency::create([
            'description' => 'Coordinación de Servicios y Suministros',
            'dependency_type_id' => 7,
            'superior_dependency_id' => 14
        ]);
        Dependency::create([
            'description' => 'Coordinación Administrativa',
            'dependency_type_id' => 7,
            'superior_dependency_id' => 14
        ]);
        Dependency::create([
            'description' => 'Secretaría',
            'dependency_type_id' => 13,
            'superior_dependency_id' => 15
        ]);
        Dependency::create([
            'description' => 'Coordinación',
            'dependency_type_id' => 7,
            'superior_dependency_id' => 15
        ]);
        Dependency::create([
            'description' => 'Dpto. de Regiones Sanitarias',
            'dependency_type_id' => 5,
            'superior_dependency_id' => 15
        ]);
        Dependency::create([
            'description' => 'Dpto. de Hospitales',
            'dependency_type_id' => 5,
            'superior_dependency_id' => 15
        ]);
        Dependency::create([
            'description' => 'Dpto. de Programas',
            'dependency_type_id' => 5,
            'superior_dependency_id' => 15
        ]);
        Dependency::create([
            'description' => 'Dpto. de UAF, SUBUAF y UEP',
            'dependency_type_id' => 5,
            'superior_dependency_id' => 15
        ]);
        Dependency::create([
            'description' => 'Coordinación',
            'dependency_type_id' => 7,
            'uoc_type_id' => 1,
            'uoc_number' => 1,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'Unidad Técnica de Apoyo',
            'dependency_type_id' => 9,
            'uoc_type_id' => 1,
            'uoc_number' => 1,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'Asesoría Jurídica y Técnica',
            'dependency_type_id' => 8,
            'uoc_type_id' => 1,
            'uoc_number' => 1,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'Unidad de Gestión Documental',
            'dependency_type_id' => 9,
            'uoc_type_id' => 1,
            'uoc_number' => 1,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'Dpto. de Planificación y Presupuesto',
            'dependency_type_id' => 5,
            'uoc_type_id' => 1,
            'uoc_number' => 1,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'Dpto. de Contratos y Garantías',
            'dependency_type_id' => 5,
            'uoc_type_id' => 1,
            'uoc_number' => 1,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'Dpto. de Compras Menores',
            'dependency_type_id' => 5,
            'uoc_type_id' => 1,
            'uoc_number' => 1,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'Dpto. de Licitaciones',
            'dependency_type_id' => 5,
            'uoc_type_id' => 1,
            'uoc_number' => 1,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'Dpto. Procesos Complementarios y Excepciones',
            'dependency_type_id' => 5,
            'uoc_type_id' => 1,
            'uoc_number' => 1,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'Dpto. de Adjudicaciones',
            'dependency_type_id' => 5,
            'uoc_type_id' => 1,
            'uoc_number' => 1,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'UOC N° 2 Servicio Nacional de Saneamiento Ambiental SENASA',
            'dependency_type_id' => 11,
            'uoc_type_id' => 1,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'Dpto. de Suministro',
            'dependency_type_id' => 5,
            'superior_dependency_id' => 47
        ]);
        Dependency::create([
            'description' => 'Dpto. de Transporte y Talleres',
            'dependency_type_id' => 5,
            'superior_dependency_id' => 47
        ]);
        Dependency::create([
            'description' => 'Dpto. de Servicios Generales',
            'dependency_type_id' => 5,
            'superior_dependency_id' => 47
        ]);
        Dependency::create([
            'description' => 'Dpto. de Gestión Administrativa',
            'dependency_type_id' => 5,
            'superior_dependency_id' => 48
        ]);
        Dependency::create([
            'description' => 'Dpto. de Ejecución de Contratos',
            'dependency_type_id' => 5,
            'superior_dependency_id' => 48
        ]);
        Dependency::create([
            'description' => 'Ventanilla Única de Proveedores RSG N° 382/14',
            'dependency_type_id' => 16,
            'superior_dependency_id' => 48
        ]);
        Dependency::create([
            'description' => 'Sección de Planificación',
            'dependency_type_id' => 6,
            'uoc_type_id' => 1,
            'uoc_number' => 1,
            'superior_dependency_id' => 59
        ]);
        Dependency::create([
            'description' => 'Sección de Presupuesto',
            'dependency_type_id' => 6,
            'uoc_type_id' => 1,
            'uoc_number' => 1,
            'superior_dependency_id' => 59
        ]);
        Dependency::create([
            'description' => 'Sección de Contratos',
            'dependency_type_id' => 6,
            'uoc_type_id' => 1,
            'uoc_number' => 1,
            'superior_dependency_id' => 60
        ]);
        Dependency::create([
            'description' => 'Sección de Seguros y Contratos',
            'dependency_type_id' => 6,
            'uoc_type_id' => 1,
            'uoc_number' => 1,
            'superior_dependency_id' => 60
        ]);
        Dependency::create([
            'description' => 'Sección Licitación Pública',
            'dependency_type_id' => 6,
            'uoc_type_id' => 1,
            'uoc_number' => 1,
            'superior_dependency_id' => 62
        ]);
        Dependency::create([
            'description' => 'Sección Licitación por Concurso de Ofertas',
            'dependency_type_id' => 6,
            'uoc_type_id' => 1,
            'uoc_number' => 1,
            'superior_dependency_id' => 62
        ]);
        Dependency::create([
            'description' => 'UEP - SP Programa de Desarrollo Infantil Temprano (DIT)',
            'dependency_type_id' => 17,
            'uoc_type_id' => 1,
            'uoc_number' => 1,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'UEP - Programa de Agua Potable y Saneamiento para Comunidades Rurales e Indígenas (BID 2222/OC-PR)',
            'dependency_type_id' => 17,
            'uoc_type_id' => 1,
            'uoc_number' => 2,
            'superior_dependency_id' => 65
        ]);
        Dependency::create([
            'description' => 'UEP - Const. Sembrando Oportunidades para 480 sistemas de agua potable y saneamiento',
            'dependency_type_id' => 17,
            'uoc_type_id' => 1,
            'uoc_number' => 2,
            'superior_dependency_id' => 65
        ]);
        Dependency::create([
            'description' => 'UEP-Pyto. Construcción y Mejoramiento de Sistemas de Agua Potable y Saneam. Básico en Pequeñas Comunidades Rurales e indígenas del País-SENASA-FOCEM',
            'dependency_type_id' => 17,
            'uoc_type_id' => 1,
            'uoc_number' => 2,
            'superior_dependency_id' => 65
        ]);
        Dependency::create([
            'description' => 'Proyecto de Modernización del Sector Agua y Saneamiento BIRF 77r0-PY',
            'dependency_type_id' => 17,
            'uoc_type_id' => 1,
            'uoc_number' => 2,
            'superior_dependency_id' => 65
        ]);
        Dependency::create([
            'description' => 'SubUOC N° 01- 1° Región Sanitaria Concepción',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SubUOC No 02 - 2° Región Sanitaria San Pedro',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SubUOC No 03 - 3° Región Sanitaria Cordillera',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SubUOC No 04 - 4° Región Sanitaria Guairá',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SubUOC No 05 - 5° Región Sanitaria Caaguazú',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SubUOC No 06 - 6° Región Sanitaria Caazapá',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SubUOC No 07 - 7° Región Sanitaria Itapúa',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SubUOC No 08 - 8° Región Sanitaria Misiones',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SUbUOC No 09 - 9° Región Sanitaria Paraguarí',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SubUOC No 10 - 10° Región Sanitaria Alto Paraná',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SubUOC No 11- 11° Región Sanitaria Central',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SubUOC No t2 - 12° Región Sanitaria Ñeembucú',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SubUOC No 13 - 13° Región Sanitaria Amambay',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SubUOC No 14 - 14° Región Sanítaria Canindeyú',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SubUOC No 15 - 15° Región Sanitaria Presidente Hayes.',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SUbUOC No 16 - 16° Región Sanitaria Boquerón',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SubUOC No 17 - 17° Región Sanitaria Alto Paraguay',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SubUOC No 18 - 18° Región Sanitaria Capital',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SUbUOC No 19 - Hospital de Barrio Obrero',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SubUOC No 20 - Hospital de Loma Pytá',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SubUOC No 21- Hospital Santa Rosa del Aguaray',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SubUOC No 22 - Hospital de Lambaré',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SubUOC No 23 - Hospital de Fernando de la Mora',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SubUOC No 24 - Hospital de Mariano Roque Alonso',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SubUOC No 25 - Hospital de San Lorenzo',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SubUOC No 26 - Hospital de Limpio',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SubUOC No 27 - Hospital de Capiatá',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SubUOC No 28 - Hospital de Luque',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SubUOC No 29 - Hospital de Ñemby',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SubUOC No 30 Hospital Materno Infantil San Pablo',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SubUOC No 31 - Hospital Materno Infantil Santísima Trinidad',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SubUOC No 32 - Hospital San Jorge',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SubUOC No 33 - INERAM "Prof. Dr. Juan Max Boettner"',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SUbUOC No 34 - Instituto de Medicina Tropical',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SubUOC No 35 - Instituto Nacional del Cáncer',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SubUOC No 36 Centro Nacíonal de Quemaduras y Cirugías Reconstructivas (CENQUER)',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SubUOC No 37 - Hospital General Pediátrico',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SubUOC No 38 - Hospital Nacional',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SubUOC No 39 - Hospital Psiquiátrico',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SubUOC No 40 - Instituto Nacional de Nefrología',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SubUOC No 41 - Hospital de Trauma',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SubUOC No 42 - Servicio de Emergencia Médica Extrahospitalaria (SEME)',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SUbUOC No 43 - Laboratorio Central',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SubUOC No 44 - Dirección General de Salud Ambiental (DIGESA)',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SubUOC No 45 - Control de Vigilancia de Enfermedades Transmitidas por Vectores (SENEPA)',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SUbUOC No 46 - Instituto Nacional de Alimentación y Nutrición (INAN)',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SUbUOC No 47 - Hospital Indígena',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
            Dependency::create([
                'description' => 'SubUOC No 48 - Hospital Distrital de Itá',
                'dependency_type_id' => 12,
                'uoc_type_id' => 2,
                'uoc_number' => 2,
                'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SubUOC No 49 - Hospital Distrital de ltaugúa',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SubUOC No 50 - Hospital Distrital de Villeta',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SubUOC No 51 - Hospital Distrital de Villa Elisa',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SUbUOC No 52 - Dirección de Recursos Físicos',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SubUOC No 53 - Superintendencia de Salud',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
        Dependency::create([
            'description' => 'SubUOC No 54 - Dirección Nacional de Vigilancia Sanitaria',
            'dependency_type_id' => 12,
            'uoc_type_id' => 2,
            'uoc_number' => 2,
            'superior_dependency_id' => 16
        ]);
    }
}
