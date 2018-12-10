<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('admin/index') ?>">Dashboard</a></li>
        <li class="active">Questions</li>
    </ul>
</div>
<!-- /breadcrumbs line -->

<!-- Message -->
<?php if ($this->session->flashdata('message')) { ?>
    <div id="msg" class="callout callout-success fade in text-semibold">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <?= $this->session->flashdata('message'); ?>
    </div>
<?php } ?>

<div class="row">
    <div class="col-md-6">
        <!--<button id="btn-show-all-children" class="btn btn-default btn-xs">Expand All</button>
        <button class="btn btn-default btn-xs">Colla</button>-->
    </div>
    <div class="col-md-6 text-right">
        <a class="btn btn-success btn-xs" href="<?= site_url('superadmin/add_que') ?>"><i class="icon-plus-circle"></i> Add Question</a>
    </div>
</div>
<br>
<style>
    td.details-control {
        background: url('../assets/images/details_open.png') no-repeat center center;
        cursor: pointer;
    }
    tr.shown td.details-control {
        background: url('../assets/images/details_close.png') no-repeat center center;
    }
</style>
<!-- Default table inside panel -->
<table id="my-table" class="table table-bordered">
    <thead>
        <tr>
            <th></th>
            <th>Id</th>
            <th>Question</th>
            <th class="text-center">Evaluation</th>
            <th class="text-center">Weight</th>
            <th class="text-center">Sub Assessment</th>
            <th class="text-center">Assessment</th>
            <th class="text-center">Is Parent</th>
            <th class="text-center">Admin</th>
            <th class="text-right">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($questions as $que):
            ?>
            <tr data-child-name="<?= $que->question_id; ?>" data-child-value="10">
                <td class="details-control"></td>
                <td><?= $que->question_id; ?></td>
                <td><?= $que->description; ?></td>
                <td class="text-center">
                    <?= ($que->evaluation) ? '<a href="#" data-toggle="tooltip" title="' . $que->evaluation . '">Evaluation</a>' : ''; ?>
                </td>
                <td class="text-center"><?= $que->weight; ?></td>
                <td class="text-center"><?= $que->sub_name; ?></td>
                <td class="text-center"><?= $que->ass_name; ?></td>
                <td class="text-center"><?= ($que->is_parent == 0) ? 'Parent' : 'Child'; ?></td>
                <td class="text-center">
                    <label class="label label-success"><?= $que->user_name; ?></label>
                </td>
                <td class="text-right">
                    <a title="remove" onclick="return confirm('Are you sure?');" href="<?= site_url('superadmin/delete_question/' . $que->question_id); ?>" class="text-danger"><i class="icon-cancel"></i></a>
                </td>
            </tr>
        <?php endforeach; ?>

        <?php
        if ($questions == NULL) {
            echo '<tr><td class="text-center text-danger text-semibold" colspan="6">No data found.</td></tr>';
        }
        ?>

    </tbody>
</table>

<?php if (isset($pagination) && $pagination != NULL) { ?>
    <div class="well text-right hidden-print">
        <div class="well text-right">
            <?= $pagination; ?>
        </div>
    </div>
<?php } ?>
<br>

<script type="text/javascript">
    jQuery(function ($) {

        // Setup - add a text input to each footer cell
        //$('#my-table thead tr').clone(true).appendTo('#my-table thead');
//        $('#my-table thead tr:eq(1) th').each(function (i) {
//            if (i === 1 || i === 2 || i === 3 || i === 4 || i === 5 || i === 6 || i === 7 || i === 8) {
//                var title = $(this).text();
//                $(this).html('<input class="form-control input-sm" type="text" placeholder="' + title + '" />');
//                $('input', this).on('keyup', function () {
//                    if (table.column(i).search() !== this.value) {
//                        table.column(i).search(this.value).draw();
//                    }
//                });
//            }
//        });

        function format(question_id) {
            if (question_id) {
                var table_row = '';
                $.ajax({
                    'async': false,
                    type: "post",
                    url: '<?= site_url('ajaxrequest/get_answer'); ?>',
                    data: {'question_id': question_id},
                    dataType: "json",
                    success: function (json) {
                        console.log(json);
                        if (json['status']) {
                            var i = 1;
                            $.each(json['data'], function (s, value) {
                                table_row = table_row + '<tr><td class="text-semibold">Answer ' + i + '</td><td>' + value.answer + '</td><td class="text-semibold">Weight</td><td>' + value.weighting + '</td><td class="text-semibold">Rating</td><td>' + value.rating + '</td></tr>';
                                i++;
                            });
                        } else {

                        }
                    }
                });

                return '<table cellpadding="5" cellspacing="0" border="0">' + table_row + '</table>';
            }
        }

        // DataTable
        var table = $('#my-table').DataTable({
            "ordering": false,
            "responsive": true,
            "lengthMenu": [
                [20, 50, 100, -1],
                [20, 50, 100, "All"]
            ]
        });

        // Add event listener for opening and closing details
        $('#my-table tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = table.row(tr);

            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            } else {
                // Open this row
                row.child(format(tr.data('child-name'))).show();
                tr.addClass('shown');
            }
        });
    });
</script>
