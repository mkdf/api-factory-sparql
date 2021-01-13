<?php


namespace APIF\Sparql\Repository;


class GraphRepository implements GraphRepositoryInterface
{
    private $_config;
    private $_baseUrl;

    public function __construct($config)
    {
        $this->_config = $config;
        $this->_baseUrl = "http://".$this->_config['sparql']['host'].":".$this->_config['sparql']['port']."/blazegraph/namespace/";
    }

    /**
     * @param $id
     * @param $key
     * @param $pwd
     * @return bool
     */
    public function checkReadAccess($dataset, $key, $pwd): bool
    {
        return true;
    }

    public function sparqlQuery ($dataset, $query, $resultsFormat) {
        $url = $this->_baseUrl . $dataset . "/sparql";
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
                "Accept: application/sparql-results+json",
                "Content-Type: application/x-www-form-urlencoded"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }


}