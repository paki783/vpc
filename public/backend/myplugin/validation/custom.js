var delay;
// var myImageUrl = 'http://localhost/hassanbhai/luxury-adventures/public/assets/frontend/images/exclamation-mark.png';
$(document).ready(function () {
    initComp();
});
function initComp
    () {
    // $("form").valid();
    // $("form").validationEngine({promptPosition: "topRight", scroll: false,opacity:0.50});

    $('.ajaxFormSubmitAlter').on('click', function (e) {
        //e.preventDefault();
        var form = $('form.ajaxForm');
        var validateid = $(this).data('id');
        // alert(validateid);
        if (validateid !== undefined) {

            var form2 = $('form.validate' + validateid);
            var check2 = form2.valid();
            if (!check2) {
                return false;
            }
            // alert('asd');
        } else {
            var check = form.valid();
            if (!check) {
                return false;
            }
        }
        $("button[type=button]").attr("disabled", 'disabled');
        $("button[type=submit]").attr("disabled", 'disabled');
        Swal.fire({
            // imageUrl: myImageUrl,
            // imageWidth: 100,
            title: 'Are you sure?',
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: "No",
            confirmButtonColor: '#0d67fc',
            cancelButtonColor: '#ff5b5b',
            confirmButtonText: 'Yes'
        })
            .then((willDelete) => {
                if (willDelete.value) {
                    var validateid = $(this).data('id');
                    if (validateid !== undefined) {
                        var form2 = $('form.validate' + validateid);
                        form2.submit();
                    } else {
                        if ($('form.ajaxForm').hasClass('validate')) {
                            var form = $('form.validate');
                            form.submit();
                        } else {
                            form.submit();
                        }
                    }
                    // swal("Data has been saved", { icon: "success",});
                } else {
                    $("button[type=button]").removeAttr("disabled");
                    $("button[type=submit]").removeAttr("disabled");
                    $("#loader").hide();
                    return false;
                }
            });
    });

    $("form.ajaxForm").ajaxForm({
        dataType: "json",
        beforeSubmit: function () {
            $("#loader").show();
            // faction = $("form.login").attr("action");
            $("button[type=button]").attr("disabled", 'disabled');
            $("button[type=submit]").attr("disabled", 'disabled');
            // if (faction == undefined)
            // {
            // if( ! $("form.ajaxForm").hasClass('nopopup') ){
            //     r = confirm("Are you sure?");
            //   if (!r){
            //       $("button[type=submit]").removeAttr("disabled");
            //       $("#loader").hide();
            //       return false;
            //   }
            // }
            // }
        },
        error: function () {
            $("button[type=button]").removeAttr("disabled");
            $("button[type=submit]").removeAttr("disabled");
            $("#loader").hide();
            error("Error Accured.Invalid File Format.");
        },
        success: function (data) {
            $("#loader").hide();
            $("button[type=button]").removeAttr("disabled");
            $("button[type=submit]").removeAttr("disabled");
            if (data == null || data == "") {
                window.location.reload(true);
            }
            else if (data['error'] !== undefined) {
                error(data['error']);
            }
            else if (data['success'] !== undefined) {
                success(data['success']);
            }
            if (data['redirect'] !== undefined) {
                window.setTimeout(function () { window.location = data['redirect']; }, 2000);

            }
            if (data['reload'] !== undefined) {
                window.location.reload(true);
            }
            if (data['fieldsEmpty'] == 'yes') {

                resetForm();

            }
        }
    });

    delay = function (ms, func) {
        return setTimeout(func, ms);
    };

    toastr.options = {
        positionClass: 'toast-bottom-right'
    };


    $(".add_more").click(function () {
        data = $("#add_more_trade").html();
        $("#trade_div").append(data);
    })
    $(".Removediv").click(function () {
        $(this).parent().remove();
    });

    $(".example3").on("click", ".ajaxBtnAlter", function (event) {
        event.preventDefault();
        $("#loader").show();
        href = $(this).attr("href");
        rel = $(this).attr("rel");
        ele = $(this);
        Swal.fire({
            // imageUrl: myImageUrl,
            // imageWidth: 100,
            title: 'Are you sure?',
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: "No",
            confirmButtonColor: '#0d67fc',
            cancelButtonColor: '#ff5b5b',
            confirmButtonText: 'Yes'
        })
            .then((willDelete) => {
                if (willDelete.value) {
                    $.ajax({
                        url: href,
                        dataType: "json",
                        error: function (jqXHR, textStatus, errorThrown) {
                            $("#loader").hide();
                            error("Request not completed.Please try Again");
                        },
                        success: function (data) {
                            $("#loader").hide();
                            if (data == null || data == "") {
                                window.location.reload(true);
                            }
                            if (data['error'] !== undefined) {
                                error(data['error']);
                            }
                            if (data['success'] !== undefined) {
                                success(data['success']);
                            }
                            if (data['details']) {
                                $(ele).parent().parent().parent().remove();
                                $(ele).parent().parent().parent().remove();
                            }
                            if (data['redirect'] !== undefined) {
                                setTimeout(function () {
                                    window.location = data['redirect'];
                                }, 1000);
                            }
                            if (data['deleteRow'] !== undefined) {

                                $(ele).closest("tr").remove();
                                $(ele).closest("tr").css("display", 'none');

                            }
                            if (data['reload'] !== undefined) {
                                window.location.reload(true);
                            }
                            if (data['fieldsEmpty'] == 'yes') {

                                resetForm();

                            }
                        }
                    });
                    // swal("Data has been saved", { icon: "success",});
                } else {
                    $("#loader").hide();
                    return false;
                }
            });
        $("#loader").hide();
        return false;
    });

    $(".ajaxbtn").on("click", function (event) {
        event.preventDefault();
        $("#loader").show();
        $("button[type=button]").attr("disabled", 'disabled');
        $("button[type=submit]").attr("disabled", 'disabled');
        href = $(this).attr("href");
        rel = $(this).attr("rel");
        ele = $(this);
        if (rel === "delete") {
            var r = confirm("Do you want to perform this action?");
            if (r === true) {
                $.ajax({
                    url: href,
                    dataType: "json",
                    error: function (jqXHR, textStatus, errorThrown) {
                        $("#loader").hide();
                        $("button[type=button]").removeAttr("disabled");
                        $("button[type=submit]").removeAttr("disabled");
                        error("Request not completed.Please try Again");
                    },
                    success: function (data) {
                        $("#loader").hide();
                        $("button[type=button]").removeAttr("disabled");
                        $("button[type=submit]").removeAttr("disabled");
                        if (data == null || data == "") {
                            window.location.reload(true);
                        }
                        if (data['error'] !== undefined) {
                            error(data['error']);
                        }
                        if (data['success'] !== undefined) {
                            success(data['success']);
                        }
                        if (data['details']) {
                            $(ele).parent().parent().parent().remove();
                            $(ele).parent().parent().parent().remove();
                        }
                        if (data['redirect'] !== undefined) {
                            setTimeout(function () {
                                window.location = data['redirect'];
                            }, 1500);
                        }
                        if (data['deleteRow'] !== undefined) {
                            $(ele).closest("tr").remove();
                            $(ele).closest("tr").css("display", 'none');

                        }
                        if (data['reload'] !== undefined) {
                            window.location.reload(true);
                        }
                        if (data['fieldsEmpty'] == 'yes') {

                            resetForm();

                        }
                    }
                });
            }
            else { $("#loader").hide(); }
        }
        else {
            alert(href);
            $.ajax({
                url: href,
                dataType: "json",
                error: function (jqXHR, textStatus, errorThrown) {
                    $("#loader").hide();
                    $("button[type=button]").removeAttr("disabled");
                    $("button[type=submit]").removeAttr("disabled");
                    error("Request not completed.Please try Again");
                },
                success: function (data) {
                    $("#loader").hide();
                    $("button[type=button]").removeAttr("disabled");
                    $("button[type=submit]").removeAttr("disabled");
                    if (data == null || data == "") {
                        window.location.reload(true);
                    }
                    if (data['error'] !== undefined) {
                        error(data['error']);
                        $("button[type=button]").removeAttr("disabled");
                        $("button[type=submit]").removeAttr("disabled");
                    }
                    if (data['success'] !== undefined) {
                        $("button[type=button]").removeAttr("disabled");
                        $("button[type=submit]").removeAttr("disabled");
                        success(data['success']);
                    }
                    if (data['redirect'] !== undefined) {
                        $("button[type=button]").removeAttr("disabled");
                        $("button[type=submit]").removeAttr("disabled");
                        setTimeout(function () {
                            window.location = data['redirect'];
                        }, 1500);
                    }
                    if (data['deleteRow'] !== undefined) {
                        $("button[type=button]").removeAttr("disabled");
                        $("button[type=submit]").removeAttr("disabled");
                        $(ele).closest("tr").remove();
                        $(ele).closest("tr").css("display", 'none');
                    }
                    if (data['reload'] !== undefined) {
                        window.location.reload(true);
                    }
                    if (data['fieldsEmpty'] == 'yes') {

                        resetForm();

                    }
                }
            });
        }
        return false;
    });

    $(".example3").on("click", ".ajax", function (event) {
        event.preventDefault();
        $("#loader").show();
        $("button[type=button]").attr("disabled", 'disabled');
        $("button[type=submit]").attr("disabled", 'disabled');
        href = $(this).attr("href");
        rel = $(this).attr("rel");
        ele = $(this);
        if (rel === "delete") {
            var r = confirm("Do you want to perform this action?");
            if (r === true) {
                $.ajax({
                    url: href,
                    dataType: "json",
                    error: function (jqXHR, textStatus, errorThrown) {
                        $("#loader").hide();
                        $("button[type=button]").removeAttr("disabled");
                        $("button[type=submit]").removeAttr("disabled");
                        error("Request not completed.Please try Again");
                    },
                    success: function (data) {
                        $("#loader").hide();
                        $("button[type=button]").removeAttr("disabled");
                        $("button[type=submit]").removeAttr("disabled");
                        if (data == null || data == "") {
                            window.location.reload(true);
                        }
                        if (data['error'] !== undefined) {
                            error(data['error']);
                        }
                        if (data['success'] !== undefined) {
                            success(data['success']);
                        }
                        if (data['details']) {
                            $(ele).parent("tr").parent().parent().remove();
                            $(ele).parent("div").parent().parent().remove();
                        }
                        if (data['redirect'] !== undefined) {
                            setTimeout(function () {
                                window.location = data['redirect'];
                            }, 1500);
                        }
                        if (data['deleteRow'] !== undefined) {
                            $(ele).closest("tr").remove();
                            $(ele).closest("tr").css("display", 'none');

                        }
                        if (data['reload'] !== undefined) {
                            window.location.reload(true);
                        }
                        if (data['fieldsEmpty'] == 'yes') {

                            resetForm();

                        }
                    }
                });
            }
            else { $("#loader").hide(); }
        }
        else {
            // alert(href);
            $.ajax({
                url: href,
                dataType: "json",
                error: function (jqXHR, textStatus, errorThrown) {
                    $("#loader").hide();
                    $("button[type=button]").removeAttr("disabled");
                    $("button[type=submit]").removeAttr("disabled");
                    error("Request not completed.Please try Again");
                },
                success: function (data) {
                    $("#loader").hide();
                    $("button[type=button]").removeAttr("disabled");
                    $("button[type=submit]").removeAttr("disabled");
                    if (data == null || data == "") {
                        window.location.reload(true);
                    }
                    if (data['error'] !== undefined) {
                        error(data['error']);
                        $("button[type=button]").removeAttr("disabled");
                        $("button[type=submit]").removeAttr("disabled");
                    }
                    if (data['success'] !== undefined) {
                        $("button[type=button]").removeAttr("disabled");
                        $("button[type=submit]").removeAttr("disabled");
                        success(data['success']);
                    }
                    if (data['redirect'] !== undefined) {
                        $("button[type=button]").removeAttr("disabled");
                        $("button[type=submit]").removeAttr("disabled");
                        setTimeout(function () {
                            window.location = data['redirect'];
                        }, 1500);
                    }
                    if (data['deleteRow'] !== undefined) {
                        $("button[type=button]").removeAttr("disabled");
                        $("button[type=submit]").removeAttr("disabled");
                        $(ele).closest("tr").remove();
                        $(ele).closest("tr").css("display", 'none');
                    }
                    if (data['reload'] !== undefined) {
                        window.location.reload(true);
                    }
                    if (data['fieldsEmpty'] == 'yes') {

                        resetForm();

                    }
                }
            });
        }
        return false;
    });

    $(".ajaxNotable").click(function () {
        $("#loader").show();
        $("button[type=button]").attr("disabled", 'disabled');
        $("button[type=submit]").attr("disabled", 'disabled');
        href = $(this).attr("href");
        rel = $(this).attr("rel");
        ele = $(this);
        if (rel === "delete") {
            var r = confirm("Do you want to perform this action?");
            if (r === true) {
                $.ajax({
                    url: href,
                    dataType: "json",
                    error: function (jqXHR, textStatus, errorThrown) {
                        $("#loader").hide();
                        $("button[type=button]").removeAttr("disabled");
                        $("button[type=submit]").removeAttr("disabled");
                        error("Request not completed.Please try Again");
                    },
                    success: function (data) {
                        $("#loader").hide();
                        $("button[type=button]").removeAttr("disabled");
                        $("button[type=submit]").removeAttr("disabled");
                        if (data == null || data == "") {
                            window.location.reload(true);
                        }
                        if (data['error'] !== undefined) {
                            error(data['error']);
                        }
                        if (data['success'] !== undefined) {
                            success(data['success']);
                        }
                        if (data['details']) {
                            $(ele).parent("tr").remove();
                            $(ele).parent('div').remove();
                        }
                        if (data['redirect'] !== undefined) {
                            setTimeout(function () {
                                window.location = data['redirect'];
                            }, 1500);
                        }
                        if (data['deleteRow'] !== undefined) {
                            $(ele).closest("tr").remove();
                            $(ele).closest("tr").css("display", 'none');

                        }
                        if (data['reload'] !== undefined) {
                            window.location.reload(true);
                        }
                        if (data['fieldsEmpty'] == 'yes') {

                            resetForm();

                        }
                    }
                });
            }
            else { $("#loader").hide(); }
        }
        else {
            alert(href);
            $.ajax({
                url: href,
                dataType: "json",
                error: function (jqXHR, textStatus, errorThrown) {
                    $("#loader").hide();
                    $("button[type=button]").removeAttr("disabled");
                    $("button[type=submit]").removeAttr("disabled");
                    error("Request not completed.Please try Again");
                },
                success: function (data) {
                    $("#loader").hide();
                    $("button[type=button]").removeAttr("disabled");
                    $("button[type=submit]").removeAttr("disabled");
                    if (data == null || data == "") {
                        window.location.reload(true);
                    }
                    if (data['error'] !== undefined) {
                        error(data['error']);
                        $("button[type=button]").removeAttr("disabled");
                        $("button[type=submit]").removeAttr("disabled");
                    }
                    if (data['success'] !== undefined) {
                        $("button[type=button]").removeAttr("disabled");
                        $("button[type=submit]").removeAttr("disabled");
                        success(data['success']);
                    }
                    if (data['redirect'] !== undefined) {
                        $("button[type=button]").removeAttr("disabled");
                        $("button[type=submit]").removeAttr("disabled");
                        setTimeout(function () {
                            window.location = data['redirect'];
                        }, 1500);
                    }
                    if (data['deleteRow'] !== undefined) {
                        $("button[type=button]").removeAttr("disabled");
                        $("button[type=submit]").removeAttr("disabled");
                        $(ele).closest("tr").remove();
                        $(ele).closest("tr").css("display", 'none');
                    }
                    if (data['reload'] !== undefined) {
                        window.location.reload(true);
                    }
                    if (data['fieldsEmpty'] == 'yes') {

                        resetForm();

                    }
                }
            });
        }
        return false;
    });

    // $(".date").datepicker(
    //         {
    //             changeMonth: true,
    //             changeYear: true,
    //             dateFormat: "yy-mm-dd"
    //         });
    /*$('.time').timepicker({
     timeFormat: "hh:mm tt"
     });*/
    $(".ajaxselect").change(function () {
        surl = $(this).attr("data-url");
        target_id = $(this).attr("data-target");
        val = $(this).val();
        $.ajax({
            url: surl + "/" + val,
            success: function (data) {
                $("#" + target_id).html(data);
            }
        });
    });
    if ($.fn.select2) {
        $(".select2").select2();
    }
}

function setSelected(id, value) {
    $("#" + id + " option").each(function () {
        val = $(this).val();
        if (value == val) {
            $(this).attr("selected", "selected");
        }

    });
}
function deleteP(url) {
    var r = confirm("Would you like to delete?")
    if (r == true) {
        window.location = url;
    }
}

function error(message) {
    delay(200, function () {
        return toastr.error(message, 'Error');
    });
}
function success(message) {
    delay(200, function () {
        return toastr.success(message, 'Success');
    });
}

function Removediv(val) {
    $(val).parent().parent().parent().remove();
}
function resetForm() {

    $("form input[type=text]").val("");

    $("form input[type=password]").val("");

    $("form input[type=email]").val("");

    $("form input[type=color]").val("");

    $("form input[type=date]").val("");

    $("form input[type=datetime-local]").val("");

    $("form input[type=file]").val("");

    $("form input[type=image]").val("");

    $("form input[type=month]").val("");

    $("form input[type=number]").val("");

    $("form input[type=range]").val("");

    $("form input[type=tel]").val("");

    $("form input[type=url]").val("");

    $("form input[type=week]").val("");

    $("form select").val("");

    $("form textarea").val("");

}
function ajaxRequest(href) {
    $.ajax({
        url: href,
        dataType: "json",
        error: function (jqXHR, textStatus, errorThrown) {
            $("#loader").hide();
            error("Request not completed.Please try Again");
        },
        success: function (data) {
            $("#loader").hide();
            if (data == null || data == "") {
                window.location.reload(true);
            }
            if (data['error'] !== undefined) {
                error(data['error']);
            }
            if (data['success'] !== undefined) {
                success(data['success']);
            }
            if (data['redirect'] !== undefined) {
                window.location = data['redirect'];
            }
            if (data['deleteRow'] !== undefined) {
                $("button[type=submit]").removeAttr("disabled");
                $(ele).closest("tr").remove();
                $(ele).closest("tr").css("display", 'none');
            }
        }
    });
}
