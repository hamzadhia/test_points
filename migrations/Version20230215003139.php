<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230215003139 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE attribution_points (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, bonus_id INT NOT NULL, active_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_4B296198A76ED395 (user_id), INDEX IDX_4B29619869545666 (bonus_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE attribution_points ADD CONSTRAINT FK_4B296198A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE attribution_points ADD CONSTRAINT FK_4B29619869545666 FOREIGN KEY (bonus_id) REFERENCES bonus (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attribution_points DROP FOREIGN KEY FK_4B296198A76ED395');
        $this->addSql('ALTER TABLE attribution_points DROP FOREIGN KEY FK_4B29619869545666');
        $this->addSql('DROP TABLE attribution_points');
    }
}
