<?php
require_once __DIR__ . '/../includes/auth.php';
require_login();
require_once __DIR__ . '/../includes/functions.php';

$user = current_user();
$msg = '';

// Admin can manage users
$isAdmin = $_SESSION['role'] === 'admin';
if ($isAdmin && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['manage_user'])) {
    verify_csrf();
    $action = $_POST['manage_user'];
    $validRoles = ['admin','hod','faculty','student','tpo'];
    if ($action === 'add_user') {
        $username = trim($_POST['username']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role = in_array($_POST['role'], $validRoles) ? $_POST['role'] : 'student';
        $full_name = trim($_POST['full_name']);
        $email = trim(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ?: '');
        if (safe_exec("INSERT INTO users (username,password,role,full_name,email) VALUES (?,?,?,?,?)",
            [$username,$password,$role,$full_name,$email])) {
            $msg = 'User added.';
        }
    } elseif ($action === 'toggle') {
        if (safe_exec("UPDATE users SET status = 1-status WHERE id=?", [(int)$_POST['id']])) {
            $msg = 'User status updated.';
        }
    } elseif ($action === 'delete') {
        if (safe_exec("DELETE FROM users WHERE id=?", [(int)$_POST['id']])) {
            $msg = 'User deleted.';
        }
    }
    redirect('modules/settings.php');
}

// Profile update (own)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    verify_csrf();
    $full_name = trim($_POST['full_name']);
    $email = trim(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ?: '');
    $phone = trim($_POST['phone']);
    $pdo->prepare("UPDATE users SET full_name=?,email=?,phone=? WHERE id=?")
        ->execute([$full_name,$email,$phone,$_SESSION['user_id']]);
    $_SESSION['full_name'] = $full_name;
    $msg = 'Profile updated.';
    $user = current_user();
}
$users = $isAdmin ? $pdo->query("SELECT * FROM users ORDER BY role, full_name")->fetchAll() : [];
?>
<?php
$active = 'settings';
$page_title = 'AIML AcademicHub';
require_once __DIR__ . '/../includes/layout.php';
?>
<h2>Settings & Profile</h2>
<?php if ($msg): ?><div class="alert alert-success"><?= e($msg) ?></div><?php endif; ?>

<div class="card">
    <h3>My Profile</h3>
    <form method="post" class="grid grid-2">
        <input type="hidden" name="update_profile" value="1">
        <?= csrf_field() ?>
        <div class="form-group"><label>Full Name</label><input name="full_name" value="<?= e($user['full_name']) ?>" required></div>
        <div class="form-group"><label>Username</label><input value="<?= e($user['username']) ?>" disabled></div>
        <div class="form-group"><label>Email</label><input name="email" type="email" value="<?= e($user['email']) ?>"></div>
        <div class="form-group"><label>Phone</label><input name="phone" value="<?= e($user['phone']) ?>"></div>
        <div class="form-group" style="grid-column:1/-1"><button class="btn btn-primary" type="submit">Update Profile</button></div>
    </form>
</div>

<?php if ($isAdmin): ?>
<div class="card">
    <h3>Add User</h3>
    <form method="post" class="grid grid-3">
        <input type="hidden" name="manage_user" value="add_user">
        <?= csrf_field() ?>
        <div class="form-group"><label>Username</label><input name="username" required></div>
        <div class="form-group"><label>Password</label><input name="password" required></div>
        <div class="form-group"><label>Role</label>
            <select name="role">
                <option value="admin">Admin</option>
                <option value="hod">HOD</option>
                <option value="faculty">Faculty</option>
                <option value="student">Student</option>
                <option value="tpo">TPO</option>
            </select>
        </div>
        <div class="form-group"><label>Full Name</label><input name="full_name" required></div>
        <div class="form-group"><label>Email</label><input name="email" type="email"></div>
        <div class="form-group" style="grid-column:1/-1"><button class="btn btn-primary" type="submit">Add User</button></div>
    </form>
</div>

<div class="card">
    <h3>All Users</h3>
    <table>
        <tr><th>Username</th><th>Name</th><th>Role</th><th>Email</th><th>Status</th><th>Actions</th></tr>
        <?php foreach ($users as $u): ?>
        <tr>
            <td><?= e($u['username']) ?></td>
            <td><?= e($u['full_name']) ?></td>
            <td><span class="badge badge-info"><?= role_label($u['role']) ?></span></td>
            <td><?= e($u['email']) ?></td>
            <td><?= $u['status']?'<span class="badge badge-success">Active</span>':'<span class="badge badge-danger">Inactive</span>' ?></td>
            <td>
                <form method="post" style="display:inline">
                    <input type="hidden" name="manage_user" value="toggle"><input type="hidden" name="id" value="<?= $u['id'] ?>"><?= csrf_field() ?>
                    <button class="btn btn-sm btn-warning" type="submit">Toggle</button>
                </form>
                <?php if ($u['id'] != $_SESSION['user_id']): ?>
                <form method="post" style="display:inline">
                    <input type="hidden" name="manage_user" value="delete"><input type="hidden" name="id" value="<?= $u['id'] ?>"><?= csrf_field() ?>
                    <button type="submit" class="btn btn-sm btn-danger btn-delete" data-title="Delete user?" data-msg="This will permanently remove the user.">Delete</button>
                </form>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
<?php endif; ?>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>


