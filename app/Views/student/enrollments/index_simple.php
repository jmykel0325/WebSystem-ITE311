<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title><?= esc($title ?? 'My Enrollments') ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
  <div class="container">
    <h1 class="mb-4">My Enrollments</h1>

    <div id="alerts"></div>

    <div class="row g-4">
      <div class="col-lg-6">
        <div class="card">
          <div class="card-header fw-bold">Enrolled Courses</div>
          <div class="card-body">
            <?php if (! empty($enrolled)): ?>
              <ul id="enrolled-list" class="list-group">
                <?php foreach ($enrolled as $c): ?>
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                      <div class="fw-semibold"><?= esc($c['title']) ?></div>
                      <small class="text-muted"><?= esc($c['description'] ?? '') ?></small>
                    </div>
                    <span class="badge text-bg-secondary">
                      <?= esc(isset($c['enrollment_date']) ? date('M d, Y', strtotime($c['enrollment_date'])) : '') ?>
                    </span>
                  </li>
                <?php endforeach; ?>
              </ul>
            <?php else: ?>
              <p class="text-muted mb-0">No enrollments yet.</p>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="card">
          <div class="card-header fw-bold">Available Courses</div>
          <div class="card-body">
            <?php if (! empty($available)): ?>
              <ul id="available-list" class="list-group">
                <?php foreach ($available as $c): ?>
                  <li class="list-group-item d-flex justify-content-between align-items-center" data-course-id="<?= (int)$c['id'] ?>">
                    <div>
                      <div class="fw-semibold"><?= esc($c['title']) ?></div>
                      <small class="text-muted"><?= esc($c['description'] ?? '') ?></small>
                    </div>
                    <button class="btn btn-primary btn-sm btn-enroll" data-course-id="<?= (int)$c['id'] ?>">
                      Enroll
                    </button>
                  </li>
                <?php endforeach; ?>
              </ul>
            <?php else: ?>
              <p class="text-muted mb-0">No available courses.</p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script>
    const CSRF_TOKEN_NAME = '<?= csrf_token() ?>';
    let   CSRF_HASH       = '<?= csrf_hash() ?>';
    const ENROLL_URL      = '<?= site_url('course/enroll') ?>';

    function showAlert(type, msg) {
      const html = `<div class="alert alert-${type} alert-dismissible fade show mt-3" role="alert">
                      ${msg}
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>`;
      $('#alerts').html(html);
    }

    $(document).on('click', '.btn-enroll', function() {
      const $btn = $(this);
      const courseId = $btn.data('course-id');
      $btn.prop('disabled', true);

      $.post(ENROLL_URL, { [CSRF_TOKEN_NAME]: CSRF_HASH, course_id: courseId })
        .done(function(res) {
          if (res.status === 'ok') {
            showAlert('success', res.message || 'Enrolled successfully.');

            const $li = $btn.closest('li');
            $li.find('.btn-enroll').remove();
            if (!$('#enrolled-list').length) {
              $('<ul id="enrolled-list" class="list-group mt-3"></ul>').appendTo('.col-lg-6:first .card-body');
            }
            $('#enrolled-list').prepend($li);

            if (res.csrf && res.csrf.hash) CSRF_HASH = res.csrf.hash;
          } else {
            showAlert('danger', res.message || 'Could not enroll.');
            $btn.prop('disabled', false);
          }
        })
        .fail(function(xhr) {
          showAlert('danger', (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Request failed.');
          $btn.prop('disabled', false);
        });
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
