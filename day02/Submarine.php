<?php

class Submarine{
    private $horizontal_position;
    private $depth;
    private $aim;

    public function __construct(int $initial_horizontal_position = 0, int $initial_depth = 0, int $initial_aim = 0)
    {
        $this->horizontal_position = $initial_horizontal_position;
        $this->depth = $initial_depth;
        $this->aim = $initial_aim;
    }

    public function forward(int $distance = 0) : Submarine
    {
        $this->horizontal_position += $distance;
        $this->depth += $distance * $this->aim;
        return $this;
    }

    public function down(int $distance = 0) : Submarine
    {
        //$this->depth += $distance;
        $this->aim += $distance;
        return $this;
    }

    public function up(int $distance = 0) : Submarine
    {
        //$this->depth -= $distance;
        $this->aim -= $distance;
        return $this;
    }

    public function getHorizontalPosition() : int
    {
        return $this->horizontal_position;
    }

    public function getDepth() : int
    {
        return $this->depth;
    }

    public function getAim() : int
    {
        return $this->aim;
    }

    public function getPositionString() : string
    {
        return "Horizontal: ".$this->horizontal_position."\r\nDepth: ".$this->depth."\r\nAim: ".$this->aim."\r\n";
    }
}