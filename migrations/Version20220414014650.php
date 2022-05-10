<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220414014650 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE job_composite_job_cron (job_composite_id INT NOT NULL, job_cron_id INT NOT NULL, INDEX IDX_6DC41FBB5EF6B87C (job_composite_id), INDEX IDX_6DC41FBBA2ACEED9 (job_cron_id), PRIMARY KEY(job_composite_id, job_cron_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE job_composite_job_cron ADD CONSTRAINT FK_6DC41FBB5EF6B87C FOREIGN KEY (job_composite_id) REFERENCES job (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE job_composite_job_cron ADD CONSTRAINT FK_6DC41FBBA2ACEED9 FOREIGN KEY (job_cron_id) REFERENCES job (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE job DROP FOREIGN KEY FK_FBD8E0F84D068E3E');
        $this->addSql('DROP INDEX IDX_FBD8E0F84D068E3E ON job');
        $this->addSql('ALTER TABLE job DROP relation_job_composite_id, DROP list_sous_jobs');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE job_composite_job_cron');
        $this->addSql('ALTER TABLE job ADD relation_job_composite_id INT DEFAULT NULL, ADD list_sous_jobs LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE job ADD CONSTRAINT FK_FBD8E0F84D068E3E FOREIGN KEY (relation_job_composite_id) REFERENCES job (id)');
        $this->addSql('CREATE INDEX IDX_FBD8E0F84D068E3E ON job (relation_job_composite_id)');
    }
}
