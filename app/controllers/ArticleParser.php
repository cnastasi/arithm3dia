<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Psr\Log\LoggerInterface;

/**
 * Description of Engine
 *
 * @author christian
 */
class ArticleParser {

    private $path;
    private $content;
    private $conjunctions = [
        "a",
        'at',
        'to',
        'in',
        'of',
        'from',
        'is',
        'have',
        's',
        'has',
        'as',
        'few',
        'the',
        'like',
        'more',
        'than',
        'their',
        'i',
        'am',
        'you',
        'he',
        'she',
        'it',
        'they',
        'your',
        'wich',
        'according',
        'or',
        'and',
        'that',
        'there',
        'for',
        'its',
        'that',
        'helped',
        'problably',
        'one',
        'many', 'more',
        'most', 'last', 'over',
        'with', 'not', 'but', 'with',
        'an', 'by', 'next', 'want', 'find', 'say', 'what', 'put', 'them', 'were', 'become', 'too', 'an', 'come',
        'me', 'say', 'dont', 'two', 'we', 'out', 'then', 'no', 'if', 'was', 'reduce', 'within', 'this', 'when', 'continue',
        'partially', 'feels', 'raucous', 'into', 'start', 'run', 'particularly', 'high', 'off',  'ago', 'made', 'make', 'almost',
        'entered', 'mark', 'since', 'after', 'are', 'very', 'really', 'often', 'highly', 'even', 'wich', 'part', 'on', 'will', 'us', 
    ];
    private $punctuation  = ['.', ',', ']', '[', '(', ')', "'", '"', ' - ', '!', '?'];
    private $result;
    private $terms;
    private $logger;
    private $totalSentences;

    public function __construct($path, LoggerInterface $logger = null) {
        $this->path   = $path;
        $this->terms  = [];
        $this->logger = $logger;
    }

    public function load() {
        $this->logger->info("Loading: {$this->path}");
        $this->content = file_get_contents($this->path);
    }

    public function parse() {
        $this->change("\n", " ");
        $this->change('.', "\n");

        $rows = explode("\n", $this->content);

        foreach ($rows as $line) {
            $words = $this->normalizeLine($line);

            if (count($words) > 0) {
                $this->result[] = $words; //array_count_values($words);  
            }
        }

        $this->totalSentences = count($this->result);

        $this->logger->info("Parsed {$this->totalSentences} sentences");
    }

    public function save() {
        $counter = 0;
        
        foreach ($this->result as $sentence) {
            $this->logger->info("Saving: " . implode(' ', $sentence));
            $this->saveTerms($sentence);
            $this->saveLinks($sentence);

            $counter ++;
            $this->logger->info("Saved: {$counter}/{$this->totalSentences}");
        }
    }

    private function normalizeLine($line) {

        $line  = str_replace($this->punctuation, '', $line);
        $line  = strtolower($line);
        $words = explode(' ', $line);

        foreach ($words as $key => &$word) {
            if (in_array($word, $this->conjunctions) || trim($word) === '') {
                unset($words[$key]);
            }
        }

        return $words;
    }

    private function exclude($words) {
        foreach ($words as $word) {
            $this->content = preg_replace("/\\b($word)/i", ' ', $this->content);
        }
    }

    private function change($str, $replace) {
        $this->content = str_replace($str, $replace, $this->content);
    }

    public function getContent() {
        return explode("\n", $this->content);
    }

    public function getResult() {
        return $this->result;
    }

    private function saveTerms($sentence) {
        foreach ($sentence as $word) {
            $this->saveTermsOnDB($word);
            //$this->saveLinks($sentence);            
        }
    }

    private function saveLinks(array &$sentence) {
        // echo "\n";
        // print_r($sentence);
        $term1 = array_shift($sentence);

        if (count($sentence) == 0) {
            return;
        }

        $id1 = $this->terms[$term1];

        //echo "\nTerm " . implode(',', $sentence) .  "<br>";

        $tmp = [];
        
        foreach ($sentence as $term2) {
            $id2 = $this->terms[$term2];

            $tmp [] = [$id1, $id2];
            //echo "Saving [ $term1 | $term2 ]\n";
            
        }
        
        $this->saveLinkOnDB($tmp);

        $this->saveLinks($sentence);
    }

//    private function saveLinksOfATerm($sentence, $term) {
//        foreach ($sentence as $term2) {
//            if ($term2 !== $term) {
//                $this->saveLinkOnDB($this->terms[$term], $this->terms[$term2]);
//            }
//        }
//    }

    private function saveTermsOnDB($term) {
        $now = Carbon::now();

        if (!isset($this->terms[$term])) {
            $result = DB::table('terms')->select('id')->where('name', $term)->first();

            if (!$result) {
                $id                 = DB::table('terms')->insertGetId(['name' => $term, 'created_at' => $now, 'updated_at' => $now]);
                $this->terms[$term] = $id;
            } else {
                $this->terms[$term] = $result->id;
            }
        }
        return $this->terms[$term];
    }

    private function saveLinkOnDB(array $ids) {
       // sort($ids);
        
        $query = "INSERT INTO links (link1, link2, weight, created_at, updated_at) VALUES "; 
        
        $queryParts = [];
        
        foreach ($ids as $idGroup) {
            sort($idGroup);
            $queryParts[] = "({$idGroup[0]}, {$idGroup[1]}, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
        }
        
        $query .= implode(',', $queryParts);
        
        $query .= " ON DUPLICATE KEY UPDATE weight=weight+1;";
               
        DB::statement($query);
    }

    public function getTerms() {
        return $this->terms;
    }

}
