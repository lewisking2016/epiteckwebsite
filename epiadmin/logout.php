<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/admin_auth.php';

admin_no_cache();

if (!is_post() || !csrf_valid($_POST['csrf'] ?? null)) {
    redirect(admin_url('index.php'));
}

admin_logout();
redirect(admin_url('login.php'));
