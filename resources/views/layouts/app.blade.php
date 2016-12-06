<!DOCTYPE html>
<html lang="en">
    @include('partials.header')
<body>
    <div id="app">
        @include('partials.navbar')
        @yield('content')
    </div>
</body>
</html>

<script>
// Script to show Toaster Notifications !!
  @if(Session::has('message'))
    var type = "{{ Session::get('alert-type', 'info') }}";
    switch(type){
        case 'info':
            toastr.info("{{ Session::get('message') }}");
            break;

        case 'warning':
            toastr.warning("{{ Session::get('message') }}");
            break;

        case 'success':
            toastr.success("{{ Session::get('message') }}");
            break;

        case 'error':
            toastr.error("{{ Session::get('message') }}");
            break;
    }
  @endif
</script>
