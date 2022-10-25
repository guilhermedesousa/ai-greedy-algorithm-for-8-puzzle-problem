<?php

/**
 * Accepts the initial and goal states of the 8-puzzle problem
 * and provides functions to calculate the f-score of any given node
 */

class Puzzle
{
    public array $startSate;
    public array $goalState;
    public array $openNodes;
    public array $closedNodes;
    public static int $size;

    public function __construct()
    {
        $this->startSate = [[1,8,2], [0,4,3], [7,6,5]];
        $this->goalState = [[1,2,3], [4,5,6], [7,8,0]];
        $this->openNodes = [];
        $this->closedNodes = [];
        self::$size = 8;
    }

    public function calculateEvaluationFunction(Node $node): int
    {
        return $this->calculateHeuristic($node->data);
    }

    public function calculateHeuristic(array $node): int
    {
        $h = 0;

        for ($i = 0; $i < 3; $i++) {
            for ($j = 0; $j < 3; $j++) {
                if ($node[$i][$j] !== 0) {
                    $h += $this->getManhattanDistance($node[$i][$j], $i, $j);
                }
            }
        }

        return $h;
    }

    private function getManhattanDistance(int $tile, int $row, int $col): int
    {
        $goal = $this->goalState;

        for ($i = 0; $i < 3; $i++) {
            $j = array_search($tile, $goal[$i]);

            if ($j !== false) {
                return abs($row - $i) + abs($col - $j);
            }
        }

        return 0;
    }

    private static function sortByFscore(Node $child1, Node $child2): int
    {
        if ($child1->fScore == $child2->fScore) {
            return 0;
        }
        return ($child1->fScore < $child2->fScore) ? -1 : 1;
    }

    public function process(): void
    {
        $init = new Node($this->startSate, 0);
        $init->fScore = $this->calculateEvaluationFunction($init);

        $this->openNodes[] = $init;
        $found = false;
        $qtyNodes = 0;

        while (!$found) {
            $cur = $this->openNodes[0];
            $qtyNodes++;

            foreach ($cur->data as $puzzle) {
                foreach ($puzzle as $tile) {
                    echo "$tile ";
                }
                echo PHP_EOL;
            }
            echo PHP_EOL;

            if ($this->calculateHeuristic($cur->data) === 0) {
                $found = true;
                echo "Number of nodes generated: $qtyNodes" . PHP_EOL;
            }

            foreach ($cur->generateChildren() as $child) {
                $child->fScore = $this->calculateEvaluationFunction($child);
                $this->openNodes[] = $child;
            }

            $this->closedNodes[] = $cur;
            array_shift($this->openNodes);

            usort($this->openNodes, array('Puzzle', 'sortByFscore'));
        }
    }
}