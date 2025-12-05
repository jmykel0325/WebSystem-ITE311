<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="container py-4">
  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
  <?php endif; ?>
  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
  <?php endif; ?>

  <h1 class="h4 mb-2">Questions for: <?= esc($quizMeta['title']) ?></h1>
  <p class="text-muted mb-3">
    <strong>Course:</strong> <?= esc($quizMeta['course_title']) ?>
  </p>

  <div class="mb-3">
    <a href="<?= site_url('teacher/quizzes/create?course_id=' . (int)$quizMeta['course_id'] . '&title=' . urlencode($quizMeta['title'])) ?>" class="btn btn-primary">
      Add New Question
    </a>
    <a href="<?= site_url('teacher/quizzes') ?>" class="btn btn-light">Back to Quiz List</a>
  </div>

  <table class="table table-striped align-middle">
    <thead>
      <tr>
        <th style="width:60px;">#</th>
        <th>Question</th>
        <th>Correct</th>
        <th style="width:160px;">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($questions)): ?>
        <tr><td colspan="4" class="text-muted">No questions yet.</td></tr>
      <?php else: $i = 1; foreach ($questions as $q): ?>
        <?php $payload = json_decode($q['answer'] ?? '', true) ?: []; $correct = $payload['correct'] ?? ''; ?>
        <tr>
          <td><?= $i++ ?></td>
          <td><?= esc(mb_strimwidth($q['question'], 0, 80, '...')) ?></td>
          <td><?= esc($correct) ?></td>
          <td>
            <a href="<?= site_url('teacher/quizzes/edit/' . (int)$q['id']) ?>" class="btn btn-sm btn-outline-primary">Edit</a>
            <a href="<?= site_url('teacher/quizzes/delete/' . (int)$q['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this question?');">Delete</a>
          </td>
        </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>
<?= $this->endSection() ?>
