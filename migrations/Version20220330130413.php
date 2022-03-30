<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220330130413 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE enchere ADD ligne_panier_id INT NOT NULL, ADD fournisseur_id INT NOT NULL');
        $this->addSql('ALTER TABLE enchere ADD CONSTRAINT FK_38D1870F38989DF4 FOREIGN KEY (ligne_panier_id) REFERENCES ligne_panier (id)');
        $this->addSql('ALTER TABLE enchere ADD CONSTRAINT FK_38D1870F670C757F FOREIGN KEY (fournisseur_id) REFERENCES fournisseur (id)');
        $this->addSql('CREATE INDEX IDX_38D1870F38989DF4 ON enchere (ligne_panier_id)');
        $this->addSql('CREATE INDEX IDX_38D1870F670C757F ON enchere (fournisseur_id)');
        $this->addSql('ALTER TABLE fournisseur ADD ligne_panier_id INT NOT NULL');
        $this->addSql('ALTER TABLE fournisseur ADD CONSTRAINT FK_369ECA3238989DF4 FOREIGN KEY (ligne_panier_id) REFERENCES ligne_panier (id)');
        $this->addSql('CREATE INDEX IDX_369ECA3238989DF4 ON fournisseur (ligne_panier_id)');
        $this->addSql('ALTER TABLE ligne_panier ADD session_enchere_id INT NOT NULL');
        $this->addSql('ALTER TABLE ligne_panier ADD CONSTRAINT FK_21691B489D65A0 FOREIGN KEY (session_enchere_id) REFERENCES session_enchere (id)');
        $this->addSql('CREATE INDEX IDX_21691B489D65A0 ON ligne_panier (session_enchere_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE enchere DROP FOREIGN KEY FK_38D1870F38989DF4');
        $this->addSql('ALTER TABLE enchere DROP FOREIGN KEY FK_38D1870F670C757F');
        $this->addSql('DROP INDEX IDX_38D1870F38989DF4 ON enchere');
        $this->addSql('DROP INDEX IDX_38D1870F670C757F ON enchere');
        $this->addSql('ALTER TABLE enchere DROP ligne_panier_id, DROP fournisseur_id');
        $this->addSql('ALTER TABLE fournisseur DROP FOREIGN KEY FK_369ECA3238989DF4');
        $this->addSql('DROP INDEX IDX_369ECA3238989DF4 ON fournisseur');
        $this->addSql('ALTER TABLE fournisseur DROP ligne_panier_id');
        $this->addSql('ALTER TABLE ligne_panier DROP FOREIGN KEY FK_21691B489D65A0');
        $this->addSql('DROP INDEX IDX_21691B489D65A0 ON ligne_panier');
        $this->addSql('ALTER TABLE ligne_panier DROP session_enchere_id');
    }
}
