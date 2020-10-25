<?php
use Migrations\AbstractMigration;

class Alunos extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('alunos');
        $table->addColumn('rga', 'string', ['limit' => 15])
            ->addColumn('nome', 'string', ['limit' => 255])
            ->addColumn('curso', 'string', ['limit' => 255])
            ->addColumn('registrado_em', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP'
            ])
            ->addColumn('status', 'enum', [
                'values' => ['ativo', 'inativo'],
                'default' => 'ativo'
            ])
            ->create();

    }
}
