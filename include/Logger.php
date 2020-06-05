<?php
class Logger {

  private static $instance;
  private $filename;

  const DEBUG = 0;
  const INFO = 1;
  const WARN = 2;
  const ERROR = 3;

  private function __construct($filename='.apache2/log/application.log') {
    // log everything
    $this -> filename = $filename;
  }

  public static function instance() {
    if (!isset(self::$instance)) {
      self::$instance = new Logger();
    }
    return self::$instance;
  }

  public function debug($message) {
    return $this -> log(self::DEBUG, $message);
  }

  public function info($message) {
    return $this -> log(self::INFO, $message);
  }

  public function warn($message) {
    return $this -> log(self::WARN, $message);
  }

  public function error($message) {
    return $this -> log(self::ERROR, $message);
  }
  
  private function log($level, $message) {
    $names = array('DEBUG', 'INFO', 'WARN', 'ERROR');
    $timestamp = date("Y-m-d H:i:s", time());
    $fd = fopen($this -> filename, "a");
    if (!is_string($message)) {
      $message = print_r($message, true);
    }
    foreach(preg_split("/((\r?\n)|(\r\n?))/", $message) as $line) {
      fprintf($fd, "%s %s %s\r\n", $timestamp, $names[$level], $line);
    }
    fclose($fd);
  }
}