<?php

namespace App\Http\Controllers;

use App\Models\AbsentEntry;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;

class AbsentEntryController extends Controller
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
        // initialize variable status then assign with late
        $status = $request->status;
        
        // if they absensence in range time they have to so status is ontime, if no
        if ($time->hour >= 7 && $time->hour <= 8 && $status !== null) {
            $status = 'ontime';
        } else {
            $status = 'late';
        }

        // get data employee id
        $employeeID = Employee::find($request->user()->id)->id;

        // store the image using random name
        $absentPicture = $request
            ->file('absent_picture')
            ->store('absent-entries');

        $absentEntry = AbsentEntry::create([
            'employee_id' => $employeeID,
            'status' => $status,
            'absent_picture' => $absentPicture,
        ]);

        return response()->json(['data' => $absentEntry], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AbsentEntry  $absentEntry
     * @return \Illuminate\Http\Response
     */
    public function show(AbsentEntry $absentEntry)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AbsentEntry  $absentEntry
     * @return \Illuminate\Http\Response
     */
    public function edit(AbsentEntry $absentEntry)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AbsentEntry  $absentEntry
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AbsentEntry $absentEntry)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AbsentEntry  $absentEntry
     * @return \Illuminate\Http\Response
     */
    public function destroy(AbsentEntry $absentEntry)
    {
        //
    }
}
