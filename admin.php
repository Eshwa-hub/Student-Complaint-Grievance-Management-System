<?php
require_once 'config.php';require_admin();
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf();$id=(int)($_POST['id']??0);$status=$_POST['status']??'';$priority=$_POST['priority']??'';$message=trim($_POST['message']??'');$resolution=trim($_POST['resolution']??'');
 if(!in_array($status,['Pending','In Progress','Resolved','Closed'],true)||!in_array($priority,['Low','Medium','High'],true)){flash('Invalid status or priority.','error');}
 else{
  $q=db()->prepare("UPDATE complaints SET status=?,priority=?,resolution=?,updated_at=NOW() WHERE id=?");$q->execute([$status,$priority,$resolution,$id]);
  if($message!==''){$q=db()->prepare("INSERT INTO responses(complaint_id,user_id,message) VALUES(?,?,?)");$q->execute([$id,$_SESSION['user']['id'],$message]);}
  flash('Complaint updated successfully.');
 }
 header('Location: admin.php');exit;
}
$counts=db()->query("SELECT status,COUNT(*) n FROM complaints GROUP BY status")->fetchAll();$stats=['Pending'=>0,'In Progress'=>0,'Resolved'=>0,'Closed'=>0];foreach($counts as $r)$stats[$r['status']]=$r['n'];
$filter=$_GET['status']??'';$sql="SELECT c.*,u.name student,cat.name category FROM complaints c JOIN users u ON u.id=c.student_id JOIN categories cat ON cat.id=c.category_id";$args=[];
if(in_array($filter,['Pending','In Progress','Resolved','Closed'],true)){$sql.=" WHERE c.status=?";$args[]=$filter;}
$sql.=" ORDER BY c.created_at DESC";$q=db()->prepare($sql);$q->execute($args);$items=$q->fetchAll();
$page_title='Admin Dashboard';require 'partials/header.php';
?>
<div class="page-heading"><div><span class="eyebrow">Administration</span><h1>Grievance overview</h1><p class="muted">Review student submissions and record progress.</p></div></div>
<div class="stats"><?php foreach($stats as $s=>$n):?><a class="stat-card stat-link" href="admin.php?status=<?=urlencode($s)?>"><span><?=h($s)?></span><strong><?=(int)$n?></strong><small>View cases →</small></a><?php endforeach;?></div>
<section class="panel"><div class="section-head"><div><h2>All complaints</h2><p class="muted"><?=count($items)?> case(s)<?= $filter?' · filtered by '.h($filter):''?></p></div><a class="text-link" href="admin.php">Clear filter</a></div>
<?php if(!$items):?><div class="empty"><h3>No complaints found</h3></div><?php else:?><div class="table-wrap"><table><thead><tr><th>Reference / Student</th><th>Subject</th><th>Category</th><th>Priority</th><th>Status</th><th>Manage</th></tr></thead><tbody>
<?php foreach($items as $c):?><tr><td><span class="code"><?=h($c['complaint_code'])?></span><small class="block"><?=h($c['student'])?></small></td><td><?=h($c['subject'])?></td><td><?=h($c['category'])?></td><td><?=h($c['priority'])?></td><td><span class="status <?=strtolower(str_replace(' ','-',h($c['status'])))?>"><?=h($c['status'])?></span></td><td><a href="complaint.php?id=<?=(int)$c['id']?>">Details</a></td></tr>
<tr class="manage-row"><td colspan="6"><form method="post" class="admin-form"><input type="hidden" name="csrf" value="<?=h(csrf_token())?>"><input type="hidden" name="id" value="<?=(int)$c['id']?>">
<select name="status"><?php foreach(['Pending','In Progress','Resolved','Closed'] as $s):?><option <?=$c['status']===$s?'selected':''?>><?=h($s)?></option><?php endforeach;?></select>
<select name="priority"><?php foreach(['Low','Medium','High'] as $p):?><option <?=$c['priority']===$p?'selected':''?>><?=h($p)?></option><?php endforeach;?></select>
<input name="message" placeholder="Add progress remark (optional)"><input name="resolution" placeholder="Resolution summary (optional)" value="<?=h($c['resolution'])?>"><button class="btn small primary">Save update</button></form></td></tr><?php endforeach;?>
</tbody></table></div><?php endif;?></section>
<?php require 'partials/footer.php';?>
