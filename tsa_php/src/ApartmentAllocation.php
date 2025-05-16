<?php

function solveApartment($A, $B, $N, $M, $K) {
    /**
     * @param array $A - Array of integers representing the minimum apartment sizes each applicant desires.
     * @param array $B - Array of integers representing the sizes of available apartments.
     * @param int $N - Number of applicants (length of array A).
     * @param int $M - Number of available apartments (length of array B).
     * @param int $K - Maximum allowable size difference for a valid match.
     * @return int - Maximum number of apartments that can be allocated to applicants.
     */

   sort($A);
sort($B);

$i = 0;
$j = 0;
$matches = 0;

while ($i < $N && $j < $M) {
    if (abs($A[$i] - $B[$j]) <= $K) {
        $matches++;
        $i++;
        $j++;
    } elseif ($B[$j] < $A[$i] - $K) {
        $j++;
    } else {
        $i++;
    }
}


    return $matches;
}