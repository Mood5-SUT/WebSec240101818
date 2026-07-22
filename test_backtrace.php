<?php

class Base {
    public static function test() {
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        echo "Static test() called. Backtrace object: \n";
        if (isset($trace[1]['object'])) {
            echo "Found object of class: " . get_class($trace[1]['object']) . "\n";
        } else {
            echo "No object found in backtrace.\n";
        }
    }
}

class Child extends Base {
    public function __construct() {
        echo "Child __construct starting...\n";
        $this->test();
        echo "Child __construct finished.\n";
    }
}

$c = new Child();
