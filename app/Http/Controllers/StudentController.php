<?php

namespace App\Http\Controllers;

use App\Models\Student;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function index()
    {
        return view('student.home');
    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:191',
            'email' => 'required|email|max:191',
            'phone' => 'required|max:191',
            'course' => 'required|max:191',
        ]);
        if (!$validator->fails()) {
            $student = new Student;
            $student->name = $request->input('name');
            $student->email = $request->input('email');
            $student->phone = $request->input('phone');
            $student->course = $request->input('course');
            $student->save();
            return response()->json([
                'status' => 200,
                'message' => 'Student added successfully!',
            ]);
        }
        return response()->json([
            'status' => 400,
            'errors' => $validator->messages(),
        ]);
    }


    public function fetch(): \Illuminate\Http\JsonResponse
    {
        $students = Student::all();
        return response()->json([
            'students' => $students,
        ]);
    }

    public function edit($id)
    {
        $student = Student::find($id);
        if ($student) {
            return response()->json([
                'status' => 200,
                'student' => $student,
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Student not found',
        ]);
    }


    public function update(Request $request, $id)
    {

        dd($request);

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:191',
            'email' => 'required|email|max:191',
            'phone' => 'required|max:191',
            'course' => 'required|max:191',
        ]);

        if ($validator->fails()) {

            return response()->json([
                    'status' => 200,
                    'message' => 'Student updated successfully!',
                ]);

//            $student = Student::find($id);
//
//            if($student){
//                $student->name = $request->input('name');
//                $student->email = $request->input('email');
//                $student->phone = $request->input('phone');
//                $student->course = $request->input('course');
//                $student->update();
//                return response()->json([
//                    'status' => 200,
//                    'message' => 'Student updated successfully!',
//                ]);
//            }
        } else {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }
    }

    public function destroy(Student $student)
    {
        //
    }
}
