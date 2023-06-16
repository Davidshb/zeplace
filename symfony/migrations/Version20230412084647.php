<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230412084647 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE brand (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_1C52F9585E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sneaker (id INT AUTO_INCREMENT NOT NULL, brand_id INT NOT NULL, sku VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, coloris_code VARCHAR(255) NOT NULL, coloris_name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, retail_price INT NOT NULL, stock_xlink VARCHAR(255) NOT NULL, drop_date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_4259B88AF9038C4 (sku), INDEX IDX_4259B88A44F5D008 (brand_id), INDEX IDX_4259B88AF9038C4 (sku), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(50) NOT NULL, email VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_sneaker (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, sneaker_id INT NOT NULL, purchase_date DATETIME NOT NULL, selling_date VARCHAR(255) DEFAULT NULL, selling_price INT NOT NULL, purchase_price INT NOT NULL, shipping_cost INT NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_1A54A680A76ED395 (user_id), INDEX IDX_1A54A680B44896C4 (sneaker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sneaker ADD CONSTRAINT FK_4259B88A44F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_sneaker ADD CONSTRAINT FK_1A54A680A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_sneaker ADD CONSTRAINT FK_1A54A680B44896C4 FOREIGN KEY (sneaker_id) REFERENCES sneaker (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sneaker DROP FOREIGN KEY FK_4259B88A44F5D008');
        $this->addSql('ALTER TABLE user_sneaker DROP FOREIGN KEY FK_1A54A680A76ED395');
        $this->addSql('ALTER TABLE user_sneaker DROP FOREIGN KEY FK_1A54A680B44896C4');
        $this->addSql('DROP TABLE brand');
        $this->addSql('DROP TABLE sneaker');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_sneaker');
    }
}
