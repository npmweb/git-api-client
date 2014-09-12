<?php namespace NpmWeb\GitApiClient;

use Bitbucket\API\Repositories;

class BitbucketApiClient implements GitApiClientInterface {

    protected $repositoriesClient;

    public function __construct( Repositories $repositoriesClient )
    {
        $this->repositoriesClient = $repositoriesClient;
    }

    public function getReposOwnedBy( $account, $language = null )
    {
        $response = $this->repositoriesClient->all('npmweb');
        $this->handleErrorResponse($response);
        $repoNames = $this->extractRepos( $response );

        while( $response = $this->repositoriesClient->next() ) {
            $this->handleErrorResponse($response);
            $repoNames = array_merge( $repoNames, $this->extractRepos( $response, $language ) );
        }

        return $repoNames;
    }

    protected function handleErrorResponse( $response )
    {
        if( 200 != $response->getStatusCode() ) {
            throw new \Exception('Could not connect to Bitbucket API. '.$response->getStatusCode().': '.$response->getReasonPhrase());
        }
    }

    protected function extractRepos( $response, $language = null )
    {
        $responseObj = json_decode($response->getContent());
        $repos = $responseObj->values;
        if( $language ) {
            $repos = array_filter( $repos, function($repo) use($language) {
                return !isset($repo->language) || $language == $repo->language;
            });
        }
        $repos = array_map( function($repo) {
            return (object)array(
                'name' => $repo->name,
                'url' => $repo->links->clone[1]->href,
            );
        }, $repos );
        return $repos;
    }

}