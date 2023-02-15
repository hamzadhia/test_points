<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230215061413 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attribution_points DROP FOREIGN KEY FK_4B29619869545666');
        $this->addSql('ALTER TABLE user_bonus DROP FOREIGN KEY FK_7D5A8422A76ED395');
        $this->addSql('ALTER TABLE user_bonus DROP FOREIGN KEY FK_7D5A842269545666');
        $this->addSql('DROP TABLE bonus');
        $this->addSql('DROP TABLE user_bonus');
        $this->addSql('DROP INDEX IDX_4B29619869545666 ON attribution_points');
        $this->addSql('ALTER TABLE attribution_points DROP bonus_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bonus (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, points INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user_bonus (user_id INT NOT NULL, bonus_id INT NOT NULL, INDEX IDX_7D5A8422A76ED395 (user_id), INDEX IDX_7D5A842269545666 (bonus_id), PRIMARY KEY(user_id, bonus_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_bonus ADD CONSTRAINT FK_7D5A8422A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_bonus ADD CONSTRAINT FK_7D5A842269545666 FOREIGN KEY (bonus_id) REFERENCES bonus (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE attribution_points ADD bonus_id INT NOT NULL');
        $this->addSql('ALTER TABLE attribution_points ADD CONSTRAINT FK_4B29619869545666 FOREIGN KEY (bonus_id) REFERENCES bonus (id)');
        $this->addSql('CREATE INDEX IDX_4B29619869545666 ON attribution_points (bonus_id)');
    }
}
