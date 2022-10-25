<?php

/**
 * Defines the structure of the state
 * and provides functions to move the empty space
 * and generate child states from the current one
 */
class Node
{
    public array $data;
    public int $fScore;

    public function __construct(array $data, int $fScore)
    {
        $this->data = $data;
        $this->fScore = $fScore;
    }

    public function generateChildren(): array
    {
        [$x, $y] = $this->findZeroPosition($this->data);
        $zeroPositions = [[$x, $y-1], [$x, $y+1], [$x+1, $y], [$x-1, $y]];
        $children = [];

        foreach ($zeroPositions as $pos) {
            $child = $this->shuffle($this->data, $x, $y, $pos[0], $pos[1]);

            if ($child) {
                $childNode = new Node($child,0);
                $children[] = $childNode;
            }
        }

        return $children;
    }

    public function shuffle($puzzle, $x1, $y1, $x2, $y2): array
    {
        if ($x2 >= 0 && $x2 <= 2 && $y2 >= 0 && $y2 <= 2 ) {
            $tempPuzzle = $this->copy($puzzle);
            $temp = $tempPuzzle[$x2][$y2];
            $tempPuzzle[$x2][$y2] = $tempPuzzle[$x1][$y1];
            $tempPuzzle[$x1][$y1] = $temp;

            return $tempPuzzle;
        } else {
            return [];
        }
    }

    public function findZeroPosition(array $puzzle): array
    {
        for ($i = 0; $i < count($puzzle); $i++) {
            for ($j = 0; $j < count($puzzle[$i]); $j++) {
                if ($puzzle[$i][$j] === 0) {
                    return array($i, $j);
                }
            }
        }

        return [];
    }

    public function copy(array $root): array
    {
        $temp = [];
        foreach ($root as $row) {
            $t = [];
            foreach ($row as $tile) {
                $t[] = $tile;
            }
            $temp[] = $t;
        }
        return $temp;
    }
}