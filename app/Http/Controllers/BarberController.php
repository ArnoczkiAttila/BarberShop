<?php

namespace App\Http\Controllers;

use App\Models\Barber;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BarberController extends Controller
{
    public function index() {
        $barbers = Barber::all()->load('appointments');
        return response()->json($barbers,200,options:JSON_UNESCAPED_UNICODE);
    }
    public function store(Request $request) {
        try {
            $request->validate([
                'barber_name'=>['required','string','max:255']
            ],[
                'required'=>'A :attribute mező kitöltése kötelező!',
                'string'=>'A :attribute mezőnek string tipusúnak kell lennie!',
                'max'=>'A :attribute mezőnek a hossza nem lehet több 255-nél'
            ],[
                'barber_name'=>'név'
            ]);
        } catch (ValidationException $e) {
            return response()->json(['success'=>false,'message'=>$e->errors()],200,options:JSON_UNESCAPED_UNICODE);
            
        }
        Barber::create($request->all());
        return response()->json(['success'=>true,'message'=>'Sikeres léterhozás!'],200,options:JSON_UNESCAPED_UNICODE);
    }
    public function destroy(Request $request) {
        try {
            Barber::findOrFail($request->id)->delete();
            return response()->json(['success'=>true,'message'=>'Sikeres törlés!'],200,options:JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            return response()->json(['success'=>false,'message'=>'Nincs ilyen fodrász!'],200,options:JSON_UNESCAPED_UNICODE);
        }
    }
}
