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

    public function sparqlQuery ($dataset, $query, $headers, $resultsFormat) {
        //get "accept" header from $headers. If not present, set to */*
        if (array_key_exists('Accept', $headers)) {
            $resultsFormatHeader = $headers['Accept'];
        }
        else {
            $resultsFormatHeader = "*/*";
        }
        //if resultsFormat parameter explicitly passed, overwrite accept header
        if (!is_null($resultsFormat) && array_key_exists($resultsFormat,$this->_responseTypes)) {
            $resultsFormatHeader = $this->_responseTypes[$resultsFormat];
        }
        $headers['Accept'] = $resultsFormatHeader;

        //format headers for cURL
        $headersFormatted = array();
        foreach ($headers as $key => $value) {
            $headersFormatted[] = $key.': '.$value;
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
            CURLOPT_HTTPHEADER => $headersFormatted,
        ));

        $response = curl_exec($curl);
        $curlInfo = null;
        if (!curl_errno($curl)) {
            $curlInfo = curl_getinfo($curl);
        }
        curl_close($curl);
        $data = [
            'response' => $response,
            'curlInfo' => $curlInfo
        ];
        return $data;
    }


}