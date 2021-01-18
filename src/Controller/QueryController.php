<?php

namespace APIF\Sparql\Controller;

use APIF\Sparql\Repository\GraphRepositoryInterface;
use ARC2;
use Laminas\Mvc\Controller\AbstractRestfulController;
use APIF\Core\Service\JsonModel;
use APIF\Core\Repository\APIFCoreRepositoryInterface;


class QueryController extends AbstractRestfulController
{
    private $_config;
    private $_repository;
    private $_apif_repository;
    //private $_activityLog;

    public function __construct(GraphRepositoryInterface $repository, APIFCoreRepositoryInterface $apif_repository, array $config)
    {
        $this->_config = $config;
        $this->_repository = $repository;
        $this->_apif_repository = $apif_repository;
        //$this->_activityLog = $activityLog;
    }

    private function _getAuth() {
        //Check AUTH has been passed
        $request_method = $_SERVER["REQUEST_METHOD"];
        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            header('WWW-Authenticate: Basic realm="Realm"');
            header('HTTP/1.0 401 Unauthorized');
            echo 'Dataset key must be provided as HTTP Basic Auth username';
            exit;
        } else {
            $auth = [
                'user'  => $_SERVER['PHP_AUTH_USER'],
                'pwd'   => $_SERVER['PHP_AUTH_PW']
            ];
            return $auth;
        }
    }

    /*
     *
     *
      "@type": ["al:Create", "al:ActivityLogEntry"],
        "al:datasetId": "datahub__activity_log",
        "al:documentId": "datahub__activity_log",
    "al:summary": "short description",

        "al:request": {
      "@type": "al:HTTPRequest",
      "al:agent": {
        "@type": "al:Agent",
        "al:key": "datahub__activity_log_key123"
      },
      "al:endpoint": "http://apif-beta.local/object/datahub__activity_log",
      "al:httpRequestMethod": "al:POST",
      "al:parameters": [],
      "al:payload": "{}"
    },

     *
     */

    /*
    private function _handleException($ex) {
        if (is_a($ex, MongoDB\Driver\Exception\AuthenticationException::class) ){
            $this->getResponse()->setStatusCode(403);
        }elseif(is_a($ex->getPrevious(), MongoDB\Driver\Exception\AuthenticationException::class)){
            $this->getResponse()->setStatusCode(403);
        }elseif(is_a($ex, \Throwable::class)){
            $this->getResponse()->setStatusCode(500);
        }else{
            // This will never happen
            $this->getResponse()->setStatusCode(500);
        }
    }
    */

    /*
     * GET - Handling a GET request
     * brings back all docs from a dataset (subject to limit), or a query
     * if query param is provided
     */
    public function get($id) {
        $key = $this->_getAuth()['user'];
        $pwd = $this->_getAuth()['pwd'];
        $queryParam = $this->params()->fromQuery('query', null);

        /* parser instantiation */
        $parser = ARC2::getSPARQLParser();


        if (is_null($queryParam)){
            $this->getResponse()->setStatusCode(400);
            return new JsonModel(['error' => 'Bad GET request, missing query parameter']);
        }

        if ($this->_apif_repository->checkReadAccess($id, $key, $pwd)) {
            //has read access
            $acceptedQueryTypes = [
                'select',
                'ask',
                'construct'
            ];
            $parser->parse($queryParam);
            if (!$parser->getErrors()) {
                $q_infos = $parser->getQueryInfos();
                $queryType = $q_infos['query']['type'];
                if (!in_array($queryType, $acceptedQueryTypes)) {
                    $this->getResponse()->setStatusCode(400);
                    return new JsonModel(['error' => 'sparql query type not allowed. Query must be of type: '.implode(",",$acceptedQueryTypes)]);
                }
                //print_r($queryType);
            }
            else {
                //echo "invalid query: " . print_r($parser->getErrors());
                $this->getResponse()->setStatusCode(400);
                return new JsonModel(['error' => 'invalid sparql query', 'details' => json_encode($parser->getErrors())]);
            }

            $data = $this->jsonDecode($this->_repository->sparqlQuery($id,$queryParam,"json"));
            return new JsonModel($data);
        }
        else {
            //doesn't have read access
            $this->getResponse()->setStatusCode(401);
            return new JsonModel(['error' => 'Unauthorized: You do not have read access to this dataset']);
        }

        $data = [
            'message' => 'Success'
        ];
        return new JsonModel($data);
    }

    /*
    public function getList() {
    }
    */

    /*
     * CREATE - Handling a POST request
    public function create($data) {
    }
    */

    /*
     * UPDATE - Handling a PUT request
    public function update($id, $data) {
    }
    */

    /*
     * DELETE - Handling a DELETE request
    public function delete($id)  {
    }
    */

}