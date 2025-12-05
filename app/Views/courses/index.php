<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2">
                <div>
                    <h4 class="mb-0">
                        <i class="bi bi-search me-2"></i>
                        Browse Courses
                    </h4>
                    <small class="text-white-50">Search available courses by <strong>course title</strong>.</small>
                </div>
            </div>
            <div class="card-body">
                <form id="courseSearchForm" class="row g-2 align-items-center mb-3" method="get" action="<?= site_url('courses/search') ?>">
                    <div class="col-sm-9 col-md-10">
                        <input
                            type="text"
                            name="q"
                            id="courseSearchInput"
                            class="form-control"
                            placeholder="Type to search courses..."
                            value="<?= esc($searchTerm ?? '') ?>"
                            autocomplete="off"
                        >
                    </div>
                    <div class="col-sm-3 col-md-2 d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search me-1"></i>
                            Search
                        </button>
                    </div>
                </form>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <small id="courseCountText" class="text-muted">
                        <?php if (!empty($courses)): ?>
                            Showing <strong><?= count($courses) ?></strong> course(s)
                            <?php if (!empty($searchTerm)): ?>
                                for "<?= esc($searchTerm) ?>"
                            <?php endif; ?>
                        <?php else: ?>
                            No courses found.
                        <?php endif; ?>
                    </small>
                    <small class="text-muted d-none d-md-inline">Results update instantly as you type.</small>
                </div>

                <?php if (empty($courses)): ?>
                    <div class="text-center text-muted py-5" id="coursesEmptyState">
                        <i class="bi bi-inbox display-5"></i>
                        <h5 class="mt-3 mb-1">No Courses Found</h5>
                        <p class="mb-0">Try a different keyword or clear the search box.</p>
                    </div>
                <?php endif; ?>

                <div class="table-responsive" id="coursesTableWrapper" <?= empty($courses) ? 'style="display:none;"' : '' ?>>
                    <table class="table table-hover align-middle mb-0" id="coursesTable">
                        <thead class="table-light">
                            <tr>
                                <th>Course Number</th>
                                <th>Course Title</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($courses)): ?>
                            <?php foreach ($courses as $course): ?>
                                <tr>
                                    <td class="course-number">
                                        <span class="badge bg-primary">
                                            <?= esc($course['course_number'] ?? 'N/A') ?>
                                        </span>
                                    </td>
                                    <td class="course-title">
                                        <a href="<?= site_url('courses/show/' . (int)($course['id'] ?? 0)) ?>" class="text-decoration-none text-dark fw-semibold">
                                            <?= esc($course['title'] ?? '') ?>
                                        </a>
                                    </td>
                                    <td class="course-description">
                                        <small class="text-muted">
                                            <?= esc(substr((string)($course['description'] ?? ''), 0, 80)) ?>
                                            <?php if (!empty($course['description']) && strlen($course['description']) > 80): ?>
                                                ...
                                            <?php endif; ?>
                                        </small>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    (function () {
        const form = document.getElementById('courseSearchForm');
        const input = document.getElementById('courseSearchInput');
        const tableWrapper = document.getElementById('coursesTableWrapper');
        const table = document.getElementById('coursesTable');
        const tbody = table ? table.querySelector('tbody') : null;
        const emptyState = document.getElementById('coursesEmptyState');
        const countText = document.getElementById('courseCountText');

        if (!input) return;

        // Client-side instant filter (filters current rows by TITLE only, starts-with match)
        input.addEventListener('input', function () {
            const term = this.value.toLowerCase();

            if (!tbody) return;
            const rows = tbody.querySelectorAll('tr');
            let visibleCount = 0;

            rows.forEach(function (row) {
                const title = (row.querySelector('.course-title')?.textContent || '').toLowerCase().trim();

                // Match ONLY against the course title, and only when the title STARTS WITH the term
                const match = !term || title.startsWith(term);
                row.style.display = match ? '' : 'none';
                if (match) visibleCount++;
            });

            if (countText) {
                if (rows.length === 0) {
                    countText.innerHTML = 'No courses found.';
                } else {
                    countText.innerHTML = 'Showing <strong>' + visibleCount + '</strong> course(s)';
                }
            }
        });

        // AJAX submit for server-side search
        if (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const term = input.value.trim();
                const url  = form.getAttribute('action') || '<?= site_url('courses/search') ?>';

                fetch(url + '?q=' + encodeURIComponent(term), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                    .then(function (res) { return res.json(); })
                    .then(function (data) {
                        if (!tbody || !data || data.ok !== true) {
                            return;
                        }

                        tbody.innerHTML = '';

                        if (!Array.isArray(data.courses) || data.courses.length === 0) {
                            if (tableWrapper) tableWrapper.style.display = 'none';
                            if (emptyState) emptyState.style.display = '';
                            if (countText) {
                                if (term) {
                                    countText.innerHTML = 'No courses found for "' + term.replace(/</g, '&lt;').replace(/>/g, '&gt;') + '"';
                                } else {
                                    countText.innerHTML = 'No courses found.';
                                }
                            }
                            return;
                        }

                        if (tableWrapper) tableWrapper.style.display = '';
                        if (emptyState) emptyState.style.display = 'none';

                        data.courses.forEach(function (course) {
                            const tr = document.createElement('tr');
                            const number = (course.course_number || 'N/A');
                            const title  = (course.title || '');
                            const desc   = (course.description || '');

                            const shortDesc = desc.length > 80 ? desc.substring(0, 80) + '...' : desc;

                            tr.innerHTML =
                                '<td class="course-number"><span class="badge bg-primary">' +
                                escapeHtml(String(number)) + '</span></td>' +
                                '<td class="course-title"><strong>' + escapeHtml(String(title)) + '</strong></td>' +
                                '<td class="course-description"><small class="text-muted">' +
                                escapeHtml(String(shortDesc)) + '</small></td>';

                            tbody.appendChild(tr);
                        });

                        if (countText) {
                            const total = data.courses.length;
                            if (term) {
                                countText.innerHTML = 'Showing <strong>' + total + '</strong> course(s) for "' +
                                    term.replace(/</g, '&lt;').replace(/>/g, '&gt;') + '"';
                            } else {
                                countText.innerHTML = 'Showing <strong>' + total + '</strong> course(s)';
                            }
                        }
                    })
                    .catch(function () {
                        // Fail silently for now; could add an alert if desired
                    });
            });
        }

        function escapeHtml(str) {
            return str
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }
    })();
</script>
<?= $this->endSection() ?>
