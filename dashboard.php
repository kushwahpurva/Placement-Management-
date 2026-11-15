<?php
require_once 'config.php';
require_login();
$pdo = db();
$stats = [
    'students' => (int) $pdo->query('SELECT COUNT(*) FROM students')->fetchColumn(),
    'companies' => (int) $pdo->query('SELECT COUNT(*) FROM companies')->fetchColumn(),
    'jobs' => (int) $pdo->query('SELECT COUNT(*) FROM jobs WHERE last_date >= CURDATE()')->fetchColumn(),
    'applications' => (int) $pdo->query('SELECT COUNT(*) FROM applications')->fetchColumn()
];
$recent = $pdo->query("SELECT a.id, s.name AS student, j.job_title, c.company_name, a.status, a.applied_on
 FROM applications a JOIN students s ON s.id=a.student_id JOIN jobs j ON j.id=a.job_id JOIN companies c ON c.id=j.company_id
 ORDER BY a.applied_on DESC, a.id DESC LIMIT 6")->fetchAll();
$page_title = 'Dashboard';
require 'partials_header.php';
?>
<div class="welcome-panel">
    <div><span class="eyebrow light">OVERVIEW</span>
        <h2>Good work starts with great connections.</h2>
        <p>Manage student profiles, employer partners and placement applications from one place.</p>
    </div>
    <div class="welcome-art">↗</div>
</div>
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon violet">♙</div><span>Total
            students</span><strong><?= $stats['students'] ?></strong><small>Registered student records</small>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">▣</div><span>Partner
            companies</span><strong><?= $stats['companies'] ?></strong><small>Companies in the database</small>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">▤</div><span>Active job
            openings</span><strong><?= $stats['jobs'] ?></strong><small>Open for applications</small>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange">☷</div><span>Total
            applications</span><strong><?= $stats['applications'] ?></strong><small>Applications received</small>
    </div>
</div>
<div class="section-heading">
    <div>
        <h2>Recent applications</h2>
        <p>Latest student applications received</p>
    </div><a class="btn secondary" href="applications.php">View all →</a>
</div>
<div class="table-card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Job role</th>
                    <th>Company</th>
                    <th>Applied on</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recent as $r): ?>
                    <tr>
                        <td><strong><?= e($r['student']) ?></strong></td>
                        <td><?= e($r['job_title']) ?></td>
                        <td><?= e($r['company_name']) ?></td>
                        <td><?= e(date('d M Y', strtotime($r['applied_on']))) ?></td>
                        <td><span class="badge <?= strtolower($r['status']) ?>"><?= e($r['status']) ?></span></td>
                    </tr><?php endforeach; ?>
                <?php if (!$recent): ?>
                    <tr>
                        <td colspan="5" class="empty">No applications found.</td>
                    </tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require 'partials_footer.php'; ?>