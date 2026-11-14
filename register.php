<?php
require_once 'config.php';
if (logged_in()) { header('Location: dashboard.php'); exit; }
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
 verify_csrf();
 $name=trim($_POST['name']??''); $email=strtolower(trim($_POST['email']??''));
 $phone=trim($_POST['phone']??''); $course=trim($_POST['course']??'');
 $password=$_POST['password']??'';
 if ($name==='' || !filter_var($email,FILTER_VALIDATE_EMAIL) || strlen($password)<8) $error='Enter a name, valid email and password of at least 8 characters.';
 else {
  try {
   $q=db()->prepare("INSERT INTO users(name,email,phone,course,password,role) VALUES(?,?,?,?,?,'student')");
   $q->execute([$name,$email,$phone,$course,password_hash($password,PASSWORD_DEFAULT)]);
   flash('Registration successful. Please log in.'); header('Location: login.php'); exit;
  } catch(PDOException $e) { $error='This email may already be registered. Please try another.'; }
 }
}
$page_title='Student Registration'; require 'partials/header.php';
?>
<div class="auth-wrap"><section class="panel auth-panel"><span class="eyebrow">Create your account</span><h1>Student registration</h1><p class="muted">Register to submit and track college grievances.</p>
<?php if($error): ?><div class="alert error"><?=h($error)?></div><?php endif; ?>
<form method="post" class="form">
<input type="hidden" name="csrf" value="<?=h(csrf_token())?>">
<label>Full name<input name="name" required maxlength="120" value="<?=h($_POST['name']??'')?>"></label>
<label>Email address<input type="email" name="email" required value="<?=h($_POST['email']??'')?>"></label>
<label>Phone number <span class="muted">(optional)</span><input name="phone" value="<?=h($_POST['phone']??'')?>"></label>
<label>Course / Class<input name="course" value="<?=h($_POST['course']??'')?>"></label>
<label>Password<input type="password" name="password" minlength="8" required></label>
<button class="btn primary full">Create account</button>
</form><p class="form-foot">Already registered? <a href="login.php">Sign in</a></p></section></div>
<?php require 'partials/footer.php'; ?>
