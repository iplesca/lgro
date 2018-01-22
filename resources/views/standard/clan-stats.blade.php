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
                    <td class="text-right"><span class="wn8-bg-{{ wn8value($member->wn8Level) }}">{{ $member->wn8 }}</span></td>
                    <td class="text-right"><span class="wn8-bg-{{ wn8value($member->wn830Level) }}">{{ $member->wn8_30 }}</span></td>
                    <td class="text-right">{{ $member->joined }} zile</td>
                    <td class="text-right">{{ $member->last_played }} zile</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
    <script type="text/javascript" src="{{ asset('datatables.min.js') }}"></script>
    <script>
        var ranks = {
            'commander': 1,
            'executive_officer': 2,
            'personnel_officer': 3,
            'quartermaster': 4,
            'intelligence_officer': 5,
            'combat_officer': 6,
            'recruitment_officer': 7,
            'junior_officer': 8,
            'private': 9,
            'recruit': 10,
            'reservist': 11
        }
        $(document).ready(function() {
            $.fn.dataTableExt.oSort["rank-desc"] = function (x, y) {
                return ranks[x] < ranks[y];
            };

            $.fn.dataTableExt.oSort["rank-asc"] = function (x, y) {
                return ranks[x] > ranks[y];
            }
            $('#clanMembers').DataTable({
                paging: false,
                searching: false,
                aoColumns: [{
                    sType: 'rank',
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
