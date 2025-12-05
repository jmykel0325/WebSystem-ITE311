<div class="modal fade" id="teacherStudentsModal" tabindex="-1" aria-labelledby="teacherStudentsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="teacherStudentsModalLabel">Enrolled Students</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="teacherStudentsModalBody">
          <p class="text-muted mb-0">Loading students...</p>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  var modalEl = document.getElementById('teacherStudentsModal');
  if (!modalEl) return;

  var studentsModal = new bootstrap.Modal(modalEl);
  var modalTitleEl = document.getElementById('teacherStudentsModalLabel');
  var modalBodyEl  = document.getElementById('teacherStudentsModalBody');

  document.querySelectorAll('.btn-view-students').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var courseId    = this.getAttribute('data-course-id');
      var courseTitle = this.getAttribute('data-course-title') || 'Course';

      if (modalTitleEl) {
        modalTitleEl.textContent = 'Enrolled Students - ' + courseTitle;
      }
      if (modalBodyEl) {
        modalBodyEl.innerHTML = '<p class="text-muted mb-0">Loading students...</p>';
      }

      fetch('<?= site_url('teacher/courses/students') ?>/' + courseId, {
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
        .then(function (res) { return res.json(); })
        .then(function (data) {
          if (!modalBodyEl) return;

          if (!data || data.ok !== true || !Array.isArray(data.students) || data.students.length === 0) {
            modalBodyEl.innerHTML = '<p class="text-muted mb-0">No approved students enrolled for this course.</p>';
            return;
          }

          var html = '<div class="table-responsive"><table class="table table-sm align-middle mb-0">' +
                     '<thead><tr><th>#</th><th>Name</th><th>Email</th><th>Enrolled</th></tr></thead><tbody>';

          data.students.forEach(function (s, idx) {
            var enrolled = s.enrollment_date ? new Date(s.enrollment_date) : null;
            var enrolledStr = enrolled ? enrolled.toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: '2-digit' }) : 'N/A';

            html += '<tr>' +
                    '<td>' + (idx + 1) + '</td>' +
                    '<td>' + (s.name || '') + '</td>' +
                    '<td>' + (s.email || '') + '</td>' +
                    '<td><small class="text-muted">' + enrolledStr + '</small></td>' +
                    '</tr>';
          });

          html += '</tbody></table></div>';
          modalBodyEl.innerHTML = html;
        })
        .catch(function () {
          if (!modalBodyEl) return;
          modalBodyEl.innerHTML = '<p class="text-danger mb-0">Failed to load students. Please try again.</p>';
        });

      studentsModal.show();
    });
  });
});
</script>
