var skeletonId = 'skeleton';
var contentId = 'content';
var skipCounter = 0;
var takeAmount = 10;


function getRequests(mode, page, section) {
    if(mode == 'sent') {
        ajaxCall('/get-sent-requests',page,'GET', section);
    }else{
        ajaxCall('/get-received-requests',page,'GET', section);
    }
}

function getMoreRequests(mode) {
  // Optional: Depends on how you handle the "Load more"-Functionality
  // your code here...
}

function getConnections(page, section) {
    ajaxCall('/get-connections',page,'GET', section);
}

function getMoreConnections() {
  // Optional: Depends on how you handle the "Load more"-Functionality
  // your code here...
}

function getConnectionsInCommon(userId, connectionId) {
  // your code here...
}

function getMoreConnectionsInCommon(userId, connectionId) {
  // Optional: Depends on how you handle the "Load more"-Functionality
  // your code here...
}

function getSuggestions(page, section) {
  ajaxCall('/get-suggestions',page,'GET','suggestions_table', section);

}

function getMoreSuggestions() {
  // Optional: Depends on how you handle the "Load more"-Functionality
  // your code here...
}

function sendRequest(userId, suggestionId) {
  // your code here...
}

function deleteRequest(userId, requestId) {
   ajaxPostCall('/delete-request',{'userId' : userId, 'requestedUserId' : requestId},'POST','get_sent_requests_btn');
}

function acceptRequest(userId, requestId) {
    ajaxPostCall('/accept-request',{'userId' : userId, 'requestedUserId' : requestId},'POST','get_received_requests_btn');
}

function removeConnection(connectionId) {
    ajaxPostCall('/remove-connection',{'connectionId' : connectionId},'POST','get_connections_btn');
}

$(function () {
  getSuggestions(1, 'suggestions');
});

function successResponse(section , response) {
    if(section == 'sent_request') {
        $('#request_section').empty();
        var html = '';
        console.log(response.data.data);
        $.each(response.data.data, function (key) {
            var item = response.data.data[key];
            console.log(item.sent_requests_user.id);
            console.log(item.sender);
            html += '<tr id="user_'+item.sent_requests_user.id+'">' +
                '                <td colspan="2" class="align-middle">'+item.sent_requests_user.name+'</td>' +
                '                <td colspan="3" class="align-middle">'+item.sent_requests_user.email+'</td>' +
                '                <td align="right">' +
                '                    <button id="cancel_request_btn_'+item.sent_requests_user.id+'" onclick="deleteRequest('+item.sender+', '+item.sent_requests_user.id+')" class="btn btn-danger me-1">Withdraw Request</button>' +
                '                </td>' +
                '            </tr>'
        });
        $('#request_section').append(html);
    }
    else if(section == 'received_request') {
        $('#request_section').empty();
        var html = '';
        $.each(response.data.data, function (key) {
            var item = response.data.data[key];
            html += '<tr id="user_'+item.received_requests_user.id+'">\n' +
                '                <td colspan="2" class="align-middle">'+item.received_requests_user.name+'</td>\n' +
                '                <td colspan="3" class="align-middle">'+item.received_requests_user.email+'</td>\n' +
                '                <td align="right">\n' +
                '                    <button id="accept_request_btn_'+item.received_requests_user.id+'" onclick="acceptRequest('+item.receiver+', '+item.received_requests_user.id+')" class="btn btn-primary me-1">Accept</button>\n' +
                '                </td>\n' +
                '            </tr>'
        });
        $('#request_section').append(html);
    }
    else if(section == 'suggestions') {
        $('#suggestions_table').empty();
        var html = '';
        $.each(response.data.data, function (key) {
            var item = response.data.data[key];
            html += '<tr id="user_'+item.id+'">\n' +
                '                <td colspan="2" class="align-middle">'+item.name+'</td>\n' +
                '                <td colspan="3" class="align-middle">'+item.email+'</td>\n' +
                '                <td align="right">\n' +
                '                    <button id="'+item.id+'" class="btn btn-primary me-1 sendRequest">Connect</button>\n' +
                '                </td>\n' +
                '            </tr>'
        });
        $('#suggestions_table').append(html);
    }
    else if(section == 'connections') {
        $('#connection_table').empty();
        var html = '';
        $.each(response.data, function (key) {
            var item = response.data[key];
            console.log(item);
            html += '<tr id="user_'+item.id+'">\n' +
                '                <td colspan="2" class="align-middle">'+item.name+'</td>\n' +
                '                <td colspan="3" class="align-middle">'+item.email+'</td>\n' +
                '                <td align="right">\n' +
                '                <button style="width: 220px" id="get_connections_in_common_'+item.id+'" class="btn btn-primary" type="button"'+
                '                data-bs-toggle="collapse" data-bs-target="#collapse_'+item.id+'" aria-expanded="false" aria-controls="collapseExample">'+
                '                Connections in common () </button>'+
                '                <button id="create_request_btn_'+item.id+'" onclick="removeConnection('+item.connection_id+')" class="btn btn-danger me-1">Remove Connection</button>'+
                '                </td>\n' +
                '            </tr>'
        });
        $('#connection_table').append(html);
    }
}

$('#get_suggestions_btn').on("click", function() {
    $('#connections_tab').hide();
    $('#suggestions_tab').show();
    $('#request_tab').hide();
    getSuggestions(1, 'suggestions');
});

$('#get_sent_requests_btn').on("click", function() {
    $('#connections_tab').hide();
    $('#suggestions_tab').hide();
    $('#request_tab').show();
    getRequests('sent',1, 'sent_request');
});

$('#get_received_requests_btn').on("click", function() {
    $('#connections_tab').hide();
    $('#suggestions_tab').hide();
    $('#request_tab').show();
    getRequests('received', 1, 'received_request');
})

$('#get_connections_btn').on("click", function() {
    $('#connections_tab').show();
    $('#suggestions_tab').hide();
    $('#request_tab').hide();
    getConnections(1, 'connections');
})
