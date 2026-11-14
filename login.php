<?php
require_once 'config.php';
if (logged_in()) { header('Location: '.($_SESSION['user']['role']==='admin'?'admin.php':'dashboard.php')); exit; }
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
 verify_csrf();
 $email=strtolower(trim($_POST['email']??'')); $password=$_POST['password']??'';
 $q=db()->prepare("SELECT id,name,email,password,role FROM users WHERE email=? LIMIT 1"); $q->execute([$email]); $u=$q->fetch();
 if ($u && password_verify($password,$u['password'])) {
  session_regenerate_id(true); unset($u['password']); $_SESSION['user']=$u;
  header('Location: '.($u['role']==='admin'?'admin.php':'dashboard.php')); exit;
 } else $error='Incorrect email or password.';
}
$page_title='Login'; require 'partials/header.php';
?>
<div class="auth-wrap"><section class="panel auth-panel"><span class="eyebrow">Welcome back</span><h1>Sign in</h1><p class="muted">Access your grievance dashboard.</p>
<?php if($error): ?><div class="alert error"><?=h($error)?></div><?php endif; ?>
<form method="post" class="form"><input type="hidden" name="csrf" value="<?=h(csrf_token())?>">
<label>Email address<input type="email" name="email" required></label>
<label>Password<input type="password" name="password" required></label>
<button class="btn primary full">Login</button></form>
<p class="form-foot">New student? <a href="register.php">Create an account</a></p>
<p class="hint">Admin accounts are created separately using the setup instructions in README.txt.</p>
</section></div>
<?php require 'partials/footer.php'; ?>
