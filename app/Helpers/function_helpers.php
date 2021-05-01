<?php

    function roundName($round)
    {
        if ($round <= 3) {
            $roundNameData =[
                '3' => 'Quarter Final',
                '2' => 'Semi Final',
                '1' => 'Final'
            ];
            $res = '';
            foreach ($roundNameData as $k => $v) {
                if ($k == $round) {
                    $res = $v;
                    break;
                }
            }
            return $res;
        } else {
            return "Round Of ".$round;
        }
    }
