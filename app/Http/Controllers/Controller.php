<?php

namespace App\Http\Controllers;

abstract class Controller implements \Illuminate\Routing\Controllers\HasMiddleware
{
    public array $middlewareList = [];
    protected static bool $isResolving = false;

    /**
     * Define middleware for the controller. Called by static::middleware when triggered inside constructor context.
     */
    public function registerMiddleware($middleware)
    {
        $options = new ControllerMiddlewareOptions($middleware);
        $this->middlewareList[] = $options;
        return $options;
    }

    /**
     * Resolve middleware list for Laravel 11/12.
     * Overloaded to handle both static calls by Laravel and constructor $this->middleware(...) calls.
     */
    public static function middleware(...$args): array|ControllerMiddlewareOptions
    {
        // 1. Detect if it was called as an instance method ($this->middleware()) inside the constructor
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        $callerObject = null;
        if (isset($trace[1]['object']) && $trace[1]['object'] instanceof self) {
            $callerObject = $trace[1]['object'];
        }

        if (!empty($args)) {
            if ($callerObject) {
                return $callerObject->registerMiddleware(...$args);
            }
            // Fallback for direct static calls with arguments
            return new ControllerMiddlewareOptions($args[0]);
        }

        // 2. Otherwise it's Laravel resolving the list of middlewares for routing.
        if (static::$isResolving) {
            return [];
        }

        static::$isResolving = true;
        try {
            $instance = new static();
            $middlewares = [];

            foreach ($instance->middlewareList as $options) {
                $m = new \Illuminate\Routing\Controllers\Middleware($options->middleware);
                
                if (!empty($options->only)) {
                    $m->only($options->only);
                }
                
                if (!empty($options->except)) {
                    $m->except($options->except);
                }
                
                $middlewares[] = $m;
            }

            static::$isResolving = false;
            return $middlewares;
        } catch (\Throwable $e) {
            static::$isResolving = false;
            return [];
        }
    }
}

class ControllerMiddlewareOptions
{
    public $middleware;
    public array $only = [];
    public array $except = [];

    public function __construct($middleware)
    {
        $this->middleware = $middleware;
    }

    public function only($methods)
    {
        $this->only = is_array($methods) ? $methods : [$methods];
        return $this;
    }

    public function except($methods)
    {
        $this->except = is_array($methods) ? $methods : [$methods];
        return $this;
    }
}
