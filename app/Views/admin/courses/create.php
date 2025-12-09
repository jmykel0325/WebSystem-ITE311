<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="bi bi-plus-circle me-2"></i>
                    Create New Course
                </h4>
            </div>
            <div class="card-body">
                <!-- Error Messages -->
                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <ul class="mb-0">
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Create Form -->
                <?= form_open('admin/courses/store', ['class' => 'needs-validation', 'novalidate' => '']) ?>
                    
                    <div class="mb-3">
                        <label for="course_number" class="form-label fw-bold">
                            <i class="bi bi-hash me-2"></i>
                            Course Number <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="course_number" 
                               name="course_number" 
                               value="<?= old('course_number') ?>"
                               required
                               placeholder="e.g., CS101, IT201, MATH301"
                               maxlength="50">
                        <div class="form-text">
                            Enter a unique course number (e.g., CS101, IT201) - This helps students identify the course
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label fw-bold">
                            <i class="bi bi-book me-2"></i>
                            Course Title <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="title" 
                               name="title" 
                               value="<?= old('title') ?>"
                               required
                               placeholder="e.g., Introduction to Web Development">
                        <div class="form-text">
                            Enter a descriptive title for the course
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold">
                            <i class="bi bi-text-paragraph me-2"></i>
                            Course Description
                        </label>
                        <textarea class="form-control" 
                                  id="description" 
                                  name="description" 
                                  rows="4"
                                  placeholder="Describe what students will learn in this course..."><?= old('description') ?></textarea>
                        <div class="form-text">
                            Optional: Provide a brief description of the course content
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="semester" class="form-label fw-bold">
                            <i class="bi bi-calendar-event me-2"></i>
                            Semester <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="semester" name="semester" required>
                            <option value="">-- Select Semester --</option>
                            <option value="first" <?= old('semester') === 'first' ? 'selected' : '' ?>>First Semester</option>
                            <option value="second" <?= old('semester') === 'second' ? 'selected' : '' ?>>Second Semester</option>
                        </select>
                        <div class="form-text">
                            Choose whether this course is for the first or second semester
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="school_year" class="form-label fw-bold">
                            School Year
                        </label>
                        <input type="text"
                               class="form-control"
                               id="school_year"
                               name="school_year"
                               value="<?= old('school_year') ?>"
                               placeholder="Automatically set from start date (e.g., 2025-2026)"
                               readonly>
                        <div class="form-text">
                            Pick a <strong>Start Date</strong>; the school year will be filled in automatically.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="start_date" class="form-label fw-bold">
                            <i class="bi bi-calendar-date me-2"></i>
                            Start Date
                        </label>
                        <input type="date"
                               class="form-control"
                               id="start_date"
                               name="start_date"
                               value="<?= old('start_date') ?>">
                        <div class="form-text">
                            Optional: When the course starts.
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="end_date" class="form-label fw-bold">
                            <i class="bi bi-calendar-check me-2"></i>
                            End Date
                        </label>
                        <input type="date"
                               class="form-control"
                               id="end_date"
                               name="end_date"
                               value="<?= old('end_date') ?>">
                        <div class="form-text">
                            Optional: When the course ends. Set the duration of the course here if necessary.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-clock-history me-2"></i>
                            Class Time (Teacher Schedule)
                        </label>
                        <div class="row g-2">
                            <div class="col-6">
                                <label for="start_time" class="form-label small mb-1">Start Time</label>
                                <input type="time"
                                       class="form-control"
                                       id="start_time"
                                       name="start_time"
                                       value="<?= old('start_time') ?>">
                            </div>
                            <div class="col-6">
                                <label for="end_time" class="form-label small mb-1">End Time</label>
                                <input type="time"
                                       class="form-control"
                                       id="end_time"
                                       name="end_time"
                                       value="<?= old('end_time') ?>">
                            </div>
                        </div>
                        <div class="form-text">
                            Optional: Set the daily time range for this class (e.g., 09:00 to 10:30).
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="bi bi-calendar-week me-2"></i>
                            Class Days
                        </label>

                        <?php
                            $oldDaysPattern = old('days_pattern') ?? '';
                            $preselectedDays = array_filter(array_map('trim', explode(',', $oldDaysPattern)));
                            $dayOptions = [
                                'Mon' => 'M',
                                'Tue' => 'T',
                                'Wed' => 'W',
                                'Thu' => 'Th',
                                'Fri' => 'F',
                                'Sat' => 'Sa',
                                'Sun' => 'Su',
                            ];
                        ?>

                        <div class="row g-2">
                            <?php foreach ($dayOptions as $dayLabel => $dayCode): ?>
                                <div class="col-4 col-sm-3">
                                    <div class="form-check">
                                        <input class="form-check-input class-day-checkbox"
                                               type="checkbox"
                                               value="<?= esc($dayCode) ?>"
                                               id="day_<?= esc(strtolower($dayLabel)) ?>"
                                               <?= in_array($dayCode, $preselectedDays, true) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="day_<?= esc(strtolower($dayLabel)) ?>">
                                            <?= esc($dayLabel) ?>
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <input type="hidden"
                               id="days_pattern"
                               name="days_pattern"
                               value="<?= esc($oldDaysPattern) ?>">

                        <div class="form-text">
                            Choose the days when the class meets. We will store them as a pattern (e.g., <strong>M,T,W</strong>).
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="teacher_id" class="form-label fw-bold">
                            <i class="bi bi-person-badge me-2"></i>
                            Assign Teacher <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="teacher_id" name="teacher_id" required>
                            <option value="">-- Select a Teacher --</option>
                            <?php foreach ($teachers as $teacher): ?>
                                <option value="<?= $teacher['id'] ?>" <?= old('teacher_id') == $teacher['id'] ? 'selected' : '' ?>>
                                    <?= esc($teacher['name']) ?> (<?= esc($teacher['email']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text">
                            Select the teacher who will manage this course
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="<?= site_url('admin/courses') ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>
                            Create Course
                        </button>
                    </div>

                <?= form_close() ?>
            </div>
        </div>

        <!-- Info Card -->
        <div class="card mt-3 border-info">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="bi bi-info-circle text-info me-2"></i>
                    What happens next?
                </h6>
                <ul class="mb-0">
                    <li>The course will be created and assigned to the selected teacher</li>
                    <li>You can upload learning materials from the course management page</li>
                    <li>Students can enroll in the course to access materials</li>
                    <li>The assigned teacher can manage course content and materials</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
// Bootstrap form validation
(function() {
    'use strict';
    var forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();

// Auto-generate school year based on start date
document.addEventListener('DOMContentLoaded', function () {
    var startDateInput = document.getElementById('start_date');
    var schoolYearInput = document.getElementById('school_year');

    function updateSchoolYear() {
        if (!startDateInput || !schoolYearInput || !startDateInput.value) {
            return;
        }

        var date = new Date(startDateInput.value);
        if (isNaN(date.getTime())) {
            return;
        }

        var yearStart = date.getFullYear();
        // Typical PH school year: starts mid-year and ends next calendar year
        var yearEnd = yearStart + 1;
        schoolYearInput.value = yearStart + '-' + yearEnd;
    }

    if (startDateInput) {
        startDateInput.addEventListener('change', updateSchoolYear);
        // Initialize on page load if value already present
        updateSchoolYear();
    }

    // Class days: sync checkboxes to hidden pattern field
    var dayCheckboxes = document.querySelectorAll('.class-day-checkbox');
    var patternInput = document.getElementById('days_pattern');

    function updateDaysPattern() {
        if (!patternInput) return;
        var selected = [];
        dayCheckboxes.forEach(function(cb) {
            if (cb.checked) {
                selected.push(cb.value);
            }
        });
        patternInput.value = selected.join(',');
    }

    dayCheckboxes.forEach(function(cb) {
        cb.addEventListener('change', updateDaysPattern);
    });

    // Initialize from any preselected values
    updateDaysPattern();
});
</script>

<?= $this->endSection() ?>
