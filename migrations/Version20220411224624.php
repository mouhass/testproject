<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220411224624 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE job_cron_job_composite');
        $this->addSql('ALTER TABLE job ADD relation_job_composite_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE job ADD CONSTRAINT FK_FBD8E0F84D068E3E FOREIGN KEY (relation_job_composite_id) REFERENCES job (id)');
        $this->addSql('CREATE INDEX IDX_FBD8E0F84D068E3E ON job (relation_job_composite_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE job_cron_job_composite (job_cron_id INT NOT NULL, job_composite_id INT NOT NULL, INDEX IDX_8EBAE813A2ACEED9 (job_cron_id), INDEX IDX_8EBAE8135EF6B87C (job_composite_id), PRIMARY KEY(job_cron_id, job_composite_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE job_cron_job_composite ADD CONSTRAINT FK_8EBAE8135EF6B87C FOREIGN KEY (job_composite_id) REFERENCES job (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE job_cron_job_composite ADD CONSTRAINT FK_8EBAE813A2ACEED9 FOREIGN KEY (job_cron_id) REFERENCES job (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE job DROP FOREIGN KEY FK_FBD8E0F84D068E3E');
        $this->addSql('DROP INDEX IDX_FBD8E0F84D068E3E ON job');
        $this->addSql('ALTER TABLE job DROP relation_job_composite_id');
    }
}
