<?php

namespace XmlProcessor;

class ProgramsXml
{
    private $xml;
    private $tripsXml;
    private $descriptorsXml;

    public function __construct($programsXmlFilePath, DescriptorsXml $descriptorsXml, TripsXml $tripsXml = null) 
    {
        $this->xml = new \DOMDocument();        
        $this->xml->load($programsXmlFilePath);
        $this->descriptorsXml = $descriptorsXml;
        $this->tripsXml = $tripsXml;
    }

    public function getPrograms()
    {
        $xpath = new \DOMXPath($this->xml);
        $programs = $xpath->query('//program');

        $programData = [];
        foreach ($programs as $program) {
            $programId = $program->getAttribute('id');
            $serial = $xpath->evaluate('string(serial)', $program);
            $name = $xpath->evaluate('string(name)', $program);

            $tags = $xpath->evaluate('tags/tag', $program);
            $tagNames = [];
            foreach ($tags as $tag) {
                $tagId = $tag->getAttribute('id');
                $tagName = $this->descriptorsXml->getTagName($tagId);
                $tagNames[] = $tagName;
            }            
            $firstImage = $xpath->evaluate('string(images/img[1]/@src)', $program);

            $rooms = $xpath->evaluate('rooms/room', $program);
            $availableRooms = [];
            foreach ($rooms as $room) {
                $roomId = $room->getAttribute('id');
                $roomName = $room->nodeValue;
                $numPeople = $room->getAttribute('num_people');
                $availableRooms[] = [
                'id' => $roomId,
                'name' => $roomName,
                'num_people' => $numPeople,
                ];
            }

            $shortDescription = $xpath->evaluate('string(descriptions/paragraph[not(@id)]/text)', $program);
            $shortDescription = strip_tags($shortDescription); // Remove HTML tags
            $shortDescription = html_entity_decode($shortDescription, ENT_QUOTES | ENT_HTML5, 'UTF-8'); // Convert HTML entities to UTF-8 characters
            $shortDescription = preg_replace('/&#?[a-z0-9]+;/i', '', $shortDescription); // Remove remaining HTML entities
            $shortDescription = substr($shortDescription, 0, 180); // Limit to 180 characters
            
            $locationValue = $this->getLocationFromProgram($xpath, $program);            
            
            $programData[] = [
            'id' => $programId,
            'serial' => $serial,
            'name' => $name,
            'tags' => $tagNames,
            'image' => $firstImage,
            'available_rooms' => $availableRooms,
            'short_description' => $shortDescription,
            'location' => $locationValue
            ];
        }

        return $programData;
    }

    

   
    public function getTripsForProgram($programId)
    {
        $associatedTrips = $this->tripsXml->getTripsByProgramId($programId);
        return $associatedTrips;
    }

    public function getProgramById($programId)
    {
        $xpath = new \DOMXPath($this->xml);
        $query = sprintf('//program[@id="%s"]', $programId);
        $programNode = $xpath->query($query)->item(0);

        if ($programNode) {
            $programData = [
            'id' => $programNode->getAttribute('id'),
            'serial' => $xpath->evaluate('string(serial)', $programNode),
            'name' => $xpath->evaluate('string(name)', $programNode),
            'tags' => [],
            'images' => [],
            'available_rooms' => [],
            'short_description' => '',
            'locations' => '',
            ];

            $tags = $xpath->evaluate('tags/tag', $programNode);
            foreach ($tags as $tag) {
                $tagId = $tag->getAttribute('id');
                $tagName = $this->descriptorsXml->getTagName($tagId);
                $programData['tags'][] = $tagName;
            }

            $imageNodes = $xpath->evaluate('images/img/@src', $programNode);
            foreach ($imageNodes as $imageNode) {
                $programData['images'][] = $imageNode->nodeValue;
            }

       
            $rooms = $xpath->evaluate('rooms/room', $programNode);
            foreach ($rooms as $room) {
                $roomId = $room->getAttribute('id');
                $roomName = $room->nodeValue;
                $numPeople = $room->getAttribute('num_people');
                $programData['available_rooms'][] = [
                'name' => $roomName,
                'num_people' => $numPeople,
                ];
            }

        
            $description = $xpath->evaluate('string(descriptions/paragraph[not(@id)]/text)', $programNode);        
            $description = html_entity_decode($description, ENT_QUOTES | ENT_HTML5, 'UTF-8');         
            $programData['description'] = $description;
            $programData['locations'] = $this->getLocationFromProgram($xpath, $programNode);           

            $trips = $this->getTripsForProgram($programId);
            $programData['trips'] = $trips;

            return $programData;
        }

        return null; 
    }

    private function getLocationFromProgram($xpath, $programNode)
    {
        $locationNodes = $xpath->evaluate('locations/location', $programNode);
            $locations = [];
            foreach ($locationNodes as $locationNode) {
                $locationValue = $this->descriptorsXml->getLocationValue($locationNode->getAttribute('id'));
                if($locationValue){
                    $locations[] = $locationValue;
                }                
            }
            return implode(' / ', $locations);
    }    

}
