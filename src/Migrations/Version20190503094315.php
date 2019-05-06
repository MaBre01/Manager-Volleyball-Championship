<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190503094315 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, club_id INT NOT NULL, championship_id INT DEFAULT NULL, account_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, point INT NOT NULL, validated TINYINT(1) NOT NULL, active TINYINT(1) NOT NULL, team_manager_first_name VARCHAR(255) NOT NULL, team_manager_last_name VARCHAR(255) NOT NULL, team_manager_phone_number VARCHAR(255) NOT NULL, INDEX IDX_C4E0A61F61190A32 (club_id), INDEX IDX_C4E0A61F94DDBCE9 (championship_id), UNIQUE INDEX UNIQ_C4E0A61F9B6B5FBA (account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE account (id INT AUTO_INCREMENT NOT NULL, team_id INT DEFAULT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, UNIQUE INDEX UNIQ_7D3656A4296CD8AE (team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE championship (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, began TINYINT(1) NOT NULL, specification_point_win_point INT NOT NULL, specification_point_win_with_bonus_point INT NOT NULL, specification_point_loose_point INT NOT NULL, specification_point_loose_with_bonus_point INT NOT NULL, specification_point_forfeit_point INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE club (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, game_day_id INT NOT NULL, home_team_id INT NOT NULL, outside_team_id INT NOT NULL, INDEX IDX_232B318C74F7ECEE (game_day_id), INDEX IDX_232B318C9C4C13F6 (home_team_id), INDEX IDX_232B318C5B573341 (outside_team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game_day (id INT AUTO_INCREMENT NOT NULL, championship_id INT NOT NULL, start DATETIME DEFAULT NULL, end DATETIME DEFAULT NULL, number INT NOT NULL, phase TINYINT(1) NOT NULL, INDEX IDX_FEFA3A5594DDBCE9 (championship_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gap (id INT AUTO_INCREMENT NOT NULL, day VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pitch (id INT AUTO_INCREMENT NOT NULL, club_id INT NOT NULL, address VARCHAR(255) NOT NULL, INDEX IDX_279FBED961190A32 (club_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pitch_gap (pitch_id INT NOT NULL, gap_id INT NOT NULL, INDEX IDX_141E3020FEEFC64B (pitch_id), INDEX IDX_141E3020F25E7C02 (gap_id), PRIMARY KEY(pitch_id, gap_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE set_point (id INT AUTO_INCREMENT NOT NULL, game_id INT NOT NULL, home_team_point INT NOT NULL, outside_team_point INT NOT NULL, number INT NOT NULL, INDEX IDX_23540F42E48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F61190A32 FOREIGN KEY (club_id) REFERENCES club (id)');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F94DDBCE9 FOREIGN KEY (championship_id) REFERENCES championship (id)');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F9B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE account ADD CONSTRAINT FK_7D3656A4296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C74F7ECEE FOREIGN KEY (game_day_id) REFERENCES game_day (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C9C4C13F6 FOREIGN KEY (home_team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C5B573341 FOREIGN KEY (outside_team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE game_day ADD CONSTRAINT FK_FEFA3A5594DDBCE9 FOREIGN KEY (championship_id) REFERENCES championship (id)');
        $this->addSql('ALTER TABLE pitch ADD CONSTRAINT FK_279FBED961190A32 FOREIGN KEY (club_id) REFERENCES club (id)');
        $this->addSql('ALTER TABLE pitch_gap ADD CONSTRAINT FK_141E3020FEEFC64B FOREIGN KEY (pitch_id) REFERENCES pitch (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pitch_gap ADD CONSTRAINT FK_141E3020F25E7C02 FOREIGN KEY (gap_id) REFERENCES gap (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE set_point ADD CONSTRAINT FK_23540F42E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE account DROP FOREIGN KEY FK_7D3656A4296CD8AE');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C9C4C13F6');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C5B573341');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61F9B6B5FBA');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61F94DDBCE9');
        $this->addSql('ALTER TABLE game_day DROP FOREIGN KEY FK_FEFA3A5594DDBCE9');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61F61190A32');
        $this->addSql('ALTER TABLE pitch DROP FOREIGN KEY FK_279FBED961190A32');
        $this->addSql('ALTER TABLE set_point DROP FOREIGN KEY FK_23540F42E48FD905');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C74F7ECEE');
        $this->addSql('ALTER TABLE pitch_gap DROP FOREIGN KEY FK_141E3020F25E7C02');
        $this->addSql('ALTER TABLE pitch_gap DROP FOREIGN KEY FK_141E3020FEEFC64B');
        $this->addSql('DROP TABLE team');
        $this->addSql('DROP TABLE account');
        $this->addSql('DROP TABLE championship');
        $this->addSql('DROP TABLE club');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE game_day');
        $this->addSql('DROP TABLE gap');
        $this->addSql('DROP TABLE pitch');
        $this->addSql('DROP TABLE pitch_gap');
        $this->addSql('DROP TABLE set_point');
    }
}
