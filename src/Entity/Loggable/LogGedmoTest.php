<?php

namespace App\Entity\Loggable;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Loggable\Entity\MappedSuperclass\AbstractLogEntry;
use Gedmo\Loggable\Entity\Repository\LogEntryRepository;

/**
 * @ORM\Table(
 *      options={"row_format":"DYNAMIC","collate"="utf8_general_ci"},
 *      indexes={
 *          @ORM\Index(name="log_class_lookup_idx", columns={"object_class"}),
 *          @ORM\Index(name="log_date_lookup_idx", columns={"logged_at"}),
 *          @ORM\Index(name="log_user_lookup_idx", columns={"username"}),
 *          @ORM\Index(name="log_version_lookup_idx", columns={"object_id", "object_class", "version"})
 *      }
 * )
 * @ORM\Entity(repositoryClass=LogEntryRepository::class)
 */
class LogGedmoTest extends AbstractLogEntry
{
}
