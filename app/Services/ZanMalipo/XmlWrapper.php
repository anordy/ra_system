<?php

namespace App\Services\ZanMalipo;

use DOMDocument;

class XmlWrapper
{

    private $document;
    private $root;

    /**
     * @throws \DOMException
     */
    public function __construct($root_element)
    {
        $this->document = new DOMDocument("1.0", "ISO-8859-15");
        $this->root = $this->document->createElement($root_element);
    }



    public function addToRoot($node)
    {
        $this->root->appendChild($node);
    }
    /**
     * @param $key
     * @param $value
     * @param $node
     * @return \DOMElement|DOMDocument
     * @throws \DOMException
     */
    public function addChild($key, $value, $node = null)
    {
        if (!empty($node)) {
            $node->appendChild($this->document->createElement($key, htmlentities($value, ENT_XML1 | ENT_QUOTES)));
        } else {
            $this->document->appendChild($this->document->createElement($key, htmlentities($value, ENT_XML1 | ENT_QUOTES)));
        }
        return $node ?? $this->document;
    }


    /**
     * @param $key
     * @param $value
     * @param $node
     * @return \DOMElement|
     * @throws \DOMException
     */
    public function addChildrenToNode($array, &$node): \DOMElement
    {
        foreach ($array as $key => $value) {
            $node->appendChild($this->document->createElement($key, htmlentities($value, ENT_XML1 | ENT_QUOTES)));
        }
        return $node;
    }


    /**
     * @param $child
     * @param \DOMNode $parent_node
     * @return \DOMNode
     */
    public function addChildNodeToNode($child, \DOMNode &$parent_node)
    {
        $parent_node->appendChild($child);
        return $parent_node;
    }

    /**
     * @param $key
     * @param $value
     * @return \DOMElement
     * @throws \DOMException
     */
    public function createElement($key, $value = null)
    {
        return $this->document->createElement($key, empty($value) ? '' : htmlentities($value, ENT_XML1 | ENT_QUOTES));
    }

    public function toXML()
    {
        $this->document->appendChild($this->root);
        return $this->document->saveXML($this->document->documentElement);
    }


    public static function xmlStringToArray($xml)
    {
        return json_decode(json_encode((array)simplexml_load_string($xml)), true);
    }
}
