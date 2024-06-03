<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240603075616 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_variant ADD products_id INT NOT NULL');
        $this->addSql('ALTER TABLE product_variant ADD CONSTRAINT FK_209AA41D6C8A81A9 FOREIGN KEY (products_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_209AA41D6C8A81A9 ON product_variant (products_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_variant DROP FOREIGN KEY FK_209AA41D6C8A81A9');
        $this->addSql('DROP INDEX IDX_209AA41D6C8A81A9 ON product_variant');
        $this->addSql('ALTER TABLE product_variant DROP products_id');
    }
}
