<?php
namespace App\Controllers\Teacher;

use App\Controllers\BaseController;
use App\Models\QuizModel;
use App\Models\GradeModel;
use App\Models\SubmissionModel;
use CodeIgniter\Database\BaseConnection;

class Quizzes extends BaseController
{
    protected QuizModel $quizModel;
    protected BaseConnection $db;
    protected GradeModel $gradeModel;

    public function __construct()
    {
        $this->quizModel = new QuizModel();
        $this->db        = \Config\Database::connect();
        $this->gradeModel = new GradeModel();
    }

    protected function ensureTeacher()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }
        if (session()->get('role') !== 'teacher') {
            return redirect()->back()->with('error', 'Access denied');
        }
        return null;
    }

    public function index()
    {
        if ($redirect = $this->ensureTeacher()) {
            return $redirect;
        }

        $teacherId = (int) session('user_id');

        // Group by course + lesson + quiz title so each quiz appears once with a question count
        $quizzes = $this->db->table('quizzes q')
            ->select('MIN(q.id) AS any_id, q.title, COUNT(*) AS question_count, l.title AS lesson_title, c.title AS course_title')
            ->join('lessons l', 'l.id = q.lesson_id')
            ->join('courses c', 'c.id = l.course_id')
            ->where('c.teacher_id', $teacherId)
            ->groupBy('c.id, c.title, l.id, l.title, q.title')
            ->orderBy('c.title', 'ASC')
            ->orderBy('q.title', 'ASC')
            ->get()
            ->getResultArray();

        return view('teacher/quizzes/index', [
            'title'   => 'My Quizzes',
            'quizzes' => $quizzes,
        ]);
    }

    public function create()
    {
        if ($redirect = $this->ensureTeacher()) {
            return $redirect;
        }

        $teacherId = (int) session('user_id');

        $courses = $this->db->table('courses')
            ->select('id, title')
            ->where('teacher_id', $teacherId)
            ->orderBy('title', 'ASC')
            ->get()
            ->getResultArray();

        $lessons = $this->db->table('lessons l')
            ->select('l.id, l.title, l.course_id, c.title AS course_title')
            ->join('courses c', 'c.id = l.course_id')
            ->where('c.teacher_id', $teacherId)
            ->orderBy('c.title', 'ASC')
            ->orderBy('l.title', 'ASC')
            ->get()
            ->getResultArray();

        // Prefill may come either from flashdata (after saving a question)
        // or from query parameters when adding a new question from manage page.
        $prefill = session()->getFlashdata('quiz_prefill') ?? [];

        $getCourseId = (int) ($this->request->getGet('course_id') ?? 0);
        $getTitle    = trim((string) ($this->request->getGet('title') ?? ''));

        if (empty($prefill)) {
            if ($getCourseId > 0) {
                $prefill['course_id'] = $getCourseId;
            }
            if ($getTitle !== '') {
                $prefill['title'] = $getTitle;
            }
        }

        // Determine active course for listing existing questions
        $activeCourseId = old('course_id');
        if (empty($activeCourseId) && !empty($prefill['course_id'] ?? null)) {
            $activeCourseId = (int) $prefill['course_id'];
        }

        $existingQuizzes = [];
        if (!empty($activeCourseId)) {
            $existingQuizzes = $this->db->table('quizzes q')
                ->select('q.id, q.title, q.question, q.answer, l.title AS lesson_title')
                ->join('lessons l', 'l.id = q.lesson_id')
                ->join('courses c', 'c.id = l.course_id')
                ->where('c.teacher_id', $teacherId)
                ->where('c.id', $activeCourseId)
                ->orderBy('q.id', 'ASC')
                ->get()
                ->getResultArray();
        }

        return view('teacher/quizzes/create', [
            'title'           => 'Create Quiz',
            'courses'         => $courses,
            'lessons'         => $lessons,
            'prefill'         => $prefill,
            'existingQuizzes' => $existingQuizzes,
            'validation'      => \Config\Services::validation(),
        ]);
    }

    public function store()
    {
        if ($redirect = $this->ensureTeacher()) {
            return $redirect;
        }

        $teacherId = (int) session('user_id');

        $rules = [
            'course_id'      => 'required|integer',
            'title'          => 'permit_empty|min_length[3]',
            'question'       => 'required|min_length[5]',
            'option_a'       => 'required|min_length[1]',
            'option_b'       => 'required|min_length[1]',
            'option_c'       => 'required|min_length[1]',
            'option_d'       => 'required|min_length[1]',
            'correct_option' => 'required|in_list[A,B,C,D]',
        ];

        $addAnother = $this->request->getPost('add_another');

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors())
                ->with('error', 'Please check the fields below and correct any highlighted errors.');
        }

        $courseId = (int) $this->request->getPost('course_id');

        // Pick first lesson of this course to keep DB relation; if none, auto-create one
        $lesson = $this->db->table('lessons l')
            ->select('l.id')
            ->join('courses c', 'c.id = l.course_id')
            ->where('c.teacher_id', $teacherId)
            ->where('c.id', $courseId)
            ->orderBy('l.id', 'ASC')
            ->get(1)
            ->getRowArray();

        if (! $lesson) {
            // Auto-create a basic lesson so quizzes can exist without manual lesson creation
            $newLesson = [
                'course_id'  => $courseId,
                'title'      => 'Quiz Lesson',
                'content'    => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $this->db->table('lessons')->insert($newLesson);
            $lessonId = (int) $this->db->insertID();
        } else {
            $lessonId = (int) $lesson['id'];
        }

        $options = [
            'A' => trim((string) $this->request->getPost('option_a')),
            'B' => trim((string) $this->request->getPost('option_b')),
            'C' => trim((string) $this->request->getPost('option_c')),
            'D' => trim((string) $this->request->getPost('option_d')),
        ];
        $correct = $this->request->getPost('correct_option');

        $title = trim((string) $this->request->getPost('title'));

        // If title was left empty when adding another question, reuse the most
        // recent quiz title for this teacher + course.
        if ($title === '') {
            $lastQuiz = $this->db->table('quizzes q')
                ->select('q.title')
                ->join('lessons l', 'l.id = q.lesson_id')
                ->join('courses c', 'c.id = l.course_id')
                ->where('c.teacher_id', $teacherId)
                ->where('c.id', $courseId)
                ->orderBy('q.id', 'DESC')
                ->get(1)
                ->getRowArray();

            if ($lastQuiz && !empty($lastQuiz['title'])) {
                $title = (string) $lastQuiz['title'];
            }
        }

        $data = [
            'lesson_id' => $lessonId,
            'title'     => $title,
            'question'  => (string) $this->request->getPost('question'),
            'answer'    => json_encode(['options' => $options, 'correct' => $correct]),
        ];

        $this->quizModel->insert($data);

        if ($addAnother === '1') {
            return redirect()->to('/teacher/quizzes/create')
                ->with('success', 'Question saved. You can add another question for this quiz.')
                ->with('quiz_prefill', [
                    'course_id' => $courseId,
                    'title'     => $title,
                ]);
        }

        return redirect()->to('/teacher/quizzes')->with('success', 'Quiz created successfully.');
    }

    public function edit($id)
    {
        if ($redirect = $this->ensureTeacher()) {
            return $redirect;
        }

        $teacherId = (int) session('user_id');
        $quizId    = (int) $id;

        $quiz = $this->db->table('quizzes q')
            ->select('q.*, l.course_id')
            ->join('lessons l', 'l.id = q.lesson_id')
            ->join('courses c', 'c.id = l.course_id')
            ->where('q.id', $quizId)
            ->where('c.teacher_id', $teacherId)
            ->get()
            ->getRowArray();

        if (! $quiz) {
            return redirect()->to('/teacher/quizzes')->with('error', 'Quiz not found.');
        }

        $courses = $this->db->table('courses')
            ->select('id, title')
            ->where('teacher_id', $teacherId)
            ->orderBy('title', 'ASC')
            ->get()
            ->getResultArray();

        $lessons = $this->db->table('lessons l')
            ->select('l.id, l.title, l.course_id')
            ->join('courses c', 'c.id = l.course_id')
            ->where('c.teacher_id', $teacherId)
            ->orderBy('c.title', 'ASC')
            ->orderBy('l.title', 'ASC')
            ->get()
            ->getResultArray();

        return view('teacher/quizzes/edit', [
            'title'      => 'Edit Quiz',
            'quiz'       => $quiz,
            'courses'    => $courses,
            'lessons'    => $lessons,
            'validation' => \Config\Services::validation(),
        ]);
    }

    public function update($id)
    {
        if ($redirect = $this->ensureTeacher()) {
            return $redirect;
        }

        $teacherId = (int) session('user_id');
        $quizId    = (int) $id;

        $rules = [
            'lesson_id'      => 'required|integer',
            'question'       => 'required|min_length[5]',
            'option_a'       => 'required|min_length[1]',
            'option_b'       => 'required|min_length[1]',
            'option_c'       => 'required|min_length[1]',
            'option_d'       => 'required|min_length[1]',
            'correct_option' => 'required|in_list[A,B,C,D]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $existing = $this->db->table('quizzes q')
            ->select('q.id, q.lesson_id, q.title')
            ->join('lessons l', 'l.id = q.lesson_id')
            ->join('courses c', 'c.id = l.course_id')
            ->where('q.id', $quizId)
            ->where('c.teacher_id', $teacherId)
            ->get()
            ->getRowArray();

        if (! $existing) {
            return redirect()->to('/teacher/quizzes')->with('error', 'Quiz not found.');
        }

        $lessonId = (int) $this->request->getPost('lesson_id');

        $lesson = $this->db->table('lessons l')
            ->select('l.id')
            ->join('courses c', 'c.id = l.course_id')
            ->where('l.id', $lessonId)
            ->where('c.teacher_id', $teacherId)
            ->get()
            ->getRowArray();

        if (! $lesson) {
            return redirect()->back()->withInput()->with('error', 'Invalid lesson selected.');
        }

        $options = [
            'A' => trim((string) $this->request->getPost('option_a')),
            'B' => trim((string) $this->request->getPost('option_b')),
            'C' => trim((string) $this->request->getPost('option_c')),
            'D' => trim((string) $this->request->getPost('option_d')),
        ];
        $correct = $this->request->getPost('correct_option');

        $newTitle = trim((string) $this->request->getPost('title'));

        $data = [
            'lesson_id' => $lessonId,
            'title'     => $newTitle,
            'question'  => (string) $this->request->getPost('question'),
            'answer'    => json_encode(['options' => $options, 'correct' => $correct]),
        ];

        $this->quizModel->update($quizId, $data);

        // If title changed, apply it to all questions in this quiz group
        $originalTitle   = (string) ($existing['title'] ?? '');
        $originalLesson  = (int) ($existing['lesson_id'] ?? 0);

        if ($originalLesson > 0 && $originalTitle !== '' && $newTitle !== '' && $newTitle !== $originalTitle) {
            $this->db->table('quizzes')
                ->where('lesson_id', $originalLesson)
                ->where('title', $originalTitle)
                ->update(['title' => $newTitle]);
        }

        return redirect()->to('/teacher/quizzes')->with('success', 'Quiz updated successfully.');
    }

    public function delete($id)
    {
        if ($redirect = $this->ensureTeacher()) {
            return $redirect;
        }

        $teacherId = (int) session('user_id');
        $quizId    = (int) $id;

        $quiz = $this->db->table('quizzes q')
            ->select('q.id')
            ->join('lessons l', 'l.id = q.lesson_id')
            ->join('courses c', 'c.id = l.course_id')
            ->where('q.id', $quizId)
            ->where('c.teacher_id', $teacherId)
            ->get()
            ->getRowArray();

        if (! $quiz) {
            return redirect()->to('/teacher/quizzes')->with('error', 'Quiz not found.');
        }

        $this->quizModel->delete($quizId);

        return redirect()->to('/teacher/quizzes')->with('success', 'Quiz deleted successfully.');
    }

    public function manage($id)
    {
        if ($redirect = $this->ensureTeacher()) {
            return $redirect;
        }

        $teacherId = (int) session('user_id');
        $quizId    = (int) $id;

        // Find the quiz row and confirm ownership
        $quizMeta = $this->db->table('quizzes q')
            ->select('q.lesson_id, q.title, l.title AS lesson_title, c.id AS course_id, c.title AS course_title, c.teacher_id')
            ->join('lessons l', 'l.id = q.lesson_id')
            ->join('courses c', 'c.id = l.course_id')
            ->where('q.id', $quizId)
            ->get()
            ->getRowArray();

        if (! $quizMeta || (int)$quizMeta['teacher_id'] !== $teacherId) {
            return redirect()->to('/teacher/quizzes')->with('error', 'Quiz not found.');
        }

        // Get all questions that belong to this quiz (same lesson + title)
        $questions = $this->db->table('quizzes')
            ->where('lesson_id', $quizMeta['lesson_id'])
            ->where('title', $quizMeta['title'])
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();

        return view('teacher/quizzes/manage_questions', [
            'title'     => 'Manage Questions',
            'quizMeta'  => $quizMeta,
            'questions' => $questions,
        ]);
    }

    public function scores($id)
    {
        if ($redirect = $this->ensureTeacher()) {
            return $redirect;
        }

        $teacherId = (int) session('user_id');
        $quizId    = (int) $id;

        // Confirm quiz belongs to this teacher and get metadata, including lesson_id
        $quizMeta = $this->db->table('quizzes q')
            ->select('q.id AS quiz_id, q.lesson_id, q.title, l.title AS lesson_title, c.id AS course_id, c.title AS course_title, c.teacher_id')
            ->join('lessons l', 'l.id = q.lesson_id')
            ->join('courses c', 'c.id = l.course_id')
            ->where('q.id', $quizId)
            ->get()
            ->getRowArray();

        if (! $quizMeta || (int) $quizMeta['teacher_id'] !== $teacherId) {
            return redirect()->to('/teacher/quizzes')->with('error', 'Quiz not found.');
        }

        // Get all question IDs that belong to this quiz group (same lesson + title)
        $questions = $this->db->table('quizzes')
            ->select('id')
            ->where('lesson_id', $quizMeta['lesson_id'])
            ->where('title', $quizMeta['title'])
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();

        if (empty($questions)) {
            return view('teacher/quizzes/scores', [
                'title'  => 'Quiz Scores',
                'quiz'   => $quizMeta,
                'scores' => [],
            ]);
        }

        $questionIds = array_column($questions, 'id');

        // Aggregate scores from submissions table so it matches student result logic
        $submissionModel = new SubmissionModel();
        $builder = $submissionModel->builder();

        $results = $builder
            ->select('s.user_id, u.name AS student_name, u.email AS student_email, AVG(s.score) AS avg_score, MAX(s.submitted_at) AS last_submitted_at')
            ->from('submissions s')
            ->join('users u', 'u.id = s.user_id')
            ->whereIn('s.quiz_id', $questionIds)
            ->groupBy('s.user_id, u.name, u.email')
            ->orderBy('avg_score', 'DESC')
            ->orderBy('last_submitted_at', 'DESC')
            ->get()
            ->getResultArray();

        // Normalize to integer percentage for the view
        $scores = [];
        foreach ($results as $row) {
            $scores[] = [
                'student_id'    => $row['user_id'],
                'student_name'  => $row['student_name'],
                'student_email' => $row['student_email'],
                'score'         => isset($row['avg_score']) ? (int) round($row['avg_score']) : 0,
                'created_at'    => $row['last_submitted_at'],
            ];
        }

        return view('teacher/quizzes/scores', [
            'title'  => 'Quiz Scores',
            'quiz'   => $quizMeta,
            'scores' => $scores,
        ]);
    }
}
