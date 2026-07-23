<?php
require_once __DIR__ . '/../includes/auth.php';
require_role(['admin','hod','tpo']);
require_once __DIR__ . '/../includes/functions.php';

// Aggregate report data
$totalStudents = get_stats('students');
$totalFaculty = get_stats('faculty');
$avgCgpa = $pdo->query("SELECT ROUND(AVG(cgpa),2) a FROM students")->fetch()['a'];
$totalProjects = get_stats('projects');
$ongoingProjects = $pdo->query("SELECT COUNT(*) c FROM projects WHERE status='ongoing'")->fetch()['c'];
$totalPlacements = get_stats('placements');
$selectedPlacements = $pdo->query("SELECT COUNT(*) c FROM placements WHERE offer_status='selected'")->fetch()['c'];
$totalInternships = get_stats('internships');
$totalResearch = get_stats('research');
$totalPubs = get_stats('publications');
$totalPatents = get_stats('patents');

// Attendance summary per student
$attendanceSummary = $pdo->query("
    SELECT s.roll_no, s.full_name,
    COUNT(*) total,
    SUM(CASE WHEN a.status='present' THEN 1 ELSE 0 END) present
    FROM attendance a JOIN students s ON a.student_id=s.id
    GROUP BY a.student_id
")->fetchAll();

// Course-wise average marks
$courseMarks = $pdo->query("
    SELECT c.course_name, COUNT(*) entries, ROUND(AVG(m.marks_obtained/NULLIF(m.max_marks,0)*100),1) avg_pct
    FROM marks m JOIN courses c ON m.course_id=c.id
    GROUP BY m.course_id
")->fetchAll();
?>
<?php
$active = 'reports';
$page_title = 'AIML AcademicHub';
require_once __DIR__ . '/../includes/layout.php';
?>
<h2>Reports & Analytics</h2>

<div class="grid grid-4">
    <div class="stat"><h3>Students</h3><div class="num"><?= $totalStudents ?></div></div>
    <div class="stat"><h3>Faculty</h3><div class="num"><?= $totalFaculty ?></div></div>
    <div class="stat"><h3>Avg CGPA</h3><div class="num"><?= $avgCgpa ?></div></div>
    <div class="stat"><h3>Projects</h3><div class="num"><?= $totalProjects ?></div></div>
    <div class="stat"><h3>Placements</h3><div class="num"><?= $selectedPlacements ?>/<?= $totalPlacements ?></div></div>
    <div class="stat"><h3>Internships</h3><div class="num"><?= $totalInternships ?></div></div>
    <div class="stat"><h3>Research</h3><div class="num"><?= $totalResearch ?></div></div>
    <div class="stat"><h3>Publications</h3><div class="num"><?= $totalPubs ?></div></div>
</div>

<div class="card" style="margin-top:20px">
    <h3>Attendance Summary</h3>
    <table>
        <tr><th>Roll No</th><th>Student</th><th>Present</th><th>Total</th><th>%</th></tr>
        <?php foreach ($attendanceSummary as $a): ?>
        <tr>
            <td><?= e($a['roll_no']) ?></td>
            <td><?= e($a['full_name']) ?></td>
            <td><?= $a['present'] ?></td>
            <td><?= $a['total'] ?></td>
            <td><?= $a['total']>0 ? round($a['present']/$a['total']*100,1) : 0 ?>%</td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<div class="card">
    <h3>Course-wise Average Marks</h3>
    <table>
        <tr><th>Course</th><th>Entries</th><th>Average %</th></tr>
        <?php foreach ($courseMarks as $c): ?>
        <tr><td><?= e($c['course_name']) ?></td><td><?= $c['entries'] ?></td><td><?= $c['avg_pct'] ?>%</td></tr>
        <?php endforeach; ?>
    </table>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>

