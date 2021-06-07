<?php


namespace APIF\Sparql\Service;


class SPARQLQueryType implements SPARQLQueryTypeInterface
{
    private $_config;

    public function __construct($config)
    {
        $this->_config = $config;
    }

    const UPDATE_TERMS = array('insert', 'delete', 'create', 'drop', 'copy', 'clear', 'move');
    const QUERY_TERMS = array('select', 'describe', 'construct', 'ask');

    static function isUPDATE($query){
        return in_array(self::guess($query), self::UPDATE_TERMS);
    }

    static function isQUERY($query){
        return in_array(self::guess($query), self::QUERY_TERMS);
    }

    static function guess($query){
        // Remove comments
        $q = preg_replace("/^#.*$/mi", "", $query);
        // Remove prefix declarations
        $q = preg_replace("/\s*prefix\s*[^:]+:\s+<[^>]+\>\s/mis", "", $q);
        // We assume UPDATE commands are not included within brackets, as per SPARQL syntax spec.
        // Remove content within {}
        $q = self::stripBetween($q,'{}');
        // Remove content within ()
        $q = self::stripBetween($q,'()');
        // Remove Strings
        $q = self::stripQuotedStrings($q);
        // Get first term
        $q = strtolower(trim($q));

        $terms = preg_split("/\s+/mis", $q);

        $update = array_values(array_intersect($terms, self::UPDATE_TERMS));
        if (!empty($update)){
            return $update;
        }
        $query = array_values(array_intersect($terms, self::QUERY_TERMS));
        return $query;
    }

    static function stripQuotedStrings($q){
        $q = preg_replace('/""".*"""/mis', "", $q);
        $q = preg_replace('/".*"/mi', "", $q);
        return $q;
    }

    static function removeVariables($q){
        return preg_replace("/\?[a-z_0-9A-Z]+/mi", " ", $query);
    }

    static function stripBetweenCurlyBrackets($q){
        return self::stripBetween($q,'{}');
    }

    static function stripBetween($q,$brackets){
        $left = str_split($brackets)[0];
        $right = str_split($brackets)[1];
        $opened = 0;
        $array = str_split($q);
        $pos = 0;
        $keep = "";
        foreach($array as $c){
            if ($c == $left){
                $opened += 1;
            }else if ($c == $right){
                $opened -= 1;
            }else if($opened == 0){
                // Ignore characters within curly brackets
                $keep .= $c;
            }
            $pos++;
        }
        return $keep;
    }
}