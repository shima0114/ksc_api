
var urlPrefix = "/api/index.php";

$(function() {
    var pageName = getParam("p");
    if (!!!pageName) {
        pageName = "menu";
    }
    //includePage();
    showPage(pageName);
});

function showPage(pageName) {
    $("#main-area").load("/edit/" + pageName + ".html");
}

function showEditPage(pageName) {
    $("#editor-area").load("/edit/" + pageName + ".html");
}

function callApi(url, nextPage) {
    var data = {};
    $($("#param-area").children().serializeArray()).each(function(i, v) {
        data[v.name] = v.value;
    });

    $.ajax({
        url: url,
        type: 'post', // getかpostを指定(デフォルトは前者)
        dataType: 'json', // 「json」を指定するとresponseがJSONとしてパースされたオブジェクトになる
        data: JSON.stringify(data),
    })
    // ・ステータスコードは正常で、dataTypeで定義したようにパース出来たとき
        .done(function (response) {
            alert("SUCCESS :: " + JSON.stringify(response));
            showEditPage(nextPage);
        })
        // ・サーバからステータスコード400以上が返ってきたとき
        // ・ステータスコードは正常だが、dataTypeで定義したようにパース出来なかったとき
        // ・通信に失敗したとき
        .fail(function () {
            alert("failed")
        });
}

function renderTemplate(targetTmpl, renderArea, apiName, callBack) {
    // JsRenderテンプレート読み込み
    var $tmpl = $("#" + targetTmpl);
    // JSONを読み込み
    $.getJSON(urlPrefix+ apiName, function(jsonData) {
        console.log(jsonData);
        // 読みこんだJSONをテンプレートへ反映し出力
        $("#" + renderArea).append($tmpl.render(jsonData));
        // callBack 実行
        $(callBack);
    })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log("エラー：" + textStatus);
            console.log("テキスト：" + jqXHR.responseText);
        });
}

function getParam(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}