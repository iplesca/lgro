@if(session()->has('pop_message'))
    <script>alert('{{ session()->get('pop_message') }}');</script>
@endif