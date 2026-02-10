<?php
require __DIR__ . '/app/bootstrap.php';

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($uri === '/robots.txt') {
    header('Content-Type: text/plain; charset=utf-8');
    echo "User-agent: *\nAllow: /\nSitemap: " . rtrim($config['base_url'], '/') . "/sitemap.xml\n";
    exit;
}

if ($uri === '/sitemap.xml') {
    header('Content-Type: application/xml; charset=utf-8');
    $courses = $repo->seoCourseSlugs();
    $teachers = $repo->seoTeacherSlugs();
    echo '<?xml version="1.0" encoding="UTF-8"?>';
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    foreach ([ '/', '/kurslar' ] as $p) {
        echo '<url><loc>' . e(rtrim($config['base_url'], '/') . $p) . '</loc></url>';
    }
    foreach ($courses as $c) {
        echo '<url><loc>' . e(rtrim($config['base_url'], '/') . '/kurs/' . $c['slug']) . '</loc></url>';
    }
    foreach ($teachers as $t) {
        echo '<url><loc>' . e(rtrim($config['base_url'], '/') . '/ogretmen/' . slugify($t['full_name'])) . '</loc></url>';
    }
    echo '</urlset>';
    exit;
}

$routeHandled = true;

switch (true) {
    case $uri === '/':
        render('home', seo_meta('Nettedersin LMS - Premium Online Eğitim', 'Terminal gerektirmeyen, üretime alınabilir LMS platformu.') + compact('config'));
        break;

    case $uri === '/kayit' && $method === 'GET':
        render('auth_register', seo_meta('Kayıt Ol', 'Öğrenci veya öğretmen hesabı oluştur.') + compact('config'));
        break;
    case $uri === '/kayit' && $method === 'POST':
        verify_csrf();
        try {
            $auth->register(trim($_POST['name'] ?? ''), trim($_POST['email'] ?? ''), (string)($_POST['password'] ?? ''), (string)($_POST['role'] ?? 'student'));
            $auth->login((string)$_POST['email'], (string)$_POST['password']);
            redirect('/dashboard');
        } catch (Throwable $e) {
            render('auth_register', seo_meta('Kayıt Ol', 'Kayıt ol') + ['error' => $e->getMessage(), 'config' => $config]);
        }
        break;

    case $uri === '/giris' && $method === 'GET':
        render('auth_login', seo_meta('Giriş', 'Hesabınıza giriş yapın.') + compact('config'));
        break;
    case $uri === '/giris' && $method === 'POST':
        verify_csrf();
        try {
            $auth->login((string)$_POST['email'], (string)$_POST['password']);
            redirect('/dashboard');
        } catch (Throwable $e) {
            render('auth_login', seo_meta('Giriş', 'Giriş') + ['error' => $e->getMessage(), 'config' => $config]);
        }
        break;

    case $uri === '/cikis' && $method === 'POST':
        verify_csrf();
        $auth->logout();
        redirect('/');
        break;

    case $uri === '/sifre-sifirla' && $method === 'GET':
        render('auth_reset_request', seo_meta('Şifre Sıfırla', 'Şifrenizi sıfırlayın.') + compact('config'));
        break;
    case $uri === '/sifre-sifirla' && $method === 'POST':
        verify_csrf();
        $token = $auth->requestReset((string)$_POST['email']);
        $msg = $token ? ('Reset linki (mail yerine gösterim): ' . rtrim($config['base_url'], '/') . '/sifre-yenile?token=' . $token) : 'Eğer kayıtlıysa sıfırlama linki üretildi.';
        render('auth_reset_request', seo_meta('Şifre Sıfırla', 'Şifre sıfırla') + ['message' => $msg, 'config' => $config]);
        break;
    case $uri === '/sifre-yenile' && $method === 'GET':
        render('auth_reset_form', seo_meta('Yeni Şifre', 'Yeni şifre belirleyin.') + ['token' => (string)($_GET['token'] ?? ''), 'config' => $config]);
        break;
    case $uri === '/sifre-yenile' && $method === 'POST':
        verify_csrf();
        try {
            $auth->resetPassword((string)$_POST['token'], (string)$_POST['password']);
            render('auth_reset_form', seo_meta('Yeni Şifre', 'Şifre güncellendi') + ['message' => 'Şifreniz güncellendi.', 'token' => '', 'config' => $config]);
        } catch (Throwable $e) {
            render('auth_reset_form', seo_meta('Yeni Şifre', 'Hata') + ['error' => $e->getMessage(), 'token' => (string)$_POST['token'], 'config' => $config]);
        }
        break;

    case $uri === '/dashboard':
        require_auth();
        $user = current_user();
        if ($user['role'] === 'student') {
            $stats = $repo->recentStudentStats((int)$user['id']);
            render('dashboard_student', seo_meta('Öğrenci Paneli', 'Öğrenci dashboard') + compact('stats', 'config'));
        } elseif ($user['role'] === 'teacher') {
            $courses = $repo->teacherCourses((int)$user['id']);
            $questions = $repo->teacherQuestions((int)$user['id']);
            render('dashboard_teacher', seo_meta('Öğretmen Paneli', 'Öğretmen dashboard') + compact('courses', 'questions', 'config'));
        } else {
            $pendingCourses = $repo->adminPendingCourses();
            $orders = $repo->adminOrders();
            $users = $repo->allUsers();
            render('dashboard_admin', seo_meta('Admin Paneli', 'Admin dashboard') + compact('pendingCourses', 'orders', 'users', 'config'));
        }
        break;

    case $uri === '/kurslar':
        $uid = current_user()['id'] ?? null;
        $courses = $repo->getPublishedCourses($uid ? (int)$uid : 0);
        render('courses', seo_meta('Kurslar', 'Tüm kursları keşfet.') + compact('courses', 'config'));
        break;

    case preg_match('#^/kurs/([a-z0-9\-]+)$#', $uri, $m):
        $course = $repo->getCourseBySlug($m[1]);
        if (!$course || $course['status'] !== 'published') {
            http_response_code(404); render('simple_page', seo_meta('404', 'Bulunamadı') + ['title' => 'Kurs bulunamadı', 'config' => $config]); break;
        }
        $sections = $repo->getCourseSections((int)$course['id']);
        foreach ($sections as &$section) {
            $section['lessons'] = $repo->getLessonsBySection((int)$section['id']);
        }
        unset($section);
        $hasAccess = current_user() ? $repo->userHasAccess((int)current_user()['id'], (int)$course['id']) : false;
        $courseSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'Course',
            'name' => $course['title'],
            'description' => $course['description'],
            'provider' => ['@type' => 'Organization', 'name' => 'Nettedersin']
        ];
        render('course_detail', seo_meta($course['title'], $course['description']) + compact('course', 'sections', 'hasAccess', 'courseSchema', 'config'));
        break;

    case preg_match('#^/ders/(\d+)$#', $uri, $m):
        require_auth();
        $lesson = $repo->getLesson((int)$m[1]);
        if (!$lesson) { http_response_code(404); exit('Ders bulunamadı'); }
        $hasAccess = $repo->userHasAccess((int)current_user()['id'], (int)$lesson['course_id']);
        $progress = $repo->lessonProgress((int)current_user()['id'], (int)$lesson['id']);
        $comments = $repo->getLessonComments((int)$lesson['id']);
        render('lesson', seo_meta($lesson['title'], $lesson['course_title']) + compact('lesson', 'hasAccess', 'progress', 'comments', 'config'));
        break;

    case preg_match('#^/ders/(\d+)/ilerleme$#', $uri, $m) && $method === 'POST':
        require_role(['student']); verify_csrf();
        $repo->saveProgress((int)current_user()['id'], (int)$m[1], (int)($_POST['watched_seconds'] ?? 0), ((int)($_POST['completed'] ?? 0) === 1));
        redirect('/ders/' . (int)$m[1]);
        break;

    case preg_match('#^/ders/(\d+)/yorum$#', $uri, $m) && $method === 'POST':
        require_role(['student']); verify_csrf();
        $body = trim((string)($_POST['comment_body'] ?? ''));
        if ($body !== '') { $repo->addComment((int)current_user()['id'], (int)$m[1], $body); }
        redirect('/ders/' . (int)$m[1]);
        break;

    case $uri === '/ogretmen/kurs' && $method === 'POST':
        require_role(['teacher']); verify_csrf();
        $title = trim((string)($_POST['title'] ?? ''));
        $desc = trim((string)($_POST['description'] ?? ''));
        if ($title) {
            $repo->createCourse((int)current_user()['id'], $title, slugify($title) . '-' . random_int(100, 999), $desc);
        }
        redirect('/dashboard');
        break;

    case $uri === '/ogretmen/section' && $method === 'POST':
        require_role(['teacher']); verify_csrf();
        $repo->createSection((int)($_POST['course_id'] ?? 0), trim((string)($_POST['title'] ?? 'Bölüm')));
        redirect('/dashboard');
        break;


    case $uri === '/ogretmen/question' && $method === 'POST':
        require_role(['teacher']); verify_csrf();
        $repo->createQuestion((int)current_user()['id'], [
            'question_text' => trim((string)($_POST['question_text'] ?? '')),
            'option_a' => trim((string)($_POST['option_a'] ?? '')),
            'option_b' => trim((string)($_POST['option_b'] ?? '')),
            'option_c' => trim((string)($_POST['option_c'] ?? '')),
            'option_d' => trim((string)($_POST['option_d'] ?? '')),
            'correct_option' => trim((string)($_POST['correct_option'] ?? 'A')),
            'topic' => trim((string)($_POST['topic'] ?? '')),
        ]);
        redirect('/dashboard');
        break;

    case $uri === '/ogretmen/test' && $method === 'POST':
        require_role(['teacher']); verify_csrf();
        $testId = $repo->createTest((int)current_user()['id'], (int)($_POST['course_id'] ?? 0), trim((string)($_POST['title'] ?? 'Test')), (int)($_POST['duration_seconds'] ?? 1800));
        $latestQuestions = $repo->teacherQuestions((int)current_user()['id']);
        foreach (array_slice($latestQuestions, 0, 10) as $q) {
            $repo->attachQuestionToTest($testId, (int)$q['id']);
        }
        redirect('/dashboard');
        break;

    case $uri === '/admin/course-status' && $method === 'POST':
        require_role(['admin']); verify_csrf();
        $repo->updateCourseStatus((int)($_POST['course_id'] ?? 0), (string)($_POST['status'] ?? 'draft'));
        $repo->logAudit((int)current_user()['id'], 'admin.course_status', ['course_id' => (int)($_POST['course_id'] ?? 0), 'status' => (string)($_POST['status'] ?? 'draft')]);
        redirect('/dashboard');
        break;

    case $uri === '/admin/user-status' && $method === 'POST':
        require_role(['admin']); verify_csrf();
        $repo->updateUserStatus((int)($_POST['user_id'] ?? 0), (string)($_POST['status'] ?? 'active'));
        $repo->logAudit((int)current_user()['id'], 'admin.user_status', ['user_id' => (int)($_POST['user_id'] ?? 0)]);
        redirect('/dashboard');
        break;

    case $uri === '/admin/order-paid' && $method === 'POST':
        require_role(['admin']); verify_csrf();
        $paymentService->markPaidByAdmin((int)($_POST['order_id'] ?? 0), (int)current_user()['id']);
        redirect('/dashboard');
        break;

    case $uri === '/ogrenci/satin-al' && $method === 'GET':
        require_role(['student']);
        $plans = $repo->plans();
        $orders = $repo->myOrders((int)current_user()['id']);
        render('purchase', seo_meta('Paket Satın Al', 'Manuel/test ödeme ile paket al.') + compact('plans', 'orders', 'config'));
        break;
    case $uri === '/ogrenci/satin-al' && $method === 'POST':
        require_role(['student']); verify_csrf();
        $paymentService->createManualOrder((int)current_user()['id'], (int)($_POST['plan_id'] ?? 0));
        redirect('/ogrenci/satin-al');
        break;

    case preg_match('#^/test/(\d+)$#', $uri, $m):
        require_role(['student']);
        $test = $repo->getTest((int)$m[1]);
        if (!$test) { http_response_code(404); exit('Test yok'); }
        $questions = $repo->testQuestions((int)$m[1]);
        render('test_start', seo_meta($test['title'], 'Test çöz') + compact('test', 'questions', 'config'));
        break;

    case preg_match('#^/test/(\d+)/submit$#', $uri, $m) && $method === 'POST':
        require_role(['student']); verify_csrf();
        $attemptId = $repo->submitAttempt((int)current_user()['id'], (int)$m[1], (array)($_POST['q'] ?? []), (int)($_POST['duration_seconds'] ?? 0));
        redirect('/test-sonuc/' . $attemptId);
        break;

    case preg_match('#^/test-sonuc/(\d+)$#', $uri, $m):
        require_role(['student']);
        $result = $repo->attemptResult((int)$m[1], (int)current_user()['id']);
        if (!$result) { http_response_code(404); exit('Sonuç yok'); }
        render('test_result', seo_meta('Test Sonucu', 'Test sonucu ekranı') + compact('result', 'config'));
        break;

    case preg_match('#^/ogretmen/(.+)$#', $uri, $m):
        $name = ucfirst(str_replace('-', ' ', $m[1]));
        $schemaJson = json_encode(['@context' => 'https://schema.org', '@type' => 'Person', 'name' => $name], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        render('simple_page', seo_meta('Öğretmen ' . $name, 'Öğretmen profili') + ['title' => 'Öğretmen Profili: ' . $name, 'content' => 'SEO profili.', 'schemaJson' => $schemaJson, 'config' => $config]);
        break;

    case preg_match('#^/blog/(.+)$#', $uri, $m):
        $title = ucfirst(str_replace('-', ' ', $m[1]));
        render('simple_page', seo_meta($title, 'Blog içeriği') + ['title' => $title, 'content' => 'SEO blog sayfası.', 'config' => $config]);
        break;

    default:
        $routeHandled = false;
}

if (!$routeHandled) {
    http_response_code(404);
    render('simple_page', seo_meta('404', 'Sayfa bulunamadı') + ['title' => '404 - Sayfa Bulunamadı', 'content' => 'Aradığınız içerik bulunamadı.', 'config' => $config]);
}
