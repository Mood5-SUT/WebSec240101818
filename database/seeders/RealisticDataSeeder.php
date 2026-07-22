<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Course;
use App\Models\Assignment;
use App\Models\Submission;
use App\Models\Enrollment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class RealisticDataSeeder extends Seeder
{
    public function run(): void
    {
        // Fetch existing users
        $admin = User::where('email', 'admin@study.com')->first();
        $instructor = User::where('email', 'instructor@study.com')->first();
        $student1 = User::where('email', 'student@study.com')->first();
        $student2 = User::where('email', 'student2@study.com')->first();

        if (!$admin || !$instructor || !$student1 || !$student2) {
            return;
        }

        // Create mock files to use as reference/submission files
        Storage::disk('public')->makeDirectory('assignments');
        Storage::disk('public')->makeDirectory('submissions');
        
        Storage::disk('public')->put('assignments/pcap_analysis_guidelines.txt', "Packet Analysis Guide:\n1. Open file in Wireshark\n2. Filter for http.request.method == \"POST\"\n3. Inspect credentials in URL-encoded form data.");
        Storage::disk('public')->put('submissions/wireshark_report_student1.txt', "Wireshark Lab Report\nAuthor: Student User\nFlag identified: FLAG{n3tw0rk_sec_1s_cruc1al}");

        // 1. Create Courses
        $course1 = Course::updateOrCreate(
            ['title' => 'Introduction to Cybersecurity & Ethical Hacking'],
            [
                'description' => "Learn the fundamentals of penetration testing, network sniffing, password security, and vulnerability scanning. Gain hands-on experience using modern security frameworks.",
                'instructor_id' => $instructor->id,
                'created_at' => Carbon::now()->subDays(10),
            ]
        );

        $course2 = Course::updateOrCreate(
            ['title' => 'Advanced Secure Software Development'],
            [
                'description' => "Focuses on writing secure code, identifying OWASP Top 10 vulnerabilities (SQLi, XSS, Command Injection), and implementing robust secure design patterns in web frameworks.",
                'instructor_id' => $instructor->id,
                'created_at' => Carbon::now()->subDays(8),
            ]
        );

        $course3 = Course::updateOrCreate(
            ['title' => 'Cryptography & Public Key Infrastructure'],
            [
                'description' => "Deep dive into symmetric and asymmetric encryption systems, hashing algorithms, digital signatures, OpenSSL commands, and certificate authority design.",
                'instructor_id' => $admin->id, // Admins can teach courses as well
                'created_at' => Carbon::now()->subDays(5),
            ]
        );

        // 2. Create Assignments
        $assign1 = Assignment::updateOrCreate(
            ['title' => 'Wireshark Packet Analysis Lab'],
            [
                'course_id' => $course1->id,
                'description' => "Download the attached guide, analyze the raw network packet streams, identify the unencrypted credentials, and submit the flag.",
                'file_path' => 'assignments/pcap_analysis_guidelines.txt',
                'created_at' => Carbon::now()->subDays(9),
            ]
        );

        $assign2 = Assignment::updateOrCreate(
            ['title' => 'OWASP Top 10 SQLi Mitigation Lab'],
            [
                'course_id' => $course2->id,
                'description' => "Rewrite the raw query string concatenations into parameterized Eloquent bindings. Submit a detailed report of the SQLi vulnerabilities found.",
                'created_at' => Carbon::now()->subDays(7),
            ]
        );

        $assign3 = Assignment::updateOrCreate(
            ['title' => 'XSS Escaping & Input Sanitization'],
            [
                'course_id' => $course2->id,
                'description' => "Implement input validation and Blade escaping mechanisms to mitigate stored and reflected XSS payloads.",
                'created_at' => Carbon::now()->subDays(6),
            ]
        );

        // 3. Create Enrollments
        Enrollment::updateOrCreate(
            ['user_id' => $student1->id, 'course_id' => $course1->id]
        );
        Enrollment::updateOrCreate(
            ['user_id' => $student1->id, 'course_id' => $course2->id]
        );
        Enrollment::updateOrCreate(
            ['user_id' => $student2->id, 'course_id' => $course2->id]
        );
        Enrollment::updateOrCreate(
            ['user_id' => $student2->id, 'course_id' => $course3->id]
        );

        // 4. Create Submissions
        // Graded Submission (Student 1)
        Submission::updateOrCreate(
            ['assignment_id' => $assign1->id, 'user_id' => $student1->id],
            [
                'submitted_text' => "I analyzed the packet captures guide. I identified HTTP requests on port 80. The unencrypted user was 'admin' and the plaintext password was 'P@ssw0rd123!'. The flag value is FLAG{n3tw0rk_sec_1s_cruc1al}.",
                'file_path' => 'submissions/wireshark_report_student1.txt',
                'grade' => 'A',
                'grade_review_status' => 'none',
                'created_at' => Carbon::now()->subDays(5),
            ]
        );

        // Ungraded Submission (Student 1)
        Submission::updateOrCreate(
            ['assignment_id' => $assign2->id, 'user_id' => $student1->id],
            [
                'submitted_text' => "I replaced the concatenations inside User::whereRaw(...) with parameterized bindings. I tested the payloads and verified that the SQLi bypass is no longer functional.",
                'grade' => null,
                'grade_review_status' => 'none',
                'created_at' => Carbon::now()->subDays(4),
            ]
        );

        // Submission with active Grade Review Request (Student 2)
        Submission::updateOrCreate(
            ['assignment_id' => $assign2->id, 'user_id' => $student2->id],
            [
                'submitted_text' => "Mitigated SQLi by switching query builder concatenation to prepared statements bindings: \$user = DB::select('SELECT * FROM users WHERE email = ?', [\$request->email]);",
                'grade' => 'B+',
                'grade_review_status' => 'requested',
                'created_at' => Carbon::now()->subDays(3),
            ]
        );
    }
}
