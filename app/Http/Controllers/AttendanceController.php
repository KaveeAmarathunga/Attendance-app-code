<?php

namespace App\Http\Controllers;

use App\Http\Traits\HttpResponses;
use App\Models\Attendance;
use App\Models\AttendanceRequestFrom;
use App\Models\Company;
use App\Models\UserType;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

class AttendanceController extends Controller
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
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function edit(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attendance $attendance)
    {
        //
    }

    /**
     * Summary of getMyTodayAttendance
     * @param \Illuminate\Http\Request $request
     */
    public function getMyTodayAttendance(Request $request)
    {
        try {
            $today = Carbon::today()->toDateString();

            $epf_number = $request->query('epf_number');

            $attendanceRecode = Attendance::where('epf_number', $epf_number)
                ->whereDate('date', $today)
                ->first();
            Log::info($attendanceRecode);
            if ($attendanceRecode) {
                $todayAttendance = $attendanceRecode;
            } else {
                $attendanceRecode = Attendance::create([
                    'epf_number' => $epf_number,
                    'date' => $today
                ]);
            }
            $todayAttendance = Attendance::where('epf_number', $epf_number)
                ->whereDate('date', $today)
                ->first();
            return $this->success([
                'today_attendance' => $todayAttendance
            ]);
        } catch (ValidationException $e) {
            return $this->error('', 'Invalid EPF Number', 422);
        } catch (Exception $e) {
            return $this->error('', 'Server Error', 500);
        }
    }

    /**
     * Summary of checkIn
     * @param Request $request
     */
    public function checkIn(Request $request)
    {
        Log::info('called');
        try {
            $request->validate([
                'epf_number' => 'required|exists:users,epf_number',
                'check_in_time' => 'required',
                'request_from' => 'array',
                'request_from.*' => 'exists:users,epf_number'
            ]);

            $today = Carbon::today()->toDateString();
            $attendance = Attendance::where('epf_number', $request->epf_number)
                ->whereDate('date', $today)
                ->first();

            if ($attendance) {
                $attendance->check_in = $request->check_in_time;
                $attendance->save();

                // Loop through the request_from array and create a new record for each
                foreach ($request->request_from as $request_epf_number) {
                    AttendanceRequestFrom::create([
                        'attendance_id' => $attendance->attendance_id,
                        'requested_epf_number' => $request_epf_number,
                    ]);
                }

                return $this->success([]);
            }

            return $this->error('', 'Check In Failed', 500);
        } catch (ValidationException $e) {
            return $this->error('', 'Unprocessable content', 422);
        } catch (Exception $e) {
            Log::error($e->getMessage()); // Log the error for debugging
            return $this->error('', 'Server Error', 500);
        }
    }

    /** 
     * @param Request $request
     */
    public function checkOut(Request $request)
    {
        try {
            $request->validate([
                'epf_number' => 'required|exists:users,epf_number',
                'check_out_time' => 'required',
                'working_place' => 'required',
            ]);

            $today = Carbon::today()->toDateString();
            $attendance = Attendance::where('epf_number', $request->epf_number)
                ->whereDate('date', $today)
                ->first();


            if ($attendance) {
                $attendance->check_out = $request->check_out_time;
                $attendance->working_place = $request->working_place;
                $attendance->site_number = $request->site_number ?? null;
                $attendance->save();

                return $this->success([]);
            }
        } catch (ValidationException $e) {
            return $this->error('', 'Unprocesable content', 422);
        } catch (Exception $e) {
            return $this->error('', 'Server Error', 500);
        }
    }

    /**
     * Summary of getNumberOfWorkingDays
     * @param \Illuminate\Http\Request $request
     */
    public function getNumberOfWorkingDays(Request $request)
    {
        try {
            $epf_number = $request->query('epf_number');

            $working_days = Attendance::where('epf_number', $epf_number)->whereMonth('date', now()->month)->whereYear('date', now()->year)->whereNotNull('check_in_approved_by')->count();

            return $this->success([
                'number_of_working_days' => $working_days
            ]);
        } catch (ValidationException $e) {
            return $this->error('', 'Unprocesable content', 422);
        } catch (Exception $e) {
            return $this->error('', 'Server Error', 500);
        }
    }

    /**
     * Summary of getCheckinApprovalList
     * @param \Illuminate\Http\Request $request
     */
    public function getCheckinApprovalList(Request $request)
    {
        try {
            // Log::info('called');
            $company_name = $request->query('company');
            $epf_number = $request->query('epf_number');
            // Log::info($epf_number);
            $today = Carbon::today();
            $tec_user_type_id = UserType::where('usertype_name',  operator: 'technician')->first()->usertype_id;
            $exe_user_type_id = UserType::where('usertype_name',  operator: 'executive')->first()->usertype_id;
            Log::info($exe_user_type_id);
            $company_id = Company::where('name', $company_name)->first()->company_id;
            $tech_checking_Aproval_list = Attendance::with([
                'user' => function ($query) {
                    $query->select('name', 'epf_number');
                },
                'attendanceRequests'
            ])->whereNotNull('check_in')->whereNull('check_in_approved_by')
                ->whereHas('user', function ($query) use ($company_id, $tec_user_type_id) {
                    $query->where('company_id', $company_id)->where('usertype_id', $tec_user_type_id);
                })
                ->whereHas('attendanceRequests', function ($query) use ($epf_number) {
                    $query->where('requested_epf_number', $epf_number);
                })
                ->whereDate('date', $today)->select('attendance_id', 'check_in', 'epf_number')
                ->get()->map(function ($attendance) {
                    return [
                        'attendance_id' => $attendance->attendance_id,
                        'name' => $attendance->user->name,
                        'epf_number' => $attendance->user->epf_number,
                        'time' => $attendance->check_in
                    ];
                });

            $exe_checking_Approval_list = Attendance::with([
                'user' => function ($query) {
                    $query->select('name', 'epf_number');
                },
                // 'attendanceRequests'
            ])->whereNotNull('check_in')->whereNull('check_in_approved_by')
                ->whereHas('user', function ($query) use ($company_id, $exe_user_type_id,$epf_number) {
                    $query->where('company_id', $company_id)->where('usertype_id', $exe_user_type_id)->where('supervisor',$epf_number);
                })
                // ->whereHas('attendanceRequests', function ($query) use ($epf_number) {
                //     $query->where('requested_epf_number', $epf_number);
                // })
                ->whereDate('date', $today)->select('attendance_id', 'check_in', 'epf_number')
                ->get()->map(function ($attendance) {
                    return [
                        'attendance_id' => $attendance->attendance_id,
                        'name' => $attendance->user->name,
                        'epf_number' => $attendance->user->epf_number,
                        'time' => $attendance->check_in
                    ];
                });

                Log::info(
                    $exe_checking_Approval_list
                );
            if ($tech_checking_Aproval_list->isEmpty()) {
                $tech_checkout_Aproval_list = Attendance::with([
                    'user' => function ($query) {
                        $query->select('name', 'epf_number');
                    },
                    'attendanceRequests'
                ])->whereNotNull('check_out')->whereNull('check_out_approved_by')
                    ->whereHas('user', function ($query) use ($company_id, $tec_user_type_id) {
                        $query->where('company_id', $company_id)->where('usertype_id', $tec_user_type_id);
                    })
                    ->whereHas('attendanceRequests', function ($query) use ($epf_number) {
                        $query->where('requested_epf_number', $epf_number);
                    })
                    ->whereDate('date', $today)->select('attendance_id', 'check_out', 'epf_number', 'working_place', 'site_number')
                    ->get()->map(function ($attendance) {
                        return [
                            'attendance_id' => $attendance->attendance_id,
                            'name' => $attendance->user->name,
                            'epf_number' => $attendance->user->epf_number,
                            'time' => $attendance->check_out,
                            'working_place' => $attendance->working_place,
                            'site_no' => $attendance->site_number
                        ];
                    });
                Log::info($tech_checkout_Aproval_list);
                $tech_approval_list = $tech_checkout_Aproval_list;
                $tech_list_type = 'check_out';
                // if ($tech_checkout_Aproval_list->isEmpty()) {
                //     return $this->error('', '', 404);
                // }
                // return $this->success(['tech_list_type' => 'check_out', 'tech_check_out_list' => $tech_checkout_Aproval_list]);
            } else {
                $tech_approval_list = $tech_checking_Aproval_list;
                $tech_list_type = 'check_in';
            }

            if ($exe_checking_Approval_list->isEmpty()) {
                $exe_checkout_Approval_list = Attendance::with([
                    'user' => function ($query) {
                        $query->select('name', 'epf_number');
                    },
                    // 'attendanceRequests'
                ])->whereNotNull('check_out')->whereNull('check_out_approved_by')
                    ->whereHas('user', function ($query) use ($company_id, $exe_user_type_id,$epf_number) {
                        $query->where('company_id', $company_id)->where('usertype_id', $exe_user_type_id)->where('supervisor',$epf_number);
                    })
                    // ->whereHas('attendanceRequests', function ($query) use ($epf_number) {
                    //     $query->where('requested_epf_number', $epf_number);
                    // })
                    ->whereDate('date', $today)->select('attendance_id', 'check_out', 'epf_number', 'working_place', 'site_number')
                    ->get()->map(function ($attendance) {
                        return [
                            'attendance_id' => $attendance->attendance_id,
                            'name' => $attendance->user->name,
                            'epf_number' => $attendance->user->epf_number,
                            'time' => $attendance->check_out,
                            'working_place' => $attendance->working_place,
                            'site_no' => $attendance->site_number
                        ];
                    });

                    Log::info($exe_checkout_Approval_list);
                $exe_approval_list = $exe_checkout_Approval_list;
                $exe_list_type = 'check_out';
            } else {
                Log::info($exe_checking_Approval_list);
                $exe_approval_list = $exe_checking_Approval_list;
                $exe_list_type = 'check_in';
            }

            Log::info($tech_checking_Aproval_list);
            return $this->success(['tech_list_type' => $tech_list_type, 
            'tech_list' => $tech_approval_list, 
            'exe_list_type' => $exe_list_type, 
            'exe_list' => $exe_approval_list]);
        } catch (ValidationException $e) {
            return $this->error('', 'Unprocesable content', 422);
        } catch (Exception $e) {
            return $this->error('', 'Server Error', 500);
        }
    }

    public function approveCheckIn(Request $request)
    {
        try {

            $request->validate([
                'approved_list' => 'array|required',
                'epf_number' => 'required|exists:users,epf_number'
            ]);
            $epf_number = $request->epf_number;
            $approved_list = $request->approved_list;

            Log::info($approved_list);

            DB::beginTransaction();
            foreach ($approved_list as $item) {
                // Validate each item in the array to ensure it has the required keys
                if (!isset($item['id']) || !isset($item['time']) || !isset($item['morning_allowance'])) {
                    throw new Exception('Invalid data in approved_list');
                }

                if ($item['morning_allowance'] == 1) {
                    $morning_allowance = 1;
                } elseif ($item['morning_allowance'] == 2) {
                    $morning_allowance = 2;
                } else {
                    $morning_allowance = 0;
                }
                // Update the attendance record with the new data
                DB::table('attendances')
                    ->where('attendance_id', $item['id'])
                    ->update([
                        'check_in_approved_by' => $epf_number,
                        'check_in' => $item['time'],
                        'morning_allowence' => $morning_allowance
                    ]);
            }

            DB::commit();

            return $this->success([
                'updated_count' => count($approved_list)
            ], 'Attendance records approved and updated successfully.');
        } catch (ValidationException $e) {
            return $this->error('', 'Unprocesable content', 422);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error('', 'Server Error', 500);
        }
    }

    public function approveCheckOut(Request $request)
    {
        try {
            $request->validate([
                'approved_list' => 'array|required',
                'epf_number' => 'required|exists:users,epf_number'
            ]);

            $epf_number = $request->epf_number;
            $approved_list = $request->approved_list;

            Log::info($approved_list);

            DB::beginTransaction();
            foreach ($approved_list as $item) {
                Log::info($item['id']);

                // Validate each item in the array to ensure it has the required keys
                if (!isset($item['id']) || !isset($item['time']) || !isset($item['evening_allowance'])) {
                    throw new Exception('Invalid data in approved_list');
                }


                if ($item['evening_allowance'] == 1) {
                    $evening_allowance = 1;
                } else {
                    $evening_allowance = 0;
                }
                // Update the attendance record with the new data
                DB::table('attendances')
                    ->where('attendance_id', $item['id'])
                    ->update([
                        'check_out_approved_by' => $epf_number,
                        'check_out' => $item['time'],
                        'working_place' => $item['working_place'],
                        'site_number' => $item['site_no'] ?? null,
                        'evening_allowence' => $evening_allowance
                    ]);
            }

            DB::commit();

            return $this->success([
                'updated_count' => count($approved_list)
            ], 'Attendance records approved and updated successfully.');
        } catch (ValidationException $e) {
            return $this->error('', 'Unprocesable content', 422);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error('', 'Server Error', 500);
        }
    }
}
