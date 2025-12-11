<?php if (!empty($courses)): ?>
    <?php foreach ($courses as $course): ?>
        <tr>
            <td>
                <strong class="badge bg-primary">
                    <?= !empty($course['course_number']) ? esc($course['course_number']) : 'N/A' ?>
                </strong>
            </td>
            <td>
                <?php if (!empty($course['semester'])): ?>
                    <span class="badge bg-secondary d-block mb-1">
                        <?= $course['semester'] === 'first' ? 'First Semester' : 'Second Semester' ?>
                    </span>
                <?php else: ?>
                    <span class="text-muted d-block mb-1">N/A</span>
                <?php endif; ?>

                <?php if (!empty($course['start_date']) || !empty($course['end_date'])): ?>
                    <small class="text-muted">
                        <?php if (!empty($course['start_date'])): ?>
                            <?= date('M d, Y', strtotime($course['start_date'])) ?>
                        <?php else: ?>
                            ?
                        <?php endif; ?>
                        &nbsp;–&nbsp;
                        <?php if (!empty($course['end_date'])): ?>
                            <?= date('M d, Y', strtotime($course['end_date'])) ?>
                        <?php else: ?>
                            ?
                        <?php endif; ?>
                    </small>
                <?php else: ?>
                    <small class="text-muted">N/A</small>
                <?php endif; ?>
            </td>
            <td>
                <?php if (!empty($course['days_pattern'])): ?>
                    <span class="badge bg-light text-dark border d-block mb-1">
                        <?= esc($course['days_pattern']) ?>
                    </span>
                <?php else: ?>
                    <span class="text-muted d-block mb-1">N/A</span>
                <?php endif; ?>

                <?php if (!empty($course['start_time']) || !empty($course['end_time'])): ?>
                    <small class="text-muted">
                        <?= !empty($course['start_time']) ? date('h:i A', strtotime($course['start_time'])) : '?' ?>
                        &ndash;
                        <?= !empty($course['end_time']) ? date('h:i A', strtotime($course['end_time'])) : '?' ?>
                    </small>
                <?php endif; ?>
            </td>
            <td>
                <strong><?= esc($course['title']) ?></strong>
                <?php if (!empty($course['description'])): ?>
                    <br>
                    <small class="text-muted">
                        <?= esc(substr($course['description'], 0, 60)) ?>
                        <?= strlen($course['description']) > 60 ? '...' : '' ?>
                    </small>
                <?php endif; ?>
            </td>
            <td>
                <?php if (!empty($course['teacher_name'])): ?>
                    <i class="bi bi-person-circle me-1"></i>
                    <?= esc($course['teacher_name']) ?>
                    <br>
                    <small class="text-muted"><?= esc($course['teacher_email']) ?></small>
                <?php else: ?>
                    <span class="text-muted">No teacher assigned</span>
                <?php endif; ?>
            </td>
            <td class="text-center">
                <span class="badge bg-info">
                    <?= (int)($course['material_count'] ?? 0) ?>
                </span>
            </td>
            <td class="text-center">
                <button type="button"
                        class="btn btn-outline-success btn-sm btn-view-students"
                        data-course-id="<?= (int)$course['id'] ?>"
                        data-course-title="<?= esc($course['title']) ?>">
                    <?= (int)($course['enrollment_count'] ?? 0) ?>
                </button>
            </td>
            <td>
                <small class="text-muted">
                    <?= !empty($course['created_at']) ? date('M d, Y', strtotime($course['created_at'])) : 'N/A' ?>
                </small>
            </td>
            <td>
                <div class="btn-group" role="group">
                    <a href="<?= site_url('admin/course/' . $course['id'] . '/upload') ?>"
                       class="btn btn-sm btn-info"
                       title="Manage Materials">
                        <i class="bi bi-folder-plus"></i>
                    </a>
                    <a href="<?= site_url('materials/list/' . $course['id']) ?>"
                       class="btn btn-sm btn-secondary"
                       title="View Materials">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="<?= site_url('admin/courses/edit/' . $course['id']) ?>"
                       class="btn btn-sm btn-primary"
                       title="Edit Course">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <a href="<?= site_url('admin/courses/delete/' . $course['id']) ?>"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Are you sure you want to delete this course? This will also delete all materials and enrollments!')"
                       title="Delete Course">
                        <i class="bi bi-trash"></i>
                    </a>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="9" class="text-center text-muted">No courses found.</td>
    </tr>
<?php endif; ?>
