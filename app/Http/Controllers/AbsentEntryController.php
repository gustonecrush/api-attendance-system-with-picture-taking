<?php

namespace App\Http\Controllers;

use App\Http\Resources\AbsentResource;
use App\Models\AbsentEntry;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AbsentEntryController extends Controller
{
    private $absentEntries;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->absentEntries = AbsentEntry::all();
        $absentEntriesResource = AbsentResource::collection(
            $this->absentEntries
        );
        return $this->sendResponse(
            $absentEntriesResource,
            'Get Absent Entries Successfully!',
            200
        );
    }

    public function employee(Request $request)
    {
        $user = $request->user();
        $employeeID = Employee::where('user_id', '=', $user->id)->first()
            ->employee_id;
        $this->absentEntries = AbsentEntry::where('employee_id', '=', $employeeID)->get();
        $absentEntriesResource = AbsentResource::collection($this->absentEntries);
        return $this->sendResponse(
            $absentEntriesResource,
            'Get Absent Entries Successfully!',
            200
        );
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
            'absent_picture' => ['required', 'file'],
        ]);

        // return the errors if validator is failed
        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        // create status
        $time = Carbon::now();
        dd($time);
        // initialize variable status
        $status = $request->status;

        // if they absensence in range time they have to so status is ontime
        if ($status === null) {
            if ($time->hour >= 7 && $time->hour <= 8) {
                $status = 'ontime';
            } else {
                $status = 'late';
            }
        }

        // get data employee id
        $employeeID = Employee::where(
            'user_id',
            '=',
            $request->user()->id
        )->first()->employee_id;

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

    public function download(Request $request, $id)
    {
        $absent = AbsentEntry::find((int) $id);
        $dokumenPath = public_path('storage/' . $absent->absent_picture);
        return response()->download($dokumenPath);
    }
}
