@extends(ISTEAM_TEMPLATE . '.layouts.main')

@section('content')
    <h1>{{ $member->nickname }} [{{ $member->score  }}]</h1>
    @if ($member->user->id == \Illuminate\Support\Facades\Auth::user()->id)
        <small>ID: {{ $member->wargaming_id }}</small><br>
    @else
        <a class="btn btn-primary" title="Tancuri în garaj" href="{{ route('profile-tanks', ['memberId' => $member->id]) }}">Garaj</a>
    @endif
    <br>
    @if (!empty($user->wot_created_at))
    <table style="width:70%">
        <tr>
            <td>
                <div>
                    <strong>Tanchist din data de:</strong> {{ $user->wot_created_at }}<br>
                    <strong>Cont premium:</strong> {{ $member->premium ? 'DA' : 'NU'  }}
                    @if ($member->premium)
                        - <em>până pe {{ $member->premium_expire }}</em>
                        <br>
                    @endif
                    <strong>Experiență free:</strong> {{ number_format($member->free_xp, 0, ',', '.') }}<br>
                </div>
            </td>
            @if ($member->id == \Illuminate\Support\Facades\Auth::user()->id)
            <td>
                <div>
                    <strong>Credite:</strong> {{ number_format($member->credits, 0, ',', '.') }}<br>
                    <strong>Gold:</strong> {{ number_format($member->gold, 0, ',', '.') }}<br>
                    <strong>Bond-uri:</strong> {{ number_format($member->bonds, 0, ',', '.') }}<br>
                </div>
            </td>
            @endif
        </tr>
    </table>
    @endif
    <br>
    <h2>Statistici</h2>
    <div class="tabel_stats">
        <table style="width: 80%">
            <tr>
                <td>
                    <h3>General</h3>
                    <strong>Kill-uri:</strong> {{ number_format($member->stats['frags'], 0, ',', '.') }}<br>
                    <strong>Experiență:</strong> {{ number_format($member->stats['xp'], 0, ',', '.') }}<br>
                    <strong>Experiență medie pe luptă:</strong> {{ number_format($member->stats['battle_avg_xp'], 0, ',', '.') }}<br>
                    <strong>Tancuri spotate:</strong> {{ number_format($member->stats['spotted'], 0, ',', '.') }}<br>
                    <strong>Eficiență armură:</strong> {{ $member->stats['tanking_factor'] }}<br>

                    <h3>Lupte</h3>
                    <strong>Total:</strong> {{ number_format($member->stats['battles'], 0, ',', '.') }}<br>
                    <strong>Câștigate:</strong> {{ number_format($member->stats['wins'], 0, ',', '.') }}<br>
                    <strong>Remize:</strong> {{ number_format($member->stats['draws'], 0, ',', '.') }}<br>
                    <strong>Pierdute:</strong> {{ number_format($member->stats['losses'], 0, ',', '.') }}<br>
                    <strong>Supraviețuitor:</strong> {{ number_format($member->stats['survived_battles'], 0, ',', '.') }}<br>
                </td>
                <td>
                    <h3>Damage</h3>
                    <strong>Total:</strong> {{ number_format($member->stats['damage_dealt'], 0, ',', '.') }}<br>
                    <strong>Primit:</strong> {{ number_format($member->stats['damage_received'], 0, ',', '.') }}<br>
                    @if (isset($member->stats['avg_damage_blocked']))
                    <strong>Medie blocat:</strong> {{ number_format($member->stats['avg_damage_blocked'], 0, ',', '.') }}<br>
                    @endif

                    @if (isset($member->stats['avg_damage_assisted']))
                    <h3>Damage assist</h3>
                    <strong>Medie:</strong> {{ $member->stats['avg_damage_assisted'] }}<br>
                    <strong>Medie spot:</strong> {{ number_format($member->stats['avg_damage_assisted_radio'], 0, ',', '.') }}<br>
                    <strong>Medie șenile blocate:</strong> {{ number_format($member->stats['avg_damage_assisted_track'], 0, ',', '.') }} <br>
                    @endif

                    <h3>Bază</h3>
                    <strong>Puncte captură:</strong> {{ number_format($member->stats['capture_points'], 0, ',', '.') }}<br>
                    <strong>Puncte reset:</strong> {{ number_format($member->stats['dropped_capture_points'], 0, ',', '.')}}<br>
                </td>
                <td>
                    <h3>Lovituri</h3>
                    <h5>primite</h5>
                    <strong>Total:</strong> {{ number_format($member->stats['direct_hits_received'], 0, ',', '.') }}<br>
                    <strong>Cu penetrare:</strong> {{ number_format($member->stats['piercings_received'], 0, ',', '.') }}<br>

                    <h5>trase</h5>
                    <strong>Total:</strong> {{ number_format($member->stats['shots'], 0, ',', '.') }}<br>
                    <strong>Atinse:</strong> {{ number_format($member->stats['hits'], 0, ',', '.') }}<br>
                    <strong>Cu penetrare:</strong> {{ number_format($member->stats['piercings'], 0, ',', '.') }}<br>

                    <h3>Artilerie</h3>
                    <strong>Tancuri în șoc:</strong> {{ number_format($member->stats['stun_number'], 0, ',', '.') }}<br>
                    <strong>Damage assist:</strong> {{ number_format($member->stats['stun_assisted_damage'], 0, ',', '.') }}<br>
                </td>
            </tr>
        </table>
    </div>
    <br><br><br><br><br><br><br><br><br><br><br>
@endsection
