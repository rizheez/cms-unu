<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\AcademicCalendar;
use App\Models\Faculty;
use App\Models\Lecturer;
use App\Models\StudyProgram;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class DemoAcademicsSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedAcademics();
        $this->seedAcademicCalendars();
    }

    private function seedAcademics(): void
    {
        collect([
            ['slug' => 'fakultas-ekonomi-dan-bisnis', 'name' => 'Fakultas Ekonomi dan Bisnis', 'short_name' => 'FEB', 'dean_name' => 'Dekan Fakultas Ekonomi dan Bisnis', 'email' => 'feb@unukaltim.ac.id', 'image' => 'seed/faculties/ekonomi-bisnis.jpg', 'programs' => [
                ['name' => 'Akuntansi', 'degree_level' => 'S1', 'head_name' => 'Ketua Program Studi Akuntansi', 'accreditation' => 'Baik'],
            ]],
            ['slug' => 'fakultas-teknik', 'name' => 'Fakultas Teknik', 'short_name' => 'FT', 'dean_name' => 'Dekan Fakultas Teknik', 'email' => 'ft@unukaltim.ac.id', 'image' => 'seed/faculties/teknik.jpg', 'programs' => [
                ['name' => 'Arsitektur', 'degree_level' => 'S1', 'head_name' => 'Ketua Program Studi Arsitektur', 'accreditation' => 'Baik'],
                ['name' => 'Desain Interior', 'degree_level' => 'S1', 'head_name' => 'Ketua Program Studi Desain Interior', 'accreditation' => 'Baik'],
                ['name' => 'Teknik Industri', 'degree_level' => 'S1', 'head_name' => 'Ketua Program Studi Teknik Industri', 'accreditation' => 'Baik'],
                ['name' => 'Teknik Informatika', 'degree_level' => 'S1', 'head_name' => 'Ketua Program Studi Teknik Informatika', 'accreditation' => 'Baik'],
                ['name' => 'Teknologi Industri Pertanian', 'degree_level' => 'S1', 'head_name' => 'Ketua Program Studi Teknologi Industri Pertanian', 'accreditation' => 'Baik'],
            ]],
            ['slug' => 'fakultas-ilmu-sosial-dan-kependidikan', 'name' => 'Fakultas Ilmu Sosial dan Kependidikan', 'short_name' => 'FISK', 'dean_name' => 'Dekan Fakultas Ilmu Sosial dan Kependidikan', 'email' => 'fisk@unukaltim.ac.id', 'image' => 'seed/faculties/ilmu-sosial-kependidikan.jpg', 'programs' => [
                ['name' => 'Hubungan Internasional', 'degree_level' => 'S1', 'head_name' => 'Ketua Program Studi Hubungan Internasional', 'accreditation' => 'Baik'],
                ['name' => 'Ilmu Komunikasi', 'degree_level' => 'S1', 'head_name' => 'Ketua Program Studi Ilmu Komunikasi', 'accreditation' => 'Baik'],
                ['name' => 'Pendidikan Anak Usia Dini', 'degree_level' => 'S1', 'head_name' => 'Ketua Program Studi Pendidikan Anak Usia Dini', 'accreditation' => 'Baik'],
            ]],
            ['slug' => 'fakultas-farmasi', 'name' => 'Fakultas Farmasi', 'short_name' => 'FF', 'dean_name' => 'Dekan Fakultas Farmasi', 'email' => 'farmasi@unukaltim.ac.id', 'image' => 'seed/faculties/farmasi.jpg', 'programs' => [
                ['name' => 'Farmasi', 'degree_level' => 'S1', 'head_name' => 'Ketua Program Studi Farmasi', 'accreditation' => 'Baik'],
            ]],
        ])->each(function (array $faculty, int $index): void {
            $model = Faculty::query()->updateOrCreate(
                ['slug' => $faculty['slug']],
                [
                    'name' => $faculty['name'],
                    'short_name' => $faculty['short_name'],
                    'dean_name' => $faculty['dean_name'],
                    'email' => $faculty['email'],
                    'phone' => '+62 812 4000 20'.($index + 10),
                    'description' => $faculty['name'].' menaungi pembelajaran, riset, dan pengabdian masyarakat di Universitas Nahdlatul Ulama Kalimantan Timur.',
                    'image' => $faculty['image'],
                    'accreditation' => 'Baik',
                    'order' => $index + 1,
                    'is_active' => true,
                ],
            );

            foreach ($faculty['programs'] as $programIndex => $program) {
                $studyProgram = StudyProgram::query()->updateOrCreate(
                    ['slug' => Str::slug($program['name'])],
                    [
                        'faculty_id' => $model->id,
                        'name' => $program['name'],
                        'degree_level' => $program['degree_level'],
                        'head_name' => $program['head_name'],
                        'description' => 'Program studi '.$program['name'].' membekali mahasiswa dengan fondasi keilmuan, praktik lapangan, dan portofolio proyek.',
                        'image' => 'seed/programs/'.Str::slug($program['name']).'.jpg',
                        'accreditation' => $program['accreditation'],
                        'order' => $programIndex + 1,
                        'is_active' => true,
                    ],
                );

                $this->seedLecturers($model, $studyProgram, $programIndex);
            }
        });
    }

    private function seedLecturers(Faculty $faculty, StudyProgram $studyProgram, int $offset): void
    {
        collect([
            ['name' => (string) $studyProgram->head_name, 'position' => 'Ketua Program Studi', 'education_level' => 'S3'],
            ['name' => 'Dosen '.$studyProgram->name.' '.($offset + 1), 'position' => 'Dosen Tetap', 'education_level' => 'S2'],
        ])->each(function (array $lecturer, int $index) use ($faculty, $studyProgram): void {
            Lecturer::query()->updateOrCreate(
                ['slug' => Str::slug($lecturer['name'])],
                [
                    'faculty_id' => $faculty->id,
                    'study_program_id' => $studyProgram->id,
                    'name' => $lecturer['name'],
                    'nidn' => '11'.str_pad((string) ($studyProgram->id * 10 + $index), 8, '0', STR_PAD_LEFT),
                    'email' => Str::slug($lecturer['name'], '.').'@unu.ac.id',
                    'position' => $lecturer['position'],
                    'education_level' => $lecturer['education_level'],
                    'bio' => $lecturer['name'].' aktif mengajar, membimbing mahasiswa, dan mengembangkan riset terapan di bidang '.$studyProgram->name.'.',
                    'photo' => 'seed/lecturers/'.Str::slug($lecturer['name']).'.jpg',
                    'expertise' => $studyProgram->name.', Pembelajaran Berbasis Proyek, Riset Terapan',
                    'order' => $index + 1,
                    'is_active' => true,
                ],
            );
        });
    }

    private function seedAcademicCalendars(): void
    {
        collect([
            ['title' => 'Pendaftaran Mahasiswa Baru Gelombang 1', 'category' => 'penerimaan', 'start_date' => now()->addDays(10), 'end_date' => now()->addDays(45), 'color' => '#00a9b7'],
            ['title' => 'Pengisian KRS Semester Ganjil', 'category' => 'perkuliahan', 'start_date' => now()->addDays(35), 'end_date' => now()->addDays(42), 'color' => '#31d4b4'],
            ['title' => 'Awal Perkuliahan Semester Ganjil', 'category' => 'perkuliahan', 'start_date' => now()->addDays(50), 'end_date' => null, 'color' => '#ffc928'],
            ['title' => 'Ujian Tengah Semester', 'category' => 'ujian', 'start_date' => now()->addDays(110), 'end_date' => now()->addDays(118), 'color' => '#ff9f1c'],
            ['title' => 'Wisuda Sarjana dan Diploma', 'category' => 'wisuda', 'start_date' => now()->addDays(160), 'end_date' => null, 'color' => '#005f69'],
        ])->each(fn (array $event): AcademicCalendar => AcademicCalendar::query()->updateOrCreate(
            ['title' => $event['title']],
            [
                'description' => 'Agenda '.$event['title'].' untuk sivitas akademika UNU.',
                'start_date' => Carbon::parse($event['start_date'])->toDateString(),
                'end_date' => $event['end_date'] ? Carbon::parse($event['end_date'])->toDateString() : null,
                'category' => $event['category'],
                'color' => $event['color'],
            ],
        ));
    }
}
