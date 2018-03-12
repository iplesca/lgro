@extends(ISTEAM_TEMPLATE . '.layouts.main')

@section('content')
    <link rel="stylesheet" type="text/css" href="{{ asset('DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}"/>
    <h1>Tancuri :: {{ $member->nickname }}</h1>
    <small>ID: {{ $member->wargaming_id }}</small><br>
    {{--<button id="t10" data-toggle="button" class="btn btn-sm btn-primary">Tier X</button>--}}
    @if ($tanks)
        <div class="table-responsive-lg">
            <table width="100%" id="tanks" class="member_tanks table table-lg table-hover table-striped no-footer">
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
                    {{--<th class="text-center" scope="col">Distincție</th>--}}
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
        var typeImg = {
            'LT': 'ilt1.png', 'MT': 'imt1.png', 'HT': 'iht1.png', 'TD': 'itd1.png', 'SPG': 'ispg1.png'
        };
        var masteryImg = {
            '1': 'i111.png', '2': 'i211.png', '3': 'i311.png', '4': 'im111.png'
        };
        $(document).ready(function() {
            $('#t10').on('click.isteam', function () {
                table.filter(function (v, k) {
                    var a = v;

                });
            });
            $.fn.dataTable.enum(['LT', 'MT', 'HT', 'TD', 'SPG']);
            var table = $('#tanks').DataTable({
                data: tanks,
                order: [[0, 'desc'], [1, 'asc'], [2, 'asc']],
                orderFixed: {
                    post: [[0, 'desc'], [1, 'asc'], [2, 'asc']]
                },
                paging: false,
                pagingType: 'numbers',
                pageLength: 40,
                renderer: 'bootstrap',
                info: false,
                rowGroup: {
                    dataSrc: 1
                },
                columnDefs: {},
                searching: false,
                columns: [{
                    title: '',
                    width:'1%',
                    data: 'tier',
                    class: 'text-center',
                    render: function (data, type, row, meta) {
                        return data;
                    }
                }, {
                    title: '',
                    width:'1%',
                    data: 'type',
                    class: 'text-center',
                    render: function (data, type, row, meta) {
                        if ('display' == type) {
                            return '<img width=20 src="{{ asset('/images')  }}/'+ typeImg[data] +'">';
                        }
                        return data;
                    }
                }, {
                    title: 'Nume',
                    data: 'name',
                    width:'400px',
                    class: 'text-left',
                    render: function (data, type, row, meta) {
                        if ('display' == type) {
                            if ('yes' == row.premium) {
                                return '<span style="width: auto; color:#CC7A00">' + data +'</span>';
                            }
                        }
                        return data;
                    }
                }, {
                    title: 'WN8',
                    width:'5%',
                    data: 'wn8',
                    class: 'text-center',
                    render: function (data, type, row, meta) {
                        return data;
                    }
                }, {
                    title: 'Lupte',
                    width:'6%',
                    data: 'battles',
                    class: 'text-center',
                    render: function (data, type, row, meta) {
                        return data;
                    }
                }, {
                    title: '%win',
                    width:'7%',
                    data: 'win_percent',
                    class: 'text-center',
                    render: function (data, type, row, meta) {
                        return data;
                    }
                }, {
                    title: 'Victorii',
                    width:'10%',
                    data: 'wins',
                    class: 'text-center',
                    render: function (data, type, row, meta) {
                        return data;
                    }
                }, {
                    title: 'Înfrângeri',
                    width:'10%',
                    data: 'losses',
                    class: 'text-center',
                    render: function (data, type, row, meta) {
                        return data;
                    }
                },
                    {{--{--}}
                    {{--title: 'Distincție',--}}
                    {{--width:'10%',--}}
                    {{--data: 'mastery',--}}
                    {{--class: 'text-center',--}}
                    {{--render: function (data, type, row, meta) {--}}
                        {{--if ('display' == type) {--}}
                            {{--if (data.length) {--}}
                                {{--return '<img width=20 src="{{ asset('/images')  }}/' + masteryImg[data] + '">';--}}
                            {{--}--}}
                        {{--}--}}
                        {{--return data;--}}
                    {{--}--}}
                {{--}, --}}
                    {
                    title: 'Kills',
                    width:'10%',
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
