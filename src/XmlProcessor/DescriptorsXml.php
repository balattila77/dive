<?php

namespace XmlProcessor;

class DescriptorsXml
{
    private $xml;

    public function __construct($xmlFilePath)
    {
        $this->xml = simplexml_load_file($xmlFilePath);
    }

    public function getTagName($tagId)
    {
        $tag = $this->xml->xpath("//tags/tag[@id='$tagId']");
        return (string)($tag[0] ?? '');
    }

    public function getCurrencyName($currencyId)
    {
        $tag = $this->xml->xpath("//currencies/currency[@id='$currencyId']");
        $code = (string)($tag[0]['code'] ?? '');
        return $code;
    }


    public function getLocationValue($locationId)
    {
        $tags = $this->xml->xpath("//locations/location[@id='$locationId']");
        $locationValue = null;

        foreach ($tags as $tag) {
            $type = (string) $tag['type'];
            $value = trim((string) $tag);

            if (in_array($type, ['co', 'ci']) && $value !== 'Európa') {
                $locationValue = $value;                
            }
        }

        return $locationValue;
    }

    

    
}
