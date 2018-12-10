<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('superadmin/index') ?>">Dashboard</a></li>
        <li class="active">Manage Squad</li>
    </ul>
</div>
<!-- /breadcrumbs line -->

<div class="row">
    <div class="col-md-10 col-xs-12">
        <?php
        $attributes = array("class" => "form-horizontal", "id" => "search", "name" => "search");
        echo form_open('', $attributes);
        ?>
        <div class="form-group">
            <div class="col-md-3">
                <select class="form-control" name="admin_id" id="admin_id">
                    <option value="">-- Select --</option>
                    <?php foreach ($admins as $a) { ?>
                        <option value="<?= $a->id; ?>"><?= $a->first_name . ' ' . $a->last_name; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="ui-widget-header" id="droppable1">
            <div class="panel panel-info">
                <div class="panel-heading"><h6 class="panel-title">SQUAD Members</h6></div>

                <div class="panel-body" style="height: 150px;">

                    <ul class="info-blocks li_containers">
                        <?php
                        if (isset($users) && count($users) > 0) {
                            foreach ($users as $u) {
                                ?>

                                <li class="bg-default sminfo ui-widget-content listitems" style="z-index: 99;" data-itemid=<?php echo $u->id; ?> >
                                    <div class="top-info" style="margin: 7px 7px 7px 7px;">
                                        <span><?= $u->first_name . ' ' . $u->last_name; ?></span>
                                    </div>
                                </li>

                                <?php
                            }
                        } else {
                            ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h6 class="panel-title text-danger">
                                        No user found.
                                    </h6>
                                </div>
                            </div>
                        <?php }
                        ?>
                    </ul>

                </div>
            </div>
        </div>

    </div>

    <div class="col-md-6">
        <?php
        if (isset($squads) && count($squads) > 0) {
            foreach ($squads as $s) {
                $s_users = get_squad_users(array('s.squad_id' => $s->squad_id, 's.status' => 0));
                ?>

                <div id="<?= $s->squad_id; ?>" class="ui-widget-header droppable">
                    <div class="panel panel-default">
                        <div class="panel-heading"><h6 class="panel-title"><?= $s->squad_name; ?></h6></div>

                        <div class="panel-body" style="height: 150px;">
                            <ul class="info-blocks">
                                <?php foreach ($s_users as $u) { ?>

                                    <li id="<?= $u->id; ?>" class="bg-default sminfo listitems" data-itemid=<?php echo $u->id; ?>>
                                        <div class="top-info" style="margin: 7px 7px 7px 7px;">
                                            <span><?= $u->user_name; ?></span>
                                        </div>
                                    </li>

                                <?php } ?>
                            </ul>
                        </div>

                    </div>
                </div>

                <?php
            }
        } else {
            ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title text-danger">
                        Not result found to display.
                    </h6>
                </div>
            </div>
        <?php }
        ?>
    </div>

</div>

<script type="text/javascript">
    $(function () {
        $(".listitems").draggable();
        $(".droppable").droppable({
            drop: function (event, ui) {
                var id = $(this).attr('id');

                $(this).addClass("ui-state-highlight");
                var itemid = ui.draggable.attr('data-itemid');
                var attrid = ui.draggable.attr('id');

                $.ajax({
                    method: "POST",
                    url: '<?= site_url('ajaxrequest/update_squad'); ?>',
                    data: {'user_id': itemid, 'squad_id': id, 'attrid': attrid},
                }).done(function (response) {
                    console.log(response);
                    var data = $.parseJSON(response);

                    if (!data.status) {
                        alert(data.message);
                    }
                });
            }
        });

        $("#droppable1").droppable({
            drop: function (event, ui) {
                $(this).addClass("ui-state-highlight");
                var itemid = ui.draggable.attr('data-itemid')
                $.ajax({
                    method: "POST",
                    url: '<?= site_url('ajaxrequest/delete_squaduser'); ?>',
                    data: {'id': itemid},
                }).done(function (response) {
                    console.log(response);
                    var data = $.parseJSON(response);

                    if (!data.status) {
                        alert(data.message);
                    }
                });
            }
        });

        $('#admin_id').on('change', function () {
            var value = $(this).val();
            if (value) {
                window.location = '<?php echo site_url("superadmin/managesquad/"); ?>' + value;
            }
        });
    });
</script>