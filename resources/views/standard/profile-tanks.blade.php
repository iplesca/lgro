@extends(ISTEAM_TEMPLATE . '.layouts.main')

@section('content')
    <link rel="stylesheet" type="text/css" href="{{ asset('DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}"/>
    <h1>{{ $member->nickname }} [{{ $member->score  }}]</h1>
    <small>ID: {{ $member->wargaming_id }}</small><br>
    @if ($tanks)
        <div class="table-responsive-lg">
            <table width="100%" id="tanks" class="clan_members table table-sm table-hover table-striped no-footer">
                <thead>
                <tr>
                    <th class="text-center">Tier</th>
                    <th class="text-center">Tip</th>
                    <th class="text-center">Nume</th>
                    <th class="text-center">WN8</th>
                    <th class="text-center">Lupte</th>
                    <th class="text-center" scope="col">%Vic</th>
                    <th class="text-center" scope="col">Victorii</th>
                    <th class="text-center" scope="col">Înfrângeri</th>
                    <th class="text-center" scope="col">Distincție</th>
                    <th class="text-center" scope="col">Max kills</th>
                </tr>
                </thead>
            </table>
        </div>
    @endif
    <script type="text/javascript" src="{{ asset('DataTables-1.10.16/js/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ asset('DataTables-1.10.16/js/dataTables.bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="//cdn.datatables.net/plug-ins/1.10.16/sorting/enum.js"></script>
    <script>
        var tanks = @json($tanks);
        $(document).ready(function() {
            var table = $('#tanks').DataTable({
                data: tanks,
//                order: [[1, 'asc']],
                paging: false,
                renderer: 'bootstrap',
                info: false,
                searching: false,
                columns: [{
                    title: 'Tier',
                    width:'90px',
                    data: 'tier',
                    class: 'text-center',
                    render: function (data, type, row, meta) {
                        return data;
                    }
                }, {
                    title: 'Tip',
                    width:'90px',
                    data: 'type',
                    class: 'text-center',
                    render: function (data, type, row, meta) {
                        return data;
                    }
                }, {
                    title: 'Nume',
                    width:'90px',
                    data: 'name',
                    class: 'text-left',
                    render: function (data, type, row, meta) {
                        return data;
                    }
                }, {
                    title: 'WN8',
//                    width:'90px',
                    data: 'wn8',
                    class: 'text-center',
                    render: function (data, type, row, meta) {
                        return data;
                    }
                }, {
                    title: 'Lupte',
//                    width:'90px',
                    data: 'battles',
                    class: 'text-center',
                    render: function (data, type, row, meta) {
                        return data;
                    }
                }, {
                    title: '%win',
//                    width:'90px',
                    data: 'win_percent',
                    class: 'text-center',
                    render: function (data, type, row, meta) {
                        return data;
                    }
                }, {
                    title: 'Victorii',
//                    width:'90px',
                    data: 'wins',
                    class: 'text-center',
                    render: function (data, type, row, meta) {
                        return data;
                    }
                }, {
                    title: 'Înfrângeri',
//                    width:'90px',
                    data: 'losses',
                    class: 'text-center',
                    render: function (data, type, row, meta) {
                        return data;
                    }
                }, {
                    title: 'Distincție',
//                    width:'90px',
                    data: 'mastery',
                    class: 'text-center',
                    render: function (data, type, row, meta) {
                        return data;
                    }
                }, {
                    title: 'Max kills',
//                    width:'90px',
                    data: 'max_kills',
                    class: 'text-center',
                    render: function (data, type, row, meta) {
                        return data;
                    }
                }]
            });
        } );
    </script>
@endsection
