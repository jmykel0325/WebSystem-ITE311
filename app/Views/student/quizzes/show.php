<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="container py-4">
  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
  <?php endif; ?>
  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
  <?php endif; ?>

  <h1 class="h4 mb-3">Quiz: <?= esc($group['title'] ?? 'Untitled Quiz') ?></h1>

  <div class="mb-3">
    <div><strong>Course:</strong> <?= esc($group['course_title']) ?></div>
    <div><strong>Lesson:</strong> <?= esc($group['lesson_title']) ?></div>
  </div>

  <?php if (!empty($completed)): ?>
    <div class="alert alert-info mb-3">
      <div class="fw-semibold mb-1">Your result</div>
      <div>Score: <strong><?= (int)$score ?>%</strong></div>
      <div class="mt-1 small text-muted">You can take this quiz only once.</div>
    </div>
  <?php endif; ?>

  <?php if (empty($completed)): ?>
    <form method="post" action="<?= site_url('student/quizzes/submit/' . (int)$group['id']) ?>">
      <?= csrf_field() ?>

      <?php $oldChoices = (array) old('choices'); ?>

      <?php $qIndex = 1; foreach ($questions as $q): ?>
        <?php $qid = (int)$q['id']; ?>
        <div class="card mb-3">
          <div class="card-body">
            <p class="fw-semibold mb-1">Question <?= $qIndex++ ?>:</p>
            <p><?= nl2br(esc($q['question'])) ?></p>

            <?php $opts = $options[$qid] ?? []; ?>
            <?php if (!empty($opts)): ?>
              <?php foreach (['A','B','C','D'] as $key): ?>
                <?php if (!empty($opts[$key] ?? '')): ?>
                  <div class="form-check">
                    <input class="form-check-input" type="radio"
                           name="choices[<?= $qid ?>]"
                           id="choice_<?= $qid ?>_<?= $key ?>"
                           value="<?= $key ?>"
                           <?= (($oldChoices[$qid] ?? '') === $key) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="choice_<?= $qid ?>_<?= $key ?>">
                      <strong><?= $key ?>.</strong> <?= esc($opts[$key]) ?>
                    </label>
                  </div>
                <?php endif; ?>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Submit Quiz</button>
        <a href="<?= site_url('student/quizzes') ?>" class="btn btn-light">Back to list</a>
      </div>
    </form>
  <?php else: ?>
    <a href="<?= site_url('student/quizzes') ?>" class="btn btn-light">Back to list</a>
  <?php endif; ?>
</div>
<?= $this->endSection() ?>
