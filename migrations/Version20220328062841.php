<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220328062841 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ligne_panier (id INT AUTO_INCREMENT NOT NULL, reference VARCHAR(255) NOT NULL, quantite INT NOT NULL, id_session_enchere INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE session_enchere (id INT AUTO_INCREMENT NOT NULL, id_panier INT NOT NULL, debut_enchere DATETIME NOT NULL, fin_enchere DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE enchere DROP FOREIGN KEY enchere_fournisseur_id_fk');
        $this->addSql('DROP INDEX enchere_fournisseur_id_fk ON enchere');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE ligne_panier');
        $this->addSql('DROP TABLE session_enchere');
        $this->addSql('ALTER TABLE enchere ADD CONSTRAINT enchere_fournisseur_id_fk FOREIGN KEY (id_fournisseur) REFERENCES fournisseur (id)');
        $this->addSql('CREATE INDEX enchere_fournisseur_id_fk ON enchere (id_fournisseur)');
    }
}
