<?php

namespace App\Http\Controllers;

use App\Http\Traits\HttpResponses;
use App\Models\Attendance;
use App\Models\Company;
use App\Models\Leave;
use App\Models\User;
use App\Models\LeaveType;
use App\Models\UserType;
use Carbon\Carbon;
use Carbon\Exceptions\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Validation\ValidationException;

class LeaveController extends Controller
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
     */
    public function store(Request $request)
    {
        return 'hi';
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    public function show(Leave $leave)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    public function edit(Leave $leave)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Leave $leave)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    public function destroy(Leave $leave)
    {
        //
    }
    /** 
     * @param  \Illuminate\Http\Request  $request
     */

    public function checkIsTodayLeave(Request $request)
    {
        try {
            $request->validate([
                'epf_number' => 'required||exists:users,epf_number'
            ]);

            $today = Carbon::today();

            $leave = Leave::where('epf_number', $request->query('epf_number'))
                ->where('status', 'accept')
                ->whereDate('from_date', '<=', $today)
                ->whereDate('to_date', '>=', $today)
                ->first();

            // Log::info($leave);
            if ($leave) {
                return $this->success([
                    'canMarkAttendance' => false
                ], 'Today is a leave day');
            } else {
                return $this->success([
                    'canMarkAttendance' => true
                ], 'Today is not a leave day');
            }
        } catch (ValidationException $e) {
            return $this->error('', 'Invalid EPF Number', 422);
        } catch (Exception $e) {
            return $this->error('', 'Server Error', 500);
        }
    }

    public function getNumberOfLeaves(Request $request)
    {
        // Log::info($request->query('epf_number'));
        try {
            $epf_number = $request->query('epf_number');
            // $first_day_of_this_month = Carbon::now()->startOfMonth();
            // $last_day_of_this_month = Carbon::now()->endOfMonth();

            $leaveRecodes = Leave::where('epf_number', $epf_number)->where('status', 'accept')->whereMonth('from_date', now()->month)->whereYear('from_date', now()->year)->get();

            // Log::info($leaveRecodes);
            $number_of_leaves = 0;
            foreach ($leaveRecodes as $leave) {
                $start_date = Carbon::parse($leave->from_date);
                $end_date = Carbon::parse($leave->to_date);

                // Calculate the number of days in the leave period
                $daysInPeriod = $end_date->diffInDays($start_date) + 1;

                for ($i = 0; $i < $daysInPeriod; $i++) {
                    $current_day = $start_date->copy()->addDays($i);
                    if ($current_day->isSameMonth(now())) {
                        $number_of_leaves++;
                    }
                }
            }
            // $leaveTypeController = new \App\Http\Controllers\LeaveTypeController();

            // $leave_count = $leaveTypeController->getLeaveCounts($epf_number);

            // Log::info($number_of_leaves);

            return $this->success(['number_of_leaves' => $number_of_leaves]);
        } catch (ValidationException $e) {
            return $this->error('', 'Invalid EPF Number', 422);
        } catch (Exception $e) {
            return $this->error('', 'Server Error', 500);
        }
    }
    /**
     * Summary of getLeaveSummery
     * @param \Illuminate\Http\Request $request
     */
    public function getLeaveSummery(Request $request)
    {
        try {
            $epf_number = $request->query('epf_number');
            $leaves = Leave::with('leaveType')->where('epf_number', $epf_number)->whereYear('from_date', now()->year)->get();

            $accepted_leaves = 0;
            $rejected_leaves  = 0;
            $pending_leaves = 0;
            Log::info($leaves);

            // $total_leaves = 0;
            foreach ($leaves as $leave) {
                $start_date = Carbon::parse($leave->from_date);
                $end_date = Carbon::parse($leave->to_date);
                $daysInPeriod = $end_date->diffInDays($start_date) + 1;
                for ($i = 0; $i < $daysInPeriod; $i++) {
                    // Log::info($leave->leaveType->leavetype_name);
                    if ($leave->leaveType->leavetype_name != 'Medical Leaves') {
                        $current_day = $start_date->copy()->addDays($i);
                        if ($current_day->isSameYear(now())) {
                            if ($leave->status == 'accept') {
                                $accepted_leaves += 1;
                            } else if ($leave->status == 'reject') {
                                $rejected_leaves += 1;
                            } else if ($leave->ststu == 'pending') {
                                $pending_leaves += 1;
                            }
                        }
                    }
                }
            }

            $leaveTypeController = new \App\Http\Controllers\LeaveTypeController();
            $leave_quantity = $leaveTypeController->getLeaveCounts($epf_number);
            $leave_balance = $leave_quantity - $accepted_leaves - $pending_leaves;

            return $this->success([
                'leave_balance' => $leave_balance,
                'accepted_leaves' => $accepted_leaves,
                'rejected_leaves' => $rejected_leaves,
                'pending_leaves' => $pending_leaves
            ]);
        } catch (ValidationException $e) {
            return $this->error('', 'Invalid EPF Number', 422);
        } catch (Exception $e) {
            return $this->error('', 'Server Error', 500);
        }
    }


    /**
     * Summary of getMyLeaves
     * @param \Illuminate\Http\Request $request
     */
    public function getMyLeaves(Request $request)
    {
        try {
            $epf_number = $request->query('epf_number');
            $state = $request->query('state');

            if ($state == 'Upcoming') {
                $leaves = Leave::with('leaveType')->where('epf_number', $epf_number)->whereDate('from_date', '>=', now())->get()->map(function ($leave) {
                    if ($leave->consider_by != null) {
                        $consider_by = User::where('epf_number', $leave->consider_by)->select('name')->firstOrFail()->name;
                    } else {
                        $consider_by = null;
                    }
                    $requested_from = User::where('epf_number', $leave->requested_from)->select('name')->firstOrFail()->name;
                    return [
                        'leave_id' => $leave->leave_id,
                        // 'epf_number' => $leave->epf_number,
                        'from_date' => $leave->from_date,
                        'to_date' => $leave->to_date,
                        'status' => $leave->status,
                        'reason' => $leave->reason,
                        'leave_type_id' => $leave->leavetype_id,
                        'leave_type_name' => $leave->leaveType->leavetype_name,
                        'consider_by' => $consider_by ?? null,
                        'requested_from' => $requested_from
                    ];
                });
            } else {
                $leaves = Leave::with('leaveType')->where('epf_number', $epf_number)->whereDate('from_date', '<', now())->get()->map(function ($leave) {
                    if ($leave->consider_by != null) {
                        $consider_by = User::where('epf_number', $leave->consider_by)->get('name')->firstOrFail()->name;
                    } else {
                        $consider_by = null;
                    }
                    $requested_from = User::where('epf_number', $leave->requested_from)->select('name')->firstOrFail()->name;
                    return [
                        'leave_id' => $leave->leave_id,
                        // 'epf_number' => $leave->epf_number,
                        'from_date' => $leave->from_date,
                        'to_date' => $leave->to_date,
                        'status' => $leave->status,
                        'reason' => $leave->reason,
                        'leave_type_id' => $leave->leavetype_id,
                        'leave_type_name' => $leave->leaveType->leavetype_name,
                        'consider_by' => $consider_by,
                        'requested_from' => $requested_from
                    ];
                });
            }
            Log::info($leaves);

            if ($leaves) {
                return $this->success(['leaves' => $leaves]);
            }
            return $this->error('', 'No leaves', 404);
        } catch (ValidationException $e) {
            return $this->error('', 'Invalid EPF Number', 422);
        } catch (Exception $e) {
            return $this->error('', 'Server Error', 500);
        }
    }

    /**
     * Summary of getLeaveRequests
     * @param \Illuminate\Http\Request $request
     */
    public function getLeaveRequests(Request $request)
    {
        try {
            $epf_number = $request->query('epf_number');
            $leave_requests = Leave::with('user', 'leaveType')->where('requested_from', $epf_number)->where('status', 'pending')->get()
                ->map(function ($leaveRequest) {
                    return [
                        'leave_id' => $leaveRequest->leave_id,
                        'name' => $leaveRequest->user->name,
                        'from_date' => $leaveRequest->from_date,
                        'to_date' => $leaveRequest->to_date,
                        'leave_type_name' => $leaveRequest->leaveType->leavetype_name,
                        'reason' => $leaveRequest->reason,
                    ];
                });
            Log::info($leave_requests);
            if ($leave_requests) {
                return $this->success(['leave_requests' => $leave_requests]);
            }

            return $this->error('', 'No leave requests', 404);
        } catch (ValidationException $e) {
            return $this->error('', 'Invalid EPF Number', 422);
        } catch (Exception $e) {
            return $this->error('', 'Server Error', 500);
        }
    }
    /**
     * Summary of considerLeave
     * @param \Illuminate\Http\Request $request
     */
    public function considerLeave(Request $request)
    {
        try {
            $request->validate([
                'consider_by' => 'required|exists:users,epf_number',
                'leave_id' => 'required|exists:leaves,leave_id',
                'action' => 'required'
            ]);

            $leave = Leave::where('leave_id', $request->input('leave_id'))->first();
            Log::info($leave);
            $leave->consider_by = $request->input('consider_by');
            $leave->status = $request->input('action');

            $result = $leave->save();

            if ($result) {
                return $this->success(['result' => $result]);
            }

            return $this->error('', '', 500);
        } catch (ValidationException $e) {
            return $this->error('', 'Invalid data', 422);
        } catch (Exception $e) {
            return $this->error('', 'Server Error', 500);
        }
    }

    /**
     * Summary of getTodatLeaveList
     * @param \Illuminate\Http\Request $request
     */
    public function getTodatLeaveList(Request $request)
    {
        try {
            $company_name = $request->query('company');
            $company_id = Company::where('name', $company_name)->firstOrFail()->company_id;
            $today = Carbon::today();

            $leave_list = Leave::with(['user' => function ($query) {
                $query->select('name', 'epf_number');
            }])
                ->whereDate('from_date', '<=', $today)
                ->whereDate('to_date', '>=', $today)
                ->where('status', '!=', 'reject')
                ->whereHas('user', function ($query) use ($company_id) {
                    $query->where('company_id', $company_id);
                })
                ->select('status', 'epf_number')
                ->get();

            $transformed_list = $leave_list->map(function ($leave) {
                return [
                    'name' => $leave->user->name,
                    'epf_number' => $leave->user->epf_number,
                    'status' => $leave->status,
                ];
            });

            // Log::info($transformed_list);
            return $this->success(['leave_list' => $transformed_list]);
        } catch (ValidationException $e) {
            return $this->error('', 'Invalid data', 422);
        } catch (Exception $e) {
            return $this->error('', 'Server Error', 500);
        }
    }

    public function getTodayAbsentees(Request $request)
    {
        try {
            $company_name = $request->query('company');
            $company_id = Company::where('name', $company_name)->firstOrFail()->company_id;
            $absent_list = collect();
            $user_type_id = UserType::where('usertype_name', 'technician')->firstOrFail()->usertype_id;

            switch ($company_name) {
                case 'Alta Vision Solar':
                    $employee_list = User::where('company_id', $company_id)->where('usertype_id', $user_type_id)->select('name', 'epf_number', 'office_phonenumber', 'personal_phonenumber')->get();
                    break;
                case 'Alta Vison Power':
                    $employee_list = User::where('company_id', $company_id)->where('usertype_id', $user_type_id)->select('name', 'epf_number', 'office_phonenumber', 'personal_phonenumber')->get();;
                    break;

                default:
                    $employee_list = User::where('company_id', $company_id)->select('name', 'epf_number', 'office_phonenumber', 'personal_phonenumber')->get();
            }

            $today = Carbon::today();
            foreach ($employee_list as $employee) {
                $leave = Leave::where('epf_number', $employee->epf_number)->whereDate('from_date', '<=', $today)->whereDate('to_date', '>=', $today)->where('status', '!=', 'reject')->first();
                if (!$leave) {
                    $attendance = Attendance::where('epf_number', $employee->epf_number)->whereDate('date', $today)->first();
                    if ($attendance) {
                        if ($attendance->check_in == null) {
                            $absent_list->push($employee);
                        }
                    } else {
                        $absent_list->push($employee);
                    }
                }
            }

            // Log::info($absent_list);
            if (!empty($absent_list)) {
                return $this->success(['absent_list' => $absent_list]);
            } 
            return $this->error('','No Absentees',404);
            // else {
            //     return $this->success(null, 'No Absentees');
            // }
        } catch (ValidationException $e) {
            return $this->error('', 'Invalid data', 422);
        } catch (Exception $e) {
            return $this->error('', 'Server Error', 500);
        }
    }
}
