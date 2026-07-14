<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Component;

class ComponentsControllerr extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('checkPermission:admin.modalities.index')->only('index'); // Permiso para index 
        $this->middleware('checkPermission:admin.modalities.create')->only(['create', 'store']);   // Permiso para create
        $this->middleware('checkPermission:admin.modalities.update')->only(['edit', 'update']);   // Permiso para update
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $modalities = Component::all();
        return view('admin.modalities.index', compact('modalities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.modalities.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'description' => 'string|required|max:150',
            'code' => 'string|required|max:10',
            'modality_type' => 'string|required|max:100',
            'dncp_verification'=> 'required',
            'dncp_objections_verification'=> 'required',
            'press_publication'=> 'required',
            'portal_difusion'=> 'required',
            'inquiries_reception'=> 'required',
            'addendas_verification'=> 'required',            
            'addenda_publication'=> 'required',          
            'clarifications_publication'=> 'required'
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $modality = new Component();
        $modality->description = $request->input('description');
        $modality->code = $request->input('code');
        $modality->modality_type = $request->input('modality_type');
        $modality->dncp_verification = $request->input('dncp_verification');
        $modality->dncp_objections_verification = $request->input('dncp_objections_verification');
        $modality->press_publication = $request->input('press_publication');
        $modality->portal_difusion = $request->input('portal_difusion');
        $modality->inquiries_reception = $request->input('inquiries_reception');
        $modality->addendas_verification = $request->input('addendas_verification');
        $modality->addenda_publication = $request->input('addenda_publication');
        $modality->clarifications_publication = $request->input('clarifications_publication');
        $modality->creator_user_id = $request->user()->id;  // usuario logueado
        $modality->save();
        return redirect()->route('modalities.index')->with('success', 'Modalidad agregado correctamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $modality = Component::find($id);
        return view('admin.modalities.update', compact('modality'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = array(
            'description' => 'string|required|max:150',
            'code' => 'string|required|max:10',
            'modality_type' => 'string|required|max:100',
            'dncp_verification'=> 'required',
            'dncp_objections_verification'=> 'required',
            'press_publication'=> 'required',
            'portal_difusion'=> 'required',
            'inquiries_reception'=> 'required',
            'addendas_verification'=> 'required',            
            'addenda_publication'=> 'required',          
            'clarifications_publication'=> 'required'
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // obtenemos la Modalidad
        $modality = Component::find($id);
        $modality->description = $request->input('description');
        $modality->code = $request->input('code');
        $modality->modality_type = $request->input('modality_type');
        $modality->dncp_verification = $request->input('dncp_verification');
        $modality->dncp_objections_verification = $request->input('dncp_objections_verification');
        $modality->press_publication = $request->input('press_publication');
        $modality->portal_difusion = $request->input('portal_difusion');
        $modality->inquiries_reception = $request->input('inquiries_reception');
        $modality->addendas_verification = $request->input('addendas_verification');
        $modality->addenda_publication = $request->input('addenda_publication');
        $modality->clarifications_publication = $request->input('clarifications_publication');
        $modality->modifier_user_id = $request->user()->id;  // usuario logueado
        $modality->save();
        return redirect()->route('modalities.index')->with('success', 'Modalidad modificado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        // Chequeamos que el usuario actual disponga de permisos de eliminacion
        if(!$request->user()->hasPermission(['admin.modalities.delete'])){
            return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para realizar esta acción.', 'code' => 200], 200);
        }

        $modality = Component::find($id);
        $modality->delete();
        return response()->json(['status' => 'success', 'message' => 'Se ha eliminado la Modalidad ' . $modality->description, 'code' => 200], 200);
    }
}