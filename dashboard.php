<?php
require_once 'config.php'; require_login();
if ($_SESSION['user']['role']==='admin') { header('Location: admin.php'); exit; }
$uid=$_SESSION['user']['id'];
$q=db()->prepare("SELECT status,COUNT(*) n FROM complaints WHERE student_id=? GROUP BY status"); $q->execute([$uid]); $counts=['Pending'=>0,'In Progress'=>0,'Resolved'=>0,'Closed'=>0]; foreach($q as $r)$counts[$r['status']]=$r['n'];
$q=db()->prepare("SELECT c.*,cat.name category FROM complaints c JOIN categories cat ON cat.id=c.category_id WHERE c.student_id=? ORDER BY c.created_at DESC");$q->execute([$uid]);$items=$q->fetchAll();
$page_title='Student Dashboard'; require 'partials/header.php';
?>
<div class="page-heading"><div><span class="eyebrow">Student workspace</span><h1>Hello, <?=h($_SESSION['user']['name'])?> 👋</h1><p class="muted">Here is an overview of your submitted complaints.</p></div><a class="btn primary" href="new_complaint.php">＋ New complaint</a></div>
<div class="stats">
<?php foreach(['Pending'=>'Waiting for review','In Progress'=>'Being reviewed','Resolved'=>'Resolution recorded','Closed'=>'Case closed'] as $s=>$desc): ?>
<div class="stat-card"><span><?=h($s)?></span><strong><?= (int)$counts[$s] ?></strong><small><?=h($desc)?></small></div>
<?php endforeach; ?>
</div>
<section class="panel"><div class="section-head"><div><h2>My complaints</h2><p class="muted">Track each case using its reference number.</p></div><a href="new_complaint.php" class="text-link">Submit a complaint →</a></div>
<?php if(!$items): ?><div class="empty"><div class="empty-icon">✉</div><h3>No complaints yet</h3><p>Your submitted complaints will appear here.</p><a class="btn primary" href="new_complaint.php">Submit your first complaint</a></div>
<?php else: ?><div class="table-wrap"><table><thead><tr><th>Reference</th><th>Subject</th><th>Category</th><th>Date</th><th>Status</th><th></th></tr></thead><tbody>
<?php foreach($items as $c): ?><tr><td class="code"><?=h($c['complaint_code'])?></td><td><?=h($c['subject'])?></td><td><?=h($c['category'])?></td><td><?=h(date('d M Y',strtotime($c['created_at'])))?></td><td><span class="status <?=strtolower(str_replace(' ','-',h($c['status'])))?>"><?=h($c['status'])?></span></td><td><a href="complaint.php?id=<?=(int)$c['id']?>">View</a></td></tr><?php endforeach; ?>
</tbody></table></div><?php endif; ?></section>
<?php require 'partials/footer.php'; ?>
