<<<<<<< HEAD
﻿<?php
=======
<?php
>>>>>>> b6df4ad04a8aa0ab8bde74ecb2f0ff952f1c32f4
require_once __DIR__ . '/../includes/auth.php';
require_role(['admin','hod','faculty','tpo']);
require_once __DIR__ . '/../includes/functions.php';

$canManage = in_array($_SESSION['role'], ['admin','hod','faculty']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $canManage) {
    verify_csrf();
    $action = $_POST['action'];
    if ($action === 'add' || $action === 'edit') {
        $employee_id = trim($_POST['employee_id']);
        $full_name = trim($_POST['full_name']);
        $email = trim(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ?: '');
        $phone = trim($_POST['phone']);
<<<<<<< HEAD
        $designation = trim($_POST['designation']);
        $qualification = trim($_POST['qualification']);
        $joined_date = valid_date($_POST['joined_date'] ?? '');
        if ($action === 'add') {
            if (safe_exec("INSERT INTO faculty (employee_id,full_name,email,phone,designation,qualification,joined_date) VALUES (?,?,?,?,?,?,?)",
                [$employee_id,$full_name,$email,$phone,$designation,$qualification,$joined_date])) {
=======
        $alt_phone = trim($_POST['alt_phone']);
        $designation = trim($_POST['designation']);
        $qualification = trim($_POST['qualification']);
        $joined_date = valid_date($_POST['joined_date'] ?? '');
        $address = trim($_POST['address']);
        if ($action === 'add') {
            if (safe_exec("INSERT INTO faculty (employee_id,full_name,email,phone,alt_phone,designation,qualification,joined_date,address) VALUES (?,?,?,?,?,?,?,?,?)",
                [$employee_id,$full_name,$email,$phone,$alt_phone,$designation,$qualification,$joined_date,$address])) {
>>>>>>> b6df4ad04a8aa0ab8bde74ecb2f0ff952f1c32f4
                flash('Faculty added successfully.');
            }
        } else {
            $id = (int)$_POST['id'];
<<<<<<< HEAD
            if (safe_exec("UPDATE faculty SET employee_id=?,full_name=?,email=?,phone=?,designation=?,qualification=?,joined_date=? WHERE id=?",
                [$employee_id,$full_name,$email,$phone,$designation,$qualification,$joined_date,$id])) {
=======
            if (safe_exec("UPDATE faculty SET employee_id=?,full_name=?,email=?,phone=?,alt_phone=?,designation=?,qualification=?,joined_date=?,address=? WHERE id=?",
                [$employee_id,$full_name,$email,$phone,$alt_phone,$designation,$qualification,$joined_date,$address,$id])) {
>>>>>>> b6df4ad04a8aa0ab8bde74ecb2f0ff952f1c32f4
                flash('Faculty updated successfully.');
            }
        }
    } elseif ($action === 'delete') {
        if (safe_exec("DELETE FROM faculty WHERE id=?", [(int)$_POST['id']])) {
            flash('Faculty deleted.', 'danger');
        }
    }
    redirect('modules/faculty.php');
}

$edit = null;
if (isset($_GET['edit'])) {
    $edit = $pdo->prepare("SELECT * FROM faculty WHERE id=?");
    $edit->execute([(int)$_GET['edit']]);
    $edit = $edit->fetch();
}
$faculty = $pdo->query("SELECT * FROM faculty ORDER BY full_name")->fetchAll();
?>
<?php
$active = 'faculty';
$page_title = 'AIML AcademicHub';
require_once __DIR__ . '/../includes/layout.php';
?>
<h2>Faculty Management</h2>
<?php show_flash(); ?>

<div class="card">
    <h3><?= $edit ? 'Edit Faculty' : 'Add Faculty' ?></h3>
        <form method="post" class="grid grid-3">
        <input type="hidden" name="action" value="<?= $edit ? 'edit' : 'add' ?>">
        <?= csrf_field() ?>
        <?php if ($edit): ?><input type="hidden" name="id" value="<?= $edit['id'] ?>"><?php endif; ?>
        <div class="form-group"><label>Employee ID</label><input name="employee_id" value="<?= e($edit['employee_id'] ?? '') ?>" required></div>
        <div class="form-group"><label>Full Name</label><input name="full_name" value="<?= e($edit['full_name'] ?? '') ?>" required></div>
        <div class="form-group"><label>Email</label><input name="email" type="email" value="<?= e($edit['email'] ?? '') ?>"></div>
        <div class="form-group"><label>Phone</label><input name="phone" value="<?= e($edit['phone'] ?? '') ?>"></div>
<<<<<<< HEAD
        <div class="form-group"><label>Designation</label><input name="designation" value="<?= e($edit['designation'] ?? '') ?>"></div>
        <div class="form-group"><label>Qualification</label><input name="qualification" value="<?= e($edit['qualification'] ?? '') ?>"></div>
        <div class="form-group" style="grid-column:1/-1"><label>Joined Date</label><input name="joined_date" type="date" value="<?= e($edit['joined_date'] ?? '') ?>"></div>
=======
        <div class="form-group"><label>Alt Phone</label><input name="alt_phone" value="<?= e($edit['alt_phone'] ?? '') ?>"></div>
        <div class="form-group"><label>Designation</label><input name="designation" value="<?= e($edit['designation'] ?? '') ?>"></div>
        <div class="form-group"><label>Qualification</label><input name="qualification" value="<?= e($edit['qualification'] ?? '') ?>"></div>
        <div class="form-group" style="grid-column:1/-1"><label>Joined Date</label><input name="joined_date" type="date" value="<?= e($edit['joined_date'] ?? '') ?>"></div>
        <div class="form-group" style="grid-column:1/-1"><label>Address</label><textarea name="address" rows="2"><?= e($edit['address'] ?? '') ?></textarea></div>
>>>>>>> b6df4ad04a8aa0ab8bde74ecb2f0ff952f1c32f4
        <div class="form-group" style="grid-column:1/-1"><button class="btn btn-primary" type="submit"><?= $edit ? 'Update' : 'Add Faculty' ?></button>
        <?php if ($edit): ?><a href="<?= base_url() ?>/modules/faculty.php" class="btn btn-warning">Cancel</a><?php endif; ?></div>
    </form>
</div>

<div class="card">
    <h3>All Faculty (<?= count($faculty) ?>)</h3>
<<<<<<< HEAD
    <table>
        <tr><th>Emp ID</th><th>Name</th><th>Email</th><th>Designation</th><th>Qualification</th><th>Joined</th><th>Actions</th></tr>
=======
    <div class="table-wrap"><table>
        <tr><th>Emp ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Alt Phone</th><th>Designation</th><th>Qualification</th><th>Joined</th><th>Address</th><th>Actions</th></tr>
>>>>>>> b6df4ad04a8aa0ab8bde74ecb2f0ff952f1c32f4
        <?php foreach ($faculty as $f): ?>
        <tr>
            <td><?= e($f['employee_id']) ?></td>
            <td><?= e($f['full_name']) ?></td>
            <td><?= e($f['email']) ?></td>
<<<<<<< HEAD
            <td><?= e($f['designation']) ?></td>
            <td><?= e($f['qualification']) ?></td>
            <td><?= e($f['joined_date']) ?></td>
=======
            <td><?= e($f['phone']) ?></td>
            <td><?= e($f['alt_phone'] ?? '') ?></td>
            <td><?= e($f['designation']) ?></td>
            <td><?= e($f['qualification']) ?></td>
            <td><?= e($f['joined_date']) ?></td>
            <td><?= e(mb_strimwidth($f['address'] ?? '', 0, 40, '…')) ?></td>
>>>>>>> b6df4ad04a8aa0ab8bde74ecb2f0ff952f1c32f4
            <td>
                <a href="?edit=<?= $f['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                <form method="post" style="display:inline">
    <input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $f['id'] ?>"><?= csrf_field() ?>
    <button type="submit" class="btn btn-sm btn-danger btn-delete" data-title="Delete this record?" data-msg="Delete this faculty?">Delete</button>
</form>
            </td>
        </tr>
        <?php endforeach; ?>
<<<<<<< HEAD
    </table>
=======
    </table></div>
>>>>>>> b6df4ad04a8aa0ab8bde74ecb2f0ff952f1c32f4
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>




