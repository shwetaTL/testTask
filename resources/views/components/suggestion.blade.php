<div class="my-2 shadow  text-white bg-dark p-1" id="suggestions_tab">
  <div class="d-flex justify-content-between">
    <table class="ms-1" width="100%" id="suggestions_table">
        @foreach($suggestions as $suggestion)
            <tr id="user_{{ $suggestion->id }}">
                <td colspan="2" class="align-middle">{{ $suggestion->name }}</td>
                <td colspan="3" class="align-middle">{{ $suggestion->email }}</td>
                <td align="right">
                    <button id="{{ $suggestion->id }}" class="btn btn-primary me-1 sendRequest">Connect</button>
                </td>
            </tr>

        @endforeach
    </table>
  {{--  <div>
      <button id="create_request_btn_" class="btn btn-primary me-1">Connect</button>
    </div>--}}
  </div>
</div>
