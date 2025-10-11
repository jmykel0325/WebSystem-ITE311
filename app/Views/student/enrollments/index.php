<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-end mb-3">
  <div>
    <div class="kicker">Student</div>
    <h1 class="page-title mb-0">My Enrollments</h1>
  </div>
</div>

<div class="row g-4">
  <div class="col-lg-6">
    <div class="card h-100">
      <div class="card-header bg-white">
        <strong>Enrolled Courses</strong>
      </div>
      <div class="card-body">
        <?php if (!empty($enrolled)): ?>
          <div class="row g-3" id="enrolled-grid">
            <?php foreach ($enrolled as $c): ?>
              <div class="col-12">
                <div class="p-3 border rounded-3 d-flex justify-content-between align-items-center">
                  <div>
                    <div class="fw-semibold"><?= esc($c['title']) ?></div>
                    <small class="text-muted"><?= esc($c['description'] ?? '') ?></small>
                  </div>
                  <div class="d-flex align-items-center gap-2">
                    <span class="badge badge-soft">
                      <i class="bi bi-calendar-check"></i>
                      <?= esc(isset($c['enrollment_date']) ? date('M d, Y', strtotime($c['enrollment_date'])) : '') ?>
                    </span>
                    <button type="button" class="btn btn-outline-danger btn-sm btn-unenroll" data-course-id="<?= (int)$c['course_id'] ?>">
                      <i class="bi bi-x-circle"></i> Unenroll
                    </button>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="empty-state">
            <i class="bi bi-emoji-neutral mb-2" style="font-size: 2rem;"></i>
            <div class="fw-semibold">No enrollments yet</div>
            <div class="text-muted">Choose a course from "Available Courses".</div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="col-lg-6">
    <div class="card h-100">
      <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <strong>Available Courses</strong>
        <span id="available-count" class="badge text-bg-light"><?= count($available ?? []) ?> courses</span>
      </div>
      <div class="card-body">
        <?php if (!empty($available)): ?>
          <div class="row g-3" id="available-grid">
            <?php foreach ($available as $c): ?>
              <div class="col-12">
                <div class="p-3 border rounded-3 d-flex justify-content-between align-items-center" data-course-id="<?= (int)$c['id'] ?>">
                  <div>
                    <div class="fw-semibold"><?= esc($c['title']) ?></div>
                    <small class="text-muted"><?= esc($c['description'] ?? '') ?></small>
                  </div>
                  <button type="button" class="btn btn-primary btn-sm btn-enroll" data-course-id="<?= (int)$c['id'] ?>">
                    <i class="bi bi-plus-circle"></i> Enroll
                  </button>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="empty-state">
            <i class="bi bi-inboxes mb-2" style="font-size: 2rem;"></i>
            <div class="fw-semibold">No available courses</div>
            <div class="text-muted">Please check back later.</div>
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

    // Unenroll button click handler
    $(document).on('click', '.btn-unenroll', function () {
      console.log('Unenroll button clicked!'); // Debug log
      const $btn = $(this);
      const courseId = $btn.data('course-id');
      console.log('Course ID:', courseId); // Debug log
      
      if (!courseId) {
        console.error('No course ID found!');
        showToast('Error: No course ID found.', 'danger');
        return;
      }
      
      // Confirm unenrollment
      if (!confirm('Are you sure you want to unenroll from this course?')) {
        return;
      }
      
      $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Unenrolling…');

      $.post(UNENROLL_URL, { [CSRF_TOKEN_NAME]: CSRF_HASH, course_id: courseId })
        .done(function (res) {
          console.log('Unenroll AJAX Response:', res); // Debug log
          if ((res && res.ok === true) || (res && res.status === 'ok')) {
            showToast(res.message || 'Successfully unenrolled.', 'success');

            const $enrolledCol = $btn.closest('.col-12');

            // Create available card if not present
            if (!$('#available-grid').length) {
              const $availableBody = $('.col-lg-6').last().find('.card-body');
              $availableBody.find('.empty-state').remove();
              $('<div id="available-grid" class="row g-3"></div>').appendTo($availableBody);
            }

            // Create a fresh available card using response data
            const title = (res.course && res.course.title) ? res.course.title : $enrolledCol.find('.fw-semibold').text();
            const desc  = (res.course && (res.course.summary || res.course.description)) ? (res.course.summary || res.course.description) : $enrolledCol.find('small.text-muted').text();
            const availableHtml = '<div class="col-12">\n'
              + '  <div class="p-3 border rounded-3 d-flex justify-content-between align-items-center" data-course-id="' + courseId + '">\n'
              + '    <div>\n'
              + '      <div class="fw-semibold">' + $('<div>').text(title).html() + '</div>\n'
              + '      <small class="text-muted">' + $('<div>').text(desc || '').html() + '</small>\n'
              + '    </div>\n'
              + '    <button type="button" class="btn btn-primary btn-sm btn-enroll" data-course-id="' + courseId + '">\n'
              + '      <i class="bi bi-plus-circle"></i> Enroll\n'
              + '    </button>\n'
              + '  </div>\n'
              + '</div>';

            $('#available-grid').prepend(availableHtml);

            // Remove the original enrolled card
            $enrolledCol.remove();

            // Update available count
            const newAvailable = $('#available-grid [data-course-id]').length;
            $('#available-count').text(newAvailable + ' courses');

            // Show enrolled empty state if no courses left
            if ($('#enrolled-grid [data-course-id]').length === 0) {
              $('#enrolled-grid').remove();
              const emptyHtml = '<div class="empty-state">'
                + '<i class="bi bi-emoji-neutral mb-2" style="font-size: 2rem;"></i>'
                + '<div class="fw-semibold">No enrollments yet</div>'
                + '<div class="text-muted">Choose a course from "Available Courses".</div>'
                + '</div>';
              $('.col-lg-6').first().find('.card-body').html(emptyHtml);
            }

            if (res.csrf && res.csrf.hash) CSRF_HASH = res.csrf.hash;

          } else {
            showToast((res && res.message) ? res.message : 'Unable to unenroll.', 'danger');
            $btn.prop('disabled', false).html('<i class="bi bi-x-circle"></i> Unenroll');
          }
        })
        .fail(function (xhr) {
          console.error('Unenroll AJAX Failed:', xhr); // Debug log
          showToast('Request failed. Please try again.', 'danger');
          $btn.prop('disabled', false).html('<i class="bi bi-x-circle"></i> Unenroll');
        });
    }); // End of unenroll click handler
  }); // End of waitForJQuery callback
</script>

<?= $this->endSection() ?>
