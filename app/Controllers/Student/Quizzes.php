<?php
namespace App\Controllers\Student;

use App\Controllers\BaseController;
use App\Models\EnrollmentModel;
use App\Models\GradeModel;
use App\Models\SubmissionModel;
use CodeIgniter\Database\BaseConnection;

class Quizzes extends BaseController
{
    protected BaseConnection $db;
    protected EnrollmentModel $enrollmentModel;

    public function __construct()
    {
        $this->db              = \Config\Database::connect();
        $this->enrollmentModel = new EnrollmentModel();
    }

    protected function ensureStudent()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }
        if (session()->get('role') !== 'student') {
            return redirect()->back()->with('error', 'Access denied');
        }
        return null;
    }

    public function index()
    {
        if ($redirect = $this->ensureStudent()) {
            return $redirect;
        }

        $studentId = (int) session('user_id');
        $cutoff    = date('Y-m-d H:i:s', strtotime('-4 months'));

        // Group by course + quiz title so each quiz appears once with question count
        $quizzes = $this->db->table('quizzes q')
            ->select('MIN(q.id) AS any_id, MIN(q.lesson_id) AS lesson_id, q.title, COUNT(*) AS question_count, l.title AS lesson_title, c.title AS course_title, c.id AS course_id')
            ->join('lessons l', 'l.id = q.lesson_id')
            ->join('courses c', 'c.id = l.course_id')
            ->join('enrollments e', 'e.course_id = c.id')
            ->where('e.user_id', $studentId)
            ->where('e.status', 'approved')
            ->where('e.enrollment_date >=', $cutoff)
            ->groupBy('c.id, c.title, l.id, l.title, q.title')
            ->orderBy('c.title', 'ASC')
            ->orderBy('q.title', 'ASC')
            ->get()
            ->getResultArray();

        // Determine completion status per quiz group based on submissions
        $submissionModel = new SubmissionModel();
        foreach ($quizzes as &$quiz) {
            $lessonId = (int) ($quiz['lesson_id'] ?? 0);
            $title    = (string) $quiz['title'];

            if ($lessonId === 0 || $title === '') {
                $quiz['completed'] = false;
                continue;
            }

            // Collect all question IDs for this quiz group
            $questionIds = $this->db->table('quizzes')
                ->select('id')
                ->where('lesson_id', $lessonId)
                ->where('title', $title)
                ->get()
                ->getResultArray();

            $ids = array_column($questionIds, 'id');
            if (empty($ids)) {
                $quiz['completed'] = false;
                continue;
            }

            $existing = $submissionModel
                ->whereIn('quiz_id', $ids)
                ->where('user_id', $studentId)
                ->first();

            $quiz['completed'] = !empty($existing);
        }
        unset($quiz);

        return view('student/quizzes/index', [
            'title'   => 'My Quizzes',
            'quizzes' => $quizzes,
        ]);
    }

    /**
     * Find a quiz group (lesson_id + title) accessible to this student,
     * using any question id from the group.
     */
    protected function findAccessibleQuizGroup(int $quizId, int $studentId): ?array
    {
        $cutoff = date('Y-m-d H:i:s', strtotime('-4 months'));

        $quiz = $this->db->table('quizzes q')
            ->select('q.id, q.lesson_id, q.title, l.title AS lesson_title, c.title AS course_title, c.id AS course_id')
            ->join('lessons l', 'l.id = q.lesson_id')
            ->join('courses c', 'c.id = l.course_id')
            ->join('enrollments e', 'e.course_id = c.id')
            ->where('q.id', $quizId)
            ->where('e.user_id', $studentId)
            ->where('e.status', 'approved')
            ->where('e.enrollment_date >=', $cutoff)
            ->get()
            ->getRowArray();

        return $quiz ?: null;
    }

    public function show($id)
    {
        if ($redirect = $this->ensureStudent()) {
            return $redirect;
        }

        $studentId = (int) session('user_id');
        $quizId    = (int) $id;

        $group = $this->findAccessibleQuizGroup($quizId, $studentId);
        if (! $group) {
            return redirect()->to('/student/quizzes')->with('error', 'Quiz not found or not accessible.');
        }

        // Load all questions for this quiz group (same lesson + title)
        $questions = $this->db->table('quizzes')
            ->where('lesson_id', $group['lesson_id'])
            ->where('title', $group['title'])
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();

        if (empty($questions)) {
            return redirect()->to('/student/quizzes')->with('error', 'Quiz has no questions.');
        }

        $questionIds = array_column($questions, 'id');

        // Check if student has already submitted for any question in this quiz
        $submissionModel = new SubmissionModel();
        $existingSubs = $submissionModel
            ->whereIn('quiz_id', $questionIds)
            ->where('user_id', $studentId)
            ->findAll();

        $alreadyCompleted = !empty($existingSubs);

        // Build options per question from answer JSON
        $optionsByQuestion = [];
        foreach ($questions as $q) {
            $payload = json_decode($q['answer'] ?? '', true) ?: [];
            $optionsByQuestion[$q['id']] = $payload['options'] ?? [];
        }

        // Aggregate score if already completed (use latest submission per question)
        $scorePercent = null;
        if ($alreadyCompleted) {
            $correctCount = 0;
            $total        = count($questions);
            foreach ($questions as $q) {
                $sub = $submissionModel
                    ->where('quiz_id', $q['id'])
                    ->where('user_id', $studentId)
                    ->orderBy('submitted_at', 'DESC')
                    ->first();
                if ($sub && (int)$sub['score'] === 100) {
                    $correctCount++;
                }
            }
            $scorePercent = $total > 0 ? round($correctCount * 100 / $total) : 0;
        }

        return view('student/quizzes/show', [
            'title'      => 'Take Quiz',
            'group'      => $group,
            'questions'  => $questions,
            'options'    => $optionsByQuestion,
            'completed'  => $alreadyCompleted,
            'score'      => $scorePercent,
        ]);
    }

    public function submit($id)
    {
        if ($redirect = $this->ensureStudent()) {
            return $redirect;
        }

        $studentId = (int) session('user_id');
        $quizId    = (int) $id;

        $group = $this->findAccessibleQuizGroup($quizId, $studentId);
        if (! $group) {
            return redirect()->to('/student/quizzes')->with('error', 'Quiz not found or not accessible.');
        }

        $submissionModel = new SubmissionModel();
        // Load all questions in this quiz group
        $questions = $this->db->table('quizzes')
            ->where('lesson_id', $group['lesson_id'])
            ->where('title', $group['title'])
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();

        if (empty($questions)) {
            return redirect()->to('/student/quizzes')->with('error', 'Quiz has no questions.');
        }

        $questionIds = array_column($questions, 'id');

        // Prevent multiple attempts: if any submission exists for this student and
        // any question in the group, block further submissions.
        $existingAny = $submissionModel
            ->whereIn('quiz_id', $questionIds)
            ->where('user_id', $studentId)
            ->first();

        if ($existingAny) {
            return redirect()->to('/student/quizzes/show/' . $quizId)
                ->with('error', 'You have already completed this quiz.');
        }

        $choices = (array) $this->request->getPost('choices'); // [question_id => option_key]
        if (empty($choices)) {
            return redirect()->back()->withInput()->with('error', 'Please answer the questions before submitting.');
        }

        $correctCount = 0;
        $total        = count($questions);

        foreach ($questions as $q) {
            $qid    = (int) $q['id'];
            $choice = $choices[$qid] ?? null;

            if (!in_array($choice, ['A','B','C','D'], true)) {
                continue; // unanswered or invalid
            }

            $payload = json_decode($q['answer'] ?? '', true) ?: [];
            $correct = $payload['correct'] ?? null;

            $scorePerQuestion = ($choice === $correct) ? 100 : 0;
            if ($scorePerQuestion === 100) {
                $correctCount++;
            }

            $submissionModel->insert([
                'quiz_id'      => $qid,
                'user_id'      => $studentId,
                'submitted_at' => date('Y-m-d H:i:s'),
                'score'        => $scorePerQuestion,
            ]);
        }

        $finalScore = $total > 0 ? round($correctCount * 100 / $total) : 0;

        // Store aggregated grade using the any_id (first question id) as quiz_id
        $gradeModel = new GradeModel();
        $grade = $gradeModel->where('student_id', $studentId)
                            ->where('quiz_id', $quizId)
                            ->first();

        $gradeData = [
            'student_id' => $studentId,
            'course_id'  => $group['course_id'],
            'quiz_id'    => $quizId,
            'score'      => $finalScore,
        ];

        if ($grade) {
            $gradeModel->update($grade['id'], $gradeData);
        } else {
            $gradeModel->insert($gradeData);
        }

        return redirect()->to('/student/quizzes/show/' . $quizId)
            ->with('success', 'Quiz submitted. Your score: ' . $finalScore . '%');
    }
}
