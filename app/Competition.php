<?php

namespace App;

use Illuminate\Database\Eloquent\Model;



class Competition extends Model
{
    private $teamNames = ['Alfa', 'Bravo', 'Charlie', 'Delta', 'Echo', 'Foxtrot', 'Golf', 'Hotel', 'India', 'Juliet', 'Kilo', 'Lima', 'Mike', 'November', 'Oscar', 'Papa', 'Quebec', 'Romeo', 'Sierra', 'Tango', 'Uniform', 'Victor', 'Whiskey', 'X-ray', 'Yankee', 'Zulu'];

    private $positions = ['p1' => 0, 'p2' => 1, 'p3' => 2, 'p4' => 3, 'p5' => 4];

    public function generateMatches()
    {
        $result = [];
        $data = $this->getData();

        // add group matches
//        echo "<pre>";
        foreach ($data['groups'] as $gId => $group) {
//            $matches = $this->generateGroupMatches($group);

//            print_r($matches);break;
//            foreach ($matches as $mId => $pair) {}
            foreach ($data['matches']['qualify'] as $mId => $pair) {
                $id = $gId .':'. $mId;
                $result[$id] = $this->initMatch(
                    $group[ $this->positions[$pair[0]] ],
                    $group[ $this->positions[$pair[1]] ]
                );
            }
        }
//        die;
        // add knock-out
        foreach ($data['matches']['knock-out'] as $kId => $pair) {
            $result[$kId] = $this->initMatch($pair[0], $pair[1], true);
        }
        // add semi-final
        $pair = $data['matches']['semifinal'];
        $result['sf'] = $this->initMatch($pair[0], $pair[1], true);
        // add final
        $pair = $data['matches']['final'];
        $result['f'] = $this->initMatch($pair[0], $pair[1], true);

        return $result;
    }
    private function generateGroupMatches($group, $offset = 1)
    {
        $nrTeams = count($group);
        $result = [];
        $permutations = [];

        for ($i=1; $i <= $nrTeams-1; $i++) {
            for ($j=$i+1; $j <= $nrTeams; $j++) {
                if (!isset($permutations["p$i:p$j"]) && !isset($permutations["p$j:p$i"])) {
                    $permutations["p$i:p$j"] = [
                        'index' => "m$offset",
                        'pair' => ["p$i", "p$j"]
                    ];
                    $offset++;
                }
            }
        }
        foreach ($permutations as $entry) {
            $result[$entry['index']] = $entry['pair'];
        }
        return $result;
    }
    // @todo If-away check
    private function initMatch($slotOne, $slotTwo, $isRef = false)
    {
        return [
            'slotOne' => ['id'  => $slotOne, 'isRef' => $isRef],
            'slotTwo' => ['id'  => $slotTwo, 'isRef' => $isRef],
            'home' => [
                'slotOne' => false,
                'slotTwo' => false,
            ],
            'away' => [
                'slotOne' => false,
                'slotTwo' => false,
            ],
            'hasPoints' => [
                'home' => false,
                'away' => false,
            ]
        ];
    }
    public function assignTeamName($tId)
    {
        return $this->teamNames[ $this->getPosition($tId) ];
    }
    public function _saveScores($data)
    {
        $file = realpath(__DIR__ .'/../public/concurs.json');
        $f = fopen($file, 'w+');
        fwrite($f, json_encode($data));
        fclose($f);
    }
    public function _getScores()
    {
        $file = realpath(__DIR__ .'/../public/concurs.json');
        return json_decode(file_get_contents($file), true);
    }
    private function getPosition($string)
    {
        return intval(preg_replace('/[a-z]/i', '', $string)) - 1;
    }
    public function getData()
    {
        return [
            'teams' => [
                't1' => [
                    'name' => 'Echipa 1',
                    'players' => ['AlecsandruCorhan', 'marioseer', 'broscoi1']
                ],
                't2' => [
                    'name' => 'Echipa 2',
                    'players' => ['SirLucasIV', '_Syu_', 'zaman95']
                ],
                't3' => [
                    'name' => 'Echipa 3',
                    'players' => ['1Alexandrw', 'ligrivis', 'gabycarutasoiu']
                ],
                't4' => [
                    'name' => 'Echipa 4',
                    'players' => ['xxWOLVERINExxx', 'deniyz', 'stefy2014']
                ],
                't5' => [
                    'name' => 'Echipa 5',
                    'players' => ['uslaro', 'Panzerwaffe', 'ciukash']
                ],
                't6' => [
                    'name' => 'Echipa 6',
                    'players' => ['Deputy_Thunder', 'tenebras', 'robert_adrian2013']
                ],
                't7' => [
                    'name' => 'Echipa 7',
                    'players' => ['DemonSMV', 'dmmoisi', 'Sulla_Felix']
                ],
                't8' => [
                    'name' => 'Echipa 8',
                    'players' => ['acid8urn', 'GX5570', 'KinezGL']
                ],
                't9' => [
                    'name' => 'Echipa 9',
                    'players' => ['Blue_Banana', 'aurel19747777', 'zugamihai']
                ],
                't10' => [
                    'name' => 'Echipa 10',
                    'players' => ['Marius6354 ', 'UrSu77', 'iuga_mari78']
                ],
            ],
            'groups' => [
                'g1' => ['t3', 't4', 't7', 't9', 't10'],
                'g2' => ['t1', 't2', 't5', 't6', 't8'],
            ],
            'matches' => [
                'pos' => ['p1' => 0, 'p2' => 1, 'p3' => 2, 'p4' => 3, 'p5' => 4],
                'qualify' => [
                    'm1'  => ['p1', 'p2'],
                    'm2'  => ['p3', 'p4'],
                    'm3'  => ['p1', 'p3'],
                    'm4'  => ['p2', 'p5'],
                    'm5'  => ['p2', 'p4'],
                    'm6'  => ['p1', 'p5'],
                    'm7'  => ['p1', 'p4'],
                    'm8'  => ['p3', 'p5'],
                    'm9'  => ['p2', 'p3'],
                    'm10' => ['p4', 'p5']
                ],
                'knock-out' => [
                    'ko1' => ['g1:p1', 'g2:p2'],
                    'ko2' => ['g1:p2', 'g2:p1'],
                ],
                'semifinal' => ['ko1:p2', 'ko2:p2'],
                'final' => ['ko1:p1', 'ko2:p1'],
            ],
            'match-scores' => [
                'm1' => [
                    'home' => [
                        'slotOne' => 0,
                        'p2' => 3,
                    ],
                    'away' => [
                        'p1' => 2,
                        'p2' => 3
                    ]
                ]
            ]
        ];
    }
}
