<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210817130931 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dummy_without_attribute ADD product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE dummy_without_attribute ADD CONSTRAINT FK_5F48D9144584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_5F48D9144584665A ON dummy_without_attribute (product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dummy_without_attribute DROP FOREIGN KEY FK_5F48D9144584665A');
        $this->addSql('DROP INDEX IDX_5F48D9144584665A ON dummy_without_attribute');
        $this->addSql('ALTER TABLE dummy_without_attribute DROP product_id');
    }
}
