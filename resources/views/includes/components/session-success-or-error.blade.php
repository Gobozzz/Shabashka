@session('success')
    <div class="success_message !mb-2">{{ $value }}</div>
@endsession
@session('error')
    <div class="error_message !mb-2">{{ $value }}</div>
@endsession