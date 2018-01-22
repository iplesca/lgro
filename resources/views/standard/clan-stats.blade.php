@extends('standard.layouts.main')

@section('content')
    <link rel="stylesheet" type="text/css" href="{{ asset('datatables.min.css') }}"/>
    <h1>Membri de clan</h1>
    @if ($members)
        <table id="clanMembers" class="clan_members" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Nume</th>
                    <th class="text-center">Lupte</th>
                    <th class="text-right">Scor</th>
                    <th class="text-right">WN8</th>
                    <th class="text-right">WN8 30</th>
                    <th class="text-right">Vechime:</th>
                    <th class="text-right">Online</th>
                </tr>
            </thead>
            <tbody>
            @foreach($members as $member)
                <tr>
                    <td>{{ $member->role }}</td>
                    <td>{{ $member->nickname }}</td>
                    <td class="text-right">{{ $member->stats['battles'] }}</td>
                    <td class="text-right">{{ $member->score }}</td>
                    <td class="text-right"><span class="wn8-bg-{{ wn8color($member->wn8) }}">{{ $member->wn8 }}</span></td>
                    <td class="text-right"><span class="wn8-bg-{{ wn8color($member->wn8_30) }}">{{ $member->wn8_30 }}</span></td>
                    <td class="text-right">{{ $member->joined }} zile</td>
                    <td class="text-right">{{ $member->last_played }} zile</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
    <script type="text/javascript" src="{{ asset('datatables.min.js') }}"></script>
    <script type="text/javascript" src="//cdn.datatables.net/plug-ins/1.10.16/sorting/enum.js"></script>
    <script>
        $(document).ready(function() {
            $.fn.dataTable.enum(['commander', 'executive_officer', 'personnel_officer', 'quartermaster',
                'intelligence_officer', 'combat_officer', 'recruitment_officer', 'junior_officer',
                'private', 'recruit', 'reservist']);
            $('#clanMembers').DataTable({
                paging: false,
                searching: false,
                aoColumns: [{
                    sType: 'enum-0',
                    bSortable: true
                }, {
                    bSortable: true
                }, {
                    bSortable: true
                }, {
                    bSortable: true
                }, {
                    bSortable: true
                }, {
                    bSortable: true
                }, {
                    bSortable: true
                }, {
                    bSortable: true
                }]
            });
        } );
    </script>
@endsection
