<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\AcademicCalendar;
use App\Models\Faculty;
use App\Models\Lecturer;
use App\Models\StudyProgram;
use App\Services\SeoService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AcademicsController extends Controller
{
    public function index(SeoService $seo): View
    {
        $seo->setDefault();

        return view('academic.index', [
            'faculties' => Faculty::query()->with('studyPrograms')->where('is_active', true)->orderBy('order')->get(),
        ]);
    }

    public function faculties(SeoService $seo): View
    {
        $seo->setDefault();

        return view('academic.faculties', [
            'faculties' => Faculty::query()->withCount('studyPrograms')->where('is_active', true)->orderBy('order')->get(),
        ]);
    }

    public function faculty(Faculty $faculty, SeoService $seo): View
    {
        $faculty->load(['studyPrograms', 'lecturers']);
        $seo->setModel($faculty, $faculty->name, $faculty->description);

        return view('academic.faculty', compact('faculty'));
    }

    public function studyProgram(StudyProgram $studyProgram, SeoService $seo): View
    {
        $studyProgram->load(['faculty', 'lecturers']);
        $seo->setModel($studyProgram, $studyProgram->name, $studyProgram->description);

        return view('academic.study-program', compact('studyProgram'));
    }

    public function lecturers(Request $request, SeoService $seo): View
    {
        $seo->setDefault();

        return view('academic.lecturers', [
            'lecturers' => Lecturer::query()
                ->with(['faculty', 'studyProgram'])
                ->when($request->integer('faculty_id'), fn ($query, int $facultyId) => $query->where('faculty_id', $facultyId))
                ->when($request->integer('study_program_id'), fn ($query, int $studyProgramId) => $query->where('study_program_id', $studyProgramId))
                ->where('is_active', true)
                ->orderBy('order')
                ->paginate(12),
            'faculties' => Faculty::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function lecturer(Lecturer $lecturer, SeoService $seo): View
    {
        $lecturer->load(['faculty', 'studyProgram']);
        $seo->setModel($lecturer, $lecturer->name, $lecturer->bio);

        return view('academic.lecturer', compact('lecturer'));
    }

    public function calendar(SeoService $seo): View
    {
        $seo->setDefault();

        return view('academic.calendar', [
            'events' => AcademicCalendar::query()->orderBy('start_date')->paginate(20),
        ]);
    }
}
