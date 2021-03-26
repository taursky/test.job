<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210326070227 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cron_job (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(191) NOT NULL, command VARCHAR(1024) NOT NULL, schedule VARCHAR(191) NOT NULL, description VARCHAR(191) NOT NULL, enabled TINYINT(1) NOT NULL, UNIQUE INDEX un_name (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cron_report (id INT AUTO_INCREMENT NOT NULL, job_id INT DEFAULT NULL, run_at DATETIME NOT NULL, run_time DOUBLE PRECISION NOT NULL, exit_code INT NOT NULL, output LONGTEXT NOT NULL, INDEX IDX_B6C6A7F5BE04EA9 (job_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE flight (id INT AUTO_INCREMENT NOT NULL, passenger_id INT DEFAULT NULL, number VARCHAR(255) NOT NULL, place INT NOT NULL, status VARCHAR(255) DEFAULT NULL, create_date DATETIME DEFAULT NULL, purchase_date DATETIME DEFAULT NULL, update_date DATETIME DEFAULT NULL, history LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', end_booking_time DATETIME DEFAULT NULL, flight_status TINYINT(1) DEFAULT NULL, INDEX IDX_C257E60E4502E565 (passenger_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, ip VARCHAR(255) DEFAULT NULL, status TINYINT(1) DEFAULT NULL, create_date VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cron_report ADD CONSTRAINT FK_B6C6A7F5BE04EA9 FOREIGN KEY (job_id) REFERENCES cron_job (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE flight ADD CONSTRAINT FK_C257E60E4502E565 FOREIGN KEY (passenger_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cron_report DROP FOREIGN KEY FK_B6C6A7F5BE04EA9');
        $this->addSql('ALTER TABLE flight DROP FOREIGN KEY FK_C257E60E4502E565');
        $this->addSql('DROP TABLE cron_job');
        $this->addSql('DROP TABLE cron_report');
        $this->addSql('DROP TABLE flight');
        $this->addSql('DROP TABLE user');
    }
}
