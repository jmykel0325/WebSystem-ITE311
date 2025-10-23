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
                        <label for="course_code" class="form-label fw-bold">
                            <i class="bi bi-hash me-2"></i>
                            Course Code <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="course_code" 
                               name="course_code" 
                               value="<?= old('course_code') ?>"
                               required
                               placeholder="e.g., CS101, IT201, MATH301"
                               maxlength="50">
                        <div class="form-text">
                            Enter a unique course code (e.g., CS101, IT201) - This helps students identify the course
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
</script>

<?= $this->endSection() ?>
