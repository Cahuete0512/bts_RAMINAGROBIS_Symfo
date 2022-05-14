<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220328070559 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE enchere DROP FOREIGN KEY FK_id_fournisseur');
        $this->addSql('DROP INDEX FK_id_fournisseur ON enchere');
        $this->addSql('ALTER TABLE enchere ADD id_ligne_panier INT NOT NULL');
        $this->addSql('ALTER TABLE fournisseur DROP FOREIGN KEY FK_lignePanier');
        $this->addSql('DROP INDEX FK_lignePanier ON fournisseur');
        $this->addSql('ALTER TABLE fournisseur DROP civiliteContact, DROP nomContact, DROP prenomContact, DROP email, DROP numeroSession, DROP idLignePanier');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE enchere DROP id_ligne_panier');
        $this->addSql('ALTER TABLE enchere ADD CONSTRAINT FK_id_fournisseur FOREIGN KEY (id_fournisseur) REFERENCES fournisseur (id) ON UPDATE CASCADE ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX FK_id_fournisseur ON enchere (id_fournisseur)');
        $this->addSql('ALTER TABLE fournisseur ADD civiliteContact VARCHAR(4) NOT NULL, ADD nomContact VARCHAR(50) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, ADD prenomContact VARCHAR(50) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, ADD email VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, ADD numeroSession VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, ADD idLignePanier INT NOT NULL');
        $this->addSql('ALTER TABLE fournisseur ADD CONSTRAINT FK_lignePanier FOREIGN KEY (idLignePanier) REFERENCES ligne_panier (id)');
        $this->addSql('CREATE INDEX FK_lignePanier ON fournisseur (idLignePanier)');
    }
}
