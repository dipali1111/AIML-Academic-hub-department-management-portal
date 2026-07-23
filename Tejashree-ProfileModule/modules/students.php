<?php
require_once __DIR__ . '/../includes/auth.php';
require_role(['admin','hod','faculty','student','tpo']);
require_once __DIR__ . '/../includes/functions.php';

$canManage = in_array($_SESSION['role'], ['admin','hod','faculty']);

// Handle add / edit / delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $canManage) {
    verify_csrf();
    $action = $_POST['action'];
    if ($action === 'add' || $action === 'edit') {
        $roll_no = trim($_POST['roll_no']);
        $full_name = trim($_POST['full_name']);
        $email = trim(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ?: '');
        $phone = trim($_POST['phone']);
        $year = (int)$_POST['year'];
        $semester = (int)$_POST['semester'];
        $division = trim($_POST['division']);
        $cgpa = (float)$_POST['cgpa'];
        $address = trim($_POST['address']);
        if ($roll_no === '' || $full_name === '' || $year < 1 || $year > 4 || $semester < 1 || $semester > 8 || $cgpa < 0 || $cgpa > 10) {
            flash('Invalid student data provided.', 'danger');
            redirect('modules/students.php');
        }
        if ($action === 'add') {
            if (safe_exec("INSERT INTO students (roll_no,full_name,email,phone,year,semester,division,cgpa,address) VALUES (?,?,?,?,?,?,?,?,?)",
                [$roll_no,$full_name,$email,$phone,$year,$semester,$division,$cgpa,$address])) {
                flash('Student added successfully.');
            }
        } else {
            $id = (int)$_POST['id'];
            if (safe_exec("UPDATE students SET roll_no=?,full_name=?,email=?,phone=?,year=?,semester=?,division=?,cgpa=?,address=? WHERE id=?",
                [$roll_no,$full_name,$email,$phone,$year,$semester,$division,$cgpa,$address,$id])) {
                flash('Student updated successfully.');
            }
        }
    } elseif ($action === 'delete') {
        if (safe_exec("DELETE FROM students WHERE id=?", [(int)$_POST['id']])) {
            flash('Student deleted.', 'danger');
        }
    }
    redirect('modules/students.php');
}

$edit = null;
if (isset($_GET['edit'])) {
    $edit = $pdo->prepare("SELECT * FROM students WHERE id=?");
    $edit->execute([(int)$_GET['edit']]);
    $edit = $edit->fetch();
}
$students = $pdo->query("SELECT * FROM students ORDER BY roll_no")->fetchAll();
?>
<?php
$active = 'students';
$page_title = 'AIML AcademicHub';
require_once __DIR__ . '/../includes/layout.php';
?>
<h2>Student Management</h2>
<?php show_flash(); ?>

<div class="card">
    <h3><?= $edit ? 'Edit Student' : 'Add Student' ?></h3>
    <form method="post" class="grid grid-3">
        <input type="hidden" name="action" value="<?= $edit ? 'edit' : 'add' ?>">
        <?= csrf_field() ?>
        <?php if ($edit): ?><input type="hidden" name="id" value="<?= $edit['id'] ?>"><?php endif; ?>
        <div class="form-group"><label>Roll No</label><input name="roll_no" value="<?= e($edit['roll_no'] ?? '') ?>" required></div>
        <div class="form-group"><label>Full Name</label><input name="full_name" value="<?= e($edit['full_name'] ?? '') ?>" required></div>
        <div class="form-group"><label>Email</label><input name="email" type="email" value="<?= e($edit['email'] ?? '') ?>"></div>
        <div class="form-group"><label>Phone</label><input name="phone" value="<?= e($edit['phone'] ?? '') ?>"></div>
        <div class="form-group"><label>Year</label><input name="year" type="number" min="1" max="4" value="<?= e($edit['year'] ?? '1') ?>"></div>
        <div class="form-group"><label>Semester</label><input name="semester" type="number" min="1" max="8" value="<?= e($edit['semester'] ?? '1') ?>"></div>
        <div class="form-group"><label>Division</label><input name="division" value="<?= e($edit['division'] ?? '') ?>"></div>
        <div class="form-group"><label>CGPA</label><input name="cgpa" type="number" step="0.01" min="0" max="10" value="<?= e($edit['cgpa'] ?? '0') ?>"></div>
        <div class="form-group" style="grid-column:1/-1"><label>Address</label><textarea name="address" rows="2"><?= e($edit['address'] ?? '') ?></textarea></div>
        <div class="form-group" style="grid-column:1/-1"><button class="btn btn-primary" type="submit"><?= $edit ? 'Update' : 'Add Student' ?></button>
        <?php if ($edit): ?><a href="<?= base_url() ?>/modules/students.php" class="btn btn-warning">Cancel</a><?php endif; ?></div>
    </form>
</div>

<div class="card">
    <h3>All Students (<?= count($students) ?>)</h3>
    <table>
        <tr><th>Roll No</th><th>Name</th><th>Email</th><th>Year/Sem</th><th>Div</th><th>CGPA</th><th>Actions</th></tr>
        <?php foreach ($students as $s): ?>
        <tr>
            <td><?= e($s['roll_no']) ?></td>
            <td><?= e($s['full_name']) ?></td>
            <td><?= e($s['email']) ?></td>
            <td><?= $s['year'] ?>/<?= $s['semester'] ?></td>
            <td><?= e($s['division']) ?></td>
            <td><?= $s['cgpa'] ?></td>
            <td>
                <a href="?edit=<?= $s['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                <form method="post" style="display:inline">
    <input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $s['id'] ?>"><?= csrf_field() ?>
    <button type="submit" class="btn btn-sm btn-danger btn-delete" data-title="Delete this record?" data-msg="Delete this student?">Delete</button>
</form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>




