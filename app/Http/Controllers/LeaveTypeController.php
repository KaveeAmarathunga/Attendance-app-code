<?php

namespace App\Http\Controllers;

use App\Http\Traits\HttpResponses;
use App\Models\LeaveType;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class LeaveTypeController extends Controller
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
     */
    public function show()
    {
        try{
            $leaveTypes = LeaveType::pluck('leavetype_name');
            if(!$leaveTypes){
                return $this->error('','No leave types',404);
            }

            return $this->success([
                'leave_types'=>$leaveTypes
            ]);

        }catch (Exception $e){
            return $this->error('','Server Eroor',500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LeaveType  $leaveType
     * @return \Illuminate\Http\Response
     */
    public function edit(LeaveType $leaveType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LeaveType  $leaveType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LeaveType $leaveType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LeaveType  $leaveType
     * @return \Illuminate\Http\Response
     */
    public function destroy(LeaveType $leaveType)
    {
        //
    }

    /**
     * Summary of getLeaveCounts
     * @param \Illuminate\Http\Request $requset
     */
    public function getLeaveCounts($epf_number){
        try{
            // $epf_number = $requset->query('epf_number');

            $user = User::with('usertype')->where('epf_number',$epf_number)->firstOrFail();
            $user_type = $user->usertype->usertype_name;
            if($user_type=='technician'){
                $key = 'number_of_leaves_for_nonexe';
            }else{
                $key = 'number_of_leaves_for_exe';
            }
            $leaveTypesCounts = LeaveType::all()->pluck($key);
            $leave_count = 0;
            foreach($leaveTypesCounts as $leaveTypeCount){
                $leave_count+=$leaveTypeCount;
            }
            Log::info($leave_count);

            return $leave_count ;

        }catch (Exception $e){
            return $this->error('','Server Eroor',500);
        }
    }
}
