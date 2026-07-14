<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call([
            DependencyTypesSeeder::class,
            UocTypesSeeder::class,
            DependenciesSeeder::class,
            OrderStatesSeeder::class,
            FundingSourcesSeeder::class,
            FinancialLevelsSeeder::class,
            ExpenditureObjectsSeeder::class,
            FinancialOrganismsSeeder::class,
            ModalitiesSeeder::class,
            PermissionsSeeder::class,
            RolesSeeder::class,
            RolesPermissionsSeeder::class,
            PositionsSeeder::class,
            UsersSeeder::class,

            Level1CatalogCodesSeeder::class,
            Level2CatalogCodesSeeder::class,
            Level3CatalogCodesSeeder::class,
            Level4CatalogCodesSeeder::class,
            Level5CatalogCodesSeeder::class,
            OrderMeasurementUnitsSeeder::class,
            OrderPresentationsSeeder::class,
            ProgramMeasurementUnitsSeeder::class,
            BudgetTypesSeeder::class,
            ProgramTypesSeeder::class,
            ProgramsSeeder::class,
            SubProgramsSeeder::class,
            DepartmentsSeeder::class,
            RegionsSeeder::class,
            DistrictsSeeder::class,
            ProvidersSeeder::class,
            OrdersSeeder::class,
        ]);
        
    }
}
