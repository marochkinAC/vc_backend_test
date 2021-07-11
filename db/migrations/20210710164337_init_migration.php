<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class InitMigration extends AbstractMigration
{

    public function up(): void
    {
        $this->execute('create schema if not exists public');
        $this->execute('create sequence if not exists auto_id_advs');
        $this->execute('alter sequence auto_id_advs owner to ads');
        $this->execute(
            <<<'EOT'
                create table if not exists advs
                (
                    id integer default nextval('public.auto_id_advs'::regclass) not null primary key,
                    text text not null,
                    price float not null,
                    show_limit integer not null,
                    show_count integer not null,
                    banner text not null
                )
            EOT
        );
    }
}
