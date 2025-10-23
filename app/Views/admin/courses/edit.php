<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0">
                    <i class="bi bi-pencil-square me-2"></i>
                    Edit Course
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

                <!-- Edit Form -->
                <?= form_open('admin/courses/update/' . $course['id'], ['class' => 'needs-validation', 'novalidate' => '']) ?>
                    
                    <div class="mb-3">
                        <label for="course_number" class="form-label fw-bold">
                            <i class="bi bi-hash me-2"></i>
                            Course Number <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="course_number" 
                               name="course_number" 
                               value="<?= old('course_number', $course['course_number'] ?? '') ?>"
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
                               value="<?= old('title', $course['title']) ?>"
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
                                  placeholder="Describe what students will learn in this course..."><?= old('description', $course['description'] ?? '') ?></textarea>
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
                                <option value="<?= $teacher['id'] ?>" 
                                        <?= old('teacher_id', $course['teacher_id']) == $teacher['id'] ? 'selected' : '' ?>>
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
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-check-circle me-2"></i>
                            Update Course
                        </button>
                    </div>

                <?= form_close() ?>
            </div>
        </div>

        <!-- Quick Actions Card -->
        <div class="card mt-3">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="bi bi-lightning me-2"></i>
                    Quick Actions
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?= site_url('admin/course/' . $course['id'] . '/upload') ?>" 
                       class="btn btn-info">
                        <i class="bi bi-folder-plus me-2"></i>
                        Upload Materials
                    </a>
                    <a href="<?= site_url('materials/list/' . $course['id']) ?>" 
                       class="btn btn-secondary">
                        <i class="bi bi-eye me-2"></i>
                        View All Materials
                    </a>
                </div>
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
