<?php
require_once 'config.php';
require_login();

$id = (int)($_GET['id'] ?? 0);

$q = db()->prepare("
    SELECT c.*, cat.name AS category, u.name AS student_name
    FROM complaints c
    JOIN categories cat ON cat.id = c.category_id
    JOIN users u ON u.id = c.student_id
    WHERE c.id = ?
");
$q->execute([$id]);
$c = $q->fetch();

if (
    !$c ||
    ($_SESSION['user']['role'] !== 'admin' &&
    $c['student_id'] != $_SESSION['user']['id'])
) {
    http_response_code(404);
    exit('Complaint not found.');
}

// Corrected responses query
$q = db()->prepare("
    SELECT r.*, u.name AS author
    FROM responses r
    LEFT JOIN users u ON u.id = r.staff_id
    WHERE r.complaint_id = ?
    ORDER BY r.created_at DESC
");
$q->execute([$id]);
$responses = $q->fetchAll();

$page_title = 'Complaint Details';
require 'partials/header.php';
?>

<div class="narrow">
    <a class="back" href="<?= $_SESSION['user']['role'] === 'admin' ? 'admin.php' : 'dashboard.php' ?>">
        ← Back
    </a>

    <section class="panel">

        <div class="section-head">
            <div>
                <span class="eyebrow">Complaint details</span>
                <h1><?= h($c['subject']) ?></h1>
                <div class="code"><?= h($c['complaint_code']) ?></div>
            </div>

            <span class="status <?= strtolower(str_replace(' ', '-', h($c['status']))) ?>">
                <?= h($c['status']) ?>
            </span>
        </div>

        <div class="detail-grid">
            <div>
                <small>Category</small>
                <b><?= h($c['category']) ?></b>
            </div>

            <div>
                <small>Priority</small>
                <b><?= h($c['priority']) ?></b>
            </div>

            <div>
                <small>Submitted</small>
                <b><?= h(date('d M Y, h:i A', strtotime($c['created_at']))) ?></b>
            </div>

            <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                <div>
                    <small>Student</small>
                    <b><?= h($c['student_name']) ?></b>
                </div>
            <?php endif; ?>
        </div>

        <h3>Description</h3>
        <p class="description"><?= nl2br(h($c['description'])) ?></p>

        <h3>Updates &amp; remarks</h3>

        <?php if (!$responses): ?>

            <p class="muted">No updates have been added yet.</p>

        <?php else: ?>

            <?php foreach ($responses as $r): ?>

                <div class="response">
                    <b><?= h($r['author'] ?? 'Staff') ?></b>

                    <small>
                        <?= h(date('d M Y, h:i A', strtotime($r['created_at']))) ?>
                    </small>

                    <p><?= nl2br(h($r['response'])) ?></p>
                </div>

            <?php endforeach; ?>

        <?php endif; ?>

        <?php if ($c['resolution']): ?>
            <h3>Resolution</h3>
            <p><?= nl2br(h($c['resolution'])) ?></p>
        <?php endif; ?>

    </section>
</div>

<?php require 'partials/footer.php'; ?>
