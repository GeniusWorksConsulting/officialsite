<div class="form-group">
    <div class="row">
        <div class="col-sm-12">
            <label>Parent Question *</label>
            <select class="form-control" id="parent_id" name="parent_id" tabindex="7">
                <option value="">Select</option>
                <?php foreach ($Quelist as $Q) { ?>
                    <option value="<?= $Q->question_id; ?>"><?= $Q->description; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
</div>
<div class="parentAnswer"></div>

<script type="text/javascript">
    $('#parent_id').on('change', function () {
        var question_id = this.value;
        if (question_id) {
            var postData = {
                'question_id': question_id
            };

            $.ajax({
                type: "post",
                url: '<?= site_url('admin/getAnswers'); ?>',
                data: postData,
                dataType: "json",
                success: function (response) {
                    $('.parentAnswer').html('');
                    console.log(response);

                    var select = '<div class="form-group"><div class="row"><div class="col-sm-12"><label>Select Answer *</label><select class="form-control" id="answer_id" name="answer_id" tabindex="8"></select></div></div></div>';
                    $('.parentAnswer').append(select);

                    var dropdown = $('#answer_id');
                    dropdown.append($('<option></option>').attr('value', '').text('Select'));

                    $.each(response, function (key, entry) {
                        dropdown.append($('<option></option>').attr('value', entry.answer_id).text(entry.answer));
                    });
                }
            });
        } else {
            $('.isChild').html('');
        }
    });
</script>