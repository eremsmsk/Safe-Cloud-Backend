<?php

namespace App\Entity\Translatable;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractTranslation;

/**
 * @ORM\Table(
 *      indexes={
 *          @ORM\Index(name="lang_idx", columns={"locale", "object_class", "field", "foreign_key"})
 *      }
 * )
 * @ORM\Entity(repositoryClass="Gedmo\Translatable\Entity\Repository\TranslationRepository")
 */
class LangGedmoTest extends AbstractTranslation
{
}