<?php

require_once __DIR__ . '/../data/functions.php';
$rows = records_all();

?>

<table>
    <thead>
        <th>Title</th>
        <th>Artist</th>
        <th>Price</th>
        <th>Format</th>
    </thead>
    <tbody>
        <?php if (count($rows) > 0): ?>
            <?php foreach ($rows as $r): ?>
                <tr>
                    <td><?= htmlspecialchars($r['title']) ?></td>
                    <td><?= htmlspecialchars($r['artist']) ?></td>
                    <td class="text-end">$<?= number_format((float)$r['price'], 2) ?></td>
                    <td><?= htmlspecialchars($r['name']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="text-muted text-center py-4">
                    No records found.
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>