@extends('standard.layouts.main')

@section('content')
    <link rel="stylesheet" type="text/css" href="{{ asset('DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}"/>
    <h1>Membri de clan</h1>
    @if ($members)
        <div class="table-responsive-lg">
        <table width="100%" id="clanMembers" class="clan_members table table-sm table-hover table-striped no-footer">
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Rank</th>
                    <th class="text-center">Nume</th>
                    <th class="text-center" scope="col">Lupte</th>
                    <th class="text-center" scope="col">Scor</th>
                    <th class="text-center" scope="col">WN8</th>
                    <th class="text-center" scope="col">WN8&nbsp;30</th>
                    <th class="text-center" scope="col">Vechime:</th>
                    <th class="text-center" scope="col">Online</th>
                </tr>
            </thead>
        </table>
        </div>
    @endif
    <script type="text/javascript" src="{{ asset('DataTables-1.10.16/js/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ asset('DataTables-1.10.16/js/dataTables.bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="//cdn.datatables.net/plug-ins/1.10.16/sorting/enum.js"></script>
    <script>
        var members = @json($members);
        var ranks = {
            'commander': 'Comandant',
            'executive_officer': 'Comandant Executiv',
            'personnel_officer': 'Ofițer de Cadre',
            'quartermaster': 'Trezorerier',
            'intelligence_officer': 'Ofițer de Informații',
            'combat_officer': 'Ofițer Strateg',
            'recruitment_officer': 'Ofițer Recrutor',
            'junior_officer': 'Ofițer junior',
            'private': 'Soldat',
            'recruit': 'Recrut',
            'reservist': 'Rezervist'
        };
        var dayPlural = ' zile', daySingular = ' zi';
        var pastDays = {
            d1: 'ieri',
        };
        var standardDisplay = function (data, type, row, meta) {
            return data;
        };
        $(document).ready(function() {
            $.fn.dataTable.enum(['commander', 'executive_officer', 'personnel_officer', 'quartermaster',
                'intelligence_officer', 'combat_officer', 'recruitment_officer', 'junior_officer',
                'private', 'recruit', 'reservist']);
            var table = $('#clanMembers').DataTable({
                data: members,
                order: [[1, 'asc'], [2, 'asc']],
                paging: false,
                renderer: 'bootstrap',
                info: false,
                searching: false,
                columns: [{
                    title: '#',
                    width:'10px',
                    data: null,
                    sortable: false,
                    class: 'text-center'
                },{
                    title: 'Rank',
                    width:'17%',
                    data: 'role',
                    class: 'text-left',
                    render: function (data, type, row, meta) {
                        var result = data;
                        if ('display' == type) {
                            return ranks[data];
                        }
                        return data;
                    }
                }, {
                    title: 'Nume',
                    data: 'nickname',
                    class: 'text-left',
                    render: standardDisplay
                }, {
                    title: 'Lupte',
                    data: 'battles',
                    width:'70px',
                    render: standardDisplay
                }, {
                    title: 'Scor',
                    width:'70px',
                    data: 'score',
                    render: standardDisplay
                }, {
                    title: 'WN8',
                    width:'90px',
                    data: 'wn8',
                    class: 'text-center',
                    render: function (data, type, row, meta) {
                        var result = data;
                        if ('display' == type) {
                            return '<span class="wn8-bg-' + row.wn8color + '">'+ data +'</span>';
                        }
                        return result;
                    }
                }, {
                    title: 'WN8&nbsp;30',
                    width:'90px',
                    data: 'wn8_30',
                    class: 'text-center',
                    render: function (data, type, row, meta) {
                        var result = data;
                        if ('display' == type) {
                            return '<span class="wn8-bg-' + row.wn830color + '">'+ data +'</span>';
                        }
                        return result;
                    }
                }, {
                    title: 'Vechime',
                    width:'10%',
                    data: 'joined',
                    class: 'text-center',
                    render: function (data, type, row, meta) {
                        var result = data;
                        if ('display' == type) {
                            data = parseInt(data);
                            result = data + ' ' + dayPlural;
                            if (data == 1) {
                                result = data + ' ' + daySingular;
                            }
                            return result
                        }
                        return result;
                    }
                }, {
                    title: 'Ultima&nbsp;luptă',
                    width:'13%',
                    data: 'logout',
                    class: 'text-center',
                    render: function (data, type, row, meta) {
                        var result = data;
                        if ('display' == type) {
                            if ("undefined" != typeof pastDays['d'+data]) {
                                result = pastDays['d'+data];
                            } else {
                                result = data + ' ' + dayPlural;
                            }
                            return result;
                        }
                        return result;
                    }
                }]
            });
            table.on( 'order.dt search.dt', function () {
                table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1 + '.';
                } );
            } ).draw();
        } );
    </script>
@endsection
