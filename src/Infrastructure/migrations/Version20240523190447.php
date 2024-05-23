<?php

declare(strict_types=1);

namespace App\Infrastructure\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240523190447 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE ext_log_entries_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE message_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE message_context_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE refresh_tokens_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_base_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_blockade_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE verifying_token_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE ext_log_entries (id INT NOT NULL, action VARCHAR(8) NOT NULL, logged_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, object_id VARCHAR(64) DEFAULT NULL, object_class VARCHAR(191) NOT NULL, version INT NOT NULL, data TEXT DEFAULT NULL, username VARCHAR(191) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX log_class_lookup_idx ON ext_log_entries (object_class)');
        $this->addSql('CREATE INDEX log_date_lookup_idx ON ext_log_entries (logged_at)');
        $this->addSql('CREATE INDEX log_user_lookup_idx ON ext_log_entries (username)');
        $this->addSql('CREATE INDEX log_version_lookup_idx ON ext_log_entries (object_id, object_class, version)');
        $this->addSql('COMMENT ON COLUMN ext_log_entries.data IS \'(DC2Type:array)\'');
        $this->addSql('CREATE TABLE message (id INT NOT NULL, subject TEXT DEFAULT NULL, body TEXT NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN message.created IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN message.updated IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE message_context (id INT NOT NULL, sender_id INT DEFAULT NULL, recipient_id INT DEFAULT NULL, message_id INT DEFAULT NULL, status TEXT NOT NULL, sent_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, deleted TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_72D5BF72F624B39D ON message_context (sender_id)');
        $this->addSql('CREATE INDEX IDX_72D5BF72E92F8F78 ON message_context (recipient_id)');
        $this->addSql('CREATE INDEX IDX_72D5BF72537A1329 ON message_context (message_id)');
        $this->addSql('COMMENT ON COLUMN message_context.sent_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN message_context.created IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN message_context.updated IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN message_context.deleted IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE refresh_tokens (id INT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9BACE7E1C74F2195 ON refresh_tokens (refresh_token)');
        $this->addSql('CREATE TABLE user_admin (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE user_base (id INT NOT NULL, password VARCHAR(255) NOT NULL, enabled BOOLEAN NOT NULL, last_login TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, verified BOOLEAN NOT NULL, email VARCHAR(100) NOT NULL, role VARCHAR(255) NOT NULL, first_name VARCHAR(50) DEFAULT NULL, last_name VARCHAR(50) DEFAULT NULL, deleted TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BA35B2A8E7927C74 ON user_base (email)');
        $this->addSql('CREATE INDEX email_search_idx ON user_base (email)');
        $this->addSql('CREATE INDEX first_name_search_idx ON user_base (first_name)');
        $this->addSql('CREATE INDEX last_name_search_idx ON user_base (last_name)');
        $this->addSql('COMMENT ON COLUMN user_base.last_login IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN user_base.deleted IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN user_base.created IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN user_base.updated IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE user_blockade (id INT NOT NULL, user_id INT DEFAULT NULL, reason VARCHAR(255) NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, deleted TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E53CE04DA76ED395 ON user_blockade (user_id)');
        $this->addSql('COMMENT ON COLUMN user_blockade.created IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN user_blockade.updated IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN user_blockade.deleted IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE user_employee (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE verifying_token (id INT NOT NULL, recipient VARCHAR(255) NOT NULL, token VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE queue_jobs (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DC32533FB7336F0 ON queue_jobs (queue_name)');
        $this->addSql('CREATE INDEX IDX_DC32533E3BD61CE ON queue_jobs (available_at)');
        $this->addSql('CREATE INDEX IDX_DC3253316BA31DB ON queue_jobs (delivered_at)');
        $this->addSql('COMMENT ON COLUMN queue_jobs.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN queue_jobs.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN queue_jobs.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_queue_jobs() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'queue_jobs\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON queue_jobs;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON queue_jobs FOR EACH ROW EXECUTE PROCEDURE notify_queue_jobs();');
        $this->addSql('ALTER TABLE message_context ADD CONSTRAINT FK_72D5BF72F624B39D FOREIGN KEY (sender_id) REFERENCES user_base (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE message_context ADD CONSTRAINT FK_72D5BF72E92F8F78 FOREIGN KEY (recipient_id) REFERENCES user_base (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE message_context ADD CONSTRAINT FK_72D5BF72537A1329 FOREIGN KEY (message_id) REFERENCES message (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_admin ADD CONSTRAINT FK_6ACCF62EBF396750 FOREIGN KEY (id) REFERENCES user_base (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_blockade ADD CONSTRAINT FK_E53CE04DA76ED395 FOREIGN KEY (user_id) REFERENCES user_base (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_employee ADD CONSTRAINT FK_BD1291A1BF396750 FOREIGN KEY (id) REFERENCES user_base (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE ext_log_entries_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE message_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE message_context_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE refresh_tokens_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_base_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_blockade_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE verifying_token_id_seq CASCADE');
        $this->addSql('ALTER TABLE message_context DROP CONSTRAINT FK_72D5BF72F624B39D');
        $this->addSql('ALTER TABLE message_context DROP CONSTRAINT FK_72D5BF72E92F8F78');
        $this->addSql('ALTER TABLE message_context DROP CONSTRAINT FK_72D5BF72537A1329');
        $this->addSql('ALTER TABLE user_admin DROP CONSTRAINT FK_6ACCF62EBF396750');
        $this->addSql('ALTER TABLE user_blockade DROP CONSTRAINT FK_E53CE04DA76ED395');
        $this->addSql('ALTER TABLE user_employee DROP CONSTRAINT FK_BD1291A1BF396750');
        $this->addSql('DROP TABLE ext_log_entries');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE message_context');
        $this->addSql('DROP TABLE refresh_tokens');
        $this->addSql('DROP TABLE user_admin');
        $this->addSql('DROP TABLE user_base');
        $this->addSql('DROP TABLE user_blockade');
        $this->addSql('DROP TABLE user_employee');
        $this->addSql('DROP TABLE verifying_token');
        $this->addSql('DROP TABLE queue_jobs');
    }
}
