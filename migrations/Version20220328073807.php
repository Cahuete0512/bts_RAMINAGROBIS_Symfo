<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220328073807 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE enchere DROP FOREIGN KEY FK_lignePanier');
        $this->addSql('ALTER TABLE enchere DROP FOREIGN KEY FK_fournisseur');
        $this->addSql('DROP INDEX FK_fournisseur ON enchere');
        $this->addSql('DROP INDEX FK_lignePanier ON enchere');
        $this->addSql('ALTER TABLE fournisseur ADD civilite_contact VARCHAR(4) NOT NULL, ADD nom_contact VARCHAR(255) NOT NULL, ADD prenom_contact VARCHAR(255) NOT NULL, ADD email VARCHAR(255) NOT NULL, ADD adresse VARCHAR(255) NOT NULL, ADD numero_session VARCHAR(255) NOT NULL, ADD id_ligne_panier INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE enchere ADD CONSTRAINT FK_lignePanier FOREIGN KEY (id_ligne_panier) REFERENCES ligne_panier (id) ON UPDATE CASCADE ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE enchere ADD CONSTRAINT FK_fournisseur FOREIGN KEY (id_fournisseur) REFERENCES fournisseur (id) ON UPDATE CASCADE ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX FK_fournisseur ON enchere (id_fournisseur)');
        $this->addSql('CREATE INDEX FK_lignePanier ON enchere (id_ligne_panier)');
        $this->addSql('ALTER TABLE fournisseur DROP civilite_contact, DROP nom_contact, DROP prenom_contact, DROP email, DROP adresse, DROP numero_session, DROP id_ligne_panier');
    }
}
