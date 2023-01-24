<?php

namespace App\Http\Controllers;

use App\Models\AbsentOut;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;

class AbsentOutController extends Controller
{
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
        // validate the image has been captured
        $validator = Validator::make($request->all(), [
            'absent_picture' => ['required', File::image()],
        ]);

        // return the errors if validator is failed
        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        // create status
        $time = Carbon::now();
        //initialize variable status
        $status = $request->status;

        // if they absensence in range time they have to so status is ontime, if no
        if ($status === null) {
            if ($time->hour >= 16 && $time->hour <= 17) {
                $status = 'home ontime';
            } elseif ($time->hour < 16) {
                $status = 'home before time';
            } else {
                $status = 'late';
            }
        }

        // get data employee id
        $employeeID = Employee::where(
            'user_id',
            '=',
            $request->user()->id
        )->first()->id;

        // store the image using random name
        $absentPicture = $request->file('absent_picture')->store('absent-outs');

        $absentOut = AbsentOut::create([
            'employee_id' => $employeeID,
            'status' => $status,
            'absent_picture' => $absentPicture,
        ]);

        return response()->json(['data' => $absentOut], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AbsentOut  $absentOut
     * @return \Illuminate\Http\Response
     */
    public function show(AbsentOut $absentOut)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AbsentOut  $absentOut
     * @return \Illuminate\Http\Response
     */
    public function edit(AbsentOut $absentOut)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AbsentOut  $absentOut
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AbsentOut $absentOut)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AbsentOut  $absentOut
     * @return \Illuminate\Http\Response
     */
    public function destroy(AbsentOut $absentOut)
    {
        //
    }
}
