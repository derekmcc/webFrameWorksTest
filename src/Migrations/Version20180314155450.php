<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180314155450 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD3E2E969B');
        $this->addSql('DROP INDEX IDX_D34A04AD3E2E969B ON product');
        $this->addSql('ALTER TABLE product DROP review_id');
        $this->addSql('ALTER TABLE review ADD recipes_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6FDF2B1FA FOREIGN KEY (recipes_id) REFERENCES recipe (id)');
        $this->addSql('CREATE INDEX IDX_794381C6FDF2B1FA ON review (recipes_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product ADD review_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD3E2E969B FOREIGN KEY (review_id) REFERENCES review (id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD3E2E969B ON product (review_id)');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6FDF2B1FA');
        $this->addSql('DROP INDEX IDX_794381C6FDF2B1FA ON review');
        $this->addSql('ALTER TABLE review DROP recipes_id');
    }
}
