<?php
require_once __DIR__ . '/../includes/auth.php';
require_role(['admin','hod','faculty','student']);
require_once __DIR__ . '/../includes/functions.php';

$role = $_SESSION['role'];
$user = current_user();
$where = '';
$whereParams = [];
if ($role === 'student') {
    $sidStmt = $pdo->prepare("SELECT id FROM students WHERE user_id=?");
    $sidStmt->execute([$user['id']]);
    $sid = $sidStmt->fetch()['id'] ?? 0;
    $where = "WHERE m.student_id=?";
    $whereParams = [$sid];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $role !== 'student') {
    verify_csrf();
    if ($_POST['action'] === 'add') {
        $exam_type = enum_val($_POST['exam_type'], ['internal','external','assignment']);
        $max_marks = (float)($_POST['max_marks'] ?? 0);
        if ($exam_type === '' || $max_marks <= 0) {
            flash('Invalid exam type or max marks must be greater than zero.', 'danger');
        } elseif (safe_exec("INSERT INTO marks (student_id,course_id,exam_type,marks_obtained,max_marks) VALUES (?,?,?,?,?)",
            [(int)$_POST['student_id'],(int)$_POST['course_id'],$exam_type,(float)$_POST['marks_obtained'],$max_marks])) {
            flash('Marks added.');
        }
    } elseif ($_POST['action'] === 'delete') {
        if (safe_exec("DELETE FROM marks WHERE id=?", [(int)$_POST['id']])) {
            flash('Record deleted.', 'danger');
        }
    }
    redirect('modules/marks.php');
}

$students = $pdo->query("SELECT id,full_name,roll_no FROM students ORDER BY roll_no")->fetchAll();
$courses = $pdo->query("SELECT id,course_name FROM courses ORDER BY course_name")->fetchAll();
$marksStmt = $pdo->prepare("SELECT m.*, s.full_name, s.roll_no, c.course_name FROM marks m JOIN students s ON m.student_id=s.id JOIN courses c ON m.course_id=c.id $where ORDER BY s.roll_no");
$marksStmt->execute($whereParams);
$marks = $marksStmt->fetchAll();
?>
<?php
$active = 'academics';
$page_title = 'AIML AcademicHub';
require_once __DIR__ . '/../includes/layout.php';
?>
<h2>Academics / Marks</h2>
<?php show_flash(); ?>

<?php if ($role !== 'student'): ?>
<div class="card">
    <h3>Add Marks</h3>
        <form method="post" class="grid grid-3">
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
        <div class="form-group"><label>Exam Type</label>
            <select name="exam_type"><option value="internal">Internal</option><option value="external">External</option><option value="assignment">Assignment</option></select>
        </div>
        <div class="form-group"><label>Marks Obtained</label><input type="number" step="0.01" name="marks_obtained" required></div>
        <div class="form-group"><label>Max Marks</label><input type="number" step="0.01" name="max_marks" value="100" required></div>
        <div class="form-group" style="grid-column:1/-1"><button class="btn btn-primary" type="submit">Add Marks</button></div>
    </form>
</div>
<?php endif; ?>

<div class="card">
    <h3>Marks Records (<?= count($marks) ?>)</h3>
    <div class="table-wrap"><table>
        <tr><th>Roll No</th><th>Student</th><th>Course</th><th>Exam</th><th>Obtained</th><th>Max</th><th>%</th><?php if ($role!=='student'): ?><th>Action</th><?php endif; ?></tr>
        <?php foreach ($marks as $m): ?>
        <tr>
            <td><?= e($m['roll_no']) ?></td>
            <td><?= e($m['full_name']) ?></td>
            <td><?= e($m['course_name']) ?></td>
            <td><?= ucfirst($m['exam_type']) ?></td>
            <td><?= $m['marks_obtained'] ?></td>
            <td><?= $m['max_marks'] ?></td>
            <td><?= $m['max_marks']>0 ? round($m['marks_obtained']/$m['max_marks']*100,1) : 0 ?>%</td>
            <?php if ($role!=='student'): ?>
            <td><form method="post" style="display:inline">
    <input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $m['id'] ?>"><?= csrf_field() ?>
    <button type="submit" class="btn btn-sm btn-danger btn-delete" data-title="Delete?" data-msg="Delete?">Delete</button>
</form></td>
            <?php endif; ?>
        </tr>
        <?php endforeach; ?>
    </table></div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>



