<?php
$page_title = $page_title ?? 'Student Grievance Portal';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#142b49">
<title><?= h($page_title) ?> | Student Grievance Portal</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="topbar">
  <a class="brand" href="<?= logged_in() ? ($_SESSION['user']['role']==='admin'?'admin.php':'dashboard.php') : 'index.php' ?>" aria-label="Grievance Portal home">
    <span class="brand-emblem"><img src="assets/rcpet-logo.png" alt="RCPET educational trust emblem"></span>
    <span class="brand-copy">
      <span class="brand-title">Grievance<span class="brand-light">Portal</span></span>
      <span class="college-name">R.C.Patel Arts, Commerce, Science College Shirpur</span>
    </span>
  </a>
  <nav>
  <?php if (logged_in()): ?>
    <span class="nav-user"><?= h($_SESSION['user']['name']) ?></span>
    <a href="<?= $_SESSION['user']['role']==='admin'?'admin.php':'dashboard.php' ?>">Dashboard</a>
    <a href="logout.php">Logout</a>
  <?php else: ?>
    <a href="login.php">Login</a><a class="nav-cta" href="register.php">Register</a>
  <?php endif; ?>
  </nav>
</header>
<main class="container">
<?php show_flash(); ?>
