<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="container py-4">
  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
  <?php endif; ?>
  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
  <?php endif; ?>

  <h1 class="h4 mb-3">My Quizzes</h1>
  <p class="text-muted small">You only see quizzes from courses you are enrolled in within the last 4 months.</p>

  <table class="table table-striped align-middle">
    <thead>
      <tr>
        <th>Course</th>
        <th>Quiz Title</th>
        <th>Questions</th>
        <th>Status</th>
        <th style="width: 140px;">Action</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($quizzes)): ?>
        <tr><td colspan="5" class="text-muted">No quizzes available.</td></tr>
      <?php else: foreach ($quizzes as $q): ?>
        <tr>
          <td><?= esc($q['course_title']) ?></td>
          <td><?= esc($q['title']) ?></td>
          <td><?= (int)($q['question_count'] ?? 0) ?></td>
          <td>
            <?php $completed = !empty($q['completed']); ?>
            <?php if ($completed): ?>
              <span class="badge bg-success">Completed</span>
            <?php else: ?>
              <span class="badge bg-secondary">Not started</span>
            <?php endif; ?>
          </td>
          <td>
            <a href="<?= site_url('student/quizzes/show/' . (int)$q['any_id']) ?>" class="btn btn-sm btn-primary">
              <?= !empty($q['completed']) ? 'View Result' : 'Take Quiz' ?>
            </a>
          </td>
        </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>
<?= $this->endSection() ?>
