<?php

namespace App\Http\Controllers\Agents;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\common;
use App\ClientMaster;
use App\ClientPropertyInfo;
//use App\Http\Controllers\Auth;
use phpDocumentor\Reflection\Types\Null_;
use App\AgentPageMaster;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\AgentMaster;


class ClientController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        common::main();
        $rs_page = AgentPageMaster::obj()->getInfoById(config('constants.PAGE_MANAGER_ID_CLIENTS'));
        View::share('rs_page',$rs_page);
        View::share('SiteTitle',$rs_page['apm_browser_title']);
        $this->middleware('auth');
        //$this->ClientMaster = new ClientMaster();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
	    global $physical_path,$virtual_path;

	    $clientInfo = ClientMaster::obj()->getAllClientInfo($request);
        return view('layouts.default_layout',
                    [
                        'T_Body'        => 'agents.clients.list',
                        'clientInfo'    => $clientInfo,
                        'V_Upload'      => $virtual_path['public_images'].ClientMaster::obj()->V_Upload,
                        'Css'           => array('client'),
                        'JavaScript'    => array('client'),
                     ])->render();
    }

    /**
     * display add form
     * @return view
     */
    public function create(){
        return view('layouts.default_layout',
                    [
						'T_Body'=> 'agents.clients.addedit',
						'client_type'=>config('static_data.client_type'),
						'JavaScript'=> array('validation')
                    ])->render();
    }

    /** When click on submit button it will validate the form and after save data in database.
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request){
        $validator = \Validator::make($request->all(), ClientMaster::$rulesOnAddClients, ClientMaster::$messages);
        if($validator->fails())
        {
            return  redirect()->back()
                ->withErrors($validator->messages())
                ->withInput();
        }
        else {
            $POST = $request->all ();
            $ret_val = ClientMaster::obj()->Insert($POST);

            if(!is_object ($ret_val) && $ret_val > 0){
                return redirect()->route('clients.index')->with('success_msg', 'Client has been added successfully');
            }
            else{
                return redirect()->back()->withErrors($ret_val)->withInput();
            }
        }

    }

    /** Display form with particular client data.
     * @param $id
     * @return view
     */
    public function edit($id) {
        $editclientlist = ClientMaster::obj()->getClientInfoById($id);
        return view('layouts.default_layout',
                    [
                        'T_Body'=> 'agents.clients.addedit',
                        'client_type'=>config('static_data.client_type'),
                        'JavaScript'=> array('validation'),
                        'editclientlist' =>$editclientlist])->render();
    }

    /** When click on submit button it will validate the form and after update data in database.
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function update(Request $request, $id){
        $validator = \Validator::make($request->all(), ClientMaster::$rulesOnAddClients, ClientMaster::$messages);
        if($validator->fails())
        {
            return  redirect()->back()
                ->withErrors($validator->messages())
                ->withInput();
        }
        else
        {
            $POST = $request->all();
            $ret_val = ClientMaster::obj()->Updates ($POST, $id);

            if(!is_object ($ret_val) && $ret_val > 0){
                return redirect()->route('clients.index')->with('success_msg', 'Client has been saved successfully.');
            }
            else{
                return redirect()->back()->withErrors($ret_val)->withInput();
            }
        }
    }

    /** update the Active/Inactive field. it is for the quick action on the list page.
     * @param Request $request
     * @return mixed
     */
    public function isActive(Request $request){
        $POST = $request->all();
        $id = $request->input('id');
        $ret_val = ClientMaster::obj()->isActiveStatus($POST, $id);
        if(!is_object ($ret_val) && $ret_val > 0){
            return redirect()->route('clients.index')->with('success_msg', 'Client has been changed status successfully.');
        }
        else{
            return redirect()->back()->withErrors($ret_val)->withInput();
        }
    }

    /** Remove the particular client record.
     * @param $id
     * @return mixed
     */
    public function destroy($id){
        $ret_val = ClientMaster::obj()->Deletes($id);
        if(!is_object ($ret_val) && $ret_val > 0){
            return redirect()->route('clients.index')->with('success_msg', 'Client has been removed successfully.');
        }
        else{
            return redirect()->back()->withErrors($ret_val)->withInput();
        }
    }
}