<?php

namespace App\Traits;

trait UriTrait {

    /**
     * Get the uri of the current request with the id of the resource.
     * 
     * @param Request   $request
     * @param int       $id
     * @return String
     */
    public function getUri($request, $id)
    {
        return $request->path() . '/' . $id;
    }
}