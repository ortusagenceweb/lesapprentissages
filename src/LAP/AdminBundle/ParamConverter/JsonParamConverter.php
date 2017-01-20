<?php
namespace LAP\AdminBundle\ParamConverter;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

class JsonParamConverter implements ParamConverterInterface
{
    function supports(ParamConverter $configuration)
    {
        // If controller argument name isn't "json", false
        if( 'json' !== $configuration->getName() ) {
            return false;
        }

        return true;
    }

    function apply(Request $request, ParamConverter $configuration)
    {
        // Getting the actual value of the attribute
        $json = $request->attributes->get('json');

        // Decode action
        $json = json_decode($json, true);

        // Updating the new value of the attribute
        $request->attributes->set('json', $json);
    }
}