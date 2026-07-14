<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;

class OrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Order::create([
            'number' => 1,
            'dependency_id' => 117,
            'responsible' => "DR. NELSON MITSUI",
            'modality_id' => 1,
            'dncp_pac_id' => 0,
            'sub_program_id' => 67,
            'funding_source_id' => 3,
            'financial_organism_id' => 7,
            'total_amount' => 12261029879,
            'description' => "ADQUISICIÓN DE EQUIPOS MÉDICOS PARA LAS DIFERENTES DEPENDENCIAS DEL INCAN.",
            'ad_referendum' => false,
            'plurianualidad' => false,
            'system_awarded_by' => "ÍTEM",
            'expenditure_object_id' => 201,
            'fonacide' => false,
            'catalogs_technical_annexes' => true,
            'alternative_offers' => false,
            'open_contract' => false,
            'period_time' => "4 AÑOS",
            'manufacturer_authorization' => false,
            'financial_advance_percentage_amount' => false,
            'technical_specifications' => "SEGÚN ANEXOS POR CADA EQUIPO",
            'samples' => false,
            'evaluation_committee_proposal' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis vitae cursus tellus. Donec sed nisi arcu. Ut tellus ex, efficitur at pulvinar eget, efficitur quis metus. Nam neque velit.",
            'payment_conditions' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis vitae cursus tellus. Donec sed nisi arcu. Ut tellus ex, efficitur at pulvinar eget, efficitur quis metus. Nam neque velit, lacinia id vestibulum in, semper vitae turpis. Quisque eu quam non enim elementum sodales in quis neque. Vestibulum pellentesque leo ac magna vulputate, quis sollicitudin mi commodo. Donec pellentesque laoreet tristique. Pellentesque non enim scelerisque ante blandit faucibus. Vivamus cursus consequat vulputate. Nulla facilisi. Integer consequat ante ac urna sagittis venenatis. Aenean dictum sem suscipit lacus luctus elementum. Nam neque justo, congue eget ullamcorper pretium, tincidunt ut sem. Aenean mauris urna, blandit et vulputate sagittis, lacinia et tortor. Praesent placerat hendrerit dolor. Nulla ultricies sagittis urna a tempus.

            Donec viverra sapien quis volutpat feugiat. Curabitur in cursus elit, eget porta tellus. Nam at est dapibus, auctor neque eget, dictum neque. Donec viverra, mauris in vestibulum tempus, nulla urna posuere diam, sit amet malesuada lacus nunc sit amet neque. Etiam eleifend ipsum a metus dapibus imperdiet. Sed condimentum in ligula sit amet condimentum. Praesent tristique justo imperdiet lorem finibus euismod. In hac habitasse platea dictumst. Pellentesque hendrerit odio ac efficitur bibendum. Donec vel nisi lacus.

            Nulla cursus neque metus, at facilisis purus aliquam ut. Mauris efficitur ipsum dui, nec suscipit odio volutpat non. Curabitur vel gravida tortor, maximus luctus risus. Cras non turpis congue nisl pretium consequat non in risus. Donec tempus eget eros id mattis. Donec quam enim, tincidunt convallis placerat id, bibendum vel felis. Interdum et malesuada fames ac ante ipsum primis in faucibus. Mauris at purus velit. Cras consectetur justo id erat sodales, nec lobortis augue aliquam. Praesent vehicula porta risus id aliquet. Aliquam viverra lorem vel tellus iaculis, id sollicitudin odio vestibulum. Pellentesque at condimentum orci. Vestibulum dapibus blandit efficitur.",
            'contract_guarantee' => "Donec viverra sapien quis volutpat feugiat. Curabitur in cursus elit, eget porta tellus. Nam at est dapibus, auctor neque eget, dictum neque.",
            'product_guarantee' => "Donec viverra sapien quis volutpat feugiat. Curabitur in cursus elit, eget porta tellus. Nam at est dapibus, auctor neque eget, dictum neque.",
            'contract_administrator' => 'Instituto Nacional de Cáncer.',
            'contract_validity' => "EL PLAZO DE VIGENCIA DE ESTE CONTRATO ES DESDE LA FIRMA HASTA EL CUMPLIMIENTO TOTAL DE TODAS LAS OBLICACIONES",
            'form4_city' => 'Capiata',
            'form4_date' => "2020-11-05",
            'dncp_resolution_number' => "1890/20",
            'dncp_resolution_date' => "2020-07-05",
            'actual_state' => 1,
            'creator_user_id' => 1
        ]);
    }
}
