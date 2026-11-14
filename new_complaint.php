<?php
require_once 'config.php'; require_login();
if($_SESSION['user']['role']==='admin'){header('Location: admin.php');exit;}
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf();
 $cat=(int)($_POST['category_id']??0);$subject=trim($_POST['subject']??'');$desc=trim($_POST['description']??'');$priority=$_POST['priority']??'Medium';
 $valid=db()->prepare("SELECT id FROM categories WHERE id=?");$valid->execute([$cat]);
 if(!$valid->fetch()||$subject===''||$desc===''||!in_array($priority,['Low','Medium','High'],true))$error='Please complete all required fields correctly.';
 else{
  $code=complaint_code();$q=db()->prepare("INSERT INTO complaints(complaint_code,student_id,category_id,subject,description,priority,status) VALUES(?,?,?,?,?,?, 'Pending')");
  $q->execute([$code,$_SESSION['user']['id'],$cat,$subject,$desc,$priority]);flash("Complaint submitted successfully. Your reference is $code.");header('Location: dashboard.php');exit;
 }
}
$cats=db()->query("SELECT * FROM categories ORDER BY name")->fetchAll();$page_title='Submit Complaint';require 'partials/header.php';
?>
<div class="narrow"><a class="back" href="dashboard.php">← Back to dashboard</a><section class="panel"><span class="eyebrow">Tell us what happened</span><h1>Submit a complaint</h1><p class="muted">Provide clear details so the college team can review your concern.</p>
<?php if($error):?><div class="alert error"><?=h($error)?></div><?php endif;?>
<form method="post" class="form"><input type="hidden" name="csrf" value="<?=h(csrf_token())?>">
<label>Complaint category<select name="category_id" required><option value="">Select a category</option><?php foreach($cats as $c):?><option value="<?=(int)$c['id']?>"><?=h($c['name'])?></option><?php endforeach;?></select></label>
<label>Subject<input name="subject" maxlength="180" required placeholder="Briefly describe the issue"></label>
<label>Priority<select name="priority"><option>Low</option><option selected>Medium</option><option>High</option></select></label>
<label>Description<textarea name="description" rows="6" required placeholder="Explain the concern, including relevant dates or details."></textarea></label>
<div class="hint">Please do not include passwords or unnecessary sensitive personal information.</div>
<button class="btn primary full">Submit complaint</button></form></section></div>
<?php require 'partials/footer.php';?>
