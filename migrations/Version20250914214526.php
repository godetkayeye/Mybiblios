<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250914214526 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE author (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, date_of_birth TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, date_of_death TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, nationality VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN author.date_of_birth IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN author.date_of_death IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE author_book (author_id INT NOT NULL, book_id INT NOT NULL, PRIMARY KEY(author_id, book_id))');
        $this->addSql('CREATE INDEX IDX_2F0A2BEEF675F31B ON author_book (author_id)');
        $this->addSql('CREATE INDEX IDX_2F0A2BEE16A2B381 ON author_book (book_id)');
        $this->addSql('CREATE TABLE book (id SERIAL NOT NULL, editor_id INT DEFAULT NULL, comment_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, isbn VARCHAR(255) NOT NULL, plot TEXT NOT NULL, page_number INT NOT NULL, status VARCHAR(255) NOT NULL, edited_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CBE5A3316995AC4C ON book (editor_id)');
        $this->addSql('CREATE INDEX IDX_CBE5A331F8697D13 ON book (comment_id)');
        $this->addSql('COMMENT ON COLUMN book.edited_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE comment (id SERIAL NOT NULL, comments_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, emaail VARCHAR(255) NOT NULL, content TEXT NOT NULL, status VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, published_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9474526C63379586 ON comment (comments_id)');
        $this->addSql('COMMENT ON COLUMN comment.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN comment.published_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE editor (id SERIAL NOT NULL, book_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CCF1F1BA16A2B381 ON editor (book_id)');
        $this->addSql('CREATE TABLE editor_author (editor_id INT NOT NULL, author_id INT NOT NULL, PRIMARY KEY(editor_id, author_id))');
        $this->addSql('CREATE INDEX IDX_5798CD4A6995AC4C ON editor_author (editor_id)');
        $this->addSql('CREATE INDEX IDX_5798CD4AF675F31B ON editor_author (author_id)');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE author_book ADD CONSTRAINT FK_2F0A2BEEF675F31B FOREIGN KEY (author_id) REFERENCES author (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE author_book ADD CONSTRAINT FK_2F0A2BEE16A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A3316995AC4C FOREIGN KEY (editor_id) REFERENCES editor (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A331F8697D13 FOREIGN KEY (comment_id) REFERENCES book (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C63379586 FOREIGN KEY (comments_id) REFERENCES book (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE editor ADD CONSTRAINT FK_CCF1F1BA16A2B381 FOREIGN KEY (book_id) REFERENCES editor (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE editor_author ADD CONSTRAINT FK_5798CD4A6995AC4C FOREIGN KEY (editor_id) REFERENCES editor (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE editor_author ADD CONSTRAINT FK_5798CD4AF675F31B FOREIGN KEY (author_id) REFERENCES author (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE author_book DROP CONSTRAINT FK_2F0A2BEEF675F31B');
        $this->addSql('ALTER TABLE author_book DROP CONSTRAINT FK_2F0A2BEE16A2B381');
        $this->addSql('ALTER TABLE book DROP CONSTRAINT FK_CBE5A3316995AC4C');
        $this->addSql('ALTER TABLE book DROP CONSTRAINT FK_CBE5A331F8697D13');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526C63379586');
        $this->addSql('ALTER TABLE editor DROP CONSTRAINT FK_CCF1F1BA16A2B381');
        $this->addSql('ALTER TABLE editor_author DROP CONSTRAINT FK_5798CD4A6995AC4C');
        $this->addSql('ALTER TABLE editor_author DROP CONSTRAINT FK_5798CD4AF675F31B');
        $this->addSql('DROP TABLE author');
        $this->addSql('DROP TABLE author_book');
        $this->addSql('DROP TABLE book');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE editor');
        $this->addSql('DROP TABLE editor_author');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
