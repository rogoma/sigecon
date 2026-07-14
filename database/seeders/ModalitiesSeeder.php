<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Modality;

class ModalitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Modality::create(['description' => 'Licitación Pública Nacional', 'code' => 'LPN', 'modality_type'=> 'Convencional']);
        // Modality::create(['description' => 'Licitación Pública Nacional', 'code' => 'LPN-SBE', 'modality_type'=> 'Subasta a la Baja Electrónica']);
        // Modality::create(['description' => 'Licitación Pública Internacional', 'code' => 'LPI', 'modality_type'=> 'Convencional']);
        // Modality::create(['description' => 'Contratación Directa', 'code' => 'CD', 'modality_type'=> 'Convencional']);
        // Modality::create(['description' => 'Contratación Directa', 'code' => 'CD-SBE', 'modality_type'=> 'Subasta a la Baja Electrónica']);
        // Modality::create(['description' => 'Licitación por Concurso de Ofertas', 'code' => 'LCO', 'modality_type'=> 'Convencional']);
        // Modality::create(['description' => 'Licitación por Concurso de Ofertas', 'code' => 'LCO-SBE', 'modality_type'=> 'Subasta a la Baja Electrónica']);
        // Modality::create(['description' => 'Fondo Fijo', 'code' => 'FF', 'modality_type'=> 'Convencional']);
        // Modality::create(['description' => 'Licitación con financiamiento', 'code' => 'LCF', 'modality_type'=> 'Complementaria']);
        // Modality::create(['description' => 'Convenio Marco', 'code' => 'CM', 'modality_type'=> 'Complementaria']);
        // Modality::create(['description' => 'Precalificación', 'code' => 'PC', 'modality_type'=> 'Complementaria']);
        // Modality::create(['description' => 'Licitación en dos o más etapas', 'code' => 'LET', 'modality_type'=> 'Complementaria']);
        // Modality::create(['description' => 'Adquisición de productos agropecuarios de la agricultura familiar', 'code' => 'APAAF', 'modality_type'=> 'Complementaria']);
        // Modality::create(['description' => 'Acuerdo Nacional y Acuerdo Internacional', 'code' => 'AN-AI', 'modality_type'=> 'Complementaria']);
        // Modality::create(['description' => 'Abastecimiento Simultáneo', 'code' => 'AS', 'modality_type'=> 'Especial']);
        // Modality::create(['description' => 'Contrato Abierto', 'code' => 'CA', 'modality_type'=> 'Especial']);
        // Modality::create(['description' => 'Leasing', 'code' => 'LS', 'modality_type'=> 'Especial']);
        // Modality::create(['description' => 'Compra por Excepción-Intención de compras', 'code' => 'CVE-IC', 'modality_type'=> 'Vía Excepción']);
        // Modality::create(['description' => 'Compras por Excepción-Difusión previa', 'code' => 'CVE-DF', 'modality_type'=> 'Vía Excepción']);         

        Modality::create(['description'=>'Licitación Pública Nacional','code'=>'LPN','modality_type'=>'Convencional','dncp_verification'=>3,'dncp_objections_verification'=>2,'press_publication'=>3,'portal_difusion'=>20,'inquiries_reception'=>5,'addendas_verification'=>1,'addenda_publication'=>5,'clarifications_publication'=>2]);
        Modality::create(['description'=>'Licitación Pública Nacional-SBE','code'=>'LPN-SBE','modality_type'=>'Subasta a la Baja Electrónica','dncp_verification'=>3,'dncp_objections_verification'=>2,'press_publication'=>0,'portal_difusion'=>8,'inquiries_reception'=>0,'addendas_verification'=>1,'addenda_publication'=>0,'clarifications_publication'=>0]);
        Modality::create(['description'=>'Licitación Pública Internacional','code'=>'LPI','modality_type'=>'Convencional','dncp_verification'=>3,'dncp_objections_verification'=>2,'press_publication'=>3,'portal_difusion'=>40,'inquiries_reception'=>5,'addendas_verification'=>1,'addenda_publication'=>5,'clarifications_publication'=>2]);
        Modality::create(['description'=>'Contratación Directa','code'=>'CD','modality_type'=>'Convencional','dncp_verification'=>3,'dncp_objections_verification'=>2,'press_publication'=>0,'portal_difusion'=>5,'inquiries_reception'=>3,'addendas_verification'=>1,'addenda_publication'=>2,'clarifications_publication'=>1]);
        Modality::create(['description'=>'Contratación Directa (menor a 200 jornales)','code'=>'CD','modality_type'=>'Convencional','dncp_verification'=>3,'dncp_objections_verification'=>2,'press_publication'=>0,'portal_difusion'=>3,'inquiries_reception'=>0,'addendas_verification'=>1,'addenda_publication'=>0,'clarifications_publication'=>0]);
        Modality::create(['description'=>'Contratación Directa-SBE','code'=>'CD-SBE','modality_type'=>'Subasta a la Baja Electrónica','dncp_verification'=>3,'dncp_objections_verification'=>2,'press_publication'=>0,'portal_difusion'=>8,'inquiries_reception'=>0,'addendas_verification'=>1,'addenda_publication'=>0,'clarifications_publication'=>0]);
        Modality::create(['description'=>'Licitación por Concurso de Ofertas','code'=>'LCO','modality_type'=>'Convencional','dncp_verification'=>3,'dncp_objections_verification'=>2,'press_publication'=>0,'portal_difusion'=>10,'inquiries_reception'=>3,'addendas_verification'=>1,'addenda_publication'=>3,'clarifications_publication'=>0]);
        Modality::create(['description'=>'Licitación por Concurso de Ofertas-SBE','code'=>'LCO-SBE','modality_type'=>'Subasta a la Baja Electrónica','dncp_verification'=>3,'dncp_objections_verification'=>2,'press_publication'=>0,'portal_difusion'=>8,'inquiries_reception'=>0,'addendas_verification'=>1,'addenda_publication'=>0,'clarifications_publication'=>0]);
        Modality::create(['description'=>'Compra por Excepción-Intención de compras','code'=>'CVE-IC','modality_type'=>'Vía Excepción','dncp_verification'=>3,'dncp_objections_verification'=>2,'press_publication'=>0,'portal_difusion'=>5,'inquiries_reception'=>3,'addendas_verification'=>1,'addenda_publication'=>2,'clarifications_publication'=>1]);
        Modality::create(['description'=>'Compras por Excepción-Difusión previa','code'=>'CVE-DF','modality_type'=>'Vía Excepción','dncp_verification'=>3,'dncp_objections_verification'=>2,'press_publication'=>0,'portal_difusion'=>5,'inquiries_reception'=>3,'addendas_verification'=>1,'addenda_publication'=>2,'clarifications_publication'=>1]);
        Modality::create(['description'=>'Locación de Inmuebles','code'=>'LI','modality_type'=>'Disposición Especial/Convencional?','dncp_verification'=>3,'dncp_objections_verification'=>2,'press_publication'=>0,'portal_difusion'=>3,'inquiries_reception'=>0,'addendas_verification'=>1,'addenda_publication'=>2,'clarifications_publication'=>0]);
        Modality::create(['description'=>'Locación de Bienes Muebles','code'=>'LBM','modality_type'=>'Disposición Especial/Convencional?','dncp_verification'=>3,'dncp_objections_verification'=>2,'press_publication'=>0,'portal_difusion'=>3,'inquiries_reception'=>0,'addendas_verification'=>1,'addenda_publication'=>2,'clarifications_publication'=>0]);
        Modality::create(['description'=>'Contratación de Servicios de Terceros','code'=>'CST','modality_type'=>'Disposición Especial/Convencional?','dncp_verification'=>0,'dncp_objections_verification'=>0,'press_publication'=>0,'portal_difusion'=>0,'inquiries_reception'=>0,'addendas_verification'=>0,'addenda_publication'=>0,'clarifications_publication'=>0]);
        Modality::create(['description'=>'Contratación de Consultorías','code'=>'CC','modality_type'=>'Disposición Especial/Convencional?','dncp_verification'=>0,'dncp_objections_verification'=>0,'press_publication'=>0,'portal_difusion'=>0,'inquiries_reception'=>0,'addendas_verification'=>0,'addenda_publication'=>0,'clarifications_publication'=>0]);
        Modality::create(['description'=>'Contrato llave en mano','code'=>'CLLM','modality_type'=>'Disposición Especial/Convencional?','dncp_verification'=>0,'dncp_objections_verification'=>0,'press_publication'=>0,'portal_difusion'=>0,'inquiries_reception'=>0,'addendas_verification'=>0,'addenda_publication'=>0,'clarifications_publication'=>0]);
        Modality::create(['description'=>'Fondo Fijo','code'=>'FF','modality_type'=>'Convencional','dncp_verification'=>0,'dncp_objections_verification'=>0,'press_publication'=>0,'portal_difusion'=>0,'inquiries_reception'=>0,'addendas_verification'=>0,'addenda_publication'=>0,'clarifications_publication'=>0]);
        Modality::create(['description'=>'Licitación con financiamiento','code'=>'LCF','modality_type'=>'Complementaria','dncp_verification'=>0,'dncp_objections_verification'=>0,'press_publication'=>0,'portal_difusion'=>0,'inquiries_reception'=>0,'addendas_verification'=>0,'addenda_publication'=>0,'clarifications_publication'=>0]);
        Modality::create(['description'=>'Convenio Marco','code'=>'CM','modality_type'=>'Complementaria','dncp_verification'=>0,'dncp_objections_verification'=>0,'press_publication'=>0,'portal_difusion'=>0,'inquiries_reception'=>0,'addendas_verification'=>0,'addenda_publication'=>0,'clarifications_publication'=>0]);
        Modality::create(['description'=>'Precalificación','code'=>'PC','modality_type'=>'Complementaria','dncp_verification'=>0,'dncp_objections_verification'=>0,'press_publication'=>0,'portal_difusion'=>0,'inquiries_reception'=>0,'addendas_verification'=>0,'addenda_publication'=>0,'clarifications_publication'=>0]);
        Modality::create(['description'=>'Licitación en dos o más etapas','code'=>'LET','modality_type'=>'Complementaria','dncp_verification'=>0,'dncp_objections_verification'=>0,'press_publication'=>0,'portal_difusion'=>0,'inquiries_reception'=>0,'addendas_verification'=>0,'addenda_publication'=>0,'clarifications_publication'=>0]);
        Modality::create(['description'=>'Adquisición de productos agropecuarios de la agricultura familiar','code'=>'APAAF','modality_type'=>'Complementaria','dncp_verification'=>0,'dncp_objections_verification'=>0,'press_publication'=>0,'portal_difusion'=>0,'inquiries_reception'=>0,'addendas_verification'=>0,'addenda_publication'=>0,'clarifications_publication'=>0]);
        Modality::create(['description'=>'Acuerdo Nacional y Acuerdo Internacional','code'=>'AN-AI','modality_type'=>'Complementaria','dncp_verification'=>0,'dncp_objections_verification'=>0,'press_publication'=>0,'portal_difusion'=>0,'inquiries_reception'=>0,'addendas_verification'=>0,'addenda_publication'=>0,'clarifications_publication'=>0]);
        Modality::create(['description'=>'Abastecimiento Simultáneo','code'=>'AS','modality_type'=>'Especial','dncp_verification'=>0,'dncp_objections_verification'=>0,'press_publication'=>0,'portal_difusion'=>0,'inquiries_reception'=>0,'addendas_verification'=>0,'addenda_publication'=>0,'clarifications_publication'=>0]);
        Modality::create(['description'=>'Contrato Abierto','code'=>'CA','modality_type'=>'Especial','dncp_verification'=>0,'dncp_objections_verification'=>0,'press_publication'=>0,'portal_difusion'=>0,'inquiries_reception'=>0,'addendas_verification'=>0,'addenda_publication'=>0,'clarifications_publication'=>0]);
        Modality::create(['description'=>'Leasing','code'=>'LS','modality_type'=>'Especial','dncp_verification'=>0,'dncp_objections_verification'=>0,'press_publication'=>0,'portal_difusion'=>0,'inquiries_reception'=>0,'addendas_verification'=>0,'addenda_publication'=>0,'clarifications_publication'=>0]);
        Modality::create(['description'=>'Contratación de Obra Pública','code'=>'COP','modality_type'=>'Disposición Especial/Convencional?','dncp_verification'=>0,'dncp_objections_verification'=>0,'press_publication'=>0,'portal_difusion'=>0,'inquiries_reception'=>0,'addendas_verification'=>0,'addenda_publication'=>0,'clarifications_publication'=>0]);
        Modality::create(['description'=>'Adquisición de Bienes Inmuebles','code'=>'ABI','modality_type'=>'Disposición Especial/Convencional?','dncp_verification'=>0,'dncp_objections_verification'=>0,'press_publication'=>0,'portal_difusion'=>0,'inquiries_reception'=>0,'addendas_verification'=>0,'addenda_publication'=>0,'clarifications_publication'=>0]);       
    }
}
