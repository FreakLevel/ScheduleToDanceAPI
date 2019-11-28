<?php

namespace App\Http\Controllers;

use App\Appointment;
use App\Http\Resources\AppointmentResource;
use App\Rules\FreeAppointment;
use App\Rules\WorkingHours;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      return AppointmentResource::collection(Appointment::all())->collection;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $validator = $this->validateData($request);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all(), 400);
      } else {
        $data = $request->all();
        $appointment = new Appointment;
        $appointment->email = $data['email'];
        $appointment->name = $data['name'];
        $appointment->schedule = $data['schedule'];
        if ($appointment->save()) {
          return response()->json($appointment, 201);
        } else {
          return response()->json($appointment, 400);
        }
      }
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function validateData($request) {
      $now = date('Y-m-d H:i');
      $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'name' => 'required',
        'schedule' => [
          'required',
          'unique:appointment',
          'date',
          "after:{$now}",
          new FreeAppointment,
          new WorkingHours,
          function ($attribute, $value, $fail) {
            $formatted = date_create_from_format('Y-m-d H:i', $value)->format('Y-m-d H:i');
            if ($value != $formatted) {
              $fail("{$attribute} doesn't have correct format 'Y-m-d H:i'");
            }
          }
        ]
      ]);
      return $this->repeatDance($validator, $request);
    }

    private function repeatDance($validator, $request) {
      $appointment = $request->all();
      $schedule = date_create_from_format('Y-m-d H:i', $appointment['schedule']);
      $before = $schedule->modify('-1 hours');
      $after = $schedule->modify('+1 hours');
      $appointments = AppointmentResource::collection(Appointment::
          where([
              ['schedule', '>=', $before],
              ['schedule', '<=', $after],
              ['name', '=', $appointment['name']],
              ['email', '=', $appointment['email']]
          ])->get());
      if (count($appointments->collection->all()) > 0) {
        $validator->errors()->add('schedule', 'Cannot repeat 2 hours in a row');
      }
      return $validator;
    }
}
