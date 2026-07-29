<<<<<<< HEAD
﻿<?php
=======
<?php
>>>>>>> b6df4ad04a8aa0ab8bde74ecb2f0ff952f1c32f4
require_once __DIR__ . '/../includes/auth.php';
require_role(['admin','hod','faculty','student','tpo']);
require_once __DIR__ . '/../includes/functions.php';

$role = $_SESSION['role'];
$user = current_user();

// Restrict student view to own records
$where = '';
$whereParams = [];
if ($role === 'student') {
    $sidStmt = $pdo->prepare("SELECT id FROM students WHERE user_id=?");
    $sidStmt->execute([$user['id']]);
    $sid = $sidStmt->fetch()['id'] ?? 0;
    $where = "WHERE a.student_id=?";
    $whereParams = [$sid];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $role !== 'student') {
    verify_csrf();
    if ($_POST['action'] === 'add') {
        $att_date = valid_date($_POST['att_date']);
        $status = enum_val($_POST['status'], ['present','absent','late']);
        if ($att_date === '' || $status === '') {
            flash('Invalid date or status provided.', 'danger');
        } elseif (safe_exec("INSERT INTO attendance (student_id,course_id,att_date,status) VALUES (?,?,?,?)",
            [(int)$_POST['student_id'],(int)$_POST['course_id'],$att_date,$status])) {
            flash('Attendance marked.');
        }
    } elseif ($_POST['action'] === 'delete') {
        if (safe_exec("DELETE FROM attendance WHERE id=?", [(int)$_POST['id']])) {
            flash('Record deleted.', 'danger');
        }
    }
    redirect('modules/attendance.php');
}

$students = $pdo->query("SELECT id,full_name,roll_no FROM students ORDER BY roll_no")->fetchAll();
$courses = $pdo->query("SELECT id,course_name FROM courses ORDER BY course_name")->fetchAll();
$attendance = $pdo->prepare("SELECT a.*, s.full_name, s.roll_no, c.course_name FROM attendance a JOIN students s ON a.student_id=s.id JOIN courses c ON a.course_id=c.id $where ORDER BY a.att_date DESC");
$attendance->execute($whereParams);
$attendance = $attendance->fetchAll();
?>
<?php
$active = 'attendance';
$page_title = 'AIML AcademicHub';
require_once __DIR__ . '/../includes/layout.php';
?>
<h2>Attendance</h2>
<?php show_flash(); ?>

<?php if ($role !== 'student'): ?>
<div class="card">
    <h3>Mark Attendance</h3>
        <form method="post" class="grid grid-4">
        <input type="hidden" name="action" value="add">
        <?= csrf_field() ?>
        <div class="form-group"><label>Student</label>
            <select name="student_id" required>
                <?php foreach ($students as $s): ?><option value="<?= $s['id'] ?>"><?= e($s['roll_no'].' - '.$s['full_name']) ?></option><?php endforeach; ?>
            </select>
        </div>
        <div class="form-group"><label>Course</label>
            <select name="course_id" required>
                <?php foreach ($courses as $c): ?><option value="<?= $c['id'] ?>"><?= e($c['course_name']) ?></option><?php endforeach; ?>
            </select>
        </div>
        <div class="form-group"><label>Date</label><input type="date" name="att_date" value="<?= date('Y-m-d') ?>" required></div>
        <div class="form-group"><label>Status</label>
            <select name="status"><option value="present">Present</option><option value="absent">Absent</option><option value="late">Late</option></select>
        </div>
        <div class="form-group" style="grid-column:1/-1"><button class="btn btn-primary" type="submit">Mark</button></div>
    </form>
</div>
<?php endif; ?>

<div class="card">
    <h3>Attendance Records (<?= count($attendance) ?>)</h3>
<<<<<<< HEAD
    <table>
=======
    <div class="table-wrap"><table>
>>>>>>> b6df4ad04a8aa0ab8bde74ecb2f0ff952f1c32f4
        <tr><th>Date</th><th>Roll No</th><th>Student</th><th>Course</th><th>Status</th><?php if ($role!=='student'): ?><th>Action</th><?php endif; ?></tr>
        <?php foreach ($attendance as $a): ?>
        <tr>
            <td><?= $a['att_date'] ?></td>
            <td><?= e($a['roll_no']) ?></td>
            <td><?= e($a['full_name']) ?></td>
            <td><?= e($a['course_name']) ?></td>
            <td><span class="badge badge-<?= $a['status']==='present'?'success':($a['status']==='late'?'warning':'danger') ?>"><?= ucfirst($a['status']) ?></span></td>
            <?php if ($role!=='student'): ?>
            <td><form method="post" style="display:inline">
    <input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $a['id'] ?>"><?= csrf_field() ?>
    <button type="submit" class="btn btn-sm btn-danger btn-delete" data-title="Delete?" data-msg="Delete?">Delete</button>
</form></td>
            <?php endif; ?>
        </tr>
        <?php endforeach; ?>
<<<<<<< HEAD
    </table>
=======
    </table></div>
>>>>>>> b6df4ad04a8aa0ab8bde74ecb2f0ff952f1c32f4
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>



