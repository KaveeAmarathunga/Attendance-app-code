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
            Log::info('called');
            $company_name = $request->query('company');
            $epf_number = $request->query('epf_number');
            Log::info($epf_number);
            $today = Carbon::today();
            $user_type_id = UserType::where('usertype_name',  'technician')->first()->usertype_id;
            Log::info($user_type_id);
            $company_id = Company::where('name', $company_name)->first()->company_id;
            $checking_Aproval_list = Attendance::with([
                'user' => function ($query) {
                    $query->select('name', 'epf_number');
                },
                'attendanceRequests'
            ])->whereNotNull('check_in')->whereNull('check_in_approved_by')
                ->whereHas('user', function ($query) use ($company_id, $user_type_id) {
                    $query->where('company_id', $company_id)->where('usertype_id', $user_type_id);
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
            if (empty($checking_Aproval_list)) {
                $checkout_Aproval_list = Attendance::with([
                    'user' => function ($query) {
                        $query->select('name', 'epf_number');
                    },
                    'attendanceRequests'
                ])->whereNotNull('check_out')->whereNull('check_out_approved_by')
                    ->whereHas('user', function ($query) use ($company_id, $user_type_id) {
                        $query->where('company_id', $company_id)->where('usertype_id', $user_type_id);
                    })
                    ->whereHas('attendanceRequests', function ($query) use ($epf_number) {
                        $query->where('requested_epf_number', $epf_number);
                    })
                    ->whereDate('date', $today)->select('attendance_id', 'check_out', 'epf_number')
                    ->get()->map(function ($attendance) {
                        return [
                            'attendance_id' => $attendance->attendance_id,
                            'name' => $attendance->user->name,
                            'epf_number' => $attendance->user->epf_number,
                            'time' => $attendance->check_out
                        ];
                    });

                Log::info($checkout_Aproval_list);
                return $this->success(['list_type' => 'check_out', 'check_out_list' => $checkout_Aproval_list]);
            }

            return $this->success(['list_type' => 'check_in', 'check_in_list' => $checking_Aproval_list]);
            // Log::info($checking_Aproval_list);
        } catch (ValidationException $e) {
            return $this->error('', 'Unprocesable content', 422);
        } catch (Exception $e) {
            return $this->error('', 'Server Error', 500);
        }
    }
}
