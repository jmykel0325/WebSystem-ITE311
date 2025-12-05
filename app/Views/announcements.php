<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <?= esc(session()->getFlashdata('error')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                <?= esc(session()->getFlashdata('success')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="bi bi-megaphone me-2"></i>
                Announcements
            </h1>
        </div>

        <?php if (!empty($announcements)): ?>
            <div class="row">
                <?php foreach ($announcements as $announcement): ?>
                    <div class="col-12 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="#"
                                       class="text-decoration-none fw-semibold"
                                       style="color: var(--brand-primary);"
                                       data-bs-toggle="modal"
                                       data-bs-target="#announcementModal-<?= (int)$announcement['id'] ?>">
                                        <?= esc($announcement['title']) ?>
                                    </a>
                                </h5>
                                <div class="card-text">
                                    <?= nl2br(esc($announcement['content'])) ?>
                                </div>
                                <div class="mt-3">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar-event me-1"></i>
                                        Posted on <?= date('F j, Y \a\t g:i A', strtotime($announcement['created_at'])) ?>
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Modal -->
                        <div class="modal fade" id="announcementModal-<?= (int)$announcement['id'] ?>" tabindex="-1" aria-labelledby="announcementModalLabel-<?= (int)$announcement['id'] ?>" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="announcementModalLabel-<?= (int)$announcement['id'] ?>">
                                            <?= esc($announcement['title']) ?>
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3 text-muted small">
                                            <i class="bi bi-calendar-event me-1"></i>
                                            Posted on <?= date('F j, Y \a\t g:i A', strtotime($announcement['created_at'])) ?>
                                        </div>
                                        <div>
                                            <?= nl2br(esc($announcement['content'])) ?>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <div class="text-muted">
                    <i class="bi bi-megaphone display-1 text-muted"></i>
                    <h4 class="mt-3 text-muted">No announcements yet.</h4>
                    <p class="text-muted">Check back later for important updates and news.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
