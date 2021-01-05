<?php
class Example {
    private $a;
    private $b;
    public function f1() {
        $this->a = 1;
    }
    public function f2() {
        $this->f3();
    }
    public function f3() {
        $this->a = 2;
    }
    public function f4() {
        $this->b = 3;
    }
    public function f5() {
        $this->b = 4;
    }
}
