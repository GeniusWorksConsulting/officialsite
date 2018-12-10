<?php
foreach ($response as $question) {
    $answers = get_answers_helper($question->question_id);
    ?>
    <div class="form-group">
        <label class="h6"><?= $question->description; ?></label>
        <p class="text-orange"><?= $question->evaluation; ?></p>
        <input type="hidden" name="count[]" value="<?= $question->count; ?>">
        <input type="hidden" name="has_child[]" value="<?= $question->has_child; ?>">
        <input type="hidden" name="question_id[]" value="<?= $question->question_id; ?>">
        <select class="form-control <?= ($question->has_child == 1) ? 'has_child' . $question->question_id : ''; ?>" name="answer[]" id="<?= $question->question_id; ?>">
            <option value="">Select</option>
            <?php foreach ($answers as $ans) { ?>
                <option id="<?= $ans->answer_id; ?>" value="<?= $ans->answer_id; ?>"><?= $ans->answer; ?></option>
            <?php } ?>
        </select>
    </div>
    <?= ($question->has_child == 1) ? '<div id="nextQuestion' . $question->question_id . '"></div>' : ''; ?>
    <?php if ($question->has_child == 1) { ?>
        <script type="text/javascript">
            $('.has_child<?= $question->question_id; ?>').on('change', function () {
                var weight = this.value;
                var question_id = $(this).attr('id');
                if (weight !== '' && weight !== '0.00') {
                    var answer_id = $(this).find('option:selected').attr('id');

                    var postData = {
                        'question_id': question_id,
                        'answer_id': answer_id
                    };
                    $.ajax({
                        type: "post",
                        url: siteurl + 'ajaxrequest/getChild',
                        data: postData,
                        dataType: "html",
                        success: function (response) {
                            console.log(response);
                            $('#nextQuestion' + question_id).html('');
                            $('#nextQuestion' + question_id).append(response);
                        }
                    });
                } else {
                    $('#nextQuestion' + question_id).html('');
                }
            });
        </script>
    <?php } ?>
<?php } ?>

