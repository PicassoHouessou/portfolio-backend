<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230321164634 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE cheese_listing');
        $this->addSql('CREATE TEMPORARY TABLE __temp__ext_translations AS SELECT id, locale, object_class, field, foreign_key, content FROM ext_translations');
        $this->addSql('DROP TABLE ext_translations');
        $this->addSql('CREATE TABLE ext_translations (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(191) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content CLOB DEFAULT NULL)');
        $this->addSql('INSERT INTO ext_translations (id, locale, object_class, field, foreign_key, content) SELECT id, locale, object_class, field, foreign_key, content FROM __temp__ext_translations');
        $this->addSql('DROP TABLE __temp__ext_translations');
        $this->addSql('CREATE UNIQUE INDEX lookup_unique_idx ON ext_translations (locale, object_class, field, foreign_key)');
        $this->addSql('CREATE INDEX translations_lookup_idx ON ext_translations (locale, object_class, foreign_key)');
        $this->addSql('CREATE INDEX general_translations_lookup_idx ON ext_translations (object_class, foreign_key)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__post AS SELECT id, author_id, title, slug, created_at, updated_at, content, is_activated FROM post');
        $this->addSql('DROP TABLE post');
        $this->addSql('CREATE TABLE post (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id INTEGER NOT NULL, title VARCHAR(200) NOT NULL, slug VARCHAR(230) NOT NULL, created_at DATETIME(6) NOT NULL, updated_at DATETIME(6) DEFAULT NULL, content CLOB NOT NULL, is_activated BOOLEAN NOT NULL, CONSTRAINT FK_5A8A6C8DF675F31B FOREIGN KEY (author_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO post (id, author_id, title, slug, created_at, updated_at, content, is_activated) SELECT id, author_id, title, slug, created_at, updated_at, content, is_activated FROM __temp__post');
        $this->addSql('DROP TABLE __temp__post');
        $this->addSql('CREATE INDEX IDX_5A8A6C8DF675F31B ON post (author_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cheese_listing (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL COLLATE "BINARY", description CLOB NOT NULL COLLATE "BINARY", price INTEGER NOT NULL, created_at DATETIME(6) NOT NULL, is_published BOOLEAN NOT NULL)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__ext_translations AS SELECT id, locale, object_class, field, foreign_key, content FROM ext_translations');
        $this->addSql('DROP TABLE ext_translations');
        $this->addSql('CREATE TABLE ext_translations (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(191) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content CLOB DEFAULT NULL)');
        $this->addSql('INSERT INTO ext_translations (id, locale, object_class, field, foreign_key, content) SELECT id, locale, object_class, field, foreign_key, content FROM __temp__ext_translations');
        $this->addSql('DROP TABLE __temp__ext_translations');
        $this->addSql('CREATE INDEX translations_lookup_idx ON ext_translations (locale, object_class, foreign_key)');
        $this->addSql('CREATE UNIQUE INDEX lookup_unique_idx ON ext_translations (locale, object_class, field, foreign_key)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__post AS SELECT id, author_id, title, slug, created_at, updated_at, content, is_activated FROM post');
        $this->addSql('DROP TABLE post');
        $this->addSql('CREATE TABLE post (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id INTEGER NOT NULL, title VARCHAR(200) NOT NULL, slug VARCHAR(200) NOT NULL, created_at DATETIME(6) NOT NULL, updated_at DATETIME(6) DEFAULT NULL, content CLOB NOT NULL, is_activated BOOLEAN NOT NULL, CONSTRAINT FK_5A8A6C8DF675F31B FOREIGN KEY (author_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO post (id, author_id, title, slug, created_at, updated_at, content, is_activated) SELECT id, author_id, title, slug, created_at, updated_at, content, is_activated FROM __temp__post');
        $this->addSql('DROP TABLE __temp__post');
        $this->addSql('CREATE INDEX IDX_5A8A6C8DF675F31B ON post (author_id)');
    }
}
