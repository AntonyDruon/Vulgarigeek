<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220724131840 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaireapplication ADD utilisateur_id INT NOT NULL');
        $this->addSql('ALTER TABLE commentaireapplication ADD CONSTRAINT FK_7F221F39FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_7F221F39FB88E14F ON commentaireapplication (utilisateur_id)');
        $this->addSql('ALTER TABLE commentairearticle ADD utilisateur_id INT NOT NULL');
        $this->addSql('ALTER TABLE commentairearticle ADD CONSTRAINT FK_EF8D3962FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_EF8D3962FB88E14F ON commentairearticle (utilisateur_id)');
        $this->addSql('ALTER TABLE commentairelogiciel ADD utilisateur_id INT NOT NULL');
        $this->addSql('ALTER TABLE commentairelogiciel ADD CONSTRAINT FK_2BD015B2FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_2BD015B2FB88E14F ON commentairelogiciel (utilisateur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaireapplication DROP FOREIGN KEY FK_7F221F39FB88E14F');
        $this->addSql('DROP INDEX IDX_7F221F39FB88E14F ON commentaireapplication');
        $this->addSql('ALTER TABLE commentaireapplication DROP utilisateur_id');
        $this->addSql('ALTER TABLE commentairearticle DROP FOREIGN KEY FK_EF8D3962FB88E14F');
        $this->addSql('DROP INDEX IDX_EF8D3962FB88E14F ON commentairearticle');
        $this->addSql('ALTER TABLE commentairearticle DROP utilisateur_id');
        $this->addSql('ALTER TABLE commentairelogiciel DROP FOREIGN KEY FK_2BD015B2FB88E14F');
        $this->addSql('DROP INDEX IDX_2BD015B2FB88E14F ON commentairelogiciel');
        $this->addSql('ALTER TABLE commentairelogiciel DROP utilisateur_id');
    }
}
