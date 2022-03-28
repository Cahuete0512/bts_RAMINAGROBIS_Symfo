<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220328075037 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE enchere DROP FOREIGN KEY FK_id_fournisseur');
        $this->addSql('ALTER TABLE enchere DROP FOREIGN KEY FK_id_ligne_panier');
        $this->addSql('DROP INDEX FK_id_ligne_panier ON enchere');
        $this->addSql('DROP INDEX FK_id_fournisseur ON enchere');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE enchere ADD CONSTRAINT FK_id_fournisseur FOREIGN KEY (id_fournisseur) REFERENCES fournisseur (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE enchere ADD CONSTRAINT FK_id_ligne_panier FOREIGN KEY (id_ligne_panier) REFERENCES ligne_panier (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('CREATE INDEX FK_id_ligne_panier ON enchere (id_ligne_panier)');
        $this->addSql('CREATE INDEX FK_id_fournisseur ON enchere (id_fournisseur)');
    }
}
