<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="container py-4">
  <h1 class="h4 mb-3">Create Quiz</h1>

  <?php if (session()->getFlashdata('error')): ?>
    <?php $errors = session('errors') ?? []; ?>
    <div class="alert alert-danger">
      <?= esc(session()->getFlashdata('error')) ?>
      <?php if (!empty($errors)): ?>
        <ul class="mb-0 small">
          <?php foreach ($errors as $msg): ?>
            <li><?= esc($msg) ?></li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>
  <?php endif; ?>
  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
  <?php endif; ?>

  <form method="post" action="<?= site_url('teacher/quizzes/store') ?>">
    <?= csrf_field() ?>

    <div class="mb-3">
      <label for="course_id" class="form-label">Subject / Course</label>
      <?php
        $selectedCourse = old('course_id');
        if (empty($selectedCourse) && !empty($prefill['course_id'] ?? null)) {
            $selectedCourse = $prefill['course_id'];
        }

        $selectedCourseTitle = '';
        foreach ($courses as $course) {
            if ((int)$course['id'] === (int)$selectedCourse) {
                $selectedCourseTitle = $course['title'];
                break;
            }
        }
      ?>

      <?php if (!empty($prefill['course_id'] ?? null)): ?>
        <!-- After first question is saved, lock the course to avoid mixing subjects -->
        <div class="form-control-plaintext fw-semibold">
          <?= esc($selectedCourseTitle ?: 'Selected course') ?>
        </div>
        <input type="hidden" name="course_id" id="course_id" value="<?= (int)$selectedCourse ?>">
        <div class="form-text">Subject is locked for this quiz while you add questions. Use "Save Quiz" to finish and choose another subject.</div>
      <?php else: ?>
        <select id="course_id" name="course_id" class="form-select">
          <option value="">Select a subject</option>
          <?php foreach ($courses as $course): ?>
            <option value="<?= (int)$course['id'] ?>" <?= ($selectedCourse == $course['id']) ? 'selected' : '' ?>>
              <?= esc($course['title']) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <div class="form-text">Lessons below will be filtered by the selected subject.</div>
      <?php endif; ?>

      <?php if (!empty($errors['course_id'] ?? null)): ?>
        <div class="text-danger small"><?= esc($errors['course_id']) ?></div>
      <?php endif; ?>
    </div>
 

    <div class="mb-3">
      <label for="title" class="form-label">Quiz Title</label>
      <?php
        $titleValue = old('title');
        if ($titleValue === '' && !empty($prefill['title'] ?? '')) {
            $titleValue = $prefill['title'];
        }
        // If there are existing questions, use their title as a reliable source
        if ($titleValue === '' && !empty($existingQuizzes)) {
            $titleValue = $existingQuizzes[0]['title'] ?? '';
        }

        $titleLocked = !empty($prefill['title'] ?? '') || !empty($existingQuizzes);
      ?>

      <?php if ($titleLocked): ?>
        <!-- Lock quiz title while adding more questions to the same quiz -->
        <div class="form-control-plaintext fw-semibold">
          <?= esc($titleValue) ?>
        </div>
        <input type="hidden" name="title" id="title" value="<?= esc($titleValue) ?>">
        <div class="form-text">Quiz title is locked while you add more questions. Use "Finish Quiz" to end and start another quiz.</div>
      <?php else: ?>
        <input type="text" name="title" id="title" class="form-control" value="<?= esc($titleValue) ?>">
      <?php endif; ?>

      <?php if (!empty($errors['title']) && ! $titleLocked): ?>
        <div class="text-danger small"><?= esc($errors['title']) ?></div>
      <?php endif; ?>
    </div>

    <div class="mb-3">
      <label for="question" class="form-label">Question</label>
      <textarea name="question" id="question" rows="4" class="form-control"><?= old('question') ?></textarea>
      <?php if (!empty($errors['question'])): ?>
        <div class="text-danger small"><?= esc($errors['question']) ?></div>
      <?php endif; ?>
    </div>

    <div class="mb-3">
      <label class="form-label">Options</label>
      <div class="mb-2">
        <label class="form-label">Option A</label>
        <input type="text" name="option_a" class="form-control" value="<?= old('option_a') ?>">
      </div>
      <div class="mb-2">
        <label class="form-label">Option B</label>
        <input type="text" name="option_b" class="form-control" value="<?= old('option_b') ?>">
      </div>
      <div class="mb-2">
        <label class="form-label">Option C</label>
        <input type="text" name="option_c" class="form-control" value="<?= old('option_c') ?>">
      </div>
      <div class="mb-2">
        <label class="form-label">Option D</label>
        <input type="text" name="option_d" class="form-control" value="<?= old('option_d') ?>">
      </div>
      <?php if (!empty($errors['option_a'])): ?><div class="text-danger small"><?= esc($errors['option_a']) ?></div><?php endif; ?>
      <?php if (!empty($errors['option_b'])): ?><div class="text-danger small"><?= esc($errors['option_b']) ?></div><?php endif; ?>
      <?php if (!empty($errors['option_c'])): ?><div class="text-danger small"><?= esc($errors['option_c']) ?></div><?php endif; ?>
      <?php if (!empty($errors['option_d'])): ?><div class="text-danger small"><?= esc($errors['option_d']) ?></div><?php endif; ?>
    </div>

    <div class="mb-3">
      <label for="correct_option" class="form-label">Correct Option</label>
      <select name="correct_option" id="correct_option" class="form-select">
        <option value="">Select correct option</option>
        <?php foreach (['A','B','C','D'] as $opt): ?>
          <option value="<?= $opt ?>" <?= old('correct_option') === $opt ? 'selected' : '' ?>><?= $opt ?></option>
        <?php endforeach; ?>
      </select>
      <?php if (!empty($errors['correct_option'])): ?>
        <div class="text-danger small"><?= esc($errors['correct_option']) ?></div>
      <?php endif; ?>
    </div>

    <div class="d-flex gap-2">
      <button type="submit" name="add_another" value="0" class="btn btn-primary">
        Finish Quiz
      </button>
      <button type="submit" name="add_another" value="1" class="btn btn-outline-primary">
        Add Another Question (same quiz)
      </button>
      <a href="<?= site_url('teacher/quizzes') ?>" class="btn btn-light">Cancel</a>
    </div>
  </form>
  <script>
    (function() {
      const courseSelect = document.getElementById('course_id');
      const lessonSelect  = document.getElementById('lesson_id');
      if (!courseSelect || !lessonSelect) return;

      courseSelect.addEventListener('change', function () {
        const courseId = this.value;
        Array.from(lessonSelect.options).forEach(function (opt) {
          if (!opt.value) return; // skip placeholder
          const optCourseId = opt.getAttribute('data-course-id');
          opt.style.display = !courseId || courseId === optCourseId ? '' : 'none';
        });
        // reset selection when changing subject
        lessonSelect.value = '';
      });
    })();
  </script>
</div>
<?= $this->endSection() ?>
