<?php

/**
 * Class Event
 * 事件监听, 通常用于事件中的过程处理
 */
class Event {

    /**
     * 监听事件
     * @var array
     */
    protected static $events = [
        'auth.attempt' => null,
        'auth.login' => null,
        'auth.logout' => null,
    ];

    public static function listen($name, callable $event) {
        $events = self::$events;
        if(array_key_exists($name, $events)) {
            self::$events[] = $event;
        }
    }

    public static function fire($name) {
        $events = self::$events;
        if(array_key_exists($name, $events) && !is_null($events[$name])) {
            foreach($events[$name] as $event) {
                call_user_func_array($event, []);
            }
        }
    }

}