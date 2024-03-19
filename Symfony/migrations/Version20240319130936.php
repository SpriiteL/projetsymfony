<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240319130936 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favoris ADD users_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE favoris ADD products_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C43267B3B43D FOREIGN KEY (users_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C4326C8A81A9 FOREIGN KEY (products_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8933C43267B3B43D ON favoris (users_id)');
        $this->addSql('CREATE INDEX IDX_8933C4326C8A81A9 ON favoris (products_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE favoris DROP CONSTRAINT FK_8933C43267B3B43D');
        $this->addSql('ALTER TABLE favoris DROP CONSTRAINT FK_8933C4326C8A81A9');
        $this->addSql('DROP INDEX IDX_8933C43267B3B43D');
        $this->addSql('DROP INDEX IDX_8933C4326C8A81A9');
        $this->addSql('ALTER TABLE favoris DROP users_id');
        $this->addSql('ALTER TABLE favoris DROP products_id');
    }
}
