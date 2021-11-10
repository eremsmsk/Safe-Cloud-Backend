<?php

namespace App\GraphQL\InputType;

use App\GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\InputObjectType;
use Symfony\Contracts\Translation\TranslatorInterface;

class InputGedmoTestLangType extends InputObjectType
{
    /** @var string */
    private $customName = "InputGedmoTestLang";

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->customName;
    }

    /**
     * InputCompanyType constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $config = [
            'name' => $this->customName,
            'fields' => [
                'lang' => [
                    'type' => Type::nonNull(Type::string()),
                    'description' => $translator->trans("enterCompanyName")
                ],
                'name' => [
                    'type' => Type::nonNull(Type::string()),
                    'description' => $translator->trans("enterCompanyName")
                ],
            ]
        ];

        parent::__construct($config);
    }
}
