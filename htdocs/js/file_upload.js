
$("#upload_file").on('change', function() {
    var input = $(this),
        numFiles = input.get(0).files ? input.get(0).files.length : 1,
        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    $("#upload_file_name").val(label);
});

function addPhoto() {
    $("#loading").html("<img src='/images/load.gif'/>");
    // Create form data
    $('#send_year').val($("#input_year").val());
    $('#send_group_title').val($("#input_group_title").val());
    var fd = new FormData($('#file_upload_form').get(0));
    fd.append("file", $("#upload_file").prop("files")[0]);
    $.ajax({
        url: '/api/index.php/File/upload/album',
        type: 'post',
        dataType: 'json',
        data: fd,
        processData: false,
        contentType: false
    })
        .done(function(res){
            afterUpload();
            $("#result-msg").text('写真をアップロードしました');
            $('#modal-result').modal("show");
        })
        .fail(function(jqXHR, statusText, errorThrown){
            $("#result-msg").text('処理に失敗しました。管理者にお問い合わせください。');
            $('#modal-result').modal("show");
            console.log(jqXHR, statusText, errorThrown);
        })
        .always(function(res) {
            $("#loading").empty();
            window.setTimeout("$('#modal-result').modal('hide')", 3000);
        });
    return this;
}

function afterUpload() {
    // ファイル情報をクリア
    $("#upload_file").val('');
    $("#upload_file_name").val('');

    if ($("#photo_category").find("option:selected").val() == "new") {
        // TODO 追加に変更し、追加したものを選択
        // 対象値を保存
        var selYear = $("#input_year").val();
        var selGroupTitle = $("#input_group_title").val();

        // 作成したアルバムに追加をデフォルトにする
        $("#photo_category").val("add");
        console.log(selYear);
        changeProc(selYear);
        //$("#year_option").val(selYear);
        console.log(selGroupTitle);
        changeYear(selGroupTitle);
        //$("#photo_title").val(selGroupTitle);

        // 新規をクリア
        $("#input_year").val("");
        $("#input_group_title").val("");

    }

}

function changeCategory(category) {
    $("#category").val(category);
}

function changeProc(defaultValue) {
    $("#year_group_area").empty();
    var procCode = $("#photo_category").find("option:selected").val();
    $("#proc").val(procCode);
    if (procCode === "add") {
        $("#album_new").hide();
        $("#album_add").show();
        addOptionTag("#year_option", "/Album/lists/year", "", defaultValue);
    } else if (procCode === "new") {
        $("#album_add").hide();
        $("#album_new").show();
        $("#photo_title").find("option:gt(0)").remove();
    } else {
        alert("Valid option selected. Please contact administrator.");
        return;
    }
}

function changeYear(defaultValue) {
    var year = $("#year_option").find("option:selected").val();
    addOptionTag("#photo_title", "/Album/lists/title/?year="+year, "album_", defaultValue);
}

function selectGroupTitle() {
    var groupId = $("#photo_title").find("option:selected").attr("id");
    $("#image_group_id").val(groupId);
}

function addOptionTag(selector, apiUrl, idPrefix, defaultValue) {
    // parent select initialize.
    $(selector).empty();
    // create information option tag.
    var $optInfoTag = $("<option selected hidden>選択してください</option>");
    $(selector).append($optInfoTag);

    $.getJSON(urlPrefix+apiUrl, function(jsonData) {
        $.each(jsonData["album"], function () {
            // option tag initialize.
            var $optTag = $("<option>").addClass("overflow");
            // add value
            $optTag.attr("id", idPrefix + this["id"]).val(this["value"]).text(this["value"]);
            // append parent select tag
            $(selector).append($optTag);
        });
        if (!!defaultValue) {
            $(selector).val(defaultValue);
        }
    });
}