<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230224105326 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment ADD users_id INT DEFAULT NULL, ADD tricks_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C67B3B43D FOREIGN KEY (users_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C3B153154 FOREIGN KEY (tricks_id) REFERENCES trick (id)');
        $this->addSql('CREATE INDEX IDX_9474526C67B3B43D ON comment (users_id)');
        $this->addSql('CREATE INDEX IDX_9474526C3B153154 ON comment (tricks_id)');
        $this->addSql('ALTER TABLE picture ADD tricks_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F893B153154 FOREIGN KEY (tricks_id) REFERENCES trick (id)');
        $this->addSql('CREATE INDEX IDX_16DB4F893B153154 ON picture (tricks_id)');
        $this->addSql('ALTER TABLE user ADD is_verified TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE video ADD tricks_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2C3B153154 FOREIGN KEY (tricks_id) REFERENCES trick (id)');
        $this->addSql('CREATE INDEX IDX_7CC7DA2C3B153154 ON video (tricks_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C67B3B43D');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C3B153154');
        $this->addSql('DROP INDEX IDX_9474526C67B3B43D ON comment');
        $this->addSql('DROP INDEX IDX_9474526C3B153154 ON comment');
        $this->addSql('ALTER TABLE comment DROP users_id, DROP tricks_id');
        $this->addSql('ALTER TABLE picture DROP FOREIGN KEY FK_16DB4F893B153154');
        $this->addSql('DROP INDEX IDX_16DB4F893B153154 ON picture');
        $this->addSql('ALTER TABLE picture DROP tricks_id');
        $this->addSql('ALTER TABLE user DROP is_verified');
        $this->addSql('ALTER TABLE video DROP FOREIGN KEY FK_7CC7DA2C3B153154');
        $this->addSql('DROP INDEX IDX_7CC7DA2C3B153154 ON video');
        $this->addSql('ALTER TABLE video DROP tricks_id');
    }
}
