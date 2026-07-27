<?php
// Sidebar - role based navigation
// $active passed from page to highlight current link
$role = $_SESSION['role'] ?? '';
$nav = [
    'dashboard' => ['icon' => '&#9750;', 'label' => 'Dashboard', 'file' => 'dashboard.php'],
];

$common = [
    'students'   => ['icon' => '&#9786;', 'label' => 'Student Management', 'file' => 'modules/students.php'],
    'faculty'    => ['icon' => '&#9733;', 'label' => 'Faculty Management', 'file' => 'modules/faculty.php'],
    'attendance' => ['icon' => '&#10003;', 'label' => 'Attendance', 'file' => 'modules/attendance.php'],
    'academics'  => ['icon' => '&#9998;', 'label' => 'Academics / Marks', 'file' => 'modules/marks.php'],
    'projects'   => ['icon' => '&#9881;', 'label' => 'Projects', 'file' => 'modules/projects.php'],
    'internships'=> ['icon' => '&#9889;', 'label' => 'Internships', 'file' => 'modules/internships.php'],
    'placements' => ['icon' => '&#9737;', 'label' => 'Placements', 'file' => 'modules/placements.php'],
    'research'   => ['icon' => '&#9879;', 'label' => 'Research', 'file' => 'modules/research.php'],
    'publications'=>['icon' => '&#9783;', 'label' => 'Publications', 'file' => 'modules/publications.php'],
    'patents'    => ['icon' => '&#9961;', 'label' => 'Patents', 'file' => 'modules/patents.php'],
    'events'     => ['icon' => '&#9788;', 'label' => 'Events', 'file' => 'modules/events.php'],
    'notices'    => ['icon' => '&#9752;', 'label' => 'Notices', 'file' => 'modules/notices.php'],
    'reports'    => ['icon' => '&#9636;', 'label' => 'Reports', 'file' => 'modules/reports.php'],
    'settings'   => ['icon' => '&#9881;', 'label' => 'Settings', 'file' => 'modules/settings.php'],
];

// Role specific module visibility
$roleNav = [
    'admin' => array_merge($nav, $common),
    'hod'   => array_merge($nav, $common),
    'faculty'=> array_merge($nav, [
        'students'   => $common['students'],
        'faculty'    => $common['faculty'],
        'attendance' => $common['attendance'],
        'academics'  => $common['academics'],
        'projects'   => $common['projects'],
        'internships'=> $common['internships'],
        'research'   => $common['research'],
        'publications'=>$common['publications'],
        'patents'    => $common['patents'],
        'events'     => $common['events'],
        'notices'    => $common['notices'],
        'reports'    => $common['reports'],
        'settings'   => $common['settings'],
    ]),
    'student'=> array_merge($nav, [
        'dashboard' => $nav['dashboard'],
        'students'   => $common['students'],
        'attendance' => $common['attendance'],
        'academics'  => $common['academics'],
        'projects'   => $common['projects'],
        'internships'=> $common['internships'],
        'events'     => $common['events'],
        'notices'    => $common['notices'],
        'settings'   => $common['settings'],
    ]),
    'tpo'   => array_merge($nav, [
        'dashboard' => $nav['dashboard'],
        'students'   => $common['students'],
        'internships'=> $common['internships'],
        'placements' => $common['placements'],
        'events'     => $common['events'],
        'notices'    => $common['notices'],
        'reports'    => $common['reports'],
        'settings'   => $common['settings'],
    ]),
];
$items = $roleNav[$role] ?? $nav;
?>
<aside class="sidebar" id="sidebar">
    <div class="brand">
        <img src="<?= base_url() ?>/assets/images/zeal-logo.png" alt="Logo">
        <div>
            <h3>AIML AcademicHub</h3>
            <small>Department Portal</small>
        </div>
    </div>
    <nav>
        <?php foreach ($items as $key => $it): ?>
            <a href="<?= base_url() ?>/<?= $it['file'] ?>" class="<?= ($active ?? '') === $key ? 'active' : '' ?>">
                <span class="ic"><?= $it['icon'] ?></span> <?= $it['label'] ?>
            </a>
        <?php endforeach; ?>
        <a href="<?= base_url() ?>/logout.php"><span class="ic">&#9211;</span> Logout</a>
    </nav>
</aside>
