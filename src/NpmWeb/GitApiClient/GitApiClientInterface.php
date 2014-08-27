<?php namespace NpmWeb\GitApiClient;

interface GitApiClientInterface {

    public function getReposOwnedBy( $account, $language = null );

}
