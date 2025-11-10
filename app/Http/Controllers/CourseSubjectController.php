<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Subject;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseSubjectController extends Controller
{
    public function index()
    {
        $courses = Course::with('subjects')->orderBy('code')->get();
        
        return Inertia::render('Books/Course&Subject', [
            'courses' => $courses
        ]);
    }

    public function addCourse(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:courses,code',
            'description' => 'nullable|string'
        ]);

        Course::create($validated);

        return redirect()->route('course-subject.index');
    }

    public function addSubject(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'description' => 'nullable|string'
        ]);

        Subject::create($validated);

        return redirect()->route('course-subject.index');
    }

    public function deleteCourse($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        return redirect()->route('course-subject.index');
    }

    public function deleteSubject($id)
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();

        return redirect()->route('course-subject.index');
    }
}
