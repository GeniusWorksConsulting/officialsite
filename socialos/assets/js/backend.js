var siteurl = $('#siteurl').val();

jQuery(function ($) {
    // autocomplete off
    $('form').attr('autocomplete', 'off');

    $('.datetimepicker3').datetimepicker({
        format: 'LT'
    });

    // onload
    $(window).load(function () {
        $("#dv_loader").hide();
    });

    var maxDate = new Date();
    maxDate.setDate(maxDate.getDate() - maxDate.getDay());

    $('.daterange').daterangepicker({
        maxDate: new Date(),
        autoUpdateInput: false,
        minViewMode: 1,
        autoApply: true,
        locale: {
            cancelLabel: 'Clear'
        }
        ,
        opens: 'right',
        showDropdowns: true
    }, function (start, end, label) {
        $('#startdate').val(start.format('YYYY-MM-DD'));
        $('#enddate').val(end.format('YYYY-MM-DD'));
        console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' - ' + label);
    });

    $('.daterange').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
    });

    $('.daterange').on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
        $('#startdate').val('');
        $('#enddate').val('');
    });

// number validation
    $(".allow_number").on("input", function (evt) {
        var self = $(this);
        self.val(self.val().replace(/[^0-9.]/g, ''));
        if ((evt.which !== 46 || self.val().indexOf('.') !== -1) && (evt.which < 48 || evt.which > 57))
        {
            evt.preventDefault();
        }
    });

    $("#ckbCheckAll").click(function () {
        $(".checkBoxClass").prop('checked', $(this).prop('checked'));
    });

    $(".checkBoxClass").change(function () {
        if (!$(this).prop("checked")) {
            $("#ckbCheckAll").prop("checked", false);
        }
    });

    $('.no_answer').on('change', function () {
        var number = this.value;
        if (number) {
            $('.allanswers').html('');
            var table = '<table class="table table-hover"><thead><tr><th width="50%">Answer</th><th width="10%">Rating</th><th width="10%">Weighting</th><th width="15%">Is Zero</th><th width="15%">Section</th></tr></thead><tbody class="detail"></tbody><table>';
            $('.allanswers').append(table);

            for (var i = 0; i < number; i++) {
                var tr = '<tr>' +
                        '<td><input type="text" class="form-control input-sm" name="answer[]" required></td>' +
                        '<td><input type="text" class="form-control input-sm allow_number" name="rating[]" required></td>' +
                        '<td><input type="text" class="form-control input-sm allow_number" name="weighting[]" required></td>' +
                        '<td><select class="form-control" name="isZero[]"><option value="0">No</option><option value="1">Yes</option></select></td>' +
                        '<td><select class="form-control" name="sectionZero[]"><option value="0">No</option><option value="1">Yes</option></select></td>' +
                        '</tr>';

                $('.detail').append(tr);
            }
        } else {
            $('.allanswers').html('');
        }
    });

    $('#is_parent').on('change', function () {
        var number = this.value;
        if (number === '1') {
            $.ajax({
                type: "post",
                url: siteurl + 'admin/getQuestions',
                dataType: "html",
                success: function (response) {
                    $('.isChild').html('');
                    console.log(response);

                    //var select = '<div class="form-group"><div class="row"><div class="col-sm-12"><label>Parent Question *</label><select class="form-control" id="parent_id" name="parent_id" autofocus tabindex="7"></select></div></div></div>';
                    $('.isChild').html(response);

                    //var dropdown = $('#parent_id');
                    //dropdown.append($('<option></option>').attr('value', '').text('Select'));

                    //$.each(response, function (key, entry) {
                    //    dropdown.append($('<option></option>').attr('value', entry.question_id).text(entry.description));
                    //});
                }
            });
        } else {
            $('.isChild').html('');
        }
    });

    $('#has_child').on('change', function () {
        var number = this.value;
        if (number === '1') {
            var select = '<div class="form-group"><div class="row"><div class="col-sm-3"><label>Count Weight </label><div class="block-inner"><label class="radio-inline"><input name="count" type="radio"> Yes</label><label class="radio-inline"><input name="count" checked="checked" type="radio"> No</label></div></div></div></div>';
            $('.isCount').html(select);
        } else {
            $('.isCount').html('');
        }
    });

    $('.has_child').on('change', function () {
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



    $(".limitCharacter").each(function (i) {
        len = $(this).text().length;
        if (len > 7)
        {
            $(this).text($(this).text().substr(0, 7) + '');
        }
    });

    $(".limitCharacter10").each(function (i) {
        len = $(this).text().length;
        if (len > 10)
        {
            $(this).text($(this).text().substr(0, 10) + '');
        }
    });

    $(".limitCharacter12").each(function (i) {
        len = $(this).text().length;
        if (len > 12)
        {
            $(this).text($(this).text().substr(0, 12) + '');
        }
    });
});

function assessment(user_id, type_id) {
    var postData = {
        'user_id': user_id,
        'type_id': type_id
    };

    $.ajax({
        type: "post",
        url: siteurl + 'ajaxrequest/assessment',
        data: postData,
        dataType: "json",
        success: function (response) {
            if (response['status'] === 0) {
                console.log(JSON.stringify(response));
                if (response['response'].length === 1) {
                    window.location = siteurl + 'squad/addrating/' + response['user_id'] + '/' + response['response'][0]['assessment'];
                } else {
                    var data = response['response'];
                    $('.div_assessment').html('');
                    $.each(data, function (key, item) {
                        $('<a>', {
                            text: item.name,
                            href: siteurl + 'squad/addrating/' + response['user_id'] + '/' + item.assessment,
                            class: 'btn btn-default btn-block btn-lg'
                        }).appendTo('.div_assessment');
                    });

                    $('#small_modal').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                }
            } else {
                alert(response['response']);
            }
        }
    });
}

function qa_assessment(user_id, type_id) {
    var postData = {
        'user_id': user_id,
        'type_id': type_id
    };

    $.ajax({
        type: "post",
        url: siteurl + 'ajaxrequest/assessment',
        data: postData,
        dataType: "json",
        success: function (response) {
            if (response['status'] === 0) {
                console.log(JSON.stringify(response));
                if (response['response'].length === 1) {
                    window.location = siteurl + 'users/addrating/' + response['user_id'] + '/' + response['response'][0]['assessment'];
                } else {
                    var data = response['response'];
                    $('.div_assessment').html('');
                    $.each(data, function (key, item) {
                        $('<a>', {
                            text: item.name,
                            href: siteurl + 'users/addrating/' + response['user_id'] + '/' + item.assessment,
                            class: 'btn btn-default btn-block btn-lg'
                        }).appendTo('.div_assessment');
                    });

                    $('#small_modal').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                }
            } else {
                alert(response['response']);
            }
        }
    });
}