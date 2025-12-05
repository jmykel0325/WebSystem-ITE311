<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-end mb-3">
  <div>
    <div class="text-uppercase small text-muted fw-semibold">Student</div>
    <h1 class="h4 mb-0">My Enrollments</h1>
  </div>
</div>

<div class="row g-3">
  <div class="col-lg-6">
    <div class="card h-100 border-0">
      <div class="card-header bg-white border-bottom-0 pb-1">
        <ul class="nav nav-pills" role="tablist">
          <li class="nav-item">
            <button class="nav-link active" id="tab-active" data-bs-toggle="tab" data-bs-target="#tab-active-pane" type="button" role="tab">
              Recent Enrollments
            </button>
          </li>
          <li class="nav-item ms-2">
            <button class="nav-link" id="tab-expired" data-bs-toggle="tab" data-bs-target="#tab-expired-pane" type="button" role="tab">
              Expired Courses
            </button>
          </li>
        </ul>
      </div>
      <div class="card-body tab-content pt-2 px-0 px-md-1">
        <div class="tab-pane fade show active" id="tab-active-pane" role="tabpanel" aria-labelledby="tab-active">
        <?php if (!empty($activeEnrolled)): ?>
          <div class="row g-2" id="enrolled-grid">
            <?php foreach ($activeEnrolled as $c): ?>
              <div class="col-12">
                <div class="p-3 border rounded-3 bg-white d-flex justify-content-between align-items-start">
                  <div class="me-3 flex-grow-1">
                    <div class="fw-semibold mb-1"><?= esc($c['title']) ?></div>
                    <small class="text-muted d-block small"><?= esc($c['description'] ?? '') ?></small>
                  </div>
                  <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-2 text-end text-sm-start">
                    <span class="badge rounded-pill text-bg-light border">
                      <i class="bi bi-calendar-check me-1"></i>
                      <?php if (!empty($c['enrollment_date'])): ?>
                        <?php $enrolledAt = strtotime($c['enrollment_date']); ?>
                        Enrolled: <?= esc(date('M d, Y', $enrolledAt)) ?>
                        <span class="ms-2 text-muted">
                          · Expires: <?= esc(date('M d, Y', strtotime('+4 months', $enrolledAt))) ?>
                        </span>
                      <?php endif; ?>
                    </span>

                    <?php if (!empty($c['teacher_name'])): ?>
                      <button type="button" class="btn btn-outline-secondary btn-sm ms-sm-2" 
                              title="<?= esc($c['teacher_email'] ?? '') ?>">
                        <i class="bi bi-person-badge me-1"></i>
                        <?= esc($c['teacher_name']) ?>
                      </button>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="text-center py-4 border rounded-3 bg-light-subtle">
            <i class="bi bi-emoji-neutral mb-2" style="font-size: 2rem;"></i>
            <div class="fw-semibold">No active enrollments</div>
            <div class="text-muted small">Choose a course from "Available Courses".</div>
          </div>
        <?php endif; ?>
        </div>

        <div class="tab-pane fade" id="tab-expired-pane" role="tabpanel" aria-labelledby="tab-expired">
        <?php if (!empty($expiredEnrolled)): ?>
          <div class="row g-2">
            <?php foreach ($expiredEnrolled as $c): ?>
              <div class="col-12">
                <div class="p-3 border rounded-3 bg-light-subtle d-flex justify-content-between align-items-start">
                  <div class="me-3 flex-grow-1">
                    <div class="fw-semibold mb-1"><?= esc($c['title']) ?></div>
                    <small class="text-muted d-block small"><?= esc($c['description'] ?? '') ?></small>
                  </div>
                  <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-2 text-end text-sm-start">
                    <span class="badge rounded-pill text-bg-light border">
                      <i class="bi bi-hourglass-bottom me-1"></i>
                      <?php if (!empty($c['enrollment_date'])): ?>
                        <?php $enrolledAt = strtotime($c['enrollment_date']); ?>
                        <?php $expiredAt  = strtotime('+4 months', $enrolledAt); ?>
                        Enrolled: <?= esc(date('M d, Y', $enrolledAt)) ?>
                        <span class="ms-2 text-muted">
                          · Expired: <?= esc(date('M d, Y', $expiredAt)) ?>
                        </span>
                      <?php endif; ?>
                    </span>

                    <?php if (!empty($c['teacher_name'])): ?>
                      <button type="button" class="btn btn-outline-secondary btn-sm ms-sm-2" 
                              title="<?= esc($c['teacher_email'] ?? '') ?>">
                        <i class="bi bi-person-badge me-1"></i>
                        <?= esc($c['teacher_name']) ?>
                      </button>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="text-center py-4 border rounded-3 bg-light-subtle">
            <i class="bi bi-archive mb-2" style="font-size: 2rem;"></i>
            <div class="fw-semibold">No expired courses</div>
            <div class="text-muted small">Courses you finish or that expire will appear here.</div>
          </div>
        <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-6">
    <div class="card h-100 border-0">
      <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom-0 pb-1">
        <strong>Available Courses</strong>
        <span id="available-count" class="badge rounded-pill text-bg-light">
          <?= count($available ?? []) ?> courses
        </span>
      </div>
      <div class="card-body pt-2 px-0 px-md-1">
        <?php if (!empty($available)): ?>
          <div class="row g-2" id="available-grid">
            <?php foreach ($available as $c): ?>
              <div class="col-12">
                <div class="p-3 border rounded-3 bg-white d-flex justify-content-between align-items-start" data-course-id="<?= (int)$c['id'] ?>">
                  <div class="me-3 flex-grow-1">
                    <div class="fw-semibold mb-1">
                      <?= esc($c['title']) ?> 
                      <?php if (!empty($c['course_number'])): ?>
                        <span class="badge bg-secondary ms-1"><?= esc($c['course_number']) ?></span>
                      <?php endif; ?>
                    </div>
                    <small class="text-muted d-block small"><?= esc($c['description'] ?? '') ?></small>
                  </div>
                  <button type="button" class="btn btn-primary btn-sm btn-enroll" data-course-id="<?= (int)$c['id'] ?>">
                    <i class="bi bi-plus-circle"></i> Enroll
                  </button>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="text-center py-4 border rounded-3 bg-light-subtle">
            <i class="bi bi-inboxes mb-2" style="font-size: 2rem;"></i>
            <div class="fw-semibold">No available courses</div>
            <div class="text-muted small">Please check back later.</div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<script>
  // Check if jQuery is loaded, if not, wait for it
  function waitForJQuery(callback) {
    if (typeof $ !== 'undefined') {
      callback();
    } else {
      setTimeout(function() {
        waitForJQuery(callback);
      }, 100);
    }
  }

  waitForJQuery(function() {
    console.log('jQuery is ready!'); // Debug log
    
    const CSRF_TOKEN_NAME = '<?= csrf_token() ?>';
    let   CSRF_HASH       = '<?= csrf_hash() ?>';
    const ENROLL_URL      = '<?= site_url('course/enroll') ?>';
    const UNENROLL_URL    = '<?= site_url('course/unenroll') ?>';

    $(document).on('click', '.btn-enroll', function () {
    console.log('Enroll button clicked!'); // Debug log
    const $btn = $(this);
    const courseId = $btn.data('course-id');
    console.log('Course ID:', courseId); // Debug log
    
    if (!courseId) {
      console.error('No course ID found!');
      showToast('Error: No course ID found.', 'danger');
      return;
    }
    
    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Enrolling…');

    $.post(ENROLL_URL, { [CSRF_TOKEN_NAME]: CSRF_HASH, course_id: courseId })
      .done(function (res) {
        console.log('AJAX Response:', res); // Debug log
        if ((res && res.ok === true) || (res && res.status === 'ok')) {
          showToast(res.message || 'Enrolled successfully.', 'success');

          const $availableCol = $btn.closest('.col-12');

          // Remove empty-state in enrolled panel if present
          const $enrolledBody = $('.col-lg-6').first().find('.card-body');
          $enrolledBody.find('.empty-state').remove();

          // Ensure enrolled grid exists
          if (!$('#enrolled-grid').length) {
            $('<div id="enrolled-grid" class="row g-3"></div>').appendTo($enrolledBody);
          }

          // Create a fresh enrolled card using response data for reliability
          const title = (res.course && res.course.title) ? res.course.title : $availableCol.find('.fw-semibold').text();
          const desc  = (res.course && (res.course.summary || res.course.description)) ? (res.course.summary || res.course.description) : $availableCol.find('small.text-muted').text();
          const enrolledHtml = '<div class="col-12">\n'
            + '  <div class="p-3 border rounded-3 d-flex justify-content-between align-items-center">\n'
            + '    <div>\n'
            + '      <div class="fw-semibold">' + $('<div>').text(title).html() + '</div>\n'
            + '      <small class="text-muted">' + $('<div>').text(desc || '').html() + '</small>\n'
            + '    </div>\n'
            + '    <span class="badge badge-soft"><i class="bi bi-calendar-check"></i> ' + new Date().toLocaleDateString(undefined, { month: 'short', day: '2-digit', year: 'numeric' }) + '</span>\n'
            + '  </div>\n'
            + '</div>';

          $('#enrolled-grid').prepend(enrolledHtml);

          // Remove the original available card
          $availableCol.remove();

          // Update available count and empty state if needed
          const newAvailable = $('#available-grid [data-course-id]').length;
          $('#available-count').text(newAvailable + ' courses');
          if (newAvailable === 0) {
            $('#available-grid').remove();
            const emptyHtml = '<div class="empty-state">'
              + '<i class="bi bi-inboxes mb-2" style="font-size: 2rem;"></i>'
              + '<div class="fw-semibold">No available courses</div>'
              + '<div class="text-muted">Please check back later.</div>'
              + '</div>';
            $('.col-lg-6').last().find('.card-body').html(emptyHtml);
          }

          if (res.csrf && res.csrf.hash) CSRF_HASH = res.csrf.hash;

        } else {
          showToast((res && res.message) ? res.message : 'Unable to enroll.', 'danger');
          $btn.prop('disabled', false).html('<i class="bi bi-plus-circle"></i> Enroll');
        }
      })
      .fail(function (xhr) {
        console.error('AJAX Failed:', xhr); // Debug log
        showToast('Request failed. Please try again.', 'danger');
        $btn.prop('disabled', false).html('<i class="bi bi-plus-circle"></i> Enroll');
      });
    }); // End of enroll click handler

    // Unenroll is now managed by teachers from their Enrollments page.
  }); // End of waitForJQuery callback
</script>

<?= $this->endSection() ?>
