<?php

use Illuminate\Console\Command;

class ImportDataCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'data:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();        
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire() {
        $parser = new ArticleParser(storage_path('article1.txt'), new CLILogger($this));

        $parser->load();
        $parser->parse();
        $parser->save();

        return $parser->getTerms();
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments() {
        return array(
                //array('example', InputArgument::REQUIRED, 'An example argument.'),
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions() {
        return array(
                //array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
        );
    }

}
