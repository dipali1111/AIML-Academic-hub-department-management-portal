<?php
require_once __DIR__ . '/includes/auth.php';
require_login();
require_once __DIR__ . '/includes/functions.php';

$role = $_SESSION['role'];
$user = current_user();

// Role-specific dashboard data
$dash = [];
if ($role === 'admin') {
    $dash = [
        ['label' => 'Users', 'num' => get_stats('users'), 'file' => 'modules/settings.php'],
        ['label' => 'Students', 'num' => get_stats('students'), 'file' => 'modules/students.php'],
        ['label' => 'Faculty', 'num' => get_stats('faculty'), 'file' => 'modules/faculty.php'],
        ['label' => 'Notices', 'num' => get_stats('notices'), 'file' => 'modules/notices.php'],
    ];
} elseif ($role === 'hod') {
    $dash = [
        ['label' => 'Faculty', 'num' => get_stats('faculty'), 'file' => 'modules/faculty.php'],
        ['label' => 'Students', 'num' => get_stats('students'), 'file' => 'modules/students.php'],
        ['label' => 'Projects', 'num' => get_stats('projects'), 'file' => 'modules/projects.php'],
        ['label' => 'Research', 'num' => get_stats('research'), 'file' => 'modules/research.php'],
    ];
} elseif ($role === 'faculty') {
    $fidStmt = $pdo->prepare("SELECT id FROM faculty WHERE user_id=?");
    $fidStmt->execute([$user['id']]);
    $fid = $fidStmt->fetch()['id'] ?? 0;
    $cntCourses = $pdo->prepare("SELECT COUNT(*) c FROM courses WHERE faculty_id=?");
    $cntCourses->execute([$fid]);
    $cntProjects = $pdo->prepare("SELECT COUNT(*) c FROM projects WHERE faculty_id=?");
    $cntProjects->execute([$fid]);
    $dash = [
        ['label' => 'My Courses', 'num' => $cntCourses->fetch()['c'], 'file' => 'modules/marks.php'],
        ['label' => 'Students', 'num' => get_stats('students'), 'file' => 'modules/students.php'],
        ['label' => 'Projects', 'num' => $cntProjects->fetch()['c'], 'file' => 'modules/projects.php'],
        ['label' => 'Notices', 'num' => get_stats('notices'), 'file' => 'modules/notices.php'],
    ];
} elseif ($role === 'student') {
    $sidStmt = $pdo->prepare("SELECT id FROM students WHERE user_id=?");
    $sidStmt->execute([$user['id']]);
    $sid = $sidStmt->fetch()['id'] ?? 0;
    $cntAtt = $pdo->prepare("SELECT COUNT(*) c FROM attendance WHERE student_id=?");
    $cntAtt->execute([$sid]);
    $cntProj = $pdo->prepare("SELECT COUNT(*) c FROM projects WHERE student_id=?");
    $cntProj->execute([$sid]);
    $cntInt = $pdo->prepare("SELECT COUNT(*) c FROM internships WHERE student_id=?");
    $cntInt->execute([$sid]);
    $dash = [
        ['label' => 'My Attendance', 'num' => $cntAtt->fetch()['c'], 'file' => 'modules/attendance.php'],
        ['label' => 'My Projects', 'num' => $cntProj->fetch()['c'], 'file' => 'modules/projects.php'],
        ['label' => 'Internships', 'num' => $cntInt->fetch()['c'], 'file' => 'modules/internships.php'],
        ['label' => 'Notices', 'num' => get_stats('notices'), 'file' => 'modules/notices.php'],
    ];
} elseif ($role === 'tpo') {
    $dash = [
        ['label' => 'Companies', 'num' => get_stats('companies'), 'file' => 'modules/placements.php'],
        ['label' => 'Drives', 'num' => get_stats('placements'), 'file' => 'modules/placements.php'],
        ['label' => 'Internships', 'num' => get_stats('internships'), 'file' => 'modules/internships.php'],
        ['label' => 'Students', 'num' => get_stats('students'), 'file' => 'modules/students.php'],
    ];
}
$notices = $pdo->query("SELECT * FROM notices ORDER BY created_at DESC LIMIT 5")->fetchAll();
?>
<?php
$active = 'dashboard';
$page_title = 'Dashboard';
require_once __DIR__ . '/includes/layout.php';
?>
<h2>Welcome, <?= e($user['full_name']) ?> (<?= role_label($role) ?>)</h2>
<div class="grid grid-4" style="margin-top:16px">
    <?php foreach ($dash as $d): ?>
    <a href="<?= base_url() ?>/<?= $d['file'] ?>" style="color:inherit">
        <div class="stat"><h3><?= $d['label'] ?></h3><div class="num"><?= $d['num'] ?></div></div>
    </a>
    <?php endforeach; ?>
</div>

<div class="grid grid-2" style="margin-top:20px">
    <div class="card">
        <h3>Recent Notices</h3>
        <div class="table-wrap"><table>
            <tr><th>Title</th><th>Priority</th><th>Date</th></tr>
            <?php foreach ($notices as $n): ?>
            <tr>
                <td><?= e($n['title']) ?></td>
                <td><span class="badge badge-<?= $n['priority']==='urgent'?'danger':($n['priority']==='important'?'warning':'info') ?>"><?= ucfirst($n['priority']) ?></span></td>
                <td><?= date('d M Y', strtotime($n['created_at'])) ?></td>
            </tr>
            <?php endforeach; ?>
        </table></div>
    </div>
    <div class="card">
        <h3>Quick Actions</h3>
        <p>Use the sidebar to navigate modules. Manage students, attendance, marks, projects, internships, placements, research and more.</p>
        <a href="<?= base_url() ?>/modules/notices.php" class="btn btn-primary">View All Notices</a>
    </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
