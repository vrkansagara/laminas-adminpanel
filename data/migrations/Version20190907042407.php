<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190907042407 extends AbstractMigration
{
    /**
     * Returns the description of this migration.
     */
    public function getDescription(): string
    {
        $description = 'This is the initial migration which creates the user table.';
        return $description;
    }

    /**
     * Upgrades the schema to its newer state.
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        // Create 'user' table
        $table = $schema->createTable('user');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('email', 'string', ['notnull' => true, 'length' => 128]);
        $table->addColumn('full_name', 'string', ['notnull' => true, 'length' => 512]);
        $table->addColumn('password', 'string', ['notnull' => true, 'length' => 256]);
        $table->addColumn('status', 'integer', ['notnull' => true]);
        $table->addColumn('date_created', 'datetime', ['notnull' => true]);
        $table->addColumn('pwd_reset_token', 'string', ['notnull' => false, 'length' => 256]);
        $table->addColumn('pwd_reset_token_creation_date', 'datetime', ['notnull' => false]);
        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['email'], 'email_idx');
        $table->addOption('engine', 'InnoDB');
    }

    /**
     * Reverts the schema changes.
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $schema->dropTable('user');
    }
}
