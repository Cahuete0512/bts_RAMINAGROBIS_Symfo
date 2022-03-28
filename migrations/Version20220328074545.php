<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220328074545 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fournisseur DROP FOREIGN KEY FK_id_ligne_panier');
        $this->addSql('DROP INDEX FK_id_ligne_panier ON fournisseur');
        $this->addSql('ALTER TABLE ligne_panier DROP FOREIGN KEY FK_id_session_enchere');
        $this->addSql('DROP INDEX FK_id_session_enchere ON ligne_panier');
        $this->addSql('ALTER TABLE session_enchere DROP FOREIGN KEY FK_id_panier');
        $this->addSql('DROP INDEX FK_id_panier ON session_enchere');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fournisseur ADD CONSTRAINT FK_id_ligne_panier FOREIGN KEY (id_ligne_panier) REFERENCES ligne_panier (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('CREATE INDEX FK_id_ligne_panier ON fournisseur (id_ligne_panier)');
        $this->addSql('ALTER TABLE ligne_panier ADD CONSTRAINT FK_id_session_enchere FOREIGN KEY (id_session_enchere) REFERENCES session_enchere (id) ON UPDATE CASCADE ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX FK_id_session_enchere ON ligne_panier (id_session_enchere)');
        $this->addSql('ALTER TABLE session_enchere ADD CONSTRAINT FK_id_panier FOREIGN KEY (id_panier) REFERENCES ligne_panier (id) ON UPDATE CASCADE ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX FK_id_panier ON session_enchere (id_panier)');
    }
}
