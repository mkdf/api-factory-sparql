<?php


namespace APIF\Sparql\Repository;


class GraphRepository implements GraphRepositoryInterface
{
    private $_config;
    private $_baseUrl;
    private $_responseTypes;

    public function __construct($config)
    {
        $this->_config = $config;
        $this->_baseUrl = "http://".$this->_config['sparql']['host'].":".$this->_config['sparql']['port']."/blazegraph/namespace/";
        $this->_responseTypes = [
            'json' => 'application/sparql-results+json',
            'xml' => 'application/sparql-results+xml',
            'csv' => 'text/csv',
            'tab' => 'text/tab-separated-values',
            'binary' => 'application/x-binary-rdf-results-table'
        ];
    }

    public function sparqlQuery ($dataset, $query, $resultsFormat) {
        if (array_key_exists($resultsFormat,$this->_responseTypes)) {
            $resultsFormatHeader = $this->_responseTypes[$resultsFormat];
        }
        else {
            $resultsFormatHeader = $this->_responseTypes['json'];
        }
        $url = $this->_baseUrl . $this->_config['sparql']['namespacePrefix'] . $dataset . "/sparql";
        $postFields = "query=" . urlencode($query);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_HTTPHEADER => array(
                "Accept: ".$resultsFormatHeader,
                "Content-Type: application/x-www-form-urlencoded"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }


}