<?php
  
  namespace Swaggest\JsonSchema\RemoteRef;
  
  use Swaggest\JsonSchema\RemoteRefProvider;
  
  class BasicFetcher implements RemoteRefProvider
  {
    public function getSchemaData($url)
    {
      $arrContextOptions = [
        "ssl" => [
          "verify_peer"      => FALSE,
          "verify_peer_name" => FALSE,
        ],
      ];
      
      if ($data = file_get_contents(rawurldecode($url), FALSE, stream_context_create($arrContextOptions))) {
        return json_decode($data);
      }
      return false;
    }
  }