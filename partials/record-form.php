<?php

$formats = formats_all_query();

?>

<h1>Add Record</h1>

<form method="post">

    <label class="form-label">Title</label>
    <input name="title" class="form-control "type="text" required>

    <label class="form-label">Artist</label>
    <input name="artist" class="form-control "type="text" required>

    <label class="form-label">Price</label>
    <input name="price" class="form-control" type="number" step="0.01" min="0" required>

    <label class="form-label">Genre</label>
    <select name="format_id" class="form-select" required>
        <option value="">Select...</option>
        <?php foreach ($formats as $f): ?>
            <option value="<?= (int)$f['id'] ?>"><?= htmlspecialchars($f['name']) ?></option>
        <?php endforeach; ?>
    </select>


    <input type="hidden" name="action" value="create">

    <button class="btn btn-success">Create</button>
    <a href="?view=list" class="btn btn-outline-secondary">Cancel</a>

</form>