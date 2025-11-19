<!-- NEW -->

<!-- 'records_in_cart' comes directly from the 'index.php' page, and is filled 
    by using the 'records_by_ids' function. It's needed here to fill the cart
    table with everything the user has in their cart. Without it, the table would
    on this page would show that it's still empty -->
<h2>Your Cart</h2>

<?php $records = $records_in_cart ?? []; ?>

<?php if (empty($records)): ?>
    <p>Your cart is empty.</p>
<?php else: ?>

    <table class="table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Artist</th>
                <th>Format</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($records as $r): ?>
                <tr>
                    <td><?= htmlspecialchars($r['title']) ?></td>
                    <td><?= htmlspecialchars($r['artist']) ?></td>
                    <td><?= htmlspecialchars($r['name']) ?></td>
                    <td>$<?= number_format((float)$r['price'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <form method="post">
        <input type="hidden" name="action" value="checkout">
        <button class="btn btn-success">Complete Purchase</button>
    </form>

<?php endif; ?>
<!-- END NEW -->