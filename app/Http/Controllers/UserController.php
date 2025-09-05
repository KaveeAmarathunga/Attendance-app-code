<?php

namespace App\Http\Controllers;

use App\Http\Traits\HttpResponses;
use App\Models\Company;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
class UserController extends Controller
{

    use HttpResponses;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }

    /**
     * Summary of getSupervisorsByCompany
     * @param \Illuminate\Http\Request $request
     */
    public function getSupervisorsByCompany(Request $request){
        try{
            $request->validate([
                'company'=>'required|exists:companies,name'
            ]);
            // Log::info("Company id",$request->query('company'));

            $company_id = Company::where('name',$request->query('company'))->first()->company_id;
            // Log::info("Company id",$company_id);

           $supervisors = User::where('company_id', $company_id)
            ->where('usertype_id', 2)
            ->get(['name', 'epf_number']);

            return $this->success([
                'supervisors'=>$supervisors
            ]);
            // Log::info($supervisors);

        }catch(ValidationException $e){
            return $this->error('','Invalid company',422);
        }catch(Exception $e){
            return $this->error('','Server Error',500);
        }
    }

    /**
     * Summary of getSupervisor
     * @param \Illuminate\Http\Request $request
     */
    public function getSupervisor(Request $request)  {
        try{
            $epf_number = $request->query('epf_number');      
            $supervisor_epf_number = User::where('epf_number',$epf_number)->first()->supervisor;
            if($supervisor_epf_number){
                return $this->success([
                    'supervisor'=>$supervisor_epf_number
                ]);
            }
            return $this->error('','Invalid EFP Number',404);
        }catch(Exception $e){
            return $this->error('','Server Error',500);
        }
    }
}
