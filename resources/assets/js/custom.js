var doAjaxParamsDefault = {
    'url': null,
    'requestType': "GET",
    'contentType': 'application/x-www-form-urlencoded; charset=UTF-8',
    'dataType': 'json',
    'data': {},
    'beforeSendCallbackFunction': null,
    'successCallbackFunction': null,
    'completeCallbackFunction': null,
    'errorCallBackFunction': null,
};

function doAjax(doAjaxParams) {
    // Ref:- https://stackoverflow.com/questions/28689332/how-can-i-make-many-jquery-ajax-calls-look-pretty
    // $('.button').on('click', function() {
    //     var params = $.extend({}, doAjax_params_default);
    //     params['url'] = `your url`;
    //     params['data'] = `your data`;
    //     params['successCallbackFunction'] = `your success callback function`
    //     doAjax(params);
    // });
    var url = doAjaxParams['url'];
    var requestType = doAjaxParams['requestType'];
    var contentType = doAjaxParams['contentType'];
    var dataType = doAjaxParams['dataType'];
    var data = doAjaxParams['data'];
    var beforeSendCallbackFunction = doAjaxParams['beforeSendCallbackFunction'];
    var successCallbackFunction = doAjaxParams['successCallbackFunction'];
    var completeCallbackFunction = doAjaxParams['completeCallbackFunction'];
    var errorCallBackFunction = doAjaxParams['errorCallBackFunction'];
    //make sure that url ends with '/'
    /*if(!url.endsWith("/")){
     url = url + "/";
    }*/
    $.ajax({
        url: url,
        crossDomain: true,
        type: requestType,
        contentType: contentType,
        dataType: dataType,
        data: data,
        async: true,
        cache: false,
        beforeSend: function (jqXHR, settings) {
            if (typeof beforeSendCallbackFunction === "function") {
                beforeSendCallbackFunction();
            }
        },
        success: function (data, textStatus, jqXHR) {
            if (typeof successCallbackFunction === "function") {
                successCallbackFunction(data);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            if (typeof errorCallBackFunction === "function") {
                errorCallBackFunction(errorThrown);
            }
        },
        complete: function (jqXHR, textStatus) {
            if (typeof completeCallbackFunction === "function") {
                completeCallbackFunction();
            }
        }
    });
}


function debounce(func, wait, immediate) {
    var timeout;
    return function () {
        var context = this, args = arguments;
        var later = function () {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
};

window.onload = function() {
    $('#page-loader').css('display','none');
};
