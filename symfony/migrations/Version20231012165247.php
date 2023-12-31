<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231012165247 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sneaker (uuid VARCHAR(255) NOT NULL, sku VARCHAR(100) NOT NULL, title VARCHAR(255) NOT NULL, coloris_code VARCHAR(255) NOT NULL, coloris_name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, retail_price DOUBLE PRECISION NOT NULL, stock_xlink VARCHAR(255) NOT NULL, img_url LONGTEXT NOT NULL, thumbnail_url LONGTEXT NOT NULL, brand VARCHAR(100) NOT NULL, drop_date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', updated_at DATETIME NOT NULL, INDEX IDX_4259B88AF9038C4 (sku), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(50) NOT NULL, email VARCHAR(255) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_sneaker (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, sneaker_uuid VARCHAR(255) NOT NULL, purchase_date DATE NOT NULL, selling_date DATE DEFAULT NULL, selling_price DOUBLE PRECISION NOT NULL, purchase_price DOUBLE PRECISION NOT NULL, shipping_cost DOUBLE PRECISION NOT NULL, comment LONGTEXT NOT NULL, size VARCHAR(10) NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_1A54A680A76ED395 (user_id), INDEX IDX_1A54A680EE8D643 (sneaker_uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_token (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, token LONGTEXT NOT NULL, expire_at DATE NOT NULL, INDEX IDX_BDF55A63A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_sneaker ADD CONSTRAINT FK_1A54A680A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_sneaker ADD CONSTRAINT FK_1A54A680EE8D643 FOREIGN KEY (sneaker_uuid) REFERENCES sneaker (uuid) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_token ADD CONSTRAINT FK_BDF55A63A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_sneaker DROP FOREIGN KEY FK_1A54A680A76ED395');
        $this->addSql('ALTER TABLE user_sneaker DROP FOREIGN KEY FK_1A54A680EE8D643');
        $this->addSql('ALTER TABLE user_token DROP FOREIGN KEY FK_BDF55A63A76ED395');
        $this->addSql('DROP TABLE sneaker');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_sneaker');
        $this->addSql('DROP TABLE user_token');
    }
}
