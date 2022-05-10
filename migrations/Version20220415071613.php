<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220415071613 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE job_cron_admin (job_cron_id INT NOT NULL, admin_id INT NOT NULL, INDEX IDX_CCE69ADA2ACEED9 (job_cron_id), INDEX IDX_CCE69AD642B8210 (admin_id), PRIMARY KEY(job_cron_id, admin_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job_composite_admin (job_composite_id INT NOT NULL, admin_id INT NOT NULL, INDEX IDX_B422B4E25EF6B87C (job_composite_id), INDEX IDX_B422B4E2642B8210 (admin_id), PRIMARY KEY(job_composite_id, admin_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE job_cron_admin ADD CONSTRAINT FK_CCE69ADA2ACEED9 FOREIGN KEY (job_cron_id) REFERENCES job (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE job_cron_admin ADD CONSTRAINT FK_CCE69AD642B8210 FOREIGN KEY (admin_id) REFERENCES admin (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE job_composite_admin ADD CONSTRAINT FK_B422B4E25EF6B87C FOREIGN KEY (job_composite_id) REFERENCES job (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE job_composite_admin ADD CONSTRAINT FK_B422B4E2642B8210 FOREIGN KEY (admin_id) REFERENCES admin (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE job DROP list_destination');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE job_cron_admin');
        $this->addSql('DROP TABLE job_composite_admin');
        $this->addSql('ALTER TABLE job ADD list_destination LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\'');
    }
}
