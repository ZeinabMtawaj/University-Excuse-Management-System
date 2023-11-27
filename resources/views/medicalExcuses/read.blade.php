@extends('users.dashboard')

@section('crud')
@if (auth()->check() && auth()->user()->hasRole('faculty-member'))
    <div class="d-flex justify-content-between align-items-center mb-2">
        <a href="{{ url('/medicalExcuses/create') }}" class="btn btn-primary">@lang('messages.add_new')</a>
    </div>
@endif

<table class="table" id="dataTable1">
    <thead class="thead-light">
        <tr>
            <th scope="col" style="{{ auth()->check() && auth()->user()->hasRole('student') ? '' : 'display: none;' }}">
                @lang('messages.teacher')
            </th>
            <th scope="col" style="{{ auth()->check() && auth()->user()->hasRole('faculty-member') ? '' : 'display: none;' }}">
                @lang('messages.student')
            </th>
            @foreach($cols as $col)
                <th scope="col">@lang('messages.' . Str::slug($col, '_'))</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($data as $medicalExcuse)
            <tr>
                <td style="{{ auth()->check() && auth()->user()->hasRole('student') ? '' : 'display: none;' }}">
                    @if (auth()->check() && auth()->user()->hasRole('student'))
                      {{ $medicalExcuse->teacher->name ?? __('messages.n_a') }}
                    @endif
                </td>
                <td style="{{ auth()->check() && auth()->user()->hasRole('faculty-member') ? '' : 'display: none;' }}">
                    @if (auth()->check() && auth()->user()->hasRole('faculty-member'))
                      {{ $medicalExcuse->student->name ?? __('messages.n_a') }}
                    @endif
                </td>
                <td>
                    @if($medicalExcuse->file_path)
                        @php $name_of_file = basename($medicalExcuse->file_path); @endphp
                        @php $url = route('file.download', ['model' => 'medical-excuse', 'folder' => 'medical excuses', 'id' => $medicalExcuse->id, 'file' => $name_of_file]); @endphp
                        <a href="{{ $url }}">{{ $name_of_file }}</a>
                    @else
                        @lang('messages.no_file')
                    @endif
                </td>
                <td>{{ $medicalExcuse->date }}</td>
                <td>{{ $medicalExcuse->created_at->format('Y-m-d H:i:s') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
