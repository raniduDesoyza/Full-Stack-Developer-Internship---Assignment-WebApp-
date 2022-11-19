<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Student::query()->paginate(20);

        return Inertia::render('students', [
            'data' => $data
        ]);
    }

    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreStudentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStudentRequest $request)
    {
        Validator::make($request->all(), [
            'name' => 'required',
            'age' => 'required',
            'status' => 'required'
        ])->validate();

        Student::create($request->all());

        $this->processImage($request);

        return redirect()->back()
            ->with('message', 'New Student Added');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateStudentRequest  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {
        Validator::make($request->all(), [
            'name' => 'required',
            'age' => 'required',
            'status' => 'required'
        ])->validate();

        $student->update($request->all());

        $this->processImage($request);

        return redirect()->back()
            ->with('message', 'Student Details Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->back()
            ->with('message', 'Student Removed');
    }

    public function upload(Request $request)
    {
        if($request->hasFile('imageFilepond'))
        {
            return $request->file('imageFilepond')->store('storage/uploads/students', 'public');
        }
        return '';
    }

    public function uploadRevert(Request $request)
    {
        if($image = $request->get('image'))
        {
            $path = storage_path('app/public/' . $image);
            if(file_exists($path)){
                unlink($path);
            }
        }
    }

    protected function processImage(Request $request)
    {
        if($image = $request->get('image'))
        {
            $path = storage_path('app/public/' . $image);
            if(file_exists($path)){
                copy($path, public_path($image));
                unlink($path);
            }
        }
    }

}
