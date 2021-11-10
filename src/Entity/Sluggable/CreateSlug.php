<?php

namespace App\Entity\Sluggable;

final class CreateSlug{
    /** @var string|null */
    private $slug;

    /**
     * @param string $string
     * @param mixed $object
     */
    public function __construct(string $string, $object)
    {
        try {
            $this->slug = "";
            $className = get_class($object);
            if ($className != ""){
                $iconv = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $string);
                if (is_string($iconv)){
                    $strToLower = strtolower($iconv);
                    if (is_string($strToLower)){
                        $pregReplace = preg_replace(['/[^a-z0-9]/', '/--+/', '/^-+/', '/-+$/'], ['-', '-', '', ''], $strToLower);
                        if (is_string($pregReplace)){
                            $urlEncode = urlencode($pregReplace);
                            if (is_string($urlEncode)){
                                $this->slug = $urlEncode . "-" . $object->getId();
                            }
                        }
                    }
                }
            }
        }catch (\Exception $exception){
            $this->slug = "";
        }
    }

    /**
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }
}