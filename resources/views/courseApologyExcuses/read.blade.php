@extends('users.dashboard')

@section('crud')
@if (auth()->check() && auth()->user()->hasRole('student'))
    <div class="d-flex justify-content-between align-items-center mb-2">
        <a href="{{ url('/courseApologyExcuses/create') }}" class="btn btn-primary">@lang('messages.add_new')</a>
    </div>
@endif

<table class="table" id="dataTable1">
    <thead class="thead-light">
        <tr>
            <th scope="col" style="{{ auth()->check() && auth()->user()->hasRole('faculty-member') ? '' : 'display: none;' }}">@lang('messages.student')</th>
            @foreach($cols as $col)
                <th scope="col">@lang('messages.' . Str::slug($col, '_'))</th>
            @endforeach
            <th scope="col" style="{{ auth()->check() && auth()->user()->hasRole('faculty-member') ? '' : 'display: none;' }}">@lang('messages.action')</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $courseApologyExcuse)
            <tr>
                <td style="{{ auth()->check() && auth()->user()->hasRole('faculty-member') ? '' : 'display: none;' }}">
                    @if (auth()->check() && auth()->user()->hasRole('faculty-member'))
                        {{ $courseApologyExcuse->student->name }}
                    @endif
                </td>
                <td>{{ $courseApologyExcuse->course->name ?? __('messages.no_course') }}</td>
                <td>
                    @if($courseApologyExcuse->file_path)
                        @php $name_of_file = basename($courseApologyExcuse->file_path); @endphp
                        @php $url = route('file.download', ['model' => 'course-abology-excuse', 'folder' => 'course abologies', 'id' => $courseApologyExcuse->id, 'file' => $name_of_file]); @endphp
                        <a href="{{ $url }}">{{ $name_of_file }}</a>
                    @else
                        @lang('messages.no_file')
                    @endif
                </td>
                <td>{{ $courseApologyExcuse->date }}</td>
                <td>@lang('messages.' . Str::slug($courseApologyExcuse->status, '_'))</td>
                <td>{{ $courseApologyExcuse->created_at->format('Y-m-d H:i:s') }}</td>
                <td style="{{ auth()->check() && auth()->user()->hasRole('faculty-member') ? '' : 'display: none;' }}">
                    @if (auth()->check() && auth()->user()->hasRole('faculty-member'))
                        <i class="fa fa-check text-success" onclick="updateStatus({{ $courseApologyExcuse->id }}, true)"></i> &nbsp;&nbsp;
                        <i class="fa fa-times text-danger" onclick="updateStatus({{ $courseApologyExcuse->id }}, false)"></i>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>






<script>
  function updateStatus(absenceExcuseId, status) {
    const actionWord = status ? 'approve' : 'reject';
    const actionUpdate = status ? 'Approved' : 'Rejected';
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    Swal.fire({
      title: @json(__('messages.confirm_action')),
      text: @json(__('messages.confirm_update_status')),
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: status ? @json(__('messages.yes_approve_it')) : @json(__('messages.yes_reject_it')),
      cancelButtonText: @json(__('messages.cancel'))
    }).then((result) => {
      if (result.isConfirmed) {
        axios.post('/courseApologyExcuses/update-status', {
          id: absenceExcuseId,
          status: actionUpdate, 
          _token: token
        })
        .then(function (response) {
          Swal.fire({
            title: @json(__('messages.updated')),
            text: @json(__('messages.status_updated_success')),
            icon: 'success'
          }).then(() => {
            location.reload();
          });
        })
        .catch(function (error) {
          Swal.fire({
            title: @json(__('messages.error')),
            text: @json(__('messages.error_updating_status')),
            icon: 'error'
          });
        });
      }
    });
  }
  </script>
  
  @endsection
  