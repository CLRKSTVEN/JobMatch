<h2>Pending Users</h2>
<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Action</th>
    </tr>
    <?php foreach ($users as $u): ?>
        <tr>
            <td><?= $u->id ?></td>
            <td><?= $u->first_name ?> <?= $u->last_name ?></td>
            <td><?= $u->email ?></td>
            <td><?= $u->role ?></td>
            <td><a href="<?= site_url('admin/approve/' . $u->id) ?>">Approve</a></td>
        </tr>
    <?php endforeach; ?>
</table>