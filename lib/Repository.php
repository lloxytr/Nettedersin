<?php

class Repository
{
    public function __construct(private PDO $db)
    {
    }

    public function fetchOne(string $sql, array $params = []): ?array
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function execute(string $sql, array $params = []): bool
    {
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function lastInsertId(): string
    {
        return $this->db->lastInsertId();
    }

    public function getUserByEmail(string $email): ?array
    {
        return $this->fetchOne('SELECT * FROM users WHERE email = :email LIMIT 1', ['email' => $email]);
    }

    public function getUserById(int $id): ?array
    {
        return $this->fetchOne('SELECT * FROM users WHERE id = :id LIMIT 1', ['id' => $id]);
    }

    public function createUser(string $name, string $email, string $passwordHash, string $role = 'student'): int
    {
        $this->execute(
            'INSERT INTO users (full_name, email, password_hash, role, status, created_at) VALUES (:n,:e,:p,:r,"active",NOW())',
            ['n' => $name, 'e' => $email, 'p' => $passwordHash, 'r' => $role]
        );
        return (int)$this->lastInsertId();
    }

    public function logAudit(?int $actorId, string $action, array $context = []): void
    {
        $this->execute(
            'INSERT INTO audit_logs (actor_user_id, action, context_json, ip_address, created_at) VALUES (:a,:act,:ctx,:ip,NOW())',
            [
                'a' => $actorId,
                'act' => $action,
                'ctx' => json_encode($context, JSON_UNESCAPED_UNICODE),
                'ip' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0'
            ]
        );
    }

    public function loginAttemptCount(string $ip): int
    {
        $row = $this->fetchOne('SELECT COUNT(*) c FROM login_attempts WHERE ip_address = :ip AND attempted_at >= DATE_SUB(NOW(), INTERVAL 15 MINUTE)', ['ip' => $ip]);
        return (int)($row['c'] ?? 0);
    }

    public function addLoginAttempt(string $ip, string $email): void
    {
        $this->execute('INSERT INTO login_attempts (ip_address, email, attempted_at) VALUES (:ip,:e,NOW())', ['ip' => $ip, 'e' => $email]);
    }

    public function clearLoginAttempts(string $ip): void
    {
        $this->execute('DELETE FROM login_attempts WHERE ip_address = :ip', ['ip' => $ip]);
    }

    public function upsertSession(int $userId, string $sessionId): void
    {
        $this->execute('REPLACE INTO sessions (id, user_id, ip_address, user_agent, last_activity) VALUES (:id,:u,:ip,:ua,NOW())', [
            'id' => $sessionId,
            'u' => $userId,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
            'ua' => substr($_SERVER['HTTP_USER_AGENT'] ?? 'unknown', 0, 255)
        ]);
    }

    public function createResetToken(int $userId, string $token, string $expiresAt): void
    {
        $this->execute('INSERT INTO password_resets (user_id, token, expires_at, created_at) VALUES (:u,:t,:x,NOW())', ['u' => $userId, 't' => $token, 'x' => $expiresAt]);
    }

    public function getResetToken(string $token): ?array
    {
        return $this->fetchOne('SELECT * FROM password_resets WHERE token = :t AND used_at IS NULL AND expires_at > NOW() LIMIT 1', ['t' => $token]);
    }

    public function consumeResetToken(int $id): void
    {
        $this->execute('UPDATE password_resets SET used_at = NOW() WHERE id = :id', ['id' => $id]);
    }

    public function updatePassword(int $userId, string $hash): void
    {
        $this->execute('UPDATE users SET password_hash = :h WHERE id = :id', ['h' => $hash, 'id' => $userId]);
    }

    public function getPublishedCourses(?int $studentId): array
    {
        $sql = 'SELECT c.*, u.full_name teacher_name,
            EXISTS(
              SELECT 1 FROM course_enrollments ce WHERE ce.course_id = c.id AND ce.user_id = :uid
            ) has_access
            FROM courses c
            JOIN users u ON u.id = c.teacher_id
            WHERE c.status = "published"
            ORDER BY c.created_at DESC';
        return $this->fetchAll($sql, ['uid' => (int)$studentId]);
    }

    public function getCourseBySlug(string $slug): ?array
    {
        return $this->fetchOne('SELECT c.*, u.full_name teacher_name FROM courses c JOIN users u ON u.id=c.teacher_id WHERE c.slug=:s LIMIT 1', ['s' => $slug]);
    }

    public function getCourseSections(int $courseId): array
    {
        return $this->fetchAll('SELECT * FROM sections WHERE course_id=:c ORDER BY position ASC', ['c' => $courseId]);
    }

    public function getLessonsBySection(int $sectionId): array
    {
        return $this->fetchAll('SELECT * FROM lessons WHERE section_id=:s ORDER BY position ASC', ['s' => $sectionId]);
    }

    public function getLesson(int $lessonId): ?array
    {
        return $this->fetchOne('SELECT l.*, s.course_id, c.title course_title FROM lessons l JOIN sections s ON s.id=l.section_id JOIN courses c ON c.id=s.course_id WHERE l.id=:id', ['id' => $lessonId]);
    }

    public function saveProgress(int $userId, int $lessonId, int $seconds, bool $completed): void
    {
        $this->execute('REPLACE INTO lesson_progress (user_id, lesson_id, watched_seconds, completed, updated_at) VALUES (:u,:l,:s,:c,NOW())', [
            'u' => $userId,
            'l' => $lessonId,
            's' => $seconds,
            'c' => $completed ? 1 : 0,
        ]);
    }

    public function lessonProgress(int $userId, int $lessonId): ?array
    {
        return $this->fetchOne('SELECT * FROM lesson_progress WHERE user_id=:u AND lesson_id=:l', ['u' => $userId, 'l' => $lessonId]);
    }

    public function addComment(int $userId, int $lessonId, string $body): void
    {
        $this->execute('INSERT INTO lesson_comments (lesson_id,user_id,comment_body,status,created_at) VALUES (:l,:u,:b,"pending",NOW())', ['l' => $lessonId, 'u' => $userId, 'b' => $body]);
    }

    public function getLessonComments(int $lessonId): array
    {
        return $this->fetchAll('SELECT lc.*, u.full_name FROM lesson_comments lc JOIN users u ON u.id=lc.user_id WHERE lc.lesson_id=:l AND lc.status="approved" ORDER BY lc.created_at DESC', ['l' => $lessonId]);
    }

    public function teacherCourses(int $teacherId): array
    {
        return $this->fetchAll('SELECT * FROM courses WHERE teacher_id=:t ORDER BY id DESC', ['t' => $teacherId]);
    }

    public function createCourse(int $teacherId, string $title, string $slug, string $desc): int
    {
        $this->execute('INSERT INTO courses (teacher_id,title,slug,description,status,created_at) VALUES (:t,:ti,:s,:d,"review",NOW())', [
            't' => $teacherId, 'ti' => $title, 's' => $slug, 'd' => $desc
        ]);
        return (int)$this->lastInsertId();
    }

    public function createSection(int $courseId, string $title): int
    {
        $this->execute('INSERT INTO sections (course_id,title,position,created_at) VALUES (:c,:t,(SELECT COALESCE(MAX(position),0)+1 FROM sections s WHERE s.course_id=:c2),NOW())', ['c' => $courseId, 'c2' => $courseId, 't' => $title]);
        return (int)$this->lastInsertId();
    }

    public function createLesson(int $sectionId, string $title, ?string $video, ?string $pdf): int
    {
        $this->execute('INSERT INTO lessons (section_id,title,video_path,pdf_path,position,created_at) VALUES (:s,:t,:v,:p,(SELECT COALESCE(MAX(position),0)+1 FROM lessons l WHERE l.section_id=:s2),NOW())', [
            's' => $sectionId, 's2' => $sectionId, 't' => $title, 'v' => $video, 'p' => $pdf
        ]);
        return (int)$this->lastInsertId();
    }

    public function adminPendingCourses(): array
    {
        return $this->fetchAll('SELECT c.*,u.full_name teacher_name FROM courses c JOIN users u ON u.id=c.teacher_id WHERE c.status IN ("review","draft") ORDER BY c.created_at DESC');
    }

    public function updateCourseStatus(int $courseId, string $status): void
    {
        $this->execute('UPDATE courses SET status=:s, published_at=IF(:s2="published",NOW(),published_at) WHERE id=:id', ['s' => $status, 's2' => $status, 'id' => $courseId]);
    }

    public function allUsers(): array
    {
        return $this->fetchAll('SELECT id, full_name, email, role, status, created_at FROM users ORDER BY id DESC');
    }

    public function updateUserStatus(int $userId, string $status): void
    {
        $this->execute('UPDATE users SET status=:s WHERE id=:id', ['s' => $status, 'id' => $userId]);
    }

    public function plans(): array
    {
        return $this->fetchAll('SELECT * FROM plans ORDER BY price ASC');
    }

    public function createOrder(int $userId, int $planId, float $amount, string $method = 'manual'): int
    {
        $this->execute('INSERT INTO orders (user_id,plan_id,amount,status,payment_method,idempotency_key,created_at) VALUES (:u,:p,:a,"pending",:m,:k,NOW())', [
            'u' => $userId,
            'p' => $planId,
            'a' => $amount,
            'm' => $method,
            'k' => bin2hex(random_bytes(16)),
        ]);
        return (int)$this->lastInsertId();
    }

    public function myOrders(int $userId): array
    {
        return $this->fetchAll('SELECT o.*, p.name plan_name FROM orders o JOIN plans p ON p.id=o.plan_id WHERE o.user_id=:u ORDER BY o.id DESC', ['u' => $userId]);
    }

    public function adminOrders(): array
    {
        return $this->fetchAll('SELECT o.*, u.full_name, u.email, p.name plan_name, p.duration_days FROM orders o JOIN users u ON u.id=o.user_id JOIN plans p ON p.id=o.plan_id ORDER BY o.id DESC');
    }

    public function getOrder(int $orderId): ?array
    {
        return $this->fetchOne('SELECT o.*, p.duration_days FROM orders o JOIN plans p ON p.id=o.plan_id WHERE o.id=:id', ['id' => $orderId]);
    }

    public function markOrderPaid(int $orderId, string $providerRef): bool
    {
        $order = $this->getOrder($orderId);
        if (!$order || $order['status'] === 'paid') {
            return false;
        }

        $this->db->beginTransaction();
        try {
            $this->execute('UPDATE orders SET status="paid", paid_at=NOW() WHERE id=:id AND status!="paid"', ['id' => $orderId]);
            $this->execute('INSERT INTO payments (order_id, provider, provider_reference, amount, status, created_at) VALUES (:o,"manual",:r,:a,"paid",NOW())', [
                'o' => $orderId,
                'r' => $providerRef,
                'a' => $order['amount'],
            ]);
            $this->execute('REPLACE INTO user_plan_access (user_id, plan_id, starts_at, ends_at, created_at) VALUES (:u,:p,NOW(),DATE_ADD(NOW(), INTERVAL :d DAY),NOW())', [
                'u' => $order['user_id'],
                'p' => $order['plan_id'],
                'd' => $order['duration_days']
            ]);
            $this->db->commit();
            return true;
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function userHasAccess(int $userId, int $courseId): bool
    {
        $row = $this->fetchOne('SELECT COUNT(*) c FROM user_plan_access upa JOIN plan_course_access pca ON pca.plan_id=upa.plan_id WHERE upa.user_id=:u AND upa.ends_at > NOW() AND pca.course_id=:c', ['u' => $userId, 'c' => $courseId]);
        return ((int)($row['c'] ?? 0)) > 0;
    }

    public function testsByCourse(int $courseId): array
    {
        return $this->fetchAll('SELECT * FROM tests WHERE course_id=:c ORDER BY id DESC', ['c' => $courseId]);
    }

    public function getTest(int $testId): ?array
    {
        return $this->fetchOne('SELECT * FROM tests WHERE id=:id', ['id' => $testId]);
    }

    public function testQuestions(int $testId): array
    {
        return $this->fetchAll('SELECT q.* FROM test_questions tq JOIN questions q ON q.id=tq.question_id WHERE tq.test_id=:t ORDER BY tq.id ASC', ['t' => $testId]);
    }

    public function submitAttempt(int $userId, int $testId, array $answers, int $seconds): int
    {
        $questions = $this->testQuestions($testId);
        $correct = 0;
        $wrong = 0;

        foreach ($questions as $q) {
            $ans = $answers[(string)$q['id']] ?? null;
            if ($ans !== null && strtoupper($ans) === strtoupper($q['correct_option'])) {
                $correct++;
            } else {
                $wrong++;
                $this->execute('INSERT INTO wrong_notebook (user_id, question_id, note, created_at) VALUES (:u,:q,"",NOW())', ['u' => $userId, 'q' => $q['id']]);
            }
        }

        $score = count($questions) > 0 ? ($correct / count($questions)) * 100 : 0;

        $this->execute('INSERT INTO test_attempts (test_id, user_id, score, correct_count, wrong_count, duration_seconds, created_at) VALUES (:t,:u,:s,:c,:w,:d,NOW())', [
            't' => $testId, 'u' => $userId, 's' => $score, 'c' => $correct, 'w' => $wrong, 'd' => $seconds
        ]);
        $attemptId = (int)$this->lastInsertId();

        foreach ($questions as $q) {
            $this->execute('INSERT INTO test_answers (attempt_id, question_id, selected_option, is_correct) VALUES (:a,:q,:s,:i)', [
                'a' => $attemptId,
                'q' => $q['id'],
                's' => strtoupper((string)($answers[(string)$q['id']] ?? '')),
                'i' => strtoupper((string)($answers[(string)$q['id']] ?? '')) === strtoupper($q['correct_option']) ? 1 : 0,
            ]);
        }

        return $attemptId;
    }

    public function attemptResult(int $attemptId, int $userId): ?array
    {
        return $this->fetchOne('SELECT * FROM test_attempts WHERE id=:id AND user_id=:u', ['id' => $attemptId, 'u' => $userId]);
    }

    public function recentStudentStats(int $userId): array
    {
        $lastLesson = $this->fetchOne('SELECT l.title, lp.updated_at FROM lesson_progress lp JOIN lessons l ON l.id=lp.lesson_id WHERE lp.user_id=:u ORDER BY lp.updated_at DESC LIMIT 1', ['u' => $userId]);
        $lastTest = $this->fetchOne('SELECT score, created_at FROM test_attempts WHERE user_id=:u ORDER BY id DESC LIMIT 1', ['u' => $userId]);
        $progress = $this->fetchOne('SELECT COALESCE(AVG(completed)*100,0) p FROM lesson_progress WHERE user_id=:u', ['u' => $userId]);
        return [
            'lastLesson' => $lastLesson,
            'lastTest' => $lastTest,
            'progressPercent' => round((float)($progress['p'] ?? 0), 1),
        ];
    }

    public function seoCourseSlugs(): array
    {
        return $this->fetchAll('SELECT slug, updated_at FROM courses WHERE status="published"');
    }

    public function seoTeacherSlugs(): array
    {
        return $this->fetchAll('SELECT id, full_name, updated_at FROM users WHERE role="teacher"');
    }

    public function teacherQuestions(int $teacherId): array
    {
        return $this->fetchAll('SELECT * FROM questions WHERE teacher_id=:t ORDER BY id DESC LIMIT 100', ['t'=>$teacherId]);
    }

    public function createQuestion(int $teacherId, array $data): int
    {
        $this->execute('INSERT INTO questions (teacher_id, question_text, option_a, option_b, option_c, option_d, correct_option, topic, created_at) VALUES (:t,:q,:a,:b,:c,:d,:o,:topic,NOW())', [
            't'=>$teacherId,
            'q'=>$data['question_text'],
            'a'=>$data['option_a'],
            'b'=>$data['option_b'],
            'c'=>$data['option_c'],
            'd'=>$data['option_d'],
            'o'=>strtoupper($data['correct_option']),
            'topic'=>$data['topic'] ?? null,
        ]);
        return (int)$this->lastInsertId();
    }

    public function createTest(int $teacherId, int $courseId, string $title, int $duration): int
    {
        $this->execute('INSERT INTO tests (course_id, teacher_id, title, duration_seconds, created_at) VALUES (:c,:t,:title,:d,NOW())', [
            'c'=>$courseId,'t'=>$teacherId,'title'=>$title,'d'=>$duration
        ]);
        return (int)$this->lastInsertId();
    }

    public function attachQuestionToTest(int $testId, int $questionId): void
    {
        $this->execute('INSERT INTO test_questions (test_id, question_id) VALUES (:t,:q)', ['t'=>$testId,'q'=>$questionId]);
    }

}
