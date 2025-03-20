<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250320105116 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post ADD artist_id INT NOT NULL, ADD artist_deezer_picture_small LONGTEXT NOT NULL, ADD artist_deezer_picture_medium LONGTEXT NOT NULL, ADD artist_deezer_picture_big LONGTEXT NOT NULL, ADD artist_deezer_picture_xl LONGTEXT NOT NULL, ADD artist_deezer_nb_albums INT NOT NULL, ADD artist_deezer_nb_fans INT NOT NULL, ADD artist_deezer_radio TINYINT(1) NOT NULL, ADD artist_deezer_tracklist LONGTEXT NOT NULL, ADD artist_link LONGTEXT NOT NULL, ADD artist_deezer_type VARCHAR(255) NOT NULL, CHANGE title artist_name VARCHAR(255) NOT NULL, CHANGE content artist_picture LONGTEXT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post ADD title VARCHAR(255) NOT NULL, ADD content LONGTEXT NOT NULL, DROP artist_id, DROP artist_name, DROP artist_picture, DROP artist_deezer_picture_small, DROP artist_deezer_picture_medium, DROP artist_deezer_picture_big, DROP artist_deezer_picture_xl, DROP artist_deezer_nb_albums, DROP artist_deezer_nb_fans, DROP artist_deezer_radio, DROP artist_deezer_tracklist, DROP artist_link, DROP artist_deezer_type');
    }
}
