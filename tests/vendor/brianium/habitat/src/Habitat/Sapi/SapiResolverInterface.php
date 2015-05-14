<?php
namespace Habitat\Sapi;

interface SapiResolverInterface
{
    /**
     * Returns whether or not the current sapi
     * is cli or not
     *
     * @return bool
     */
    public function isCli();
}