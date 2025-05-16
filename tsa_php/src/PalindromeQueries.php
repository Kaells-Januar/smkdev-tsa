<?php

define('HASH', 257);
define('MOD', 1000000007);

class HashSegmentTree {
    private $n;
    private $tree;

    public function __construct($n) {
        $this->n = $n;
        $this->tree = array_fill(0, 2 * $n, 0);
    }

    public function update($index, $value) {
        $index += $this->n;
        $this->tree[$index] = $value;
        while ($index > 1) {
            $index >>= 1;
            $this->tree[$index] = ($this->tree[$index * 2] + $this->tree[$index * 2 + 1]) % MOD;
        }
    }

    public function query($l, $r) {
        $result = 0;
        $l += $this->n;
        $r += $this->n + 1;
        while ($l < $r) {
            if ($l % 2 == 1) $result = ($result + $this->tree[$l++]) % MOD;
            if ($r % 2 == 1) $result = ($result + $this->tree[--$r]) % MOD;
            $l >>= 1;
            $r >>= 1;
        }
        return $result;
    }
}

function initializeHashPowers($length) {
    $hashPower = array_fill(0, $length, 1);
    for ($i = 1; $i < $length; $i++) {
        $hashPower[$i] = ($hashPower[$i - 1] * HASH) % MOD;
    }
    return $hashPower;
}

function initializeHashTables($n, $s, $hashPower) { 
    $fwdHash = new HashSegmentTree($n);
    $bckHash = new HashSegmentTree($n);

    for ($i = 0; $i < $n; $i++) {
        $charCode = ord($s[$i]);
        $fwdHash->update($i, ($charCode * $hashPower[$i]) % MOD);
        $bckHash->update($n - 1 - $i, ($charCode * $hashPower[$i]) % MOD);
    }

    return ['fwdHash' => $fwdHash, 'bckHash' => $bckHash];
}

function processOperations($n, $operations, $s) {
    $hashPower = initializeHashPowers($n);
    $hashTables = initializeHashTables($n, $s, $hashPower);
    $fwdHash = $hashTables['fwdHash'];
    $bckHash = $hashTables['bckHash'];
    $s = str_split($s);
    $results = [];

    foreach ($operations as $op) {
        if ($op[0] === "1") {
            $index = intval($op[1]);
            $char = $op[2];
            $s[$index] = $char;
            $charCode = ord($char);
            $fwdHash->update($index, ($charCode * $hashPower[$index]) % MOD);
            $bckHash->update($n - 1 - $index, ($charCode * $hashPower[$index]) % MOD);
        } elseif ($op[0] === "2") {
            $l = intval($op[1]);
            $r = intval($op[2]);
            $fwd = $fwdHash->query($l, $r);
            $bck = $bckHash->query($n - 1 - $r, $n - 1 - $l);
            $results[] = ($fwd == $bck) ? "YES" : "NO";
        }
    }

    return $results;
}

?>
