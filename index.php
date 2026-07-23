<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

// Fetch landing data
$notices = $pdo->query("SELECT * FROM notices ORDER BY created_at DESC LIMIT 3")->fetchAll();
$events  = $pdo->query("SELECT * FROM events ORDER BY event_date ASC LIMIT 3")->fetchAll();

function svgIcon($path, $sw = 2) {
    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="' . $sw . '" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $path . '</svg>';
}
$faculty = [
    ['name' => 'Dr. Dipali Shende',              'initial' => 'DS', 'color' => '#1d4ed8', 'photo' => 'Dr. Dipali Shende.png', 'desc' => 'Associate Professor & Head of Department at Zeal College of Engineering and Research. Former Associate Dean — Quality and Assurance. Savitribai Phule Pune University.'],
    ['name' => 'Prof. Pushkraj Ravindra Sonalkar','initial' => 'PR', 'color' => '#0891b2', 'photo' => 'prof. pushkraj ravindra sonalkar.jpeg'],
    ['name' => 'Prof. Rutuja Dhole',             'initial' => 'RD', 'color' => '#059669', 'photo' => 'prof. rutuja dhole.jpeg'],
    ['name' => 'Prof. Surbhi Suryawanshi',       'initial' => 'SS', 'color' => '#d97706', 'photo' => 'prof. surbhi suryawanshi.jpeg'],
    ['name' => 'Prof. Omkar Wadekar',            'initial' => 'OW', 'color' => '#2563eb', 'photo' => 'prof. omkar wadekar.jpeg'],
    ['name' => 'Prof. Shrutika Soudagar',        'initial' => 'Sd', 'color' => '#9333ea', 'photo' => 'prof.shrutika soudagar .jpeg'],
    ['name' => 'Prof. Venkatesh Shinde',         'initial' => 'VS', 'color' => '#16a34a', 'photo' => 'prof. venkatesh santosh shinde .jpeg'],
    ['name' => 'Prof. Manjiri Chumble',          'initial' => 'MC', 'color' => '#7c3aed', 'photo' => 'prof. manjiri chumble.jpeg'],
    ['name' => 'Prof. Kajal Guddab',             'initial' => 'KG', 'color' => '#dc2626', 'photo' => 'prof. kajal guddab.jpeg'],
    ['name' => 'Prof. Dipika Bhat',              'initial' => 'DB', 'color' => '#0d9488', 'photo' => 'prof.  dipika bhat.jpeg'],
];

$stats = [
    'students'   => 500,
    'faculty'    => 50,
    'projects'   => get_stats('projects'),
    'placements' => $pdo->query("SELECT COUNT(*) c FROM placements WHERE offer_status='selected'")->fetch()['c'],
];
$publications = get_stats('publications');
$patents       = get_stats('patents');

function prioClass($p) {
    return $p === 'urgent' ? 'urgent' : ($p === 'important' ? 'important' : 'normal');
}
function monthShort($d) {
    return date('M', strtotime($d));
}
function dayNum($d) {
    return date('d', strtotime($d));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zeal Institute of Technology — Department of AI & Machine Learning</title>
    <meta name="description" content="Official departmental portal for Artificial Intelligence & Machine Learning at Zeal Institute of Technology — academic records, research, placements and more.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/css/style.css">
</head>
<body>

<!-- ===================== PAGE LOADER ===================== -->
<div class="page-loader" id="pageLoader">
    <img class="loader-logo" src="<?= base_url() ?>/assets/images/zeal-logo.png" alt="Zeal Institute of Technology">
    <div class="loader-bar-wrap"><div class="loader-bar"></div></div>
</div>

<!-- ===================== NAVBAR ===================== -->
<header class="site-header" id="siteHeader">
    <div class="container">
        <a href="<?= base_url() ?>/index.php" class="site-logo" aria-label="Zeal Institute home">
            <img src="<?= base_url() ?>/assets/images/zeal-logo.png" alt="Zeal Institute of Technology">
        </a>
        <nav class="site-nav" id="siteNav">
            <a href="#about">About</a>
            <a href="#features">Features</a>
            <a href="#faculty">Faculty</a>
            <a href="#updates">Updates</a>
            <a href="<?= base_url() ?>/login.php" class="nav-cta">Sign In</a>
        </nav>
        <button class="nav-toggle" id="navToggle" aria-label="Toggle menu">&#9776;</button>
    </div>
</header>

<!-- ===================== HERO ===================== -->
<section class="hero-section">
    <div class="hero-bg">
        <video autoplay loop muted playsinline src="<?= base_url() ?>/assets/videos/hero-video.mp4"></video>
        <div class="hero-overlay"></div>
    </div>
    <div class="container">
        <div class="hero-content">
            <span class="hero-badge">Department of Artificial Intelligence &amp; Machine Learning</span>
            <h1>Empowering the next generation of <span class="text-gradient">AI innovators</span></h1>
            <p class="hero-desc">A unified digital ecosystem for students, faculty, and industry partners — bridging academic excellence with real-world impact.</p>
            <div class="hero-actions">
                <a href="<?= base_url() ?>/login.php" class="btn btn-primary btn-arrow">Access Your Dashboard &rarr;</a>
                <a href="#features" class="btn btn-outline">Explore Platform</a>
            </div>
            <div class="hero-trust">
                <div class="trust-avatars">
                    <span>A</span><span>F</span><span>S</span><span>T</span>
                </div>
                <p>Trusted by <strong><?= $stats['faculty'] ?>+ faculty</strong> and <strong><?= $stats['students'] ?>+ students</strong></p>
            </div>
        </div>
    </div>
</section>

<!-- ===================== STATS ===================== -->
<section class="stats-bar">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item">
                <span class="stat-num count" data-to="<?= $stats['students'] ?>" data-suffix="+">0</span>
                <span class="stat-label">Students Enrolled</span>
            </div>
            <div class="stat-item">
                <span class="stat-num count" data-to="<?= $stats['faculty'] ?>" data-suffix="+">0</span>
                <span class="stat-label">Faculty Members</span>
            </div>
            <div class="stat-item">
                <span class="stat-num count" data-to="<?= $stats['projects'] ?>">0</span>
                <span class="stat-label">Active Projects</span>
            </div>
            <div class="stat-item">
                <span class="stat-num count" data-to="<?= $stats['placements'] ?>">0</span>
                <span class="stat-label">Successful Placements</span>
            </div>
        </div>
    </div>
</section>

<!-- ===================== ABOUT ===================== -->
<section class="section" id="about">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">About the Department</span>
            <h2>Where <span class="text-gradient">innovation</span> meets academic rigour</h2>
            <p class="section-desc">The AIML department at Zeal Institute of Technology is dedicated to producing industry-ready graduates through a blend of rigorous coursework, hands-on projects, and cutting-edge research.</p>
        </div>
        <div class="about-grid">
            <div class="about-card">
                <div class="about-icon"><?= svgIcon('<path d="M4 7h16M4 12h16M4 17h10"/>', 1.8) ?></div>
                <h3>Paperless Ecosystem</h3>
                <p>Every academic record — marks, projects, internships, patents — lives in one secure, auditable platform.</p>
                <span class="about-tag">NBA · NAAC · AICTE ready</span>
            </div>
            <div class="about-card accent">
                <div class="about-icon"><?= svgIcon('<path d="M3 3v18h18"/><path d="M7 14l3-3 3 3 5-6"/>', 1.8) ?></div>
                <h3>Real-time Intelligence</h3>
                <p>Live dashboards surface attendance trends, course performance and placement momentum.</p>
                <span class="about-tag">Data-driven decisions</span>
            </div>
            <div class="about-card">
                <div class="about-icon"><?= svgIcon('<rect x="5" y="11" width="14" height="9" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/>', 1.8) ?></div>
                <h3>Enterprise Security</h3>
                <p>CSRF-protected, parameterized queries with role-based access control keep data safe.</p>
                <span class="about-tag">Zero-trust architecture</span>
            </div>
            <div class="about-card">
                <div class="about-icon"><?= svgIcon('<path d="M21 15a2 2 0 0 1-2 2H8l-4 4V5a2 2 0 0 1 2-2h13a2 2 0 0 1 2 2z"/>', 1.8) ?></div>
                <h3>Instant Communication</h3>
                <p>Priority-tagged notices ensure urgent announcements reach the right audience immediately.</p>
                <span class="about-tag">Real-time alerts</span>
            </div>
            <div class="about-card">
                <div class="about-icon"><?= svgIcon('<path d="M3 21h18"/><path d="M5 21V10l7-5 7 5v11"/><path d="M9 21v-6h6v6"/>', 1.8) ?></div>
                <h3>Research &amp; Innovation</h3>
                <p>Track publications, patents and funded research from proposal through to impact.</p>
                <span class="about-tag">Innovation pipeline</span>
            </div>
        </div>
    </div>
</section>

<!-- ===================== FEATURES ===================== -->
<section class="section section-alt" id="features">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Platform Modules</span>
            <h2>Everything your department <span class="text-gradient">runs on</span></h2>
            <p class="section-desc">Five powerful modules designed to streamline every aspect of departmental administration.</p>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon" style="--fc:#3b82f6"><?= svgIcon('<path d="M4 5h16v14H4z"/><path d="M4 9h16"/><path d="M8 13h4M8 16h8"/>', 1.8) ?></div>
                <h3>Academic Records</h3>
                <p>Comprehensive marks and attendance tracking with per-student analytics and course-wide insights.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="--fc:#8b5cf6"><?= svgIcon('<path d="M22 10v6"/><path d="M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 2 9 2 12 0v-5"/>', 1.8) ?></div>
                <h3>Projects &amp; Research</h3>
                <p>Mentored project tracking, funded research, publications and patents in one unified pipeline.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="--fc:#10b981"><?= svgIcon('<rect x="3" y="7" width="18" height="13" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>', 1.8) ?></div>
                <h3>Placements &amp; Internships</h3>
                <p>Drive management, company pipeline, offer tracking and student placement readiness.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="--fc:#f59e0b"><?= svgIcon('<path d="M3 11l18-8-8 18-2-8z"/>', 1.8) ?></div>
                <h3>Notices &amp; Events</h3>
                <p>Priority-tagged announcements and an integrated calendar keeping everyone informed.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="--fc:#ef4444"><?= svgIcon('<circle cx="12" cy="8" r="4"/><path d="M4 21v-1a6 6 0 0 1 12 0v1"/>', 1.8) ?></div>
                <h3>Role-based Access</h3>
                <p>Five distinct personas — each with a tailored workspace and precisely scoped permissions.</p>
            </div>
        </div>
    </div>
</section>

<!-- ===================== FACULTY ===================== -->
<section class="section section-alt" id="faculty">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Our Faculty</span>
            <h2>Meet the <span class="text-gradient">team</span></h2>
            <p class="section-desc">Dedicated educators and researchers shaping the next generation of AI &amp; ML professionals.</p>
        </div>
        <div class="faculty-grid">
            <?php foreach ($faculty as $i => $f):
                $isHod = ($i === 0);
            ?>
            <div class="faculty-card <?= $isHod ? 'featured-hod' : '' ?> reveal">
                <div class="faculty-img" style="--fc: <?= $f['color'] ?>">
                    <?php if (!empty($f['photo'])): ?>
                    <img class="faculty-photo" src="<?= base_url() ?>/assets/images/faculty/<?= e($f['photo']) ?>" alt="<?= e($f['name']) ?>">
                    <?php endif; ?>
                    <span class="faculty-initial"><?= $f['initial'] ?></span>
                    <?php if ($isHod): ?>
                    <span class="hod-badge">Head of Department</span>
                    <?php endif; ?>
                </div>
                <div class="faculty-info">
                    <h3><?= e($f['name']) ?></h3>
                    <?php if ($isHod && !empty($f['desc'])): ?>
                    <p class="hod-desc"><?= e($f['desc']) ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ===================== NOTICES + EVENTS ===================== -->
<section class="section section-alt" id="updates">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Latest Updates</span>
            <h2>Stay <span class="text-gradient">informed</span></h2>
            <p class="section-desc">Department notices and upcoming events at a glance.</p>
        </div>
        <div class="updates-grid">
            <div class="updates-col">
                <h3 class="updates-heading">
                    <?= svgIcon('<path d="M3 11l18-8-8 18-2-8z"/>', 1.8) ?>
                    Notices
                </h3>
                <?php if (empty($notices)): ?>
                    <div class="update-card"><p>No notices yet. Check back soon.</p></div>
                <?php else: foreach ($notices as $n): ?>
                <div class="update-card">
                    <div class="update-meta">
                        <span class="priority-badge <?= prioClass($n['priority']) ?>"><?= ucfirst($n['priority']) ?></span>
                        <span class="update-date"><?= date('d M Y', strtotime($n['created_at'])) ?></span>
                    </div>
                    <h4><?= e($n['title']) ?></h4>
                    <p><?= e(mb_strimwidth($n['content'], 0, 120, '…')) ?></p>
                </div>
                <?php endforeach; endif; ?>
            </div>
            <div class="updates-col">
                <h3 class="updates-heading">
                    <?= svgIcon('<path d="M8 2v4M16 2v4M3 10h18M5 4h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/>', 1.8) ?>
                    Upcoming Events
                </h3>
                <?php if (empty($events)): ?>
                    <div class="update-card"><p>No events scheduled. New events will appear here.</p></div>
                <?php else: foreach ($events as $ev): ?>
                <div class="update-card event-card">
                    <div class="event-date">
                        <span class="event-day"><?= dayNum($ev['event_date']) ?></span>
                        <span class="event-month"><?= monthShort($ev['event_date']) ?></span>
                    </div>
                    <div class="event-details">
                        <h4><?= e($ev['title']) ?></h4>
                        <p><?= e(mb_strimwidth($ev['description'] ?? '', 0, 90, '…')) ?></p>
                        <span class="event-venue"><?= e($ev['venue'] ?? 'TBA') ?></span>
                    </div>
                </div>
                <?php endforeach; endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- ===================== CTA ===================== -->
<section class="section cta-section">
    <div class="container">
        <div class="cta-card">
            <h2>Ready to step into your dashboard?</h2>
            <p>Sign in with your department credentials to access your personalised workspace.</p>
            <a href="<?= base_url() ?>/login.php" class="btn btn-primary btn-arrow">Sign In &rarr;</a>
        </div>
    </div>
</section>

<!-- ===================== FOOTER ===================== -->
<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <img src="<?= base_url() ?>/assets/images/zeal-logo.png" alt="Zeal Institute">
                <p>Department of Artificial Intelligence &amp; Machine Learning. A secure, paperless academic ecosystem connecting students, faculty and the placement cell.</p>
            </div>
            <div class="footer-col">
                <h4>Platform</h4>
                <ul>
                    <li><a href="#features">Academics</a></li>
                    <li><a href="#features">Research</a></li>
                    <li><a href="#features">Placements</a></li>
                    <li><a href="#faculty">Faculty</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Department</h4>
                <ul>
                    <li><a href="#about">About</a></li>
                    <li><a href="#updates">Updates</a></li>
                    <li><a href="#updates">Events</a></li>
                    <li><a href="<?= base_url() ?>/login.php">Sign In</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Contact</h4>
                <ul>
                    <li><a href="mailto:zcoer@zealeducation.com">zcoer@zealeducation.com</a></li>
                    <li><a href="tel:+917558666663">+91 7558666663</a></li>
                    <li>Institute Campus, Pune</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <span>&copy; 2026 AIML AcademicHub. All rights reserved.</span>
            <span><a href="#">Privacy Policy</a> &middot; <a href="#">Terms of Use</a></span>
        </div>
    </div>
</footer>

<script>
(function() {
    'use strict';

    // --- Nav scroll ---
    var header = document.getElementById('siteHeader');
    function onScroll() {
        if (window.scrollY > 20) header.classList.add('scrolled');
        else header.classList.remove('scrolled');
    }
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

    // --- Mobile nav toggle ---
    var toggle = document.getElementById('navToggle');
    var nav = document.getElementById('siteNav');
    if (toggle) {
        toggle.addEventListener('click', function() {
            nav.classList.toggle('open');
            toggle.innerHTML = nav.classList.contains('open') ? '&#10005;' : '&#9776;';
        });
        nav.querySelectorAll('a').forEach(function(a) {
            a.addEventListener('click', function() {
                nav.classList.remove('open');
                toggle.innerHTML = '&#9776;';
            });
        });
    }

    // --- Reveal on scroll ---
    var reveals = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window) {
        var io = new IntersectionObserver(function(entries) {
            entries.forEach(function(en) {
                if (en.isIntersecting) {
                    en.target.classList.add('in');
                    io.unobserve(en.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -8% 0px' });
        reveals.forEach(function(el) { io.observe(el); });
    } else {
        reveals.forEach(function(el) { el.classList.add('in'); });
    }

    // --- Count-up stats ---
    var counters = document.querySelectorAll('.count');
    function animateCount(el) {
        var to = parseInt(el.getAttribute('data-to'), 10) || 0;
        var suffix = el.getAttribute('data-suffix') || '';
        if (to === 0) { el.textContent = '0' + suffix; return; }
        var dur = 1100, start = null;
        function step(ts) {
            if (!start) start = ts;
            var p = Math.min((ts - start) / dur, 1);
            var eased = 1 - Math.pow(1 - p, 3);
            el.textContent = Math.round(eased * to).toLocaleString('en-IN');
            if (p < 1) requestAnimationFrame(step);
            else el.textContent = Math.round(to).toLocaleString('en-IN') + suffix;
        }
        requestAnimationFrame(step);
    }
    if ('IntersectionObserver' in window && counters.length) {
        var co = new IntersectionObserver(function(entries) {
            entries.forEach(function(en) {
                if (en.isIntersecting) { animateCount(en.target); co.unobserve(en.target); }
            });
        }, { threshold: 0.5 });
        counters.forEach(function(el) { co.observe(el); });
    } else {
        counters.forEach(function(el) {
            var val = parseInt(el.getAttribute('data-to'), 10) || 0;
            var suffix = el.getAttribute('data-suffix') || '';
            el.textContent = val.toLocaleString('en-IN') + suffix;
        });
    }
})();

// --- Page loader ---
(function(){
    var loader = document.getElementById('pageLoader');
    if (!loader) return;
    var start = Date.now();
    function hide() {
        var elapsed = Date.now() - start;
        var delay = Math.max(0, 700 - elapsed);
        setTimeout(function(){ loader.classList.add('hidden'); }, delay);
    }
    if (document.readyState === 'complete') hide();
    else window.addEventListener('load', hide);
})();
</script>
</body>
</html>
