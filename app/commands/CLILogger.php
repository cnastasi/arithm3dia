<?php

use Illuminate\Console\Command;
use Psr\Log\LoggerInterface;

/**
 * Description of CLILogger
 *
 * @author christian
 */
class CLILogger implements LoggerInterface {
    private $command;
    
    public function __construct(Command $command) {
        $this->command = $command;
    }
    
    public function alert($message, array $context = array()) {
        $this->command->error($message . ' ');
    }

    public function critical($message, array $context = array()) {
        $this->command->error($message . ' ');
    }

    public function debug($message, array $context = array()) {
        $this->command->comment($message . ' ');
    }

    public function emergency($message, array $context = array()) {
        $this->command->comment($message . ' ');
    }

    public function error($message, array $context = array()) {
        $this->command->error($message . ' ');
    }

    public function info($message, array $context = array()) {
        $this->command->info($message . ' ');
    }

    public function log($level, $message, array $context = array()) {
        $this->command->info($message . ' ');
    }

    public function notice($message, array $context = array()) {
        $this->command->comment($message . ' ');
    }

    public function warning($message, array $context = array()) {
        $this->command->comment($message . ' ');
    }
}
