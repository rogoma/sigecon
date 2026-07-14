<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'district_id',
        'locality_id'
    ];

    /**
     * Para obtener el vinculo con la tabla contratos
     */
    public function contract(){
        return $this->belongsTo('App\Models\Contract');
    }

    /**
     * Relación: Una orden pertenece a un distrito-departamento.
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo('App\Models\District');
    }

    public function locality()
    {
        return $this->belongsTo(Locality::class);
    }
    
    public function orderState(){
        return $this->belongsTo('App\Models\OrderState');
    }

    /**
    * Para obtener el vinculo con la tabla itemsOrders
    */
    public function items(){
        return $this->hasMany('App\Models\ItemOrder');
    }


    /**
    * Para obtener el vinculo con la tabla events
    */
    public function events(){
        return $this->hasMany('App\Models\Event');
    }

    /**
     * Para obtener el vinculo con la tabla items2
     */
    public function itemAwards(){
        return $this->hasMany('App\Models\ItemAwards');
    }

    /**
     * Para obtener el vinculo con la tabla dependencies
     */
    public function dependency(){
        return $this->belongsTo('App\Models\Dependency');
    }

    /**
     * Para obtener el vinculo con la tabla modalities
     */
    public function modality(){
        return $this->belongsTo('App\Models\Modality');
    }


    /**
     * Para obtener el vinculo con la tabla Components
     */
    public function component(){
        return $this->belongsTo('App\Models\Component');
    }

    /**
     * Para dar formato a Fecha de Creación de la orden
     */
    public function DateFormat(){
        if(empty($this->create_at)){
            return "";
        }else{
            return date('d/m/Y', strtotime($this->create_at));
        }
    }

    // ACA TERMINA EL USO DE RELACIONES CON EL PROYECTO DE CONTRATOS



    /**
     * Para obtener el vinculo con la tabla sub_programs
     */
    public function subProgram(){
        return $this->belongsTo('App\Models\SubProgram');
    }

    /**
     * Para obtener el vinculo con la tabla funding_sources
     */
    public function fundingSource(){
        return $this->belongsTo('App\Models\FundingSource');
    }

    /**
     * Para obtener el vinculo con la tabla financial_organisms
     */
    public function financialOrganism(){
        return $this->belongsTo('App\Models\FinancialOrganism');
    }

    /**
     * Para obtener el vinculo con la tabla expenditure_objects
     */
    public function expenditureObject(){
        return $this->belongsTo('App\Models\ExpenditureObject');
    }

    /**
     * Para obtener el vinculo con la tabla users
     */
    public function creatorUser(){
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Para obtener el vinculo con la tabla users
     */
    public function modifierUser(){
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Para obtener el vinculo con la tabla budget_request_providers
     */
    public function budgetRequestProviders(){
        return $this->hasMany('App\Models\BudgetRequestProvider');
    }

    /*** Para obtener el vinculo con la tabla awardhistories*/
    public function itemAwardHistories(){
        return $this->belongsToMany('App\Models\ItemAwardHistory', 'budget_request_providers');
    }

    /**
     * Para obtener el vinculo con la tabla providers
     */
    public function providers(){
        return $this->belongsToMany('App\Models\Provider', 'budget_request_providers');
    }


    /**
     * Para obtener el vinculo con la tabla order_multi_years
     */
    public function orderMultiYears(){
        return $this->hasMany('App\Models\OrderMultiYear');
    }

    /**
     * Para obtener el vinculo con la tabla simese
     */
    public function simese(){
        return $this->hasMany('App\Models\Simese');
    }

    /**
     * Para obtener el vinculo con la tabla filesOrders
     */
    public function files(){
        return $this->hasMany('App\Models\FileOrder');
    }
    

    // /**
    //  * Para obtener el vinculo con la tabla files
    //  */
    // public function filesOrders(){
    //     return $this->hasMany('App\Models\FileOrder');
    // }


    /**
     * Para obtener el vinculo con la tabla objections
     */
    public function objections(){
        return $this->hasMany('App\Models\Objection');
    }

    /**
     * Para obtener el vinculo con la tabla queries
     */
    public function queries(){
        return $this->hasMany('App\Models\Query');
    }

    /**
     * Para obtener el vinculo con la tabla orders_order_states
     */
    public function ordersOrderStates(){
        return $this->hasMany('App\Models\OrderOrderState');
    }

    /**
     * Agregamos funciones que formatean los datos
     * para mayor utilidad en los views
     */
    public function adjudicaDateFormat(){
        if(empty($this->queries_deadline_adj)){
            return "";
        }else{
            return date('d/m/Y', strtotime($this->queries_deadline_adj));
        }
    }

    public function beginDateFormatmY(){
        if(empty($this->begin_date)){
            return "";
        }else{
            return date('m/Y', strtotime($this->begin_date));
        }
    }

    public function cdpDateFormat(){
        if(empty($this->cdp_date)){
            return "";
        }else{
            return date('d/m/Y', strtotime($this->cdp_date));
        }
    }
    public function cdpNumberFormat(){
        return number_format($this->cdp_number,0,",",".");
    }

    public function dncpPacIdFormat(){
        return number_format($this->dncp_pac_id,0,",",".");
    }

    public function totalAmountFormat(){
        return number_format($this->total_amount,0,",",".");
    }

    public function totalAmountAwardFormat(){
        return number_format($this->total_amount_award,0,",",".");
    }

    public function cdpAmountFormat(){
        return number_format($this->cdp_amount,0,",",".");
    }

    public function form4DateFormat(){
        if(empty($this->form4_date)){
            return "";
        }else{
            return date('d/m/Y', strtotime($this->form4_date));
        }
    }
    public function dncpResolutionDateFormat(){
        if(empty($this->dncp_resolution_date)){
            return "";
        }else{
            return date('d/m/Y', strtotime($this->dncp_resolution_date));
        }
    }
    public function queriesDeadline(){
        return empty($this->queries_deadline) ? "" :
               date('d/m/Y', strtotime($this->queries_deadline));
    }

    public function queriesDeadlineAdj(){
        return empty($this->queries_deadline_adj) ? "" :
               date('d/m/Y', strtotime($this->queries_deadline_adj));
    }
}
