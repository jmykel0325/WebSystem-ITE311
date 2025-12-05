<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title><?= esc($title ?? 'ITE311 LMS') ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Bootstrap 5.3 + Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
  <!-- Theme -->
  <link href="<?= base_url('assets/css/theme.css') ?>" rel="stylesheet">
  <style>
    /* Custom scrollbar for notification dropdown */
    #notificationList::-webkit-scrollbar {
      width: 10px;
    }
    #notificationList::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 10px;
      margin: 4px 0;
    }
    #notificationList::-webkit-scrollbar-thumb {
      background: #888;
      border-radius: 10px;
      border: 2px solid #f1f1f1;
    }
    #notificationList::-webkit-scrollbar-thumb:hover {
      background: #555;
    }
    /* Notification dropdown styling */
    .dropdown-menu {
      overflow: hidden !important;
    }
    /* Notification item styling */
    .notification-item {
      white-space: normal !important;
      word-wrap: break-word;
      padding: 0.5rem 1rem;
      border-bottom: 1px solid #f0f0f0;
      transition: background-color 0.2s ease;
    }
    .notification-item:last-child {
      border-bottom: none;
    }
    .notification-item:hover {
      background-color: #f8f9fa;
    }
    .notification-item[data-link] {
      cursor: pointer;
      position: relative;
    }
    .notification-item[data-link]:hover {
      background-color: #e9ecef;
    }
    .notification-item[data-link]:hover::before {
      content: "Click to view materials →";
      position: absolute;
      bottom: 2px;
      right: 50px;
      font-size: 0.7rem;
      color: #0d6efd;
      font-weight: 500;
    }
    .notification-item p {
      word-break: break-word;
      overflow-wrap: break-word;
      hyphens: auto;
    }
  </style>
  <?= csrf_meta() ?>
</head>
<body>

  <!-- Modern Navbar -->
  <nav class="navbar navbar-expand-lg navbar-modern sticky-top">
    <div class="container">
      <a class="navbar-brand fw-bold" href="<?= site_url('/') ?>">
        <i class="bi bi-mortarboard"></i> ITE311
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="topNav">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link <?= url_is('/') ? 'active' : '' ?>" href="<?= site_url('/') ?>">Home</a></li>
          <li class="nav-item"><a class="nav-link <?= url_is('about') ? 'active' : '' ?>" href="<?= site_url('about') ?>">About</a></li>
          <li class="nav-item"><a class="nav-link <?= url_is('contact') ? 'active' : '' ?>" href="<?= site_url('contact') ?>">Contact</a></li>

          <?php if (session('isLoggedIn')): ?>
            <!-- Dashboard Links -->
            <?php if (session('role') === 'student'): ?>
              <li class="nav-item"><a class="nav-link <?= url_is('student/dashboard') ? 'active' : '' ?>" href="<?= site_url('student/dashboard') ?>"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a></li>
            <?php endif; ?>

            <?php if (session('role') === 'teacher'): ?>
              <li class="nav-item"><a class="nav-link <?= url_is('teacher/dashboard') ? 'active' : '' ?>" href="<?= site_url('teacher/dashboard') ?>"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a></li>
            <?php endif; ?>

            <?php if (session('role') === 'admin'): ?>
              <li class="nav-item"><a class="nav-link <?= url_is('admin/*') ? 'active' : '' ?>" href="<?= site_url('admin') ?>"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a></li>
            <?php endif; ?>

            <li class="nav-item"><a class="nav-link <?= url_is('announcements') ? 'active' : '' ?>" href="<?= site_url('announcements') ?>">Announcements</a></li>

            <?php if (session('role') === 'student'): ?>
              <li class="nav-item"><a class="nav-link <?= url_is('student/enrollments') ? 'active' : '' ?>" href="<?= site_url('student/enrollments') ?>">My Enrollments</a></li>
              <li class="nav-item"><a class="nav-link <?= url_is('student/materials') ? 'active' : '' ?>" href="<?= site_url('student/materials') ?>">My Materials</a></li>
            <?php endif; ?>

            <?php if (session('role') === 'teacher'): ?>
              <li class="nav-item"><a class="nav-link <?= url_is('teacher/courses*') ? 'active' : '' ?>" href="<?= site_url('teacher/courses') ?>">My Courses</a></li>
            <?php endif; ?>
          <?php endif; ?>
        </ul>

        <ul class="navbar-nav align-items-center">
          <?php if (session('isLoggedIn')): ?>
            <!-- Notification Dropdown -->
            <li class="nav-item dropdown me-2">
              <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="position-relative d-inline-block">
                  <i class="bi bi-bell fs-5"></i>
                  <span class="badge bg-danger position-absolute top-0 start-100 translate-middle" id="notificationBadge" style="display: none;">0</span>
                </span>
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown" style="min-width: 320px; max-width: 400px; max-height: 80vh;">
                <li><h6 class="dropdown-header">Notifications</h6></li>
                <li><hr class="dropdown-divider"></li>
                <li style="max-height: calc(80vh - 60px); overflow: hidden;">
                  <div class="notification-scroll-container" style="max-height: calc(80vh - 60px); overflow-y: auto; overflow-x: hidden;" id="notificationList">
                    <div class="text-center p-3 text-muted" id="noNotifications">No new notifications</div>
                  </div>
                </li>
              </ul>
            </li>
            
            <li class="nav-item">
              <span class="nav-link">Hi, <strong><?= esc(session('name')) ?></strong></span>
            </li>
                <li class="nav-item">
                  <a class="logout-btn" href="<?= site_url('logout') ?>" title="Logout">
                    <i class="bi bi-power"></i>
                    <span>Logout</span>
                  </a>
                </li>
          <?php else: ?>
            <li class="nav-item">
              <a class="btn btn-primary btn-sm" href="<?= site_url('login') ?>">
                <i class="bi bi-box-arrow-in-right"></i> Login
              </a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Page header slot (optional) -->
  <?php if (isset($header)): ?>
    <header class="py-4 bg-white border-bottom">
      <div class="container">
        <?= $header ?>
      </div>
    </header>
  <?php endif; ?>

  <!-- Main content -->
  <main class="py-4">
    <div class="container">
      <?= $this->renderSection('content') ?>
    </div>
  </main>

  <!-- Toast container -->
  <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1080">
    <div id="appToast" class="toast toast-modern align-items-center text-bg-primary border-0" role="alert"
         aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body" id="appToastBody">Action completed.</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script>
    // Fallback jQuery if CDN fails
    if (typeof jQuery === 'undefined') {
      document.write('<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"><\/script>');
    }
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function showToast(message, type = 'primary') {
      const toast = document.getElementById('appToast');
      toast.className = 'toast toast-modern align-items-center text-bg-'+type+' border-0';
      document.getElementById('appToastBody').innerText = message;
      new bootstrap.Toast(toast, { delay: 2500 }).show();
    }

    <?php if (session('isLoggedIn')): ?>
    // Notification System
    function fetchNotifications() {
      $.get('<?= site_url('/notifications') ?>', function(data) {
        if (data.success) {
          // Update badge
          const badge = $('#notificationBadge');
          if (data.unread_count > 0) {
            badge.text(data.unread_count).show();
          } else {
            badge.hide();
          }

          // Update notification list
          const scrollContainer = $('#notificationList');
          scrollContainer.find('.notification-item').remove(); // Remove old notifications
          
          if (data.notifications.length > 0) {
            $('#noNotifications').hide();
            
            data.notifications.forEach(function(notification) {
              const isRead = notification.is_read == 1;
              const hasLink = notification.link && notification.link.trim() !== '';
              const item = $('<div class="notification-item dropdown-item ' + (isRead ? 'text-muted' : '') + '" style="white-space: normal; word-wrap: break-word;"></div>');
              
              // If notification has a link, make it clickable
              if (hasLink) {
                item.css('cursor', 'pointer');
                item.attr('data-link', notification.link);
              }
              
              const messageHtml = 
                '<div class="d-flex justify-content-between align-items-start">' +
                  '<div class="flex-grow-1 pe-2 notification-content" style="max-width: 220px; overflow-wrap: break-word;">' +
                    '<p class="mb-1 small" style="line-height: 1.4;">' + 
                      notification.message + 
                      (hasLink ? ' <i class="bi bi-box-arrow-up-right text-primary" style="font-size: 0.8em;"></i>' : '') +
                    '</p>' +
                    '<small class="text-muted">' + formatDate(notification.created_at) + '</small>' +
                  '</div>' +
                  '<div class="d-flex gap-1 flex-shrink-0 notification-actions">' +
                    (!isRead ? '<button class="btn btn-sm btn-link mark-read-btn p-1" data-id="' + notification.id + '" title="Mark as read"><i class="bi bi-check-circle text-success"></i></button>' : '') +
                    '<button class="btn btn-sm btn-link delete-notification-btn p-1" data-id="' + notification.id + '" title="Delete"><i class="bi bi-trash text-danger"></i></button>' +
                  '</div>' +
                '</div>';
              
              item.html(messageHtml);
              
              scrollContainer.append(item);
            });
          } else {
            $('#noNotifications').show();
          }
        }
      });
    }

    // Setup AJAX to always include CSRF token
    $.ajaxSetup({
      data: {
        '<?= csrf_token() ?>': function() {
          return '<?= csrf_hash() ?>';
        }
      }
    });

    // Mark notification as read
    $(document).on('click', '.mark-read-btn', function(e) {
      e.preventDefault();
      e.stopPropagation();
      
      const btn = $(this);
      const notificationId = btn.data('id');
      
      $.ajax({
        url: '<?= site_url('notifications/mark_read/') ?>' + notificationId,
        type: 'POST',
        dataType: 'json',
        success: function(data) {
          if (data.success) {
            btn.closest('.notification-item').fadeOut(300, function() {
              $(this).remove();
              // Refresh notifications to update count
              setTimeout(fetchNotifications, 300);
            });
          } else {
            console.error('Failed to mark as read:', data.message);
          }
        },
        error: function(xhr, status, error) {
          console.error('Error marking notification as read:', error);
          console.error('Status:', xhr.status);
          if (xhr.responseText) {
            console.error('Response:', xhr.responseText);
          }
        }
      });
    });

    // Click notification to navigate to link
    $(document).on('click', '.notification-item', function(e) {
      // Don't navigate if clicking on action buttons or their icons
      const $target = $(e.target);
      if ($target.closest('.mark-read-btn, .delete-notification-btn, .notification-actions').length > 0) {
        console.log('Clicked on action button, not navigating');
        return;
      }
      
      const $item = $(this);
      const link = $item.attr('data-link');
      console.log('Notification clicked. Link:', link);
      
      if (link && link.trim() !== '') {
        console.log('Navigating to:', link);
        // Mark as read before navigating (optional)
        window.location.href = link;
      } else {
        console.log('No link found for this notification');
      }
    });

    // Delete notification
    $(document).on('click', '.delete-notification-btn', function(e) {
      e.preventDefault();
      e.stopPropagation();
      
      const btn = $(this);
      const notificationId = btn.data('id');
      
      // Confirm deletion
      if (!confirm('Are you sure you want to delete this notification?')) {
        return;
      }
      
      $.ajax({
        url: '<?= site_url('notifications/delete/') ?>' + notificationId,
        type: 'DELETE',
        dataType: 'json',
        success: function(data) {
          if (data.success) {
            btn.closest('.notification-item').fadeOut(300, function() {
              $(this).remove();
              // Refresh notifications to update count
              setTimeout(fetchNotifications, 300);
            });
          } else {
            console.error('Failed to delete notification:', data.message);
            alert('Failed to delete notification: ' + data.message);
          }
        },
        error: function(xhr, status, error) {
          console.error('Error deleting notification:', error);
          console.error('Status:', xhr.status);
          if (xhr.responseText) {
            console.error('Response:', xhr.responseText);
          }
          alert('Error deleting notification. Please try again.');
        }
      });
    });

    // Format date helper
    function formatDate(dateString) {
      // Parse the date string as local time (not UTC)
      const date = new Date(dateString.replace(' ', 'T'));
      const now = new Date();
      
      // Calculate difference in seconds
      const diff = Math.floor((now - date) / 1000);
      
      // Handle negative differences (future dates due to timezone issues)
      if (diff < 0) return 'Just now';
      
      if (diff < 60) return 'Just now';
      if (diff < 120) return '1 minute ago';
      if (diff < 3600) return Math.floor(diff / 60) + ' minutes ago';
      if (diff < 7200) return '1 hour ago';
      if (diff < 86400) return Math.floor(diff / 3600) + ' hours ago';
      if (diff < 172800) return '1 day ago';
      return Math.floor(diff / 86400) + ' days ago';
    }

    // Fetch notifications on page load
    $(document).ready(function() {
      fetchNotifications();
      
      // Refresh notifications every 10 seconds for real-time updates
      setInterval(fetchNotifications, 10000);
    });
    <?php endif; ?>
  </script>

  <?= $this->renderSection('scripts') ?>
</body>
</html>