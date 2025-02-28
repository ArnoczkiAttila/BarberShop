<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AppointmentController extends Controller
{
    public function index() {
        $appointment = Appointment::all()->load('barber');
        return response()->json($appointment,200,options:JSON_UNESCAPED_UNICODE);
    }
    public function store(Request $request) {
        try {
            $request->validate([
                'name'=>['required','string','max:255'],
                'barber_id'=>['required','integer','exists:barbers,id'],
                'appointment'=>['required','date'],
            ],[
                'required'=>'A :attribute mező kitöltése kötelező!',
                'string'=>'A :attribute mezőnek string tipusúnak kell lennie!',
                'max'=>'A :attribute mezőnek a hossza nem lehet több 255-nél!',
                'integer'=>'A :attribute mezőnek szám tipusúnak kell lennie!',
                'exists'=>'A :attribute (barber_id) mező csak létező fodrász lehet!',
                'date'=>'Az :attribute mezőnek datetime tipusúnak kell lennie!'
            ],[
                'name'=>'név',
                'barber_id'=>'fodrász',
                'appointment'=>'időpont'
            ]);
        } catch (ValidationException $e) {
            return response()->json(['success'=>false,'message'=>$e->errors()],200,options:JSON_UNESCAPED_UNICODE);
            
        }
        Appointment::create($request->all());
        return response()->json(['success'=>true,'message'=>'Sikeres léterhozás!'],200,options:JSON_UNESCAPED_UNICODE);
    }
    public function destroy(Request $request) {
        
        Appointment::find($request->id)->delete();
        return response()->json(['success'=>true,'message'=>'Sikeres törlés!'],200,options:JSON_UNESCAPED_UNICODE);
    }
}
