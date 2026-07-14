<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

                                                                                                                                                        /**
     * Para obtener el vinculo con la tabla modalities
     */
    public function modality(){
        return $this->belongsTo('App\Models\Modality');
    }

    /**
     * Para obtener el vinculo con la tabla contract_states
     */
    public function contractState(){
        return $this->belongsTo('App\Models\ContractState');
    }

    /**
     * Para obtener el vinculo con la tabla contract_types
     */
    public function contractType(){
        return $this->belongsTo('App\Models\ContractType');
    }

    /**
     * Para obtener el vinculo con la tabla usuarios
     */
    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Para obtener el vinculo con la tabla usuarios y fiscales
     */
    public function fiscal1()
    {
        return $this->belongsTo(User::class, 'fiscal1_id');
    }

    public function fiscal2()
    {
        return $this->belongsTo(User::class, 'fiscal2_id');
    }

    public function fiscal3()
    {
        return $this->belongsTo(User::class, 'fiscal3_id');
    }

    public function fiscal4()
    {
        return $this->belongsTo(User::class, 'fiscal4_id');
    }

    public function contratista()
    {
        return $this->belongsTo(User::class, 'contra_user_id');
    }

    public function contractAdmin()
    {
        return $this->belongsTo(User::class, 'contract_admin_id');
    }

    /**
     * Para obtener el vinculo con la tabla financial_organisms
     */
    public function financialOrganism(){
        return $this->belongsTo('App\Models\FinancialOrganism');
    }

    /**
     * Para obtener el vinculo con la tabla dependencies
     */
    public function dependency(){
        return $this->belongsTo('App\Models\Dependency');
    }

    /**
     * Para obtener el vinculo con la tabla items que equivalen a pólizas de cada llamado
     */
    public function items(){
        return $this->hasMany('App\Models\Item');
    }

    /**
     * Para obtener el vinculo con la tabla items que equivalen a pólizas de cada llamado
     */
    public function itemsContracts(){
        return $this->hasMany('App\Models\ItemContract');
    }


    /**
     * Para obtener el vinculo con la tabla orders_oi que equivalen a órdenes de ejecución de inicio de obras
     */
    public function orders(){
        return $this->hasMany('App\Models\Order');
    }


    // public function items_contract(){
    //     return $this->belongsTo('App\Models\Item');
    // }

    /**
     * Para obtener el vinculo con la tabla itemsAwards que equivale a endosos de pólizas
     */
    public function itemAwards(){
        return $this->hasMany('App\Models\ItemAwards');
    }

     /*** Para obtener el vinculo con la tabla awardhistories*/
     public function itemAwardHistories(){
        return $this->belongsToMany('App\Models\ItemAwardHistory');
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
     * Para obtener el vinculo con la tabla providers
     */
    public function provider(){
        // return $this->belongsToMany('App\Models\Provider');
        return $this->belongsTo('App\Models\Provider');
        // return $this->belongsToMany(Provider::class);
    }


    /**
     * Para obtener el vinculo con la tabla simese
     */
    public function simese(){
        return $this->hasMany('App\Models\Simese');
    }

    /**
     * Para obtener el vinculo con la tabla files
     */
    public function files(){
        return $this->hasMany('App\Models\File');
    }

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

    // /**
    //  * Para obtener el vinculo con la tabla orders_order_states
    //  */
    // public function ordersOrderStates(){
    //     return $this->hasMany('App\Models\OrderOrderState');
    // }

    /**
     * Agregamos funciones que formatean los datos
     * para mayor utilidad en los views
     */
    // public function adjudicaDateFormat(){
    //     if(empty($this->queries_deadline_adj)){
    //         return "";
    //     }else{
    //         return date('d/m/Y', strtotime($this->queries_deadline_adj));
    //     }
    // }

    public function systemDateFormat(){
        if(empty($this->contract_begin_date)){
            return "";
        }else{
            return date('d/m/Y', strtotime($this->contract_begin_date));
        }
    }

    public function beginDateFormat(){
        if(empty($this->contract_begin_date)){
            return "";
        }else{
            return date('d/m/Y', strtotime($this->contract_begin_date));
        }
    }

    public function endDateFormat(){
        if(empty($this->contract_end_date)){
            return "";
        }else{
            return date('d/m/Y', strtotime($this->contract_end_date));
        }
    }

    public function signDateFormat(){
        if(empty($this->sign_date)){
            return "";
        }else{
            return date('d/m/Y', strtotime($this->sign_date));
        }
    }

    public function advance_validityDateFormat(){
        if(empty($this->advance_validity)){
            return "";
        }else{
            return date('d/m/Y', strtotime($this->advance_validity));
        }
    }

    public function minimAmountFormat(){
        return number_format($this->minim_amount,0,",",".");
    }

    public function totalAmountFormat(){
        return number_format($this->total_amount,0,",",".");
    }

    public function fidelity_validityDateFormat(){
        if(empty($this->fidelity_date)){
            return "";
        }else{
            return date('d/m/Y', strtotime($this->fidelity_validity));
        }
    }

    public function accidents_validityDateFormat(){
        if(empty($this->accidents_date)){
            return "";
        }else{
            return date('d/m/Y', strtotime($this->accidents_validity));
        }
    }

    public function risks_validityDateFormat(){
        if(empty($this->risks_date)){
            return "";
        }else{
            return date('d/m/Y', strtotime($this->risks_validity));
        }
    }

    public function civil_resp_validityDateFormat(){
        if(empty($this->civil_resp_date)){
            return "";
        }else{
            return date('d/m/Y', strtotime($this->civil_resp_validity));
        }
    }

    public function advance_from_validityDateFormat(){
        if(empty($this->advance_validity_from)){
            return "";
        }else{
            return date('d/m/Y', strtotime($this->advance_validity_from));
        }
    }

    public function advance_to_validityDateFormat(){
        if(empty($this->advance_validity_to)){
            return "";
        }else{
            return date('d/m/Y', strtotime($this->advance_validity_to));
        }
    }

    public function fidelity_from_validityDateFormat(){
        if(empty($this->fidelity_validity_from)){
            return "";
        }else{
            return date('d/m/Y', strtotime($this->fidelity_validity_from));
        }
    }

    public function fidelity_to_validityDateFormat(){
        if(empty($this->fidelity_validity_to)){
            return "";
        }else{
            return date('d/m/Y', strtotime($this->fidelity_validity_to));
        }
    }

    public function accidents_from_validityDateFormat(){
        if(empty($this->accidents_validity_from)){
            return "";
        }else{
            return date('d/m/Y', strtotime($this->accidents_validity_from));
        }
    }

    public function accidents_to_validityDateFormat(){
        if(empty($this->accidents_validity_to)){
            return "";
        }else{
            return date('d/m/Y', strtotime($this->accidents_validity_to));
        }
    }

    public function risks_from_validityDateFormat(){
        if(empty($this->risks_validity_from)){
            return "";
        }else{
            return date('d/m/Y', strtotime($this->risks_validity_from));
        }
    }

    public function risks_to_validityDateFormat(){
        if(empty($this->risks_validity_to)){
            return "";
        }else{
            return date('d/m/Y', strtotime($this->risks_validity_to));
        }
    }

    public function civil_resp_from_validityDateFormat(){
        if(empty($this->civil_resp_validity_from)){
            return "";
        }else{
            return date('d/m/Y', strtotime($this->civil_resp_validity_from));
        }
    }

    public function civil_resp_to_validityDateFormat(){
        if(empty($this->civil_resp_validity_to)){
            return "";
        }else{
            return date('d/m/Y', strtotime($this->civil_resp_validity_to));
        }
    }

    public function itemFromDateFormat(){
        if(empty($this->item_from)){
            return "";
        }else{
            return date('d/m/Y', strtotime($this->date));
        }
    }

    // public function beginDateFormatmY(){
    //     if(empty($this->begin_date)){
    //         return "";
    //     }else{
    //         return date('m/Y', strtotime($this->begin_date));
    //     }
    // }

    // public function cdpDateFormat(){
    //     if(empty($this->cdp_date)){
    //         return "";
    //     }else{
    //         return date('d/m/Y', strtotime($this->cdp_date));
    //     }
    // }

    // public function cdpNumberFormat(){
    //     return number_format($this->cdp_number,0,",",".");
    // }

    // public function dncpPacIdFormat(){
    //     return number_format($this->dncp_pac_id,0,",",".");
    // }



    // public function totalAmountAwardFormat(){
    //     return number_format($this->total_amount_award,0,",",".");
    // }

    // public function cdpAmountFormat(){
    //     return number_format($this->cdp_amount,0,",",".");
    // }

    // public function form4DateFormat(){
    //     if(empty($this->form4_date)){
    //         return "";
    //     }else{
    //         return date('d/m/Y', strtotime($this->form4_date));
    //     }
    // }
    // public function dncpResolutionDateFormat(){
    //     if(empty($this->dncp_resolution_date)){
    //         return "";
    //     }else{
    //         return date('d/m/Y', strtotime($this->dncp_resolution_date));
    //     }
    // }
    // public function queriesDeadline(){
    //     return empty($this->queries_deadline) ? "" :
    //            date('d/m/Y', strtotime($this->queries_deadline));
    // }

    // public function queriesDeadlineAdj(){
    //     return empty($this->queries_deadline_adj) ? "" :
    //            date('d/m/Y', strtotime($this->queries_deadline_adj));
    // }
}
