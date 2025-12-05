<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="container py-4">
  <h1 class="h4 mb-3">Edit Quiz</h1>

  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
  <?php endif; ?>

  <form method="post" action="<?= site_url('teacher/quizzes/update/' . (int)$quiz['id']) ?>">
    <?= csrf_field() ?>

    <div class="mb-3">
      <label for="course_id" class="form-label">Subject / Course</label>
      <?php
        $currentCourseId = $quiz['course_id'] ?? null;
      ?>
      <select id="course_id" class="form-select">
        <option value="">Select a subject</option>
        <?php foreach ($courses as $course): ?>
          <option value="<?= (int)$course['id'] ?>"
            <?= ($currentCourseId == $course['id']) ? 'selected' : '' ?>>
            <?= esc($course['title']) ?>
          </option>
        <?php endforeach; ?>
      </select>
      <div class="form-text">Lessons below are filtered by the selected subject.</div>
    </div>

    <div class="mb-3">
      <label class="form-label">Lesson</label>
      <?php
        // Find current lesson title
        $currentLessonTitle = '';
        foreach ($lessons as $lesson) {
            if ((int)$lesson['id'] === (int)$quiz['lesson_id']) {
                $currentLessonTitle = $lesson['title'];
                break;
            }
        }
      ?>
      <div class="form-control-plaintext fw-semibold">
        <?= esc($currentLessonTitle ?: 'Quiz Lesson') ?>
      </div>
      <input type="hidden" name="lesson_id" value="<?= (int)$quiz['lesson_id'] ?>">
    </div>

    <div class="mb-3">
      <label for="title" class="form-label">Quiz Title</label>
      <input type="text" name="title" id="title" class="form-control" value="<?= old('title', $quiz['title'] ?? '') ?>">
      <?php if (!empty($errors['title'])): ?>
        <div class="text-danger small"><?= esc($errors['title']) ?></div>
      <?php endif; ?>
    </div>

    <div class="mb-3">
      <label for="question" class="form-label">Question</label>
      <textarea name="question" id="question" rows="4" class="form-control"><?= old('question', $quiz['question']) ?></textarea>
      <?php if (!empty($errors['question'])): ?>
        <div class="text-danger small"><?= esc($errors['question']) ?></div>
      <?php endif; ?>
    </div>

    <div class="mb-3">
      <label for="answer" class="form-label">Correct Answer</label>
      <textarea name="answer" id="answer" rows="2" class="form-control"><?= old('answer', $quiz['answer']) ?></textarea>
      <?php if (!empty($errors['answer'])): ?>
        <div class="text-danger small"><?= esc($errors['answer']) ?></div>
      <?php endif; ?>
    </div>

    <div class="d-flex gap-2">
      <button type="submit" class="btn btn-primary">Update Quiz</button>
      <a href="<?= site_url('teacher/quizzes') ?>" class="btn btn-light">Cancel</a>
    </div>
  </form>
  <script>
    (function() {
      const courseSelect = document.getElementById('course_id');
      const lessonSelect  = document.getElementById('lesson_id');
      if (!courseSelect || !lessonSelect) return;

      const initialCourseId = courseSelect.value;
      Array.from(lessonSelect.options).forEach(function (opt) {
        if (!opt.value) return;
        const optCourseId = opt.getAttribute('data-course-id');
        opt.style.display = !initialCourseId || initialCourseId === optCourseId ? '' : 'none';
      });

      courseSelect.addEventListener('change', function () {
        const courseId = this.value;
        Array.from(lessonSelect.options).forEach(function (opt) {
          if (!opt.value) return;
          const optCourseId = opt.getAttribute('data-course-id');
          opt.style.display = !courseId || courseId === optCourseId ? '' : 'none';
        });
        lessonSelect.value = '';
      });
    })();
  </script>
</div>
<?= $this->endSection() ?>
