function ajaxForm(formItems) {
  var form = new FormData();
  formItems.forEach(formItem => {
    form.append(formItem[0], formItem[1]);
  });
  return form;
}



/**
 * 
 * @param {*} url route
 * @param {*} method POST or GET 
 * @param {*} functionsOnSuccess Array of functions that should be called after ajax
 * @param {*} form for POST request
 */
function ajax(url, method, functionsOnSuccess, form) {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  })

  if (typeof form === 'undefined') {
    form = new FormData;
  }

  if (typeof functionsOnSuccess === 'undefined') {
    functionsOnSuccess = [];
  }

  $.ajax({
    url: url,
    type: method,
    async: true,
    data: form,
    processData: false,
    contentType: false,
    dataType: 'json',
    error: function(xhr, textStatus, error) {
      console.log(xhr.responseText);
      console.log(xhr.statusText);
      console.log(textStatus);
      console.log(error);
    },
    success: function(response) {
      for (var j = 0; j < functionsOnSuccess.length; j++) {
        for (var i = 0; i < functionsOnSuccess[j][1].length; i++) {
          if (functionsOnSuccess[j][1][i] == "response") {
            functionsOnSuccess[j][1][i] = response;
          }
        }
        functionsOnSuccess[j][0].apply(this, functionsOnSuccess[j][1]);
      }
    }
  });
}


function exampleUseOfAjaxFunction(exampleVariable) {
  // show skeletons
  // hide content

  var form = ajaxForm([
    ['exampleVariable', exampleVariable],
  ]);

  var functionsOnSuccess = [
    [exampleOnSuccessFunction, [exampleVariable, 'response']]
  ];

  // POST 
  ajax('/example_route', 'POST', functionsOnSuccess, form);

  // GET
  ajax('/example_route/' + exampleVariable, 'POST', functionsOnSuccess);
}

function exampleOnSuccessFunction(exampleVariable, response) {
  // hide skeletons
  // show content

  console.log(exampleVariable);
  console.table(response);
  $('#content').html(response['content']);
}

$('#connections_tab').hide();
$('#request_tab').hide();


$(document).on("click", ".sendRequest", function(e) {
    var userId = $(this).attr('id');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    e.preventDefault();
    var formData = {
        'userId' : userId
    };
    $.ajax({
        url: 'send-request',
        type: 'POST',
        data: JSON.stringify(formData),
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function (response) {
            if(response.status) {
                $('#user_'+userId).remove();
            }
            console.log(response);
        },
        error: function (data) {
            console.log(data);
        }
    })
});

var APP_URL = $('meta[name="base-url"]').attr('content');

function ajaxCall (ajax_url, params, method, section)
{
    var url = '';
    if(params != '') {
        url = APP_URL + ajax_url + '?page='+params;
    }else{
        url = APP_URL + ajax_url;
    }
    $.ajax({
        url: url,
        type: method,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function (response) {
            if(response.status){
                if(section != ''){
                    successResponse(section, response)
                }
            }
        },
        error: function (data) {
            return data;
        }

    });
}

function ajaxPostCall(ajax_url , params, method, btn_to_click) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
    $.ajax({
        url: APP_URL + ajax_url,
        type: method,
        data: JSON.stringify(params),
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function (response) {
            if(response.status){
                console.log(response);
                $('#'+btn_to_click).click();
            }
        },
        error: function (data) {
            console.log(data);
        }

    });
}
