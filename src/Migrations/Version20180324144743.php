<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180324144743 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE recipe ADD author_id INT NOT NULL, ADD public TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE recipe ADD CONSTRAINT FK_DA88B137F675F31B FOREIGN KEY (author_id) REFERENCES app_users (id)');
        $this->addSql('CREATE INDEX IDX_DA88B137F675F31B ON recipe (author_id)');
        $this->addSql('ALTER TABLE review ADD author_id INT NOT NULL, DROP author, CHANGE date date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6F675F31B FOREIGN KEY (author_id) REFERENCES app_users (id)');
        $this->addSql('CREATE INDEX IDX_794381C6F675F31B ON review (author_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE recipe DROP FOREIGN KEY FK_DA88B137F675F31B');
        $this->addSql('DROP INDEX IDX_DA88B137F675F31B ON recipe');
        $this->addSql('ALTER TABLE recipe DROP author_id, DROP public');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6F675F31B');
        $this->addSql('DROP INDEX IDX_794381C6F675F31B ON review');
        $this->addSql('ALTER TABLE review ADD author VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, DROP author_id, CHANGE date date VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
    }
}
