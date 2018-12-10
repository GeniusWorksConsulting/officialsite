<label>User Group:</label>
<select class="form-control squad_group" name="group_id" id="group_id">
    <option value="">-- Select --</option>
    <?php
    foreach ($this->users as $group) {
        if ($this->config->item('admin_group', 'ion_auth') !== $group->name && 'admin' !== $group->name) {
            ?>
            <option class="text-first-letter" value="<?= $group->id; ?>"><?= $group->name; ?></option>
            <?php
        }
    }
    ?>
</select>
<?php if (count($list) > 0) { ?>
    <label>Squad Group: </label>
    <select class="form-control" name="squad_group" id="squad_group">
    <?php
    foreach ($list as $$row) {
        ?>

        <?php
    }
}

