<?php

namespace XmlProcessor;

class TripsXml
{
    private $descriptorsXml;
    private $xml;
    public function __construct($filePath, DescriptorsXml $descriptorsXml) 
    {
        $this->xml = new \DOMDocument();     
        $this->descriptorsXml = $descriptorsXml;   
        $this->xml->load($filePath);
    }

    public function getTripsByProgramId($programId)
{
    $xpath = new \DOMXPath($this->xml);
    $trips = $xpath->query("//t[@p='$programId']");

    $associatedTrips = [];
    foreach ($trips as $trip) {
        $attributes = $trip->attributes;
        $tripAttributes = [];
        $attributes = $trip->attributes;
        foreach ($attributes as $attribute) {
            $tripAttributes[$attribute->nodeName] = $attribute->nodeValue;
        }

        $prices = [];

        $priceElements = $xpath->query("p", $trip);
        foreach ($priceElements as $priceElement) {
            $priceValue = $priceElement->getAttribute('v');
            $priceCurrency = $priceElement->getAttribute('c');
            $prices[] = [
                'value' => $priceValue,
                'currency' => $this->descriptorsXml->getCurrencyName($priceCurrency),
            ];
        }
        $tripAttributes['prices'] = $prices;

        $associatedTrips[] = $tripAttributes;
    }

    return $associatedTrips;
}

public function displayDiscount($discountCode)
{
    if($discountCode > 0){
        switch($discountCode){
            case 1:
                return '<span class="badge badge-info">Akció</span>';
            case 2:
                return '<span class="badge badge-info">First-minute</span>';
            case 3:
                return '<span class="badge badge-info">Last-minute</span>';
            case 4:
                return '<span class="badge badge-info">Besíző</span>';
           
        }
    }
}

}